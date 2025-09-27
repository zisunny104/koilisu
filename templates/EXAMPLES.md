# KoiLiSu 範本使用範例

## 快速建立新應用

### 使用 PowerShell 腳本（推薦）

```powershell
# 切換到 koilisu 目錄
cd koilisu

# 建立新應用
.\templates\create-app.ps1 -AppName "myapp" -DisplayName "我的工具" -Description "這是我的第一個工具" -IconName "star" -InputLabel "資料" -InputPlaceholder "請輸入資料"
```

### 手動建立

```bash
# 1. 複製範本
cp -r templates/app-template apps/myapp

# 2. 手動編輯檔案，替換以下變數：
# {APP_NAME} -> myapp
# {APP_DISPLAY_NAME} -> 我的工具
# {APP_DESCRIPTION} -> 這是我的第一個工具
# {ICON_NAME} -> star
# {INPUT_LABEL} -> 資料
# {INPUT_ICON} -> edit
# {INPUT_PLACEHOLDER} -> 請輸入資料
```

## 實際範例

### 課表下載器 (kobeu)
```powershell
.\templates\create-app.ps1 -AppName "kobeu" -DisplayName "學生課表下載工具" -Description "亞洲大學學生課表下載工具，需要使用校內 VPN" -IconName "table" -InputLabel "學號" -InputIcon "id-card" -InputPlaceholder "113151000"
```

### 畢業資格審查表 (gradcheck)
```powershell
.\templates\create-app.ps1 -AppName "gradcheck" -DisplayName "學生畢業資格審查表" -Description "亞洲大學學生畢業資格審查表下載工具，需要使用校內 VPN" -IconName "graduation-cap" -InputLabel "學號" -InputIcon "id-card" -InputPlaceholder "113151000"
```

## 常用圖標名稱

- `table` - 表格
- `graduation-cap` - 畢業帽
- `download` - 下載
- `search` - 搜尋
- `calculator` - 計算機
- `file-text` - 文件
- `link` - 連結
- `star` - 星星
- `tool` - 工具
- `edit` - 編輯
- `id-card` - 身分證
- `user` - 使用者