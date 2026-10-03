# KoiLiSu 開利手工具集

## 專案概念

**KoiLiSu**（開利手）是一個收集實用小工具的開放專案，設計理念是讓每個工具都順手好用，並且可以獨立維護。

## 架構設計

### 目錄結構

```
koilisu/
├── index.php              # 路由入口
├── common/                # 共用元件
│   ├── functions.php      # 路由 / 頁面載入 / Markdown 轉換等共用函式
│   ├── header.php         # 主殼頁面 <head>、導覽列、共用樣式
│   └── footer.php         # 主殼頁面頁尾、深淺色主題切換
├── pages/                 # 主殼頁面（套用 header/footer）
│   ├── home.php           # 首頁（工具列表）
│   └── docs.php            # 本文件的渲染頁面
├── docs/
│   └── README.md           # 本文件
├── tools/                   # 檢查腳本
│   ├── checkall.py          # 語法檢查（root 與工具的 PHP／JS）、渲染工具首頁並檢查內嵌 JS，最後執行下列檢查
│   ├── checktracked.py      # 檢查版本控制內沒有誤放的機密、暫存檔
│   ├── securitycheck.php    # 路徑與 Markdown 的安全回歸測試
│   └── client-security.mjs  # pitrace／printan 前端的 Markdown、資源 scheme、ZIP 安全測試
├── templates/               # 新增工具的範本與腳本
│   ├── app-template/        # 工具骨架（config.php / index.php / view.php）
│   ├── create-app.ps1        # 建立新工具的 PowerShell 腳本
│   └── EXAMPLES.md
├── apps/                     # 各工具（Git submodule，各自獨立 repo）
│   ├── gradcheck/
│   ├── kobeu/
│   ├── pitrace/
│   ├── hapbun/
│   └── printan/
├── .gitmodules                # submodule 對應設定
├── package.json                # 專案中繼資料與 create-app 指令別名（執行時不依賴 Node）
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

個別工具可能因需求多出額外檔案，例如 pitrace 有 `js/`，hapbun 有 `install_font.php`（字型快取 `fonts/` 不進版本控制），printan 有 `js/`、`partials/`、`vendor/`、`tests/` 與 `deploy.sh`，這些都不影響共用的路由慣例。

### 路由規則

- `/koilisu/` 或 `/koilisu/index` - 首頁，顯示所有可用工具
- `/koilisu/docs` - 本架構說明文件
- `/koilisu/{app_name}` - 交給對應工具處理，動作預設為 `index`
- `/koilisu/{app_name}/{action}` - 交給對應工具處理，並帶入指定動作
- `/koilisu/?app={app_name}` - 舊版相容參數，會導向 `/koilisu/{app_name}`
- 工具目錄不存在，或路由無法解析時，回傳 `404.html`（此檔案不在 koilisu repo 內，位於部署站台的上一層目錄）

首頁與本文件（`pages/home.php`、`pages/docs.php`）是透過 `common/header.php`、`common/footer.php` 組成的「主殼頁面」；而 `/koilisu/{app_name}` 路由只會 include 該工具自己的 `index.php`，**不會**套用主殼的 header/footer。也就是說每個工具的 `view.php` 都是完整、獨立的 HTML 頁面，需要自行載入 Tocas UI 等前端資源。

### 新增工具的步驟

**方法一：使用範本腳本（建議）**

```
pwsh templates/create-app.ps1 -AppName your_tool_name -DisplayName "工具顯示名稱" -Description "工具描述"
```

腳本會把 `templates/app-template/` 複製到 `apps/your_tool_name/`，並將檔案中的 `{APP_NAME}`、`{APP_DISPLAY_NAME}`、`{APP_DESCRIPTION}` 等佔位字串換成實際內容。也可以透過 `package.json` 的別名執行，額外參數需要用 `--` 傳遞：

```
npm run create-app -- -AppName your_tool_name -DisplayName "工具顯示名稱" -Description "工具描述"
```

完成後，依需求修改 `apps/your_tool_name/view.php`（範本內建了 `validateInput()`、`executeMainFunction()` 兩個預留函式，供實作參考）。

**方法二：手動建立**

1. 建立 `apps/your_tool_name/` 目錄
2. 建立 `config.php`：

```
<?php
return [
    'name' => '工具顯示名稱',
    'description' => '工具描述',
    'version' => '1.0.0',
    'author' => '作者名稱',
    'status' => 'archived', // 選填，預設視為 active；設為 archived 會歸類到首頁的「封存工具」區塊
    'tags' => ['分類1', '分類2'] // 選填，顯示在工具卡片上的標籤
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

1. **獨立性** - 每個工具都可以單獨維護，也是獨立的 Git repo
2. **一致性** - 目前各工具都使用 Tocas UI 保持視覺一致，但這是慣例而非架構強制
3. **安全性** - 透過統一路由管理，避免直接暴露檔案路徑
4. **擴展性** - 新增工具不需要修改核心檔案

### 共用資源

- **UI 框架**：目前 5 個工具與主殼統一使用 Tocas UI 5.7.0。這是目前的實務慣例，路由層（`index.php` / `loadApp()`）本身不強制要求，工具理論上可以選用其他前端方案，只要 `view.php` 能輸出完整的 HTML 頁面即可
- **字型**：Montserrat（Google Fonts，用於主殼標題）
- **共用函式**：`common/functions.php`（`redirect()`、`loadPage()`、`loadApp()`、`renderMarkdown()`、`getAvailableApps()`）
- **主殼頁面模板**：`common/header.php`、`common/footer.php`，僅套用於 `pages/` 下的頁面
- **新增工具範本**：`templates/app-template/`、`templates/create-app.ps1`
- **分類與封存**：`config.php` 的 `tags`（標籤陣列）與 `status`（`active` 或 `archived`）為選填欄位，由 `pages/home.php` 讀取後分別渲染成標籤 chip 與「封存工具」區塊，`getAvailableApps()` 本身不做任何處理

### 各工具現況

版本、標籤與封存狀態以各工具的 `config.php` 為準，首頁會直接讀取顯示。

- **gradcheck**（GradCheck）- 透過學號查詢亞洲大學學生畢業資格審查表；已封存
- **kobeu**（KoBeU）- 亞洲大學學生課表下載工具，需校內 VPN，提供 PDF / Excel 格式；已封存
- **pitrace**（拾印）- 手繪／掃描素材去背、校正、透明化並個別輸出的圖形化工具，支援 PDF 匯入
- **hapbun**（合本）- PDF 合併排版工具，可設定多頁、封面、目錄、頁碼
- **printan**（單仔）- 熱感紙收據／標籤設計與預覽工具，所見即所印，支援 Mail Merge 批次輸出；內含 LGPL-3.0 的 libheif-js 與 SIL OFL 的 Sarasa Mono TC，授權見其 LICENSE

### 與其他開發者協作

1. **理解架構** - 閱讀此文件了解整體設計
2. **建立新工具** - 優先使用 `templates/create-app.ps1` 建立骨架，維持與既有工具一致的結構
3. **測試工具** - 確保新工具在獨立環境下正常運作（工具頁面不依賴主殼的 header/footer）
4. **更新文件** - 如有架構變更，請同步更新此說明

### 使用技術

- **後端**：PHP 7.4+
- **前端**：Tocas UI（各工具目前的慣例，非強制）＋ 原生 JavaScript
- **路由**：`index.php` 與 `common/functions.php` 組成的自訂路由系統
- **文件**：Markdown，透過 `renderMarkdown()` 逐行解析為 HTML，支援標題、粗體/斜體、行內與區塊程式碼、清單、連結、分隔線，但不是完整的 CommonMark 實作（例如不支援表格）

### 安全考量

- 所有使用者輸入都應該進行適當的驗證和過濾
- 檔案上傳功能請特別注意安全性
- 避免在工具中執行系統指令
- 使用 HTTPS 保護敏感資料傳輸

## 授權

- 根目錄（KoiLiSu 主殼）：MIT License，詳見 [LICENSE](../LICENSE)
- `apps/` 下每個工具皆為獨立 repo，目前皆採用 MIT License：gradcheck、kobeu、pitrace、hapbun、printan
- 工具用到的第三方元件（CDN 載入或隨 repo 散布）各自列在該工具 LICENSE 的「第三方元件」段落

各工具授權可能各自異動，實際內容請以該工具自己的 LICENSE 檔案為準。
