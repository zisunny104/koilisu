            </div>
            </div>
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
                                Built with ❤️ using Tocas UI
                            </div>
                        </div>
                        <div class="column is-end-aligned">
                            <div class="ts-selection is-circular is-compact">
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
// 應用卡片點擊事件
document.querySelectorAll('.app-card').forEach(card => {
    card.addEventListener('click', function() {
        const url = this.dataset.url;
        if (url) {
            window.location.href = url;
        }
    });
});

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