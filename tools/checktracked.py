"""檢核 root 與已初始化子模組的追蹤檔案，僅輸出路徑，不輸出秘密值。"""
import pathlib, subprocess, re, json
root = pathlib.Path(__file__).resolve().parents[1]
children = subprocess.check_output(['git','config','--file',str(root/'.gitmodules'),'--get-regexp','path'],text=True).splitlines()
repos = [root] + [root / row.split(' ',1)[1] for row in children]
failed = False
for repo in repos:
    tracked = subprocess.check_output(['git','-C',str(repo),'ls-files','-z'],text=True).split('\0')
    bad=[]
    for path in tracked:
        if not path: continue
        if re.search(r'(^|/)(?:\.env(?:\..*)?|\.claude|\.codex|node_modules|__pycache__)(?:/|$)|\.(?:key|pem|p12|pfx|bak|backup|log|tmp|pyc)$',path):
            if pathlib.PurePosixPath(path).name not in ['.env.example','.env.sample']: bad.append(path)
        if path == 'api/config.php': bad.append(path)
        if re.match(r'^(?:state|projects)/',path) and not path.endswith('/.gitkeep'): bad.append(path)
    ignored = subprocess.check_output(['git','-C',str(repo),'ls-files','-ci','--exclude-standard'],text=True).splitlines()
    print(json.dumps({'repo':repo.name,'unexpected_tracked':sorted(set(bad)),'tracked_but_ignored':ignored},ensure_ascii=False))
    failed |= bool(bad or ignored)
raise SystemExit(1 if failed else 0)
