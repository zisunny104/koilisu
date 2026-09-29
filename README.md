# KoiLiSu 開利手

> 讓生活中的小事更順手的開放專案

## 介紹

KoiLiSu（開利手）是一個開放的小工具集合，將一些讓日常使用更加順手的工具集中於此。

## 使用

KoiLiSu 提供各項工具的網頁入口，可直接使用：

https://toka.dev/koilisu/

## 本機部署

Clone 專案並 init submodule：

```bash
git clone --recurse-submodules https://github.com/zisunny104/koilisu.git
cd koilisu
```

或先 clone 後再 init：

```bash
git clone https://github.com/zisunny104/koilisu.git
cd koilisu
git submodule init
git submodule update
```

Pull 主專案並同步 submodule 到主專案記錄的 commit：

```bash
git pull
git submodule update --init
```

或直接把各 submodule 更新到其 remote 最新 commit：

```bash
git submodule update --remote
```

將網頁伺服器指向專案根目錄即可。

### 子專案依賴

各子專案的前端函式庫多由 CDN 載入，不需安裝；有伺服器端依賴的子專案附有 `deploy.sh`，在該子專案目錄執行即可 fetch、檢查 PHP 語法、fast-forward 更新，並檢查依賴：

- **hapbun**：中文字型 `fonts/`（不進版本控制），缺少時 `deploy.sh` 會引導下載，也可執行 `php install_font.php`；需要 PHP 的 `allow_url_fopen` 與 `openssl`
- **printan**：第三方檔案（libheif、Sarasa 字型）已隨 repo 散布，不需額外安裝

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
├── templates/        # 工具範本
│   └── app-template/
├── apps/             # 各工具子專案（gradcheck、kobeu、pitrace、hapbun、printan）
├── .gitmodules       # submodule 設定
└── index.php         # 主入口
```

各工具以獨立 repository 維護，並透過 Git submodule 與 KoiLiSu 串接。

## 專案命名

「開利手」取名自「開放」與「順手」的概念，希望工具能夠開放使用，也讓日常使用更加順手。

名稱中的「開」、「利」、「手」三字，取自客語四縣腔的讀音，並以其讀音組成 **KoiLiSu**。

四縣腔客語拼音：

**koiˊ · liˊ · suˋ**

## 回報問題

如果使用上遇到問題，可以提出 Issue。

## 授權

本專案採用 MIT License，詳見 [LICENSE](LICENSE)。

`apps/` 下的各子專案為獨立 repository，授權方式依各專案的 LICENSE 為準。

## 作者

Tokas (Xiang-zi Xie)
