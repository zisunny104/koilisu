<!DOCTYPE html>
<html id="html" class="is-rounded" lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FB 網址跳轉 | prjToka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/5.0.3/tocas.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/5.0.3/tocas.min.js"></script>

    <style type="text/css">
    /* 現代 sticky footer 布局 */
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
                    <div class="ts-text is-description">
                        Built with ❤️ using Tocas UI
                    </div>
                </div>
                <div class="column is-end-aligned">
                    <div class="ts-selection is-circular is-compact">
                        <label class="item">
                            <input type="radio" name="theme" value="light" id="theme-light">
                            <div class="text"><i class="sun icon"></i></div>
                        </label>
                        <label class="item">
                            <input checked type="radio" name="theme" value="system" id="theme-system">
                            <div class="text"><i class="desktop icon"></i></div>
                        </label>
                        <label class="item">
                            <input type="radio" name="theme" value="dark" id="theme-dark">
                            <div class="text"><i class="moon icon"></i></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // 深淺色模式功能
    function setTheme(theme) {
        document.getElementById('html').className = theme === 'system'
            ? 'is-rounded'
            : `is-rounded is-${theme}`;

        // Save theme preference to cookie
        document.cookie = `preferred-theme=${theme}; path=/; max-age=31536000`; // 1 year
    }

    function getPreferredTheme() {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'preferred-theme') {
                return value;
            }
        }
        return 'system'; // Default theme
    }

    // 初始化主題
    document.addEventListener('DOMContentLoaded', function() {
        const preferredTheme = getPreferredTheme();
        const themeRadio = document.getElementById(`theme-${preferredTheme}`);
        if (themeRadio) {
            themeRadio.checked = true;
            setTheme(preferredTheme);
        }
    });

    // Theme change event listeners
    document.getElementById('theme-light').addEventListener('change', function () {
        if (this.checked) {
            setTheme('light');
        }
    });

    document.getElementById('theme-dark').addEventListener('change', function () {
        if (this.checked) {
            setTheme('dark');
        }
    });

    document.getElementById('theme-system').addEventListener('change', function () {
        if (this.checked) {
            setTheme('system');
        }
    });
    </script>
</body>

</html>
