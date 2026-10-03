# KoiLiSu 工具範本

這是 KoiLiSu 開利手專案的標準工具範本，用於快速建立新的小工具。

## 範本結構

```
app-template/
├── config.php      # 工具設定檔
├── index.php       # 主入口檔案
├── view.php        # 主檢視範本
└── README.md       # 說明文件
```

## 使用方法

也可以直接用 `templates/create-app.ps1` 一次完成複製與替換（見 `templates/EXAMPLES.md`）；以下是手動步驟。

### 1. 複製範本
```bash
cp -r templates/app-template apps/你的工具名稱
```

### 2. 替換範本變數

在新工具的所有檔案中，替換以下變數：

| 變數名稱 | 說明 | 範例 |
|----------|------|------|
| `{APP_NAME}` | 工具資料夾名稱 | `gradcheck` |
| `{APP_DISPLAY_NAME}` | 工具顯示名稱 | `學生畢業資格審查表` |
| `{APP_DESCRIPTION}` | 工具描述 | `亞洲大學學生畢業資格審查表下載工具` |
| `{ICON_NAME}` | 主要圖示名稱 | `graduation-cap` |
| `{INPUT_LABEL}` | 輸入欄位標籤 | `學號` |
| `{INPUT_ICON}` | 輸入欄位圖示 | `id-card` |
| `{INPUT_PLACEHOLDER}` | 輸入欄位提示文字 | `113151000` |

### 3. 實作核心邏輯

修改 `view.php` 中的 JavaScript 函式：

- `validateInput()` - 實作輸入驗證邏輯
- `executeMainFunction()` - 實作主要功能邏輯
- `generateActionButtons()` - 調整按鈕生成邏輯（如需要）

### 4. 測試工具

訪問 `/koilisu/你的工具名稱` 測試新工具。

## 範本特色

✅ **現代化 UI**：基於 Tocas UI 5.7.0
✅ **響應式設計**：支援各種螢幕尺寸
✅ **深淺色主題**：內建主題切換功能
✅ **Sticky Footer**：現代化版面配置
✅ **動態按鈕**：支援多輸入處理
✅ **統一風格**：與其他 KoiLiSu 工具保持一致

## 開發建議

1. **保持簡潔**：每個工具專注於單一功能
2. **使用者友善**：提供清楚的錯誤訊息和操作提示
3. **安全考量**：驗證所有使用者輸入
4. **效能最佳化**：避免不必要的 DOM 操作
5. **風格一致**：遵循 KoiLiSu 的設計規範

## 常見工具類型

- **下載工具**：如課表下載器、畢業資格審查表
- **轉換工具**：如格式轉換器、編碼解碼器
- **查詢工具**：如成績查詢、資料查找
- **計算工具**：如學分計算器、GPA 計算器
