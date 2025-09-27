<?php
// 載入應用設定
$config = include __DIR__ . '/config.php';

// 處理不同動作
switch ($_APP['action']) {
    case 'index':
        // 顯示工具介面
        include __DIR__ . '/view.php';
        break;

    case 'redirect':
        // 處理跳轉邏輯
        include __DIR__ . '/handler.php';
        break;

    default:
        redirect('fburl');
}
