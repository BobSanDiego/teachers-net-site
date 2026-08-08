import { mkdirSync, statSync, writeFileSync } from 'node:fs';

const endpoint = process.env.VIEWS_CDP_ENDPOINT ?? process.argv[2] ?? 'http://172.21.160.1:9223';
const reviewUrl = 'https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17';
const screenshot = process.env.VIEWS_QA_SCREENSHOT ?? '/home/bobreap/projects/teachers-net-site/tmp/qa/GOV-VIEWS002A-browser-proof.png';
const attachTimeoutMs = 5000;
const commandTimeoutMs = 5000;
const errors = [];
const pageErrors = [];

const version = await fetch(`${endpoint}/json/version`).then((response) => response.json());
const wsUrl = version.webSocketDebuggerUrl;
if (!wsUrl) throw new Error('Bridge did not return webSocketDebuggerUrl.');

const socket = new WebSocket(wsUrl);
let nextId = 0;
const pending = new Map();
const failPending = (error) => { for (const item of pending.values()) item.reject(error); pending.clear(); };
socket.addEventListener('message', (event) => {
  const message = JSON.parse(event.data);
  if (message.id && pending.has(message.id)) {
    const item = pending.get(message.id); pending.delete(message.id);
    if (message.error) item.reject(new Error(JSON.stringify(message.error))); else item.resolve(message.result);
  }
});
socket.addEventListener('error', () => failPending(new Error('CDP WebSocket error.')));
socket.addEventListener('close', () => failPending(new Error('CDP WebSocket closed.')));
const command = (method, params = {}, sessionId) => new Promise((resolve, reject) => {
  const id = ++nextId;
  const timer = setTimeout(() => { pending.delete(id); reject(new Error(`${method} timeout after ${commandTimeoutMs}ms`)); }, commandTimeoutMs);
  pending.set(id, { resolve: (value) => { clearTimeout(timer); resolve(value); }, reject: (error) => { clearTimeout(timer); reject(error); } });
  socket.send(JSON.stringify({ id, method, params, ...(sessionId ? { sessionId } : {}) }));
});
await new Promise((resolve, reject) => {
  const timer = setTimeout(() => reject(new Error(`CDP attach timeout after ${attachTimeoutMs}ms`)), attachTimeoutMs);
  socket.addEventListener('open', () => { clearTimeout(timer); resolve(); }, { once: true });
  socket.addEventListener('error', () => { clearTimeout(timer); reject(new Error('CDP attach WebSocket error.')); }, { once: true });
});

const targets = await command('Target.getTargets');
const target = targets.targetInfos.find((item) => item.type === 'page' && item.url.includes('page=cfm-views'));
if (!target) throw new Error('No authenticated Chrome Views page was discoverable.');
const attached = await command('Target.attachToTarget', { targetId: target.targetId, flatten: true });
const sessionId = attached.sessionId;
await command('Runtime.enable', {}, sessionId);
await command('Page.enable', {}, sessionId);
const evaluated = await command('Runtime.evaluate', { expression: '({ title: document.title, url: location.href })', returnByValue: true }, sessionId);
const value = evaluated.result?.value ?? {};
const shot = await command('Page.captureScreenshot', { format: 'png' }, sessionId);
mkdirSync(screenshot.substring(0, screenshot.lastIndexOf('/')), { recursive: true });
writeFileSync(screenshot, Buffer.from(shot.data, 'base64'));
const bytes = statSync(screenshot).size;
if (!bytes) throw new Error('Screenshot was empty.');
await command('Target.detachFromTarget', { sessionId }).catch(() => {});
socket.close();
console.log(JSON.stringify({ endpoint, websocket: wsUrl, browser: version.Browser, pages: targets.targetInfos.filter((item) => item.type === 'page').length, result: { title: value.title, url: value.url, editorFound: value.url.includes('page=cfm-views') }, consoleErrors: errors, pageErrors, screenshot, screenshotBytes: bytes }));
