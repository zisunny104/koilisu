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
?>
<div class="column">
    <a href="/koilisu/<?= htmlspecialchars($app_key) ?>" class="ts-box is-rounded app-card<?= $archived ? ' is-archived' : '' ?>">
        <div class="ts-content is-padded">
            <div class="ts-header">
                <?= htmlspecialchars($app_config['name']) ?>
                <?php if ($archived): ?>
                <span class="ts-badge is-secondary is-small">已封存</span>
                <?php endif; ?>
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
            <div class="ts-text is-description">
                版本: <?= htmlspecialchars($app_config['version']) ?>
            </div>
        </div>
    </a>
</div>
<?php
}
?>

<div class="ts-space is-large"></div>

<!-- 歡迎區塊 -->
<div class="ts-box is-rounded">
    <div class="ts-content is-padded">
        <div class="ts-header is-large is-heavy">歡迎使用 KoiLiSu 開利手</div>
        <div class="ts-space"></div>
        <div class="ts-text is-large">
            一個關於實用小工具的開放專案，讓日常操作更加順手。<br>
            每個工具都是獨立的小應用，可以單獨維護和使用。
        </div>
    </div>
</div>

<div class="ts-divider is-section"></div>

<!-- 工具列表 -->
<div class="ts-header is-large">可用工具</div>
<div class="ts-space"></div>

<?php if (empty($active_apps)): ?>
<div class="ts-box is-rounded">
    <div class="ts-content is-padded">
        <div class="ts-text is-center-aligned">
            <div class="ts-icon is-large is-faded">📦</div>
            <div class="ts-header">尚無可用工具</div>
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
<div class="ts-space is-small"></div>
<div class="ts-text is-description">仍可正常使用，只是不再主推的舊工具。</div>
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