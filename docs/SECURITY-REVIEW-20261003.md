# 開利手與子專案資安檢核

日期：2026-10-03（臺灣）。執行環境：雲端 PHP 8.3.6／Node／Python；僅原始碼、本機 loopback 及合成資料。没有連線測試正式服務、沒有合併 main、沒有部署。

## 範圍與版本

| 專案 | main 基準 commit | 檢核 |
|---|---|---|
| koilisu | 41bd13f55e8543b12a133354c9629bab97ab0c30 | 路由、共用 Markdown、PHP include 邊界、子模組列表 |
| gradcheck | c8da34d05fd72a81057a3d7443476b47af5f9f24 | 學號輸入、DOM、外部下載 URL |
| kobeu | 525670f17975cc161d8160917272f71df4dadee7 | 學號輸入、DOM、外部下載 URL |
| pitrace | 14a54fe344092787ff5f2faf7ec4cc7eac9c5065 | 專案 ZIP／JSON、DOM、Markdown、前端資源 |
| hapbun | 0852e2d530c72d83d0f309bbfbdfec426f093c6a | 字型安裝、檔名 DOM、PDF worker、Markdown |
| printan | 876b231e0c4661057881c79cae99510ddc925dc5 | 專案檔資源白名單、Markdown、DOM、USB／网络列印邊界、CSP |
| souliong | 30da2513f7539f4f9630e8594f1119b0fea2e2b1 | 投稿 CSRF／權限／唯讀、署名、限次碼、檔案路徑與部署 |

開利手 `.gitmodules` 目前只有上列五個子模組；沒有 Kairo、KinaLog、Lixcel 掛載記錄，不能宣稱它們已掃描。Souliong 獨立納入本次任務，但未擅自新增子模組。printan 在開利手鎖定的 commit 是 4785a85e6412e800a07a1d629439b4f962a1b813；也檢核此版本，與 main 的差異限 renderer 字基線對齊及對應測試。此 PR 不改它的指標。

## 結果

