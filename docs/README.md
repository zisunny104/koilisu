# KoiLiSu 開利手工具集

## 專案概念

**KoiLiSu** (開利手) 是一個收集實用小工具的開放專案，設計理念是讓每個工具都順手好用，並且可以獨立維護。

## 架構設計

### 目錄結構

```
koilisu/
├── index.php              # 主要路由入口
├── common/                 # 共用元件
│   ├── functions.php       # 共用函數
│   ├── header.php          # 頁面頭部
│   └── footer.php          # 頁面尾部
├── pages/                  # 主要頁面
│   ├── home.php           # 首頁
│   └── docs.php           # 文件頁
├── docs/                   # 說明文件
│   └── README.md          # 架構說明
└── apps/                   # 應用程式目錄（Git 子模組）
    ├── gradcheck/         # 畢業資格審查表下載工具
    ├── kobeu/             # 課表下載器
    ├── pitrace/           # 掃描去背輸出工具
    ├── hapbun/            # PDF 合併排版工具
    │   ├── index.php      # 應用入口
    │   ├── config.php     # 應用設定
    │   └── view.php       # 主視圖
    └── [其他工具]/
```

### 路由規則

- `https://toka.dev/koilisu/` - 首頁，顯示所有可用工具
- `https://toka.dev/koilisu/docs` - 架構說明文件
- `https://toka.dev/koilisu/{app_name}` - 載入指定的應用程式
- `https://toka.dev/koilisu/{app_name}/{action}` - 執行應用程式的特定動作

### 新增工具的步驟

1. **建立應用目錄**

   ```
   mkdir apps/your_tool_name
   ```

2. **建立設定檔** (`apps/your_tool_name/config.php`)

   ```php
   <?php
   return [
       'name' => '工具顯示名稱',
       'description' => '工具描述',
       'version' => '1.0.0',
       'author' => '作者名稱'
   ];
   ```

3. **建立應用入口** (`apps/your_tool_name/index.php`)

   ```php
   <?php
   // 載入應用設定
   $config = include __DIR__ . '/config.php';

   // 處理不同動作
   switch ($_APP['action']) {
       case 'index':
           include __DIR__ . '/view.php';
           break;
       case 'process':
           include __DIR__ . '/handler.php';
           break;
       default:
           redirect('');
   }
   ```

### 設計原則

1. **獨立性** - 每個工具都是獨立的小應用，可以單獨維護
2. **一致性** - 使用相同的 UI 框架 (Tocas UI) 保持視覺一致
3. **安全性** - 透過統一路由管理，避免直接暴露檔案路徑
4. **擴展性** - 新增工具不需要修改核心檔案

### 共用資源

- **UI 框架**: Tocas UI 5.0.0
- **字體**: Montserrat (Google Fonts)
- **共用函數**: `common/functions.php`
- **頁面模板**: `common/header.php` 和 `common/footer.php`

### 與其他開發者協作

1. **理解架構** - 閱讀此文件了解整體設計
2. **遵循規範** - 新工具請按照既定的目錄結構建立
3. **測試工具** - 確保新工具在獨立環境下正常運作
4. **更新文件** - 如有架構變更，請同步更新此說明

### 技術棧

- **後端**: PHP 7.4+
- **前端**: Tocas UI + 原生 JavaScript
- **路由**: 自定義 PHP 路由系統
- **文件**: Markdown (簡易解析)

### 安全考量

- 所有用戶輸入都應該進行適當的驗證和過濾
- 檔案上傳功能請特別注意安全性
- 避免在工具中執行系統指令
- 使用 HTTPS 保護敏感資料傳輸

---

_最後更新: 2025 年 8 月_
