# KoiLiSu 開利手

> 用小工具讓生活更方便的開放專案

## 介紹

KoiLiSu（開利手）是我個人支持開放理念的 side project：用一個個小工具，讓生活中一些瑣碎但常做的事情更順手。目前收錄的工具多半是在亞洲大學生活中遇到實際需求而動手做的——下載課表、列印畢業資格審查表、去背掃描手稿、合併講義 PDF——共用同一套介面與路由，一個入口就能找到所有工具。

每個工具仍是獨立維運的儲存庫（以 Git 子模組串接），可以各自開發、測試、部署；KoiLiSu 則是把它們集合起來的共用外殼，也是持續成長的工具家族。

## 特色

✅ **一站式入口**：多個校園工具集中管理，不用到處找連結
✅ **獨立又一致**：每個工具可各自開發部署，介面與操作體驗保持一致
✅ **模組化架構**：新增工具不需要修改核心程式
✅ **深淺色主題**：內建主題切換功能
✅ **響應式設計**：支援各種螢幕尺寸

## 快速開始

### 完整安裝（包含所有官方應用）

1. 克隆倉庫並初始化子模組：
```bash
git clone --recurse-submodules https://github.com/zisunny104/koilisu.git
cd koilisu
```

或者先克隆後初始化子模組：
```bash
git clone https://github.com/zisunny104/koilisu.git
cd koilisu
git submodule init
git submodule update
```

### 僅安裝主站本身

如果只需要主站骨架，不含各工具：
```bash
git clone https://github.com/zisunny104/koilisu.git
cd koilisu
```

### 更新子模組

更新所有子模組到最新版本：
```bash
git submodule update --remote
```

2. 配置網頁伺服器指向專案根目錄

3. 透過 `https://toka.dev/koilisu/` 造訪首頁

### 建立新應用

使用內建的應用範本：

```powershell
.\templates\create-app.ps1 -AppName "myapp" -DisplayName "我的工具" -Description "工具描述"
```

## 架構

```
koilisu/
├── common/           # 共用功能
│   ├── functions.php # 核心函數
│   ├── header.php    # 頁面標頭
│   └── footer.php    # 頁面底部
├── pages/            # 靜態頁面
│   ├── home.php      # 首頁
│   └── docs.php      # 文件頁面
├── templates/        # 應用範本
│   └── app-template/ # 標準範本
├── apps/             # 應用目錄（Git 子模組）
│   ├── gradcheck/    # → gradcheck
│   ├── kobeu/        # → kobeu
│   ├── pitrace/      # → pitrace
│   └── hapbun/       # → hapbun
├── .gitmodules       # 子模組配置
└── index.php         # 主入口
```

## 官方應用（Git 子模組）

KoiLiSu 使用 Git 子模組來管理官方應用，每個應用都有獨立的儲存庫：

- [gradcheck](https://github.com/zisunny104/gradcheck) - 畢業資格審查表下載工具
- [kobeu](https://github.com/zisunny104/kobeu) - 課表下載器
- [pitrace](https://github.com/zisunny104/pitrace) - 掃描手繪稿去背、校正、輸出透明 PNG
- [hapbun](https://github.com/zisunny104/hapbun) - PDF 合併排版工具

### 子模組優勢

- 🔄 **獨立開發**：每個應用可獨立版本控制
- 📌 **版本鎖定**：主站可鎖定特定版本的應用
- 🚀 **靈活部署**：可選擇安裝全部或部分應用
- 📊 **清晰依賴**：GitHub 自動顯示子模組連結

## 開發指南

### 應用結構

每個應用包含：
- `config.php` - 應用設定
- `index.php` - 主入口
- `view.php` - 主視圖

### 範本變數

- `{APP_NAME}` - 應用名稱
- `{APP_DISPLAY_NAME}` - 顯示標題
- `{APP_DESCRIPTION}` - 應用描述
- `{ICON_NAME}` - 主要圖標

## 貢獻

歡迎提交 Pull Request 或建立 Issue！

## 授權

MIT License

## 作者

Tokas (Xiang-zi Xie)
