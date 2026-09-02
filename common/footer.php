            </div>
            </main>
            </div>

            <!-- 頁尾 -->
            <div class="ts-content is-secondary is-vertically-padded">
                <div class="ts-container">
                    <div class="ts-divider is-section"></div>
                    <div class="ts-grid">
                        <div class="column is-fluid">
                            <div class="ts-text is-description">
                                <a href="/koilisu/" style="color: inherit; text-decoration: none;">KoiLiSu 開利手</a> -
                                讓工具使用更順手的開放專案 | prjToka
                            </div>
                            <div class="ts-text is-description">
                                Built with ❤️ using Tocas UI |
                                <a href="https://github.com/zisunny104/koilisu" target="_blank" rel="noopener noreferrer"
                                   style="display: inline-block; padding: 2px 8px; background: #24292f; color: white; text-decoration: none; border-radius: 6px; font-size: 0.85em; font-weight: 500; margin-left: 4px;">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" style="vertical-align: text-bottom; margin-right: 4px;">
                                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                                    </svg>
                                    View on GitHub<span class="sr-only"> (在新視窗開啟)</span>
                                </a>
                            </div>
                        </div>
                        <div class="column is-end-aligned">
                            <div class="ts-selection is-circular is-compact" role="radiogroup" aria-label="佈景主題切換">
                                <label class="item">
                                    <input type="radio" name="theme" value="light" id="theme-light">
                                    <div class="text">淺色</div>
                                </label>
                                <label class="item">
                                    <input checked type="radio" name="theme" value="system" id="theme-system">
                                    <div class="text">系統</div>
                                </label>
                                <label class="item">
                                    <input type="radio" name="theme" value="dark" id="theme-dark">
                                    <div class="text">深色</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
// 深淺色模式功能
function setTheme(theme) {
    document.body.className = theme === 'system'
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