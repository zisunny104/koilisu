<?php
// FB 網址跳轉處理邏輯

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('fburl');
}

if (!isset($_POST['fb_url']) || empty($_POST['fb_url'])) {
    $_SESSION['error'] = '請輸入 Facebook 網址';
    redirect('fburl');
}

$fb_url = trim($_POST['fb_url']);

// 驗證網址格式
if (!filter_var($fb_url, FILTER_VALIDATE_URL)) {
    $_SESSION['error'] = '請輸入有效的網址格式';
    redirect('fburl');
}

// 檢查是否為 Facebook 網域
$parsed_url = parse_url($fb_url);
$allowed_domains = ['facebook.com', 'www.facebook.com', 'fb.com', 'www.fb.com', 'm.facebook.com'];

if (!isset($parsed_url['host']) || !in_array($parsed_url['host'], $allowed_domains)) {
    $_SESSION['error'] = '僅支援 Facebook 官方網域 (facebook.com, fb.com)';
    redirect('fburl');
}

// 檢查協定 (只允許 https)
if (isset($parsed_url['scheme']) && $parsed_url['scheme'] !== 'https') {
    // 自動轉換為 https
    $fb_url = str_replace('http://', 'https://', $fb_url);
}

// 如果沒有協定，自動加上 https
if (!isset($parsed_url['scheme'])) {
    $fb_url = 'https://' . $fb_url;
}

// 記錄跳轉 (可選，用於統計)
// 這裡可以加入日誌記錄功能

// 執行跳轉
header("Location: $fb_url");
exit;
