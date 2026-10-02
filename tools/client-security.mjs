import assert from 'node:assert/strict';
import {renderInline as pitraceInline} from '../apps/pitrace/js/help/markdown.js';
import {renderInline as printanInline} from '../apps/printan/js/help/markdown.js';
import {sanitizeAssets} from '../apps/printan/js/core/schema.js';
import {zipWrite,zipRead} from '../apps/pitrace/js/pitra-zip.js';
for (const render of [pitraceInline,printanInline]) {
    for (const input of ['<img src=x onerror=alert(1)>','[click](javascript:evil)','[click](data:text/html,evil)','[click](//evil.test)']) {
        const html=render(input);
        assert(!html.includes('<img')&&!html.includes('<a '),html);
    }
    assert(render('[source](https://example.test/?a=1&b=2)').includes('href="https://example.test/?a=1&amp;b=2"'));
}
assert.deepEqual(sanitizeAssets([{id:'svg',dataUrl:'data:image/svg+xml;base64,PHN2Zz4='},{id:'js',dataUrl:'javascript:evil'},{id:'remote',dataUrl:'https://example.test/image.png'}]),[]);
assert.equal(sanitizeAssets([{id:'png',dataUrl:'data:image/png;base64,AA=='}]).length,1);
const data=new TextEncoder().encode('safe roundtrip');
const packed=zipWrite([{name:'manifest.json',data}]);
assert.equal(new TextDecoder().decode(zipRead(packed).get('manifest.json')),'safe roundtrip');
assert.throws(()=>zipRead(new Uint8Array([1,2,3])));
console.log('pitrace／printan：Markdown 惡意輸入、正常連結、資源 scheme 白名單、ZIP 往返及毀損檔全部通過');
