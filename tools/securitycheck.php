<?php
if (PHP_SAPI !== 'cli') exit(1);
require_once __DIR__ . '/../common/functions.php';
function ck(bool $ok, string $msg): void { if (!$ok) throw new RuntimeException($msg); }
foreach (['[x](javascript:alert)', '[x](data:text/html,evil)', '[x](//evil.test)', '<img src=x onerror=alert>'] as $s) {
    $html = renderMarkdownInline($s);
    ck(!str_contains($html, '<a ') && !str_contains($html, '<img'), '拒絕不安全 Markdown：' . $s);
}
ck(str_contains(renderMarkdownInline('[x](https://example.org/?a=1&b=2)'), 'href="https://example.org/?a=1&amp;b=2"'), '正常 HTTPS 連結');
ck(str_contains(renderMarkdownInline('[x](docs/README.md)'), '<a '), '正常相對連結');
foreach (['..', '../index', 'a/b', "a\n", [], ''] as $bad) {
    http_response_code(200); ob_start(); loadApp($bad); $out = ob_get_clean();
    ck(http_response_code() === 404 && $out === '', '無效 app 路徑被擋');
    http_response_code(200); loadPage($bad);
    ck(http_response_code() === 404, '無效 page 路徑被擋');
}
echo "securitycheck：路徑與 Markdown 回歸通過\n";
