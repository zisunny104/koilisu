<?php
$page_title = '首頁';
$apps = getAvailableApps();

$active_apps = array_filter($apps, function ($config) {
    return ($config['status'] ?? 'active') !== 'archived';
});
$archived_apps = array_filter($apps, function ($config) {
    return ($config['status'] ?? 'active') === 'archived';
});

function renderAppCard($app_key, $app_config, $archived = false)
{
    $repo_url = 'https://github.com/zisunny104/koilisu/tree/main/apps/' . rawurlencode($app_key);
?>
<div class="column">
    <div class="ts-box is-rounded app-card<?= $archived ? ' is-archived' : '' ?>">
        <div class="ts-content is-padded">
            <div class="ts-header">
                <?php if ($archived): ?>
                <span class="ts-badge is-small is-end-spaced" style="--accent-color: #fef3c7; --accent-foreground-color: #92400e;">已封存</span>
                <?php endif; ?>
                <a href="/koilisu/<?= htmlspecialchars($app_key) ?>" class="stretched-link">
                    <?= htmlspecialchars($app_config['name']) ?>
                </a>
            </div>
            <div class="ts-space is-small"></div>
            <div class="ts-text"><?= htmlspecialchars($app_config['description']) ?></div>
            <?php if (!empty($app_config['tags'])): ?>
            <div class="ts-space is-small"></div>
            <div class="tag-list">
                <?php foreach ($app_config['tags'] as $tag): ?>
                <span class="ts-chip is-small"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="ts-space"></div>
            <div class="ts-grid">
                <div class="column is-fluid">
                    <div class="ts-text is-description">
                        版本: <?= htmlspecialchars($app_config['version']) ?>
                    </div>
                </div>
                <div class="column is-end-aligned">
                    <a href="<?= htmlspecialchars($repo_url) ?>" class="card-github-link" target="_blank"
                        rel="noopener noreferrer" aria-label="在 GitHub 上查看原始碼" title="在 GitHub 上查看原始碼">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<div class="ts-space is-large"></div>

<!-- 歡迎區塊 -->
<div class="ts-header is-large is-heavy">歡迎使用 KoiLiSu 開利手</div>
<div class="ts-space"></div>
<div class="ts-text is-large">
    一個關於實用小工具的開放專案，讓日常操作更加順手。<br>
    每個工具都可以獨立維護與使用。
</div>

<div class="ts-divider is-section"></div>

<!-- 工具列表 -->
<div class="ts-header is-large">工具列表</div>
<div class="ts-space"></div>

<?php if (empty($active_apps)): ?>
<div class="ts-box is-rounded">
    <div class="ts-content is-padded">
        <div class="ts-text is-center-aligned">
            <div class="ts-icon is-large is-faded">📦</div>
            <div class="ts-header">尚無工具</div>
            <div class="ts-text">工具正在開發中，敬請期待！</div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="ts-grid mobile:is-1-columns tablet:is-2-columns desktop+:is-3-columns">
    <?php foreach ($active_apps as $app_key => $app_config): ?>
    <?php renderAppCard($app_key, $app_config); ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!empty($archived_apps)): ?>
<div class="ts-divider is-section"></div>

<!-- 封存工具 -->
<div class="ts-header is-large">封存工具</div>
<div class="ts-space"></div>
<div class="ts-grid mobile:is-1-columns tablet:is-2-columns desktop+:is-3-columns">
    <?php foreach ($archived_apps as $app_key => $app_config): ?>
    <?php renderAppCard($app_key, $app_config, true); ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="ts-divider is-section"></div>

<!-- 架構說明 -->
<div class="ts-box is-rounded">
    <div class="ts-content is-padded">
        <div class="ts-header">架構說明</div>
        <div class="ts-space"></div>
        <div class="ts-text">
            想了解 KoiLiSu 的架構設計和使用方式嗎？
            <a href="/koilisu/docs" class="ts-text is-link">查看完整說明文件</a>
        </div>
    </div>
</div>