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

在伺服器的專案目錄執行 `./deploy.sh`：先檢查工作目錄與 PHP 語法，再 fast-forward 更新主專案，最後把各工具對齊到主專案記錄的版本。

- `./deploy.sh --set-check-url https://example.com/project` 儲存網站網址，之後部署會自動檢查 `.git/` 是否能被網頁下載
- `./deploy.sh --check-only` 只跑這項檢查
- 一般部署由母專案同步各子模組。若要保留其他工具的 VPS 版本，可在
  提供 `deploy.sh` 的工具目錄內單獨部署；母專案下次同步時，仍會將工具
  對齊到它記錄的提交版本。

KhaiFile 提供 Office 文件的 ODF 與 PDF 轉換、PDF 壓縮、改名及批次下載。
其 PHP-FPM、LibreOffice、Ghostscript、隔離工具與清理排程由 KhaiFile 的
部署腳本設定，母專案部署不會代為執行。依賴、資源上限及隔離驗證限制
詳見 [KhaiFile 說明](apps/khaifile/README.md)。

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
