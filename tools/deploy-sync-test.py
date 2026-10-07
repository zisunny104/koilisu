"""Exercise deployment sync without a VPS or resetting newer child commits."""
from pathlib import Path
import os
import shutil
import subprocess
import tempfile
ROOT=Path(__file__).resolve().parents[1]
env=dict(os.environ,GIT_ALLOW_PROTOCOL='file')
def run(path,*args):
    return subprocess.run(args,cwd=path,env=env,text=True,capture_output=True)
def git(path,*args):
    r=run(path,'git',*args)
    assert r.returncode==0,r.stdout+r.stderr
    return r.stdout.strip()
def init(path):
    path.mkdir();git(path,'init','-qb','main');git(path,'config','user.name','Test');git(path,'config','user.email','test@example.invalid');git(path,'config','commit.gpgsign','false')
with tempfile.TemporaryDirectory(prefix='koilisu-sync-') as temp:
    base=Path(temp);child=base/'child';init(child)
    (child/'config.php').write_text("<?php return ['version'=>'1.0.0'];")
    git(child,'add','.');git(child,'commit','-qm','base')
    old=git(child,'rev-parse','HEAD')
    source=base/'source';init(source);(source/'tools').mkdir()
    for name in ['deploy-output.sh','deploy-apps.sh']:
        shutil.copy(ROOT/'tools'/name,source/'tools'/name)
    shutil.copy(ROOT/'deploy.sh',source/'deploy.sh')
    git(source,'submodule','add',str(child),'apps/sample')
    git(source,'add','.');git(source,'commit','-qm','fixture')
    remote=base/'remote.git';git(base,'clone','--bare',str(source),str(remote))
    web=base/'web';git(base,'clone',str(remote),str(web))
    def deploy():return run(web,'bash','deploy.sh')
    r=deploy();assert r.returncode==0,r.stdout+r.stderr
    app=web/'apps/sample';assert git(app,'rev-parse','HEAD')==old
    print('PASS Uninitialized child is initialized at the recorded commit')
    (child/'new.txt').write_text('new');git(child,'add','.');git(child,'commit','-qm','new')
    new=git(child,'rev-parse','HEAD');git(app,'fetch','origin','main');git(app,'merge','--ff-only','FETCH_HEAD')
    r=deploy();assert r.returncode==0 and git(app,'rev-parse','HEAD')==new,r.stdout+r.stderr
    print('PASS Newer child commit is preserved and does not block parent deployment')
    (app/'new.txt').write_text('local edit');r=deploy()
    assert r.returncode!=0 and (app/'new.txt').read_text()=='local edit'
    print('PASS Uncommitted child file changes still stop deployment without overwriting')
    git(app,'restore','new.txt');git(app,'checkout','--detach',old)
    git(source/'apps/sample','fetch','origin','main');git(source/'apps/sample','merge','--ff-only','FETCH_HEAD')
    git(source,'add','apps/sample');git(source,'commit','-qm','advance pin');git(source,'remote','add','origin',str(remote));git(source,'push','origin','main')
    r=deploy();assert r.returncode==0 and git(app,'rev-parse','HEAD')==new,r.stdout+r.stderr
    print('PASS Older child fast-forwards to the new recorded commit')
    git(app,'config','user.name','Test');git(app,'config','user.email','test@example.invalid');git(app,'config','commit.gpgsign','false')
    git(app,'checkout','--detach',old);(app/'divergent.txt').write_text('keep');git(app,'add','.');git(app,'commit','-qm','diverged')
    diverged=git(app,'rev-parse','HEAD');r=deploy()
    assert r.returncode!=0 and git(app,'rev-parse','HEAD')==diverged and '分歧' in r.stdout
    print('PASS Divergent child is preserved and deployment stops')
