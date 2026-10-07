import os, pathlib, subprocess, re, json
root=pathlib.Path(__file__).resolve().parents[1]
import shutil
php=shutil.which('php')
if not php: raise SystemExit('需要 PHP CLI')
results=[]
for name in ['root','gradcheck','kobeu','pitrace','hapbun','printan','khaifile']:
    folder=root if name=='root' else root/'apps'/name
    files=list(folder.rglob('*')) if name!='root' else list(root.glob('*.php'))+list((root/'common').glob('*.php'))+list((root/'pages').glob('*.php'))+list((root/'templates').rglob('*.php'))+list((root/'tools').glob('*.php'))
    phps=[p for p in files if p.suffix=='.php']
    js=[p for p in files if p.suffix=='.js' and not any(x in p.parts for x in ['vendor','node_modules'])]
    failures=[]
    for p in phps+js:
        cmd=[php,'-l',str(p)] if p.suffix=='.php' else ['node','--check',str(p)]
        r=subprocess.run(cmd,capture_output=True,text=True)
        if r.returncode: failures.append([str(p),r.stderr+r.stdout])
    inline=0
    if name!='root':
        boot="$_APP=['action'=>'index']; $_SERVER['REQUEST_URI']='/koilisu/%s'; require '%s/common/functions.php'; include '%s/index.php';"%(name,root,folder)
        rendered=subprocess.run([php,'-r',boot],capture_output=True,text=True)
        if rendered.returncode or rendered.stderr: failures.append(['PHP render',rendered.stderr])
        for attrs,body in re.findall(r'<script\b([^>]*)>(.*?)</script>',rendered.stdout,re.S|re.I):
            if 'src=' in attrs or re.search('type=[\'"](importmap|application/)',attrs):continue
            if not body.strip():continue
            inline+=1
            r=subprocess.run(['node','--check','--input-type=module' if 'module' in attrs else '--input-type=commonjs'],input=body,capture_output=True,text=True)
            if r.returncode:failures.append(['inline JS',r.stderr])
    results.append(dict(project=name,php=len(phps),js=len(js),inline=inline,failures=failures))
print(json.dumps(results,ensure_ascii=False,indent=2))
failed=any(x['failures'] for x in results)
for cmd in [[php,'tools/securitycheck.php'],['node','tools/client-security.mjs'],
            [php,'apps/hapbun/tools/fontcheck.php'],['node','apps/printan/tools/fontcheck.mjs']]:
    run=subprocess.run(cmd,cwd=root)
    failed |= run.returncode != 0
raise SystemExit(1 if failed else 0)
