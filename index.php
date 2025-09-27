<?php
session_start();

// 載入共用函數
require_once __DIR__ . '/common/functions.php';

// 獲取當前 URI，去除 "/koilisu/" 前綴
$request_uri = trim(str_replace('/koilisu/', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');

// 檢查是否有 app 參數
$app_param = $_GET['app'] ?? null;

// 路由處理
if ($app_param) {
    // 自動導向路徑方式
    header("Location: /koilisu/{$app_param}");
    exit;
} else {
    // 原有的路徑路由處理
    switch ($request_uri) {
        case '':
        case 'index':
            // 主頁 - 顯示所有可用工具和說明文件
            loadPage('home');
            break;

        case 'docs':
            // 顯示架構說明文件
            loadPage('docs');
            break;

        default:
            // 檢查是否為應用路由
            $app_parts = explode('/', $request_uri);
            $app_name = $app_parts[0];
            $app_action = $app_parts[1] ?? 'index';

            if (is_dir(__DIR__ . "/apps/$app_name")) {
                // 載入對應的應用
                loadApp($app_name, $app_action);
            } else {
                // 404 錯誤
                http_response_code(404);
                include __DIR__ . '/../404.html';
            }
            break;
    }
}