#!/usr/bin/env pwsh
# KoiLiSu 新應用建立腳本

param(
    [Parameter(Mandatory = $true)]
    [string]$AppName,

    [Parameter(Mandatory = $true)]
    [string]$DisplayName,

    [Parameter(Mandatory = $true)]
    [string]$Description,

    [Parameter(Mandatory = $false)]
    [string]$IconName = "tool",

    [Parameter(Mandatory = $false)]
    [string]$InputLabel = "輸入",

    [Parameter(Mandatory = $false)]
    [string]$InputIcon = "edit",

    [Parameter(Mandatory = $false)]
    [string]$InputPlaceholder = "請輸入..."
)

Write-Host "🚀 建立新的 KoiLiSu 應用：$DisplayName" -ForegroundColor Green

# 檢查應用是否已存在
$appPath = "apps/$AppName"
if (Test-Path $appPath) {
    Write-Host "❌ 錯誤：應用 '$AppName' 已存在！" -ForegroundColor Red
    exit 1
}

# 複製範本
Write-Host "📁 複製應用範本..." -ForegroundColor Blue
Copy-Item -Path "templates/app-template" -Destination $appPath -Recurse

# 替換變數的函數
function Replace-Variables {
    param($FilePath, $Replacements)

    $content = Get-Content $FilePath -Raw
    foreach ($key in $Replacements.Keys) {
        $content = $content -replace [regex]::Escape($key), $Replacements[$key]
    }
    Set-Content $FilePath $content
}

# 準備替換變數
$replacements = @{
    '{APP_NAME}'          = $AppName
    '{APP_DISPLAY_NAME}'  = $DisplayName
    '{APP_DESCRIPTION}'   = $Description
    '{ICON_NAME}'         = $IconName
    '{INPUT_LABEL}'       = $InputLabel
    '{INPUT_ICON}'        = $InputIcon
    '{INPUT_PLACEHOLDER}' = $InputPlaceholder
}

# 替換所有檔案中的變數
Write-Host "🔄 設定應用參數..." -ForegroundColor Blue
$files = Get-ChildItem $appPath -Filter "*.php"
foreach ($file in $files) {
    Replace-Variables -FilePath $file.FullName -Replacements $replacements
    Write-Host "   ✅ 已更新：$($file.Name)" -ForegroundColor Gray
}

Write-Host "🎉 應用建立完成！" -ForegroundColor Green
Write-Host ""
Write-Host "📋 應用資訊：" -ForegroundColor Cyan
Write-Host "   名稱：$AppName" -ForegroundColor White
Write-Host "   顯示名稱：$DisplayName" -ForegroundColor White
Write-Host "   描述：$Description" -ForegroundColor White
Write-Host "   路徑：$appPath" -ForegroundColor White
Write-Host ""
Write-Host "🌐 訪問網址：" -ForegroundColor Cyan
Write-Host "   /koilisu/$AppName" -ForegroundColor White
Write-Host "   /koilisu/?app=$AppName" -ForegroundColor White
Write-Host ""
Write-Host "📝 下一步：" -ForegroundColor Yellow
Write-Host "   1. 編輯 $appPath/view.php 實作核心邏輯" -ForegroundColor White
Write-Host "   2. 修改 validateInput() 和 executeMainFunction() 函數" -ForegroundColor White
Write-Host "   3. 測試應用功能" -ForegroundColor White