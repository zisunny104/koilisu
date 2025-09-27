<!DOCTYPE html>
<html lang="zh-tw" id="html" class="is-rounded">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'KoiLiSu' ?> | prjToka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/5.0.3/tocas.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/5.0.3/tocas.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">
    <style>
    /* 重置瀏覽器預設，現代 sticky footer 布局 */
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .main-content {
        flex: 1;
    }

    .koilisu-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .app-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .app-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .markdown-content {
        line-height: 1.6;
    }

    .markdown-content h1,
    .markdown-content h2,
    .markdown-content h3 {
        margin-top: 1.5em;
        margin-bottom: 0.5em;
    }

    .markdown-content code {
        background-color: #f4f4f4;
        padding: 2px 4px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
    }

    /* 段落間距改善 */
    .ts-space.is-section {
        margin: 2rem 0;
    }

    .ts-divider {
        margin: 1.5rem 0;
    }
    </style>
</head>

<body>
    <div class="main-content">
        <?php if (!isset($hide_header) || !$hide_header): ?>
        <!-- 導航欄 -->
        <div class="ts-content is-vertically-padded koilisu-header">
            <div class="ts-container">
                <div class="ts-grid">
                    <div class="column is-fluid">
                        <div class="ts-header is-big is-heavy" style="font-family: 'Montserrat', sans-serif;">
                            <a href="/koilisu/" style="color: white; text-decoration: none;">
                                KoiLiSu 開利手
                            </a>
                        </div>
                        <div class="ts-text is-description">順手好用的開放工具集</div>
                    </div>
                    <div class="column">
                        <div class="ts-tab is-secondary is-inverted">
                            <a class="item <?= (($_SERVER['REQUEST_URI'] ?? '') === '/koilisu/' || ($_SERVER['REQUEST_URI'] ?? '') === '/koilisu/index') ? 'is-active' : '' ?>"
                                href="/koilisu/">首頁</a>
                            <a class="item <?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/koilisu/docs') === 0) ? 'is-active' : '' ?>"
                                href="/koilisu/docs">文件</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 主要內容區 -->
        <div class="ts-content is-padded" style="flex: 1;">
            <div class="ts-container"><?php