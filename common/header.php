<!DOCTYPE html>
<html lang="zh-tw" id="html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'KoiLiSu' ?> | prjToka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/5.7.0/tocas.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/5.7.0/tocas.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">
    <style>
    /* 重置瀏覽器預設，現代 sticky footer 版面 */
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

    /* 副標題與導覽文字：Tocas 的 is-description／預設文字色是灰階，
       疊在紫色漸層背景上對比不足，這裡改用半透明白色 */
    .koilisu-header .ts-text.is-description {
        color: rgba(255, 255, 255, 0.85);
    }

    .koilisu-header .ts-tab .item {
        color: rgba(255, 255, 255, 0.8);
        border-bottom-color: transparent;
    }

    .koilisu-header .ts-tab .item:hover {
        color: #fff;
    }

    .koilisu-header .ts-tab .item.is-active {
        color: #fff;
        border-bottom-color: #fff;
    }

    .app-card {
        position: relative;
        display: block;
        color: inherit;
        transition: all 0.3s ease;
    }

    .app-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* 卡片標題連結整個卡片可點擊，GitHub 連結浮在上層獨立可點 */
    .app-card .stretched-link {
        color: inherit;
        text-decoration: none;
        cursor: pointer;
    }

    .app-card .stretched-link::after {
        content: "";
        position: absolute;
        inset: 0;
    }

    .app-card .card-github-link {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--ts-gray-500);
    }

    .app-card .card-github-link:hover {
        color: var(--ts-gray-800);
    }

    .app-card.is-archived {
        opacity: 0.7;
    }

    .app-card.is-archived:hover {
        opacity: 1;
    }

    .tag-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4em;
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
        background-color: var(--ts-gray-100);
        padding: 2px 4px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
    }

    .markdown-content pre {
        background-color: var(--ts-gray-100);
        padding: 1em;
        border-radius: 6px;
        overflow-x: auto;
    }

    .markdown-content pre code {
        background: none;
        padding: 0;
    }

    .markdown-content ul,
    .markdown-content ol {
        padding-left: 1.5em;
    }

    /* 段落間距改善 */
    .ts-space {
        display: block;
        height: var(--ts-space-gap);
    }

    .ts-space.is-small {
        height: var(--ts-space-gap-small);
    }

    .ts-space.is-large {
        height: var(--ts-space-gap-large);
    }

    .ts-space.is-section {
        margin: 2rem 0;
    }

    .ts-divider {
        margin: 1.5rem 0;
    }

    /* 無障礙：跳至主要內容 */
    .skip-link {
        position: absolute;
        left: -9999px;
        top: 0;
        z-index: 1000;
        padding: 0.5em 1em;
        background: #1b1b1d;
        color: white;
        text-decoration: none;
        border-radius: 0 0 6px 0;
    }

    .skip-link:focus {
        left: 0;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
    </style>
</head>

<body class="is-rounded">
    <a href="#main-content" class="skip-link">跳至主要內容</a>
    <div class="main-content">
        <?php if (!isset($hide_header) || !$hide_header): ?>
        <!-- 導覽列 -->
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
                        <nav class="ts-tab" aria-label="主要導覽">
                            <?php $is_home = (($_SERVER['REQUEST_URI'] ?? '') === '/koilisu/' || ($_SERVER['REQUEST_URI'] ?? '') === '/koilisu/index'); ?>
                            <a class="item <?= $is_home ? 'is-active' : '' ?>" href="/koilisu/"
                                <?= $is_home ? 'aria-current="page"' : '' ?>>首頁</a>
                            <?php $is_docs = (strpos($_SERVER['REQUEST_URI'] ?? '', '/koilisu/docs') === 0); ?>
                            <a class="item <?= $is_docs ? 'is-active' : '' ?>" href="/koilisu/docs"
                                <?= $is_docs ? 'aria-current="page"' : '' ?>>文件</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 主要內容區 -->
        <main id="main-content" class="ts-content is-vertically-padded" style="flex: 1;">
            <div class="ts-container"><?php