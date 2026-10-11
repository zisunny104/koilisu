# KoiLiSu 開利手

> 讓生活中的小事更順手的開放專案

## 介紹

KoiLiSu（開利手）是一個開放的小工具集合，將一些讓日常使用更加順手的工具集中於此。

## 使用

KoiLiSu 提供各項工具的網頁入口，可直接使用：

https://toka.dev/koilisu/

## 專案結構

```text
koilisu/
├── common/           # 共用功能
│   ├── functions.php
│   ├── header.php
│   └── footer.php
├── pages/            # 靜態頁面
│   ├── home.php
│   └── docs.php
├── templates/        # 新增工具的範本與建立腳本
│   └── app-template/
├── docs/             # 架構說明（/koilisu/docs 顯示的內容）
├── tools/            # 檢查腳本（tools/checkall.py 等）
├── apps/             # 各工具（gradcheck、kobeu、pitrace、hapbun、printan、khaifile、souliong）
├── .gitmodules       # submodule 設定
└── index.php         # 主入口
```

各工具以獨立儲存庫維護，並透過 Git submodule 與 KoiLiSu 串接；主專案記錄的是各工具已審閱的版本。

## 部署

在伺服器的專案目錄執行 `./deploy.sh`：先檢查工作目錄，再快轉更新母專案與同步各工具。語法檢查與完整測試在提交前或 CI 執行，部署只檢查執行環境與網站。

- `./deploy.sh --set-check-url https://example.com/project` 儲存網站網址，之後部署會自動檢查 `.git/` 是否能被網頁下載
- `./deploy.sh --check-only` 只跑這項檢查
- 一般部署由母專案同步各子模組。若要保留其他工具的 VPS 版本，可在
  提供 `deploy.sh` 的工具目錄內單獨部署；母專案下次同步時會快轉較舊版本，保留沿同一
  歷史較新的提交；版本分歧或有未提交的檔案修改時中止。

KhaiFile 提供 Office 文件的 ODF 與 PDF 轉換、PDF 壓縮、改名及批次下載。
其 PHP-FPM、LibreOffice、Ghostscript、隔離工具與清理排程由 KhaiFile 的
部署腳本設定，母專案部署不會代為執行。依賴、資源上限及隔離驗證限制
詳見 [KhaiFile 說明](apps/khaifile/README.md)。

同步子專案時，每個工具都會列出專案版本與目前提交，即使版本沒有變更。

需要重新執行子專案的完整部署流程時：

```bash
./deploy.sh --deploy-apps all
./deploy.sh --deploy-apps khaifile
./deploy.sh --deploy-apps khaifile,printan
```

此模式直接依序執行已登記工具的 `deploy.sh`，即使程式未更新也會執行，
不更新母專案或同步子模組。沒有部署腳本的封存工具會列為略過；某項失敗
仍繼續處理其他工具，最後彙整結果並以非零狀態退出。它不會略過子專案
自己的 Git 或環境檢查，也不會強制重設版本。子專案使用自己的分支、
檢查網址與重載設定，不繼承母專案的對應環境變數。

子專案可能更新到比母專案記錄更新的提交；一般部署會保留這些提交並
列出提醒，不將它們當成檔案修改或退回舊版。其他主機若也要使用這個版本，
仍須更新母專案的子模組記錄。

## 專案命名

「開利手」取名自「開放」與「順手」的概念，希望工具能夠開放使用，也讓日常使用更加順手。

名稱中的「開」、「利」、「手」三字，取自客語四縣腔的讀音，並以其讀音組成 **KoiLiSu**。

四縣腔客語拼音：

**koiˊ · liˊ · suˋ**

## 回報問題

如果使用上遇到問題，可以提出 Issue。

## 授權

本專案採用 MIT License，詳見 [LICENSE](LICENSE)。

`apps/` 下的各工具為獨立 repository，授權方式依各工具的 LICENSE 為準。

## 作者

Tokas (Xiang-zi Xie)

子專案部署結果及一般同步會列出與母專案記錄不同的工具、記錄提交、本機提交，以及較新／較舊的提交數或歷史分歧。比較不需額外取得遠端版本，也不會重設本機提交。
