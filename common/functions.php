<?php

/**
 * KoiLiSu 共用函數庫
 * 開利手 - 順手好用的開放工具集
 */

/**
 * 重新導向到指定 URL
 */
function redirect($url, $permanent = false)
{
    $status_code = $permanent ? 301 : 302;
    http_response_code($status_code);
    header("Location: /koilisu/$url");
    exit;
}

/**
 * 載入頁面（用於主要頁面如首頁、文件頁）
 */
function loadPage($page_name)
{
    $page_file = __DIR__ . "/../pages/$page_name.php";

    if (file_exists($page_file)) {
        include __DIR__ . '/header.php';
        include $page_file;
        include __DIR__ . '/footer.php';
    } else {
        http_response_code(404);
        include __DIR__ . '/../../404.html';
    }
}

/**
 * 載入應用程式
 */
function loadApp($app_name, $action = 'index')
{
    $app_dir = __DIR__ . "/../apps/$app_name";
    $app_file = "$app_dir/index.php";

    if (file_exists($app_file)) {
        // 設定應用程式上下文
        $_APP = [
            'name' => $app_name,
            'action' => $action,
            'dir' => $app_dir
        ];

        include $app_file;
    } else {
        http_response_code(404);
        include __DIR__ . '/../../404.html';
    }
}

/**
 * 讀取 Markdown 檔案並轉換為 HTML
 */
function renderMarkdown($file_path)
{
    if (!file_exists($file_path)) {
        return '<p>檔案不存在</p>';
    }

    $markdown_content = file_get_contents($file_path);

    // 簡單的 Markdown 轉換（你可以之後整合 Parsedown 或其他 Markdown 解析器）
    $html = htmlspecialchars($markdown_content);
    $html = preg_replace('/^# (.*$)/m', '<h1>$1</h1>', $html);
    $html = preg_replace('/^## (.*$)/m', '<h2>$1</h2>', $html);
    $html = preg_replace('/^### (.*$)/m', '<h3>$1</h3>', $html);
    $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
    $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);
    $html = preg_replace('/`(.*?)`/', '<code>$1</code>', $html);
    $html = preg_replace('/\n\n/', '</p><p>', $html);
    $html = '<p>' . $html . '</p>';

    return $html;
}

/**
 * 取得所有可用的應用程式列表
 */
function getAvailableApps()
{
    $apps = [];
    $apps_dir = __DIR__ . '/../apps';

    if (is_dir($apps_dir)) {
        $dirs = scandir($apps_dir);
        foreach ($dirs as $dir) {
            if ($dir !== '.' && $dir !== '..' && is_dir("$apps_dir/$dir")) {
                $config_file = "$apps_dir/$dir/config.php";
                if (file_exists($config_file)) {
                    $config = include $config_file;
                    $apps[$dir] = $config;
                } else {
                    $apps[$dir] = [
                        'name' => ucfirst($dir),
                        'description' => '無描述',
                        'version' => '1.0.0'
                    ];
                }
            }
        }
    }

    return $apps;
}
