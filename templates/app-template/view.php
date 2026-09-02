<!DOCTYPE html>
<html id="html">

<head>
    <meta charset="UTF-8">
    <title>{APP_DISPLAY_NAME} - KoiLiSu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

    .segment {
        max-width: 300px;
    }

    .action-buttons {
        display: grid;
        gap: 10px;
        margin-top: 20px;
    }

    .button-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }
    </style>
    <script id="clientEventHandlersJS" language="javascript" type="text/javascript">
    // 驗證函數範例
    function validateInput(input) {
        // TODO: 根據應用需求實作驗證邏輯
        return input && input.trim().length > 0;
    }

    // 主要功能函數
    function executeMainFunction(input) {
        if (!validateInput(input)) {
            alert("請輸入有效的資料");
            return false;
        }

        // TODO: 實作主要功能邏輯
        alert("功能執行成功！輸入：" + input);
        return false;
    }

    // 生成動態按鈕
    function generateActionButtons() {
        const inputText = document.getElementById("mainInput").value;
        const buttonContainer = document.getElementById("actionButtons");
        buttonContainer.innerHTML = ''; // Clear existing buttons

        // 分割輸入（支援多種分隔符）
        const inputs = inputText.split(/[\s,，]+/)
            .map(item => item.trim())
            .filter(item => item.length > 0);

        // 篩選有效輸入
        const validInputs = inputs.filter(item => validateInput(item));

        // 只在有有效輸入時顯示按鈕
        if (validInputs.length > 0) {
            validInputs.forEach(input => {
                const buttonRow = document.createElement("div");
                buttonRow.className = "button-row";

                // 建立動作按鈕
                const button = document.createElement("button");
                button.className = "ts-button is-circular is-primary";
                button.innerHTML = `<span class="ts-icon is-{ICON_NAME}-icon"></span>執行 「 ${input} 」`;
                button.onclick = () => executeMainFunction(input);

                // 將按鈕加入按鈕行
                buttonRow.appendChild(button);

                // 將按鈕行加入容器
                buttonContainer.appendChild(buttonRow);
            });
        }
    }
    </script>
</head>

<body class="is-rounded">
    <div class="main-content">
        <div class="ts-container is-narrow has-vertically-padded-big">
            <div class="content">
                <div class="ts-header is-huge is-icon is-heavy">
                    <div class="ts-icon is-{ICON_NAME}-icon"></div>
                    {APP_DISPLAY_NAME} <span style="font-size:1rem;color:#888;">v1.0.0</span>
                </div>

                <div class="ts-box has-top-spaced-large" style="width: 100%">
                    <div class="ts-content">
                        <div class="ts-wrap is-vertical">
                            <div class="ts-text is-label">{INPUT_LABEL}</div>
                            <div class="ts-input is-start-icon is-underlined">
                                <span class="ts-icon is-{INPUT_ICON}-icon"></span>
                                <input type="text" id="mainInput" placeholder="{INPUT_PLACEHOLDER}"
                                    onchange="generateActionButtons()" onkeyup="generateActionButtons()">
                            </div>

                            <div id="actionButtons" class="action-buttons">
                                <!-- Action buttons will be generated here -->
                            </div>
                        </div>
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
                        <a href="/koilisu/" style="color: inherit; text-decoration: none;">KoiLiSu 開利手</a> -
                        讓工具使用更順手的開放專案 | prjToka
                    </div>
                    <div class="ts-text is-description">
                        Built with ❤️ using Tocas UI |
                        <a href="https://github.com/zisunny104/koilisu-{APP_NAME}" target="_blank"
                           style="display: inline-block; padding: 2px 8px; background: #24292f; color: white; text-decoration: none; border-radius: 6px; font-size: 0.85em; font-weight: 500; margin-left: 4px;">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" style="vertical-align: text-bottom; margin-right: 4px;">
                                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                            </svg>
                            View on GitHub
                        </a>
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