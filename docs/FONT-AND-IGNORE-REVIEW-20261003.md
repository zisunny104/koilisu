# 字型、授權與 Git 忽略規則檢核

本次涵蓋開利手、五個現有子模組與 Souliong；延續前一輪安全修補分支，所有 PR 仍為 draft，未合併 main／未部署。日期為臺灣 2026-10-03。

## 字型結果

| 字型 | 實際使用／來源 | 授權與處理 |
|---|---|---|
| Noto Sans TC | hapbun：Google Fonts v39 固定 TTF；實際 metadata 為 2.004-H2，Regular 400／Bold 700 | OFL 1.1；著作權為 Adobe 2014–2021，RFN 為 Source。補完整全文到 licenses/；CLI 一併安裝 fonts/OFL.txt |
| Sarasa Mono TC | printan：198 個自託管 Big5 子集 WOFF2；metadata 均為 1.0.41，family 仍為 Sarasa Mono TC | 原有完整 LICENSE.txt 與著作權仍在；比對上游全文 SHA-256 相同。修正第三方說明中的「未修改」描述，匯出字型分片帶完整授權 |
| JetBrains Mono | printan：@fontsource/jetbrains-mono@5.3.0，固定版本 jsDelivr | 檢查實際套件 LICENSE，補全文及來源；.ptan 匯出附完整授權。套件 LICENSE 與目前上游檔的表頭文字不同，採用實際載入版本的完整檔 |
| 系統字型、Font Awesome 字型 | 其他工具使用系統 fallback／Tocas UI 或 Font Awesome CDN | 未找到其他隨 repo 散布的文字字型；系統字型不被 Printan 內嵌匯出；圖示字型仍按其上游授權，不能以本專案 MIT 概括 |

OFL 允許網頁使用、自託管、修改、與軟體搭售及 PDF 嵌入，但字型本體不能單独販售、不能被重新改為 MIT；散布字型須保留著作權與 OFL。字型子集屬修改，需要依原授權的 Reserved Font Name 規則判斷；本次 Sarasa 授權保留的 RFN 是 Source，實際分片 primary family 為 Sarasa Mono TC，未找到以 Source 為修改版 primary name 的情況。

PDF／列印作品不因使用 OFL 字型而被改為 OFL；`.ptan` 是攜帶可取用字型分片的 JSON 專案，採保守且可審閱方式，隨每片加上完整著作權、OFL-1.1、來源。修補的是授權資訊傳遞，不聲稱先前分片已構成確定侵權。

## 安裝與引用方式修補

hapbun 維持 CLI-only：`php install_font.php`，更新時 `--force`。來源與摘要一起固定，下載大小限制 16 MiB；HTTP redirect 不追隨；TLS 驗證開啟；摘要不符拒絕；同目錄暫存完成後 rename，失敗保留既有檔案；失敗 CLI 結束碼為 1。撤掉未經摘要驗證的 curl fallback。部署工具也核對摘要與 OFL，不把非空損壞檔案當作就緒。

| 固定檔案 | SHA-256 |
|---|---|
| NotoSansTC-Regular.ttf | 619662a0583f38311e92666927e5edbfd30f2a1fbe8593685660bd11bdd46a10 |
| NotoSansTC-Bold.ttf | 33e8464f3432fd9eba5fa6ff74f5fb9ee612cad703877bd71c36e6f167c0a7e3 |

摘要取自這次 HTTPS 下載、實際解析為正確 family／字重／版本的固定 Google Fonts 檔案。版本或來源更新時應重新人工核對，不能只改 URL。部署者可寫字型目錄，PHP-FPM 只需讀取。字型快取仍不進 Git，授權文件必須進 Git；網站提供字型時是否保留授權是另一個問題，不能只看 ignore。

Printan 繼續自託管 Sarasa 分片與固定版本 JetBrains Mono CDN；不刪除必要資產。`buildEmbeddedFonts()` 只處理已知開源網頁字型，每個匯出項目附 license metadata 與全文；不匯出作業系統專有字型。此授權新增欄位保留既有格式相容性。瀏覽器 CDN fallback 未套用 hapbun CLI 的 SHA 驗證，沒有宣稱所有字型下載都已具備相同完整性保障。

