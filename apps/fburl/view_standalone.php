<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FB 網址跳轉 | prjToka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/5.0.1/tocas.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/5.0.1/tocas.min.js"></script>

    <style type="text/css">
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body {
        display: flex;
        flex-direction: column;
    }

    .main-content {
        flex: 1;
    }

    /* 確保底部grid正確對齊 */
    .ts-grid {
        display: flex;
        align-items: center;
    }

    .ts-grid .column.is-fluid {
        flex: 1;
    }

    .ts-grid .column.is-end-aligned {
        text-align: right;
    }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="ts-container is-narrow">
            <div class="ts-space is-large"></div>

            <!-- 工具標題 -->
            <div class="ts-box is-rounded">
                <div class="ts-content is-padded">
                    <div class="ts-header is-large is-heavy">🔗 FB 網址跳轉</div>
                    <div class="ts-space"></div>
                    <div class="ts-text">
                        輸入 Facebook 網址，我們會幫你安全地跳轉過去。<br>
                        這樣可以避免在網頁原始碼中直接暴露 Facebook 連結。
                    </div>
                </div>
            </div>

            <div class="ts-space"></div>

            <!-- 錯誤訊息 -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="ts-message is-negative">
                    <div class="header">錯誤</div>
                    <p><?= htmlspecialchars($_SESSION['error']) ?></p>
                </div>
                <?php unset($_SESSION['error']); ?>
                <div class="ts-space"></div>
            <?php endif; ?>

            <!-- 跳轉表單 -->
            <div class="ts-box is-rounded">
                <div class="ts-content is-padded">
                    <form action="/koilisu/fburl/redirect" method="POST" class="ts-form">
                        <div class="field">
                            <label class="label">Facebook 網址</label>
                            <div class="ts-input is-fluid">
                                <input type="url" name="fb_url" placeholder="請輸入 Facebook 網址 (例: https://www.facebook.com/...)" required>
                            </div>
                            <div class="ts-text is-description">
                                支援 facebook.com 和 fb.com 網域的網址
                            </div>
                        </div>

                        <div class="ts-space"></div>

                        <button type="submit" class="ts-button is-primary is-large">
                            <i class="icon is-external-link-alt"></i>
                            立即跳轉
                        </button>
                    </form>
                </div>
            </div>

            <div class="ts-space"></div>

            <!-- 使用說明 -->
            <div class="ts-box is-rounded">
                <div class="ts-content is-padded">
                    <div class="ts-header">使用說明</div>
                    <div class="ts-space"></div>
                    <div class="ts-list is-unordered">
                        <div class="item">僅支援 Facebook 官方網域 (facebook.com, fb.com)</div>
                        <div class="item">系統會驗證網址格式的正確性</div>
                        <div class="item">跳轉過程完全在伺服器端進行，保護隱私</div>
                        <div class="item">如果網址無效，會顯示錯誤訊息</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 開利手底部 -->
    <div class="ts-content is-secondary is-vertically-padded">
        <div class="ts-container">
            <div class="ts-grid">
                <div class="column is-fluid">
                    <div class="ts-text is-description">
                        <a href="/koilisu/" style="color: inherit; text-decoration: none;">KoiLiSu 開利手</a> - 讓工具使用更順手的開放專案 | prjToka
                    </div>
                </div>
                <div class="column is-end-aligned">
                    <div class="ts-text is-description">
                        Built with ❤️ using Tocas UI
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
