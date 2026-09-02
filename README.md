# KoiLiSu Framework

> 讓工具使用起來更順手的開放框架

## 介紹

KoiLiSu（開利手）是一個現代化的 PHP 微框架，專為建立簡單實用的小工具而設計。

## 特色

✅ **現代化 UI**：基於 Tocas UI 5.0.3
✅ **響應式設計**：支援各種螢幕尺寸
✅ **深淺色主題**：內建主題切換功能
✅ **模組化架構**：輕鬆新增工具應用
✅ **應用範本**：快速建立新工具

## 快速開始

### 完整安裝(包含所有官方應用)

1. 克隆倉庫並初始化子模組：
```bash
git clone --recurse-submodules https://github.com/zisunny104/koilisu-framework.git
cd koilisu-framework
```

或者先克隆後初始化子模組：
```bash
git clone https://github.com/zisunny104/koilisu-framework.git
cd koilisu-framework
git submodule init
git submodule update
```

### 僅安裝框架

如果只需要框架本身：
```bash
git clone https://github.com/zisunny104/koilisu-framework.git
cd koilisu-framework
```

### 更新子模組

更新所有子模組到最新版本：
```bash
git submodule update --remote
```

2. 配置網頁伺服器指向專案根目錄

3. 訪問 `https://toka.dev/koilisu/` 查看框架首頁

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

KoiLiSu 框架使用 Git 子模組來管理官方應用，每個應用都有獨立的儲存庫：

- [gradcheck](https://github.com/zisunny104/gradcheck) - 畢業資格審查表下載工具
- [kobeu](https://github.com/zisunny104/kobeu) - 課表下載器
- [pitrace](https://github.com/zisunny104/pitrace) - 掃描手繪稿去背、校正、輸出透明 PNG
- [hapbun](https://github.com/zisunny104/hapbun) - PDF 合併排版工具

### 子模組優勢

- 🔄 **獨立開發**：每個應用可獨立版本控制
- 📌 **版本鎖定**：框架可鎖定特定版本的應用
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