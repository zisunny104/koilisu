<?php
$page_title = '架構文件';
$readme_path = __DIR__ . '/../docs/README.md';
?>

<div class="ts-space is-large"></div>

<div class="ts-box is-rounded">
    <div class="ts-content is-padded">
        <div class="ts-header is-large">KoiLiSu 架構說明</div>
        <div class="ts-space"></div>

        <div class="markdown-content">
            <?= renderMarkdown($readme_path) ?>
        </div>
    </div>
</div>
