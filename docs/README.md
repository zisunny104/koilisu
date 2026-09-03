# KoiLiSu 開利手工具集

## 專案概念

**KoiLiSu**（開利手）是一個收集實用小工具的開放專案，設計理念是讓每個工具都順手好用，並且可以獨立維護。

## 架構設計

### 目錄結構

```
koilisu/
├── index.php              # 路由入口
├── common/                # 共用元件
│   ├── functions.php      # 路由 / 頁面載入 / Markdown 轉換等共用函數
│   ├── header.php         # 主殼頁面 <head>、導覽列、共用樣式
│   └── footer.php         # 主殼頁面頁尾、深淺色主題切換
├── pages/                 # 主殼頁面（套用 header/footer）
│   ├── home.php           # 首頁（工具列表）
│   └── docs.php            # 本文件的渲染頁面
├── docs/
│   └── README.md           # 本文件
├── templates/               # 新增子專案的範本與腳本
│   ├── app-template/        # 子專案骨架（config.php / index.php / view.php）
│   ├── create-app.ps1        # 建立新子專案的 PowerShell 腳本
│   └── EXAMPLES.md
├── apps/                     # 各工具子專案（Git 子模組，各自獨立 repo）
│   ├── gradcheck/
│   ├── kobeu/
│   ├── pitrace/
│   └── hapbun/
├── .gitmodules                # 子模組對應設定
├── package.json                # 專案中繼資料（不依賴 Node 執行環境）
└── LICENSE
```

`apps/<name>/` 內部的共同結構（以 gradcheck 為例）：

```
apps/gradcheck/
├── config.php     # name / description / version / author
├── index.php      # 動作路由（switch $_APP['action']）
├── view.php       # 主要畫面，獨立完整的 HTML 頁面
├── README.md
└── LICENSE
```

個別子專案可能因需求多出額外檔案，例如 pitrace 有 `js/`，hapbun 有 `fonts/`、`install_font.php`，這些都不影響共用的路由慣例。

### 路由規則

- `/koilisu/` 或 `/koilisu/index` - 首頁，顯示所有可用工具
- `/koilisu/docs` - 本架構說明文件
- `/koilisu/{app_name}` - 交給對應子專案處理，動作預設為 `index`
- `/koilisu/{app_name}/{action}` - 交給對應子專案處理，並帶入指定動作
- `/koilisu/?app={app_name}` - 舊版相容參數，會導向 `/koilisu/{app_name}`
- 子專案目錄不存在，或路由無法解析時，回傳 `404.html`（此檔案不在 koilisu repo 內，位於部署站台的上一層目錄）

首頁與本文件（`pages/home.php`、`pages/docs.php`）是透過 `common/header.php`、`common/footer.php` 組成的「主殼頁面」；而 `/koilisu/{app_name}` 路由只會 include 該子專案自己的 `index.php`，**不會**套用主殼的 header/footer。也就是說每個子專案的 `view.php` 都是完整、獨立的 HTML 頁面，需要自行載入 Tocas UI 等前端資源。

### 新增工具的步驟

**方法一：使用範本腳本（建議）**

```
pwsh templates/create-app.ps1 -AppName your_tool_name -DisplayName "工具顯示名稱" -Description "工具描述"
```

腳本會把 `templates/app-template/` 複製到 `apps/your_tool_name/`，並將檔案中的 `{APP_NAME}`、`{APP_DISPLAY_NAME}`、`{APP_DESCRIPTION}` 等佔位字串換成實際內容。也可以透過 `package.json` 的別名執行，額外參數需要用 `--` 傳遞：

```
npm run create-app -- -AppName your_tool_name -DisplayName "工具顯示名稱" -Description "工具描述"
```

完成後，依需求修改 `apps/your_tool_name/view.php`（範本內建了 `validateInput()`、`executeMainFunction()` 兩個預留函數，供實作參考）。

**方法二：手動建立**

1. 建立 `apps/your_tool_name/` 目錄
2. 建立 `config.php`：

```
<?php
return [
    'name' => '工具顯示名稱',
    'description' => '工具描述',
    'version' => '1.0.0',
    'author' => '作者名稱'
];
```

3. 建立 `index.php`：

```
<?php
$config = include __DIR__ . '/config.php';

switch ($_APP['action']) {
    case 'index':
        include __DIR__ . '/view.php';
        break;

    default:
        redirect('your_tool_name');
}
```

4. 建立 `view.php`：完整、獨立的 HTML 頁面，不會套用 `common/header.php` / `footer.php`，需自行載入 Tocas UI 等資源。

### 設計原則

1. **獨立性** - 每個工具都是獨立的小應用，可以單獨維護，也是獨立的 Git repo
2. **一致性** - 目前各子專案都使用 Tocas UI 保持視覺一致，但這是慣例而非架構強制
3. **安全性** - 透過統一路由管理，避免直接暴露檔案路徑
4. **擴展性** - 新增工具不需要修改核心檔案

### 共用資源

- **UI 框架**：目前 4 個子專案都使用 Tocas UI —— gradcheck、kobeu、hapbun 為 5.0.3，pitrace 為 5.7.0（版本不一致，需要的話可自行更新對應子專案的 CDN 連結）。這是目前的實務慣例，路由層（`index.php` / `loadApp()`）本身不強制要求，子專案理論上可以選用其他前端方案，只要 `view.php` 能輸出完整的 HTML 頁面即可
- **字體**：Montserrat（Google Fonts，用於主殼標題）
- **共用函數**：`common/functions.php`（`redirect()`、`loadPage()`、`loadApp()`、`renderMarkdown()`、`getAvailableApps()`）
- **主殼頁面模板**：`common/header.php`、`common/footer.php`，僅套用於 `pages/` 下的頁面
- **新增工具範本**：`templates/app-template/`、`templates/create-app.ps1`

### 各子專案現況

- **gradcheck**（GradCheck，v1.0.0）- 透過學號查詢亞洲大學學生畢業資格審查表；Tocas UI 5.0.3；MIT License
- **kobeu**（KoBeo，v1.7.1）- 亞洲大學學生課表下載工具，需校內 VPN，提供 PDF / Excel 格式；Tocas UI 5.0.3；MIT License
- **pitrace**（拾印，v0.1.1）- 手繪／掃描素材去背、校正、透明化並個別輸出的圖形化工具；Tocas UI 5.7.0；MIT License
- **hapbun**（合本，v1.0.4）- PDF 合併排版工具，可設定多頁、封面、目錄、頁碼；Tocas UI 5.0.3；MIT License

### 與其他開發者協作

1. **理解架構** - 閱讀此文件了解整體設計
2. **建立新工具** - 優先使用 `templates/create-app.ps1` 建立骨架，維持與既有子專案一致的結構
3. **測試工具** - 確保新工具在獨立環境下正常運作（子專案頁面不依賴主殼的 header/footer）
4. **更新文件** - 如有架構變更，請同步更新此說明

### 技術棧

- **後端**：PHP 7.4+
- **前端**：Tocas UI（各子專案目前的慣例，非強制）＋ 原生 JavaScript
- **路由**：`index.php` 與 `common/functions.php` 組成的自訂路由系統
- **文件**：Markdown，透過 `renderMarkdown()` 逐行解析為 HTML，支援標題、粗體/斜體、行內與區塊程式碼、清單、連結、分隔線，但不是完整的 CommonMark 實作（例如不支援表格）

### 安全考量

- 所有使用者輸入都應該進行適當的驗證和過濾
- 檔案上傳功能請特別注意安全性
- 避免在工具中執行系統指令
- 使用 HTTPS 保護敏感資料傳輸

## 授權

- 根目錄（KoiLiSu 主殼）：MIT License，詳見 [LICENSE](../LICENSE)
- `apps/` 下每個子專案皆為獨立 repo，目前皆採用 MIT License：gradcheck、kobeu、pitrace、hapbun

各子專案授權可能各自異動，實際內容請以該子專案自己的 LICENSE 檔案為準。