## Git／ignore 檢核

檢查每個 repo 自己的 `git ls-files`、`git ls-files -ci --exclude-standard`、可達完整 Git 歷史的常見敏感檔名，以及已追蹤程式碼的明顯憑證格式。沒有找到已追蹤的 `.env`、真實 api/config.php、私鑰、agent worktree、log／cache 或使用者匯出專案檔；沒有發現「已被 ignore 但仍持續追蹤」的檔案。這不是完整憑證偵測器／entropy 掃描，也不能排除以普通檔名保存的秘密。

- Souliong 現有 state/projects 只追蹤 `.gitkeep`，api/config.php 已忽略。本次補本機秘密、編輯器／agent 目錄、log／備份及私鑰規則。
- 開利手及所有五個子模組補 `.env.*`（保留 example/sample）、本機設定、agent 工作目錄、Python 快取、編輯器暫存及私鑰檔。子模組是獨立 repo，父層 ignore 不能代替它們自己的規則，因此三個缺漏較多的工具各有獨立 draft PR。
- hapbun 的 fonts/ 是部署快取，持續 ignore；licenses/ 全文進版控。
- printan 的 Sarasa fonts/、LICENSE、固定 libheif JS/WASM 都是產品必要散布檔，刻意保留。測試程式與合成 fixture 不因屬於 tests/ 就當成誤提交。
- 歷史只找到 Souliong 最初的 `projects/100chairs/chairs.json`、`meta.json`：座標／故事／來源等公開地圖資料，未見 PIN、token、salt 等敏感欄位。它們仍可從 Git 歷史讀到；新增 ignore 不會抹除歷史。本次沒有擅自改寫歷史。

新增 `python tools/checktracked.py` 逐 repo 檢查常見不應追蹤的檔案，以及已追蹤但被 ignore 的情況；僅輸出路徑，不輸出秘密。若日後發現真實秘密已提交，應先撤銷／輪替，再決定移除追蹤及歷史清理；只加 ignore 不能撤回外洩。

## 執行驗證

- 真實 Noto TTF 下載後用 fontTools 解析 family、weight、version、copyright；198 個 Sarasa WOFF2 解析均保留著作權，family／version 如上。Sarasa 分片本身沒有 OFL name metadata，授權依既有旁附完整 LICENSE；本次匯出另外明確保留全文。
- `php apps/hapbun/tools/fontcheck.php`：摘要不符保留原檔、成功原子替換、暫存清理通過。
- CLI 整合沙盒：使用已驗證的兩個真實 TTF 快取並關閉網路下載，成功安裝 OFL；損壞快取且不能下載時回結束碼 1、保留舊檔。
- `python apps/hapbun/tools/securitycheck.py`：HTTP force 安裝持續回 403／cli_only，沒有觸發下載。
- `node apps/printan/tools/fontcheck.mjs`：mock 字型下載、兩款字型附完整來源與授權、與旁附 LICENSE 相同、.ptan 序列化讀回保留，全部通過。未將 mock 測試描述成真實 PDF 字型載入。
- 所有新增／修改 PHP、JS 語法與 hapbun `bash -n deploy.sh` 通過；開利手可用 `python tools/checkall.py` 重跑語法與安全／字型純函式檢查。
- 各 repo `git check-ignore` 檢查 `.env.production`、agent 目錄、私鑰及保留的 example／授權文件；`git diff --check` 通過。沒有實際執行部署腳本。

## 官方來源

- [OFL FAQ：網頁／PDF／攜帶字型的文件／修改與 RFN](https://openfontlicense.org/ofl-faq/)
- [Noto Sans TC 原始 OFL](https://github.com/google/fonts/blob/main/ofl/notosanstc/OFL.txt)
- [Sarasa Gothic 原始授權](https://github.com/be5invis/Sarasa-Gothic/blob/master/LICENSE)
- [JetBrains Mono 原始 OFL](https://github.com/JetBrains/JetBrainsMono/blob/master/OFL.txt)
- [實際 Fontsource 5.3.0 套件 LICENSE](https://cdn.jsdelivr.net/npm/@fontsource/jetbrains-mono@5.3.0/LICENSE)
