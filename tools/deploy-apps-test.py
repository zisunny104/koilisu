"""Run deploy.sh's child-deployment mode in isolated repositories."""
from pathlib import Path
import os
import shutil
import subprocess
import tempfile

ROOT = Path(__file__).resolve().parents[1]
with tempfile.TemporaryDirectory(prefix='koilisu-deploy-apps-') as temporary:
    root = Path(temporary)
    (root / 'tools').mkdir()
    shutil.copy(ROOT / 'deploy.sh', root / 'deploy.sh')
    for name in ['deploy-output.sh','deploy-apps.sh']:
        shutil.copy(ROOT / 'tools' / name, root / 'tools' / name)
    subprocess.run(['git','init','-q',str(root)],check=True)
    modules = []
    for name in ['alpha','broken','omega','archived']:
        path = root / 'apps' / name
        path.mkdir(parents=True)
        subprocess.run(['git','init','-q',str(path)],check=True)
        (path / 'config.php').write_text("<?php return ['version'=>'1.2.3'];")
        subprocess.run(['git','-C',str(path),'add','config.php'],check=True)
        subprocess.run(['git','-C',str(path),'-c','user.name=Test','-c','user.email=test@example.invalid','-c','commit.gpgsign=false','commit','-qm','fixture'],check=True)
        if name != 'archived':
            (path / 'deploy.sh').write_text('''#!/bin/bash
printf '%s:%s:%s:%s\\n' "$PWD" "${DEPLOY_CHECK_URL-unset}" "${DEPLOY_BRANCH-unset}" "${DEPLOY_RELOAD_CMD-unset}" >> "$TEST_LOG"
exit ''' + ('9' if name == 'broken' else '0') + '\n')
        modules.append(f'[submodule "apps/{name}"]\npath = apps/{name}\nurl = example\n')
    (root / '.gitmodules').write_text(''.join(modules))
    log = root / 'runs'
    env = dict(os.environ,TEST_LOG=str(log),DEPLOY_CHECK_URL='https://parent.invalid',DEPLOY_BRANCH='parent',DEPLOY_RELOAD_CMD='parent')
    def run(selection):
        log.write_text('')
        return subprocess.run(['bash','deploy.sh','--deploy-apps',selection],cwd=root,env=env,text=True,capture_output=True)
    result=run('omega,alpha,omega')
    assert result.returncode == 0, result.stdout+result.stderr
    lines=log.read_text().splitlines()
    assert len(lines)==2 and '/omega:unset:unset:unset' in lines[0] and '/alpha:' in lines[1]
    assert 'v1.2.3' in result.stdout and '目前提交' in result.stdout
    print('PASS Selected children run in order, without duplicates or inherited parent settings')
    result=run('all')
    assert result.returncode != 0 and len(log.read_text().splitlines())==3
    assert '成功：2 · 失敗：1 · 略過：1' in result.stdout
    print('PASS All children run despite failures; missing scripts are reported as skipped')
    result=run('../outside')
    assert result.returncode == 2 and not log.read_text()
    print('PASS Unregistered paths are rejected before running any child')
    result=subprocess.run(['bash','deploy.sh','--deploy-apps','alpha','--check-only'],cwd=root,env=env,capture_output=True)
    assert result.returncode==2
    print('PASS Incompatible modes are rejected')