1. **Souliong：已確認並修補四項。** 管理 cookie 免碼投稿缺 CSRF；圖層 attribution 字串／suffix 直接輸出 HTML；upload=false 未在 upload 後端攔截；限次碼讀取與累加競態。詳見 [draft PR #2](https://github.com/zisunny104/souliong/pull/2) 及該 PR 的 SECURITY-REVIEW 文件。CSRF 攻擊條件依 SameSite=Lax 限制，包含同站不同源而 cookie 仍送往目標的情境，非任意跨站 POST。
2. **hapbun：已確認匿名 HTTP 能觸發固定來源字型下載／覆寫。** install_font.php 無驗證，force=1 強制寫入。改為 CLI 安裝、HTTP 403；移除前端安裝呼叫，CDN fallback 保留。不是任意 SSRF／任意路徑寫入，來源及檔名是固定常數。
3. **koilisu：補強 include 與 redirect 參數邊界。** loadApp／loadPage 原先直接拼接名稱；主路由未排除 `..`，`apps/../index.php` 可以回到主入口。實際 HTTP 可達性依 Nginx URI 正規化與路由方式，未將此列為已證實正式服務 LFI／RCE。加入保守名稱白名單，無效名稱回 404，?app 也驗證目錄與型別。共用 Markdown 原先接受任意 href scheme，改為 HTTP(S)／相對路徑；文件來源是 repo，本次歸類防禦補強，非已證明外部攻擊者可寫入的儲存型 XSS。
4. **gradcheck、kobeu：未確認額外可利用漏洞。** 學號進入 innerHTML 前經九位數字白名單；外部目的主機固定。使用者自行輸入其他學號取得校方資料的授權，仍取決於校方端點，本站程式碼無法证明其安全性。
5. **pitrace：未確認额外 DOM XSS／伺服器檔案穿越。** 主要處理在瀏覽器；ZIP 回傳 Map，不落到伺服器檔案系統。說明 Markdown 先 escape 並檢查 URL scheme；前端解析仍缺完整的大小／項目數與結構限制，惡意或過大的專案檔可能使瀏覽器耗用記憶體／失去回應，未做完整 fuzzing。
6. **printan：未確認额外可利用漏洞。** 圖片資源只接受 png/jpeg/gif/webp base64 且有数量／大小上限；排除 SVG、javascript 與遠端資源 URL。說明 Markdown 有 scheme 限制，使用者文字多走 textContent。head 已有 CSP、部分 CDN SRI。並未實際連接 USB 印表機，也未測試使用者裝置的網路印表機或全部匯入檔。

## 驗證證據與限制

| 專案 | PHP -l | Node --check（第一方 .js） | PHP 渲染後行內 JS --check |
|---|---:|---:|---:|
| koilisu（含範本與回歸工具） | 10 | 0 | 0 |
| gradcheck | 3 | 0 | 2 |
| kobeu | 3 | 0 | 2 |
| pitrace | 3 | 34 | 1 |
| hapbun | 4 | 1 | 4 |
| printan | 16 | 46 | 1 |

全數通過。printan 鎖定版本及最新 main 都跑過上述檢查。另執行：

- koilisu `php tools/securitycheck.php`：不安全 href、正常 HTTPS／相對連結、錯誤／陣列／換行名稱均驗證。
- hapbun `python tools/securitycheck.py`：本機 PHP HTTP server，force 安裝請求回 403／cli_only，沒有觸發下載。
- pitrace／printan 純函式：HTML 及 javascript/data/protocol-relative Markdown 惡意輸入被拒；正常 HTTPS 連結保留；printan 非點陣圖或遠端資源被拒；pitrace ZIP 往返与毀損檔拒絕通過。
- Souliong `php tools/checkall.php` 全數通過；新 gate 回歸套回原 main 出現 16 個預期失敗，JS 回歸重現注入。

語法／純函式檢查不等於完整瀏覽器 UI、PDF 處理、HEIC／WASM 解碼、USB／網路列印、部署設定或依賴 CVE 掃描。各專案的 CDN 套件需要另外整理版本與來源完整性；沒有將缺 SRI／未鎖版一律當作已證實 CVE。未找到 AGENTS.md；沒有採用文件內其他工作角色作為新指令。

## Nginx 防護建議（依優先順序）

### 1. 封鎖原始資料與秘密的 HTTP 路徑

Souliong 最重要：projects/、state/、layersrc/ 不應直接提供。投稿代碼與管理 PIN 都在其中。api/config.php、備份、.git 等同樣不該外流。照片、圖層、備份由應用受控路由輸出；不要封鎖所有 JSON 或 ZIP，否則正常 manifests／管理備份會壞。

下列是 **server 區塊的示意片段**，需對照實際掛載及其他 location 調整；未在你的正式 Nginx 上執行 `nginx -t`，不能直接宣稱可套用。

```nginx
autoindex off;
server_tokens off;

# 此例假設全站沒有需要公开的同名實體目錄。
location ~ /(?:projects|state|layersrc)(?:/|$) { return 404; }
location ~ /(?:config(?:\.example)?\.php)(?:/|$) { return 404; }
location ~ \.jsonl$ { return 404; }
location ~ (^|/)\.(?!well-known(?:/|$)) { return 404; }
location ~ \.(?:bak|old|orig|swp|sql|sqlite|env)$ { return 404; }
```

敏感路徑規則要在泛用 PHP regex 前；已有 `location ^~` 或 exact／alias 時要把封鎖規則放進對應範圍或改用明確 prefix，避免 regex 根本不被採用。檢核所有可達別名路徑：例如 `/koilisu/apps/souliong/projects/` 與友善路徑 `/koilisu/souliong/projects/`。工具安裝與測試目錄也應按實際實體掛載封鎖，不要一刀封鎖 `manager/.../tools` 這類合法管理路由。

PHP location 先確認 script 實際存在再交 PHP-FPM，避免把未知 .php／上傳資料當 script 執行：

```nginx
# 示例：root 型 PHP 部署；alias／框架 rewrite 配置需改成自己的版本。
location ~ \.php$ {
    try_files $uri =404;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param REMOTE_ADDR $remote_addr;
    fastcgi_param HTTPS $https if_not_empty;
    fastcgi_pass unix:/run/php/php8.3-fpm.sock; # 依實際 PHP 版本修改
}
```

### 2. 修正反代 IP 信任

Nginx 直接接 FPM 時，Souliong `trust_forwarded=false` 即可；REMOTE_ADDR 已是 Nginx 傳的位址。若上游還有 CDN，僅用 `set_real_ip_from` 信任 CDN／LB 的真實網段後再使用 `$remote_addr`。不要信任 0.0.0.0/0，也不要無條件取訪客的 X-Forwarded-For 第一個 IP。

若是 Nginx → HTTP 應用反代且後端必須開 trust_forwarded，入口應 `proxy_set_header X-Forwarded-For $remote_addr;`，並阻止後端公网直連；此程式不適合把 `$proxy_add_x_forwarded_for` 傳入後再任取最左 IP。

### 3. 大小與限流依端點配置

Souliong 影片允許 64MB，現有文件建議 Nginx 70m、PHP post_max_size 68M／upload_max_filesize 64M；不要把整個平台的所有路由都開到 70m。登入／解鎖可另設較嚴格的 limit_req，批次上傳應保留足够 burst，先 dry-run 看正常使用量；分享同一 NAT 的活動參加者可能共用 IP。用 query 路由時，單看 `/api/unlock.php` 的 location 可能漏掉 `?api=unlock`，需對照實際路由。

### 4. HTTPS 與回應標頭

使用 HTTPS，至少 `X-Content-Type-Options: nosniff`、`Referrer-Policy: strict-origin-when-cross-origin`；HSTS 先小 max-age 確認後再提高，不立刻加 includeSubDomains／preload。Souliong 有允許指定來源 iframe 的功能，不要全站強加 DENY/SAMEORIGIN 破壞它；改由頁面的 frame-ancestors 白名單處理。

CSP 先 Report-Only 後再逐頁強制。pitrace／hapbun／printan 需要各自 CDN、worker／blob／WASM、圖片 data URL；USB 列印與網路列印也有不同能力需求，不能用同一份最嚴模板硬套。逐步把 inline script 換 nonce／外部檔，固定 CDN 版本與加 SRI 或自託管。

### 5. 部署後用已知合成敏感檔驗證

先 `nginx -t` 再 reload；測試用不含秘密的 fixture 放在對應受保護資料路徑。應回 403／404；同時檢查地圖照片、圖層與授權備份仍可使用。不要把真實 codes／PIN 回應貼到聊天或公開 PR。

## Ubuntu／PHP-FPM 建議

1. **先確認 OS 維護狀態。** 如果 VPS 仍是之前的 Ubuntu 20.04，標準安全維護已於 2025 年 5 月結束；查看 Ubuntu Pro/ESM 覆蓋，或規劃備份与 staging 後逐級升級到受支援 LTS。不要只執行 apt upgrade 就假設取得所有安全修補。
2. 開啟並確認 unattended-upgrades 的實際允許來源、失敗日誌與需重啟狀態；排定可控重啟時段。檢核 PHP、Nginx、OpenSSL、ffmpeg 等來源与修補狀態，不能由本次雲端測試版本推斷你的主機版本。
3. **SSH 保留救援通道再調整。** 確認金鑰登入可用後，關閉密碼及 root 直接登入；先 `sshd -t`，保留現有 session 另開連線驗證。依實際 SSH port（以前是 22022，這次未確認）放行 UFW，再限制不需要的公開 port。RustDesk／其他服務所需 port 要先盤點，不能只開 80/443 便把自己鎖出。
4. PHP-FPM socket／後端 port 僅本機或 Unix socket；按應用分 pool／帳號。程式碼與 .git 由部署帳號寫，FPM 只讀；Souliong 只對必要 state/projects 授寫。不要 chmod -R 777，也不要把整個 web root chown 給 www-data。hapbun fonts 預裝後 FPM 只讀。
5. `display_errors=Off`、`log_errors=On`、`expose_php=Off`、`cgi.fix_pathinfo=0`；按檔案處理需求設定 memory_limit、request_terminate_timeout、pm.max_children。Souliong 用到 proc_open／ffmpeg，不能不經驗證 blanket disable_functions。
6. Souliong 與開利手若同一 origin，一個工具的 XSS 可跨到另一個工具並讀同源憑證頁面。中期可把有管理寫入功能的 Souliong 與純前端工具分 origin、分 FPM pool；原有 cookie path、登入與嵌入流程要先驗證。
7. 異機／離線備份 state/projects／額外全站圖層，加密保存含 PIN 的備份；定期在隔離環境實際還原。監控磁碟空間、inode、FPM worker、429／5xx 與備份成功，避免檔案儲存滿了才發現。

本次只提供建議與修補分支；尚未取得正式 Nginx 設定、`ufw status`、`pro status`、OS/FPM 版本，沒有聲稱這些防護已生效。

## 官方來源

- [Nginx core：location、try_files、client_max_body_size](https://nginx.org/en/docs/http/ngx_http_core_module.html)
- [Nginx FastCGI](https://nginx.org/en/docs/http/ngx_http_fastcgi_module.html)
- [Nginx Real IP：只信任指定代理](https://nginx.org/en/docs/http/ngx_http_realip_module.html)
- [Nginx limit_req 與 dry-run](https://nginx.org/en/docs/http/ngx_http_limit_req_module.html)
- [Ubuntu 維護週期](https://ubuntu.com/about/release-cycle)
- [Ubuntu 自動安全更新](https://ubuntu.com/server/docs/how-to/software/automatic-updates/)
- [Ubuntu OpenSSH](https://ubuntu.com/server/docs/how-to/security/openssh-server/)
- [Ubuntu 防火牆](https://documentation.ubuntu.com/server/how-to/security/firewalls/index.html)
