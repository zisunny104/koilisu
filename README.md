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

### 安裝

1. 克隆倉庫：
```bash
git clone https://github.com/zisunny104/koilisu-framework.git
cd koilisu-framework
```

2. 配置網頁伺服器指向專案根目錄

3. 訪問 `/koilisu/` 查看框架首頁

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
├── apps/             # 應用目錄
└── index.php         # 主入口
```

## 官方應用

- [koilisu-gradcheck](https://github.com/zisunny104/koilisu-gradcheck) - 畢業資格審查表下載工具
- [koilisu-kobeu](https://github.com/zisunny104/koilisu-kobeu) - 課表下載器
- [koilisu-fburl](https://github.com/zisunny104/koilisu-fburl) - FB URL 跳轉工具

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