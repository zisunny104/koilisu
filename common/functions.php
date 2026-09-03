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
 * 轉換一行內的 Markdown 行內語法（粗體、斜體、行內程式碼、連結）
 */
function renderMarkdownInline($text)
{
    $text = htmlspecialchars($text);
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em>$1</em>', $text);
    $text = preg_replace('/`([^`]+?)`/', '<code>$1</code>', $text);
    $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $text);
    return $text;
}

/**
 * 讀取 Markdown 檔案並轉換為 HTML
 *
 * 逐行處理的精簡 Markdown 轉換，支援標題、粗體/斜體、行內與區塊程式碼、
 * 清單、連結、分隔線；不是完整的 CommonMark 實作。
 */
function renderMarkdown($file_path)
{
    if (!file_exists($file_path)) {
        return '<p>檔案不存在</p>';
    }

    $lines = explode("\n", file_get_contents($file_path));
    $html = '';
    $in_code = false;
    $code_lines = [];
    $list_type = null;
    $paragraph = [];

    $flush_paragraph = function () use (&$paragraph, &$html) {
        if (!empty($paragraph)) {
            $text = trim(implode(' ', $paragraph));
            if ($text !== '') {
                $html .= '<p>' . renderMarkdownInline($text) . '</p>' . "\n";
            }
            $paragraph = [];
        }
    };

    $close_list = function () use (&$list_type, &$html) {
        if ($list_type) {
            $html .= "</{$list_type}>\n";
            $list_type = null;
        }
    };

    foreach ($lines as $line) {
        if (preg_match('/^```/', $line)) {
            if ($in_code) {
                $html .= '<pre><code>' . htmlspecialchars(implode("\n", $code_lines)) . '</code></pre>' . "\n";
                $code_lines = [];
                $in_code = false;
            } else {
                $flush_paragraph();
                $close_list();
                $in_code = true;
            }
            continue;
        }

        if ($in_code) {
            $code_lines[] = $line;
            continue;
        }

        if (preg_match('/^### (.*)$/', $line, $m)) {
            $flush_paragraph();
            $close_list();
            $html .= '<h3>' . renderMarkdownInline($m[1]) . '</h3>' . "\n";
            continue;
        }
        if (preg_match('/^## (.*)$/', $line, $m)) {
            $flush_paragraph();
            $close_list();
            $html .= '<h2>' . renderMarkdownInline($m[1]) . '</h2>' . "\n";
            continue;
        }
        if (preg_match('/^# (.*)$/', $line, $m)) {
            $flush_paragraph();
            $close_list();
            $html .= '<h1>' . renderMarkdownInline($m[1]) . '</h1>' . "\n";
            continue;
        }

        if (preg_match('/^- (.*)$/', $line, $m)) {
            $flush_paragraph();
            if ($list_type !== 'ul') {
                $close_list();
                $html .= "<ul>\n";
                $list_type = 'ul';
            }
            $html .= '<li>' . renderMarkdownInline($m[1]) . '</li>' . "\n";
            continue;
        }
        if (preg_match('/^\d+\. (.*)$/', $line, $m)) {
            $flush_paragraph();
            if ($list_type !== 'ol') {
                $close_list();
                $html .= "<ol>\n";
                $list_type = 'ol';
            }
            $html .= '<li>' . renderMarkdownInline($m[1]) . '</li>' . "\n";
            continue;
        }

        if (trim($line) === '---') {
            $flush_paragraph();
            $close_list();
            $html .= "<hr>\n";
            continue;
        }

        if (trim($line) === '') {
            $flush_paragraph();
            $close_list();
            continue;
        }

        $paragraph[] = $line;
    }

    $flush_paragraph();
    $close_list();
    if ($in_code) {
        $html .= '<pre><code>' . htmlspecialchars(implode("\n", $code_lines)) . '</code></pre>' . "\n";
    }

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
