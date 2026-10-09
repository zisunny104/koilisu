#!/usr/bin/env bash
# 由 deploy.sh 載入，沿用母專案的輸出函式。
registered_apps() {
    git config -f .gitmodules --get-regexp '^submodule\..*\.path$' | awk '$2 ~ /^apps\/[a-z0-9_-]+$/ {print $2}'
}
app_summary() {
    local path="$1" version='未提供' sha
    sha="$(git -C "$path" rev-parse --short HEAD)"
    if command -v php >/dev/null && [ -f "$path/config.php" ]; then
        version="$(php -r '$c=require $argv[1]; $v=$c["version"]??null; echo is_string($v)||is_numeric($v)?"v".ltrim((string)$v,"v"):"未提供";' "$PWD/$path/config.php")" || version='無法讀取'
    fi
    ok "${path#apps/} · 專案版本：${version} · 目前提交：${sha}"
}
# 比較母專案已提交的記錄，避免把檔案修改誤報為提交差異。
report_app_differences() {
    local path target current counts ahead behind found=0 relation
    while IFS= read -r path; do
        target="$(git rev-parse "HEAD:$path" 2>/dev/null)" || continue
        [ -e "$path/.git" ] || continue
        current="$(git -C "$path" rev-parse HEAD)"
        [ "$target" != "$current" ] || continue
        if [ "$found" -eq 0 ]; then
            warn '子專案提交與母專案記錄不同'
            found=1
        fi
        relation='無法比較歷史（本機未取得母專案記錄的提交）'
        if git -C "$path" cat-file -e "$target^{commit}" 2>/dev/null; then
            counts="$(git -C "$path" rev-list --left-right --count "$target...$current")"
            read -r behind ahead <<< "$counts"
            if [ "$behind" -eq 0 ]; then relation="本機較新 ${ahead} 個提交"
            elif [ "$ahead" -eq 0 ]; then relation="本機較舊 ${behind} 個提交"
            else relation="歷史分歧：本機多 ${ahead} 個、缺 ${behind} 個提交"; fi
        fi
        printf '    %s · 母專案記錄：%.7s · 本機提交：%.7s · %s\n' "${path#apps/}" "$target" "$current" "$relation"
    done < <(registered_apps)
    if [ "$found" -eq 1 ]; then
        printf '  %s\n' '請更新母專案記錄，供其他主機使用；目前提交不會被重設。'
    fi
}
deploy_apps() {
    local selection="$1" path name failures=0 successes=0 skipped=0
    local -a paths=() requested=()
    mapfile -t paths < <(registered_apps)
    [ "${#paths[@]}" -gt 0 ] || { fail "沒有已登記的子專案"; return 2; }
    if [ "$selection" != all ]; then
        IFS=',' read -r -a requested <<< "$selection"
        [ "${#requested[@]}" -gt 0 ] || { fail '請指定 all 或子專案名稱'; return 2; }
        for name in "${requested[@]}"; do
            [[ "$name" =~ ^[a-z0-9_-]+$ ]] && printf '%s\n' "${paths[@]}" | grep -qx "apps/$name" || {
                fail "未登記的子專案：$name"; return 2;
            }
        done
        paths=()
        for name in "${requested[@]}"; do
            path="apps/$name"
            [[ " ${paths[*]} " == *" $path "* ]] || paths+=("$path")
        done
    fi
    step '部署子專案'
    for path in "${paths[@]}"; do
        if [ ! -e "$path/.git" ]; then
            fail "${path#apps/}：子模組尚未初始化，請先執行一般部署"
            failures=$((failures + 1)); continue
        fi
        if [ ! -f "$path/deploy.sh" ]; then
            warn "${path#apps/}：沒有 deploy.sh，略過"
            skipped=$((skipped + 1)); continue
        fi
        printf '%s────────────────────────────%s\n' "$DIM" "$RESET"
        printf '  %s\n' "執行 ${path}/deploy.sh"
        # 子專案使用自己的分支、網址與重載設定；不能繼承母專案的檢查網址。
        if (cd "$path" && env -u DEPLOY_CHECK_URL -u DEPLOY_BRANCH -u DEPLOY_RELOAD_CMD bash ./deploy.sh); then
            successes=$((successes + 1)); app_summary "$path"
        else
            failures=$((failures + 1)); fail "${path#apps/}：部署失敗"
        fi
    done
    step '子專案部署結果'
    printf '  成功：%s · 失敗：%s · 略過：%s\n' "$successes" "$failures" "$skipped"
    printf '  部署時間：%s\n' "$(date '+%Y-%m-%d %H:%M:%S')"
    report_app_differences
    [ "$failures" -eq 0 ]
}
