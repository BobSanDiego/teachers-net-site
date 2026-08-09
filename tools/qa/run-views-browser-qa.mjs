import { mkdirSync, statSync, writeFileSync } from 'node:fs';
import { dirname, resolve } from 'node:path';

const endpoint = process.env.VIEWS_CDP_ENDPOINT ?? process.argv[2] ?? 'http://127.0.0.1:9222';
const screenshot = process.env.VIEWS_QA_SCREENSHOT ?? resolve('tmp/qa/views-browser-proof.png');
const timeoutMs = 8000;
const consoleErrors = [];
const pageErrors = [];
let socket;
let sessionId;
let nextId = 0;
const pending = new Map();

const failPending = (error) => {
  for (const item of pending.values()) item.reject(error);
  pending.clear();
};

const closeSocket = () => {
  failPending(new Error('CDP socket closed.'));
  if (socket && socket.readyState < WebSocket.CLOSING) {
    try { socket.close(); } catch { /* The process still exits after the bounded attach timeout. */ }
  }
};

const command = (method, params = {}, commandSessionId = undefined, commandTimeout = timeoutMs) => new Promise((resolveCommand, rejectCommand) => {
  const id = ++nextId;
  const timer = setTimeout(() => {
    pending.delete(id);
    rejectCommand(new Error(method + ' timeout after ' + commandTimeout + 'ms'));
  }, commandTimeout);
  pending.set(id, {
    resolve: (value) => { clearTimeout(timer); resolveCommand(value); },
    reject: (error) => { clearTimeout(timer); rejectCommand(error); },
  });
  socket.send(JSON.stringify({ id, method, params, ...(commandSessionId ? { sessionId: commandSessionId } : {}) }));
});

const cleanup = async () => {
  if (socket?.readyState === WebSocket.OPEN && sessionId) {
    await command('Target.detachFromTarget', { sessionId }, undefined, 1500).catch(() => {});
    sessionId = undefined;
  }
  closeSocket();
};

try {
  const response = await fetch(endpoint + '/json/version', { signal: AbortSignal.timeout(timeoutMs) });
  if (!response.ok) throw new Error('CDP version request returned HTTP ' + response.status + '.');
  const version = await response.json();
  if (!version.webSocketDebuggerUrl) throw new Error('CDP version response did not include webSocketDebuggerUrl.');

  socket = new WebSocket(version.webSocketDebuggerUrl);
  socket.addEventListener('message', (event) => {
    const message = JSON.parse(String(event.data));
    if (message.id && pending.has(message.id)) {
      const item = pending.get(message.id);
      pending.delete(message.id);
      message.error ? item.reject(new Error(JSON.stringify(message.error))) : item.resolve(message.result);
      return;
    }
    if (message.method === 'Runtime.exceptionThrown') {
      pageErrors.push(message.params.exceptionDetails?.text ?? 'Runtime exception');
    }
    if (message.method === 'Runtime.consoleAPICalled' && message.params.type === 'error') {
      consoleErrors.push(message.params.args?.map((arg) => arg.value ?? arg.description ?? '').join(' '));
    }
    if (message.method === 'Log.entryAdded' && message.params.entry?.level === 'error') {
      consoleErrors.push(message.params.entry.text);
    }
  });
  socket.addEventListener('error', () => failPending(new Error('CDP WebSocket error.')));
  socket.addEventListener('close', () => failPending(new Error('CDP WebSocket closed.')));
  await new Promise((resolveOpen, rejectOpen) => {
    const timer = setTimeout(() => {
      closeSocket();
      rejectOpen(new Error('CDP attach timeout after ' + timeoutMs + 'ms'));
    }, timeoutMs);
    socket.addEventListener('open', () => { clearTimeout(timer); resolveOpen(); }, { once: true });
    socket.addEventListener('error', () => { clearTimeout(timer); rejectOpen(new Error('CDP attach WebSocket error.')); }, { once: true });
  });

  const targets = await command('Target.getTargets');
  const target = targets.targetInfos.find((item) => item.type === 'page' && item.url.includes('page=cfm-views'));
  if (!target) throw new Error('No authenticated Chrome Views page was discoverable.');
  sessionId = (await command('Target.attachToTarget', { targetId: target.targetId, flatten: true })).sessionId;
  await command('Runtime.enable', {}, sessionId);
  await command('Page.enable', {}, sessionId);
  await command('Log.enable', {}, sessionId);
  const evaluated = await command('Runtime.evaluate', {
    expression: '({title:document.title,url:location.href,readyState:document.readyState,editorFound:Boolean(document.querySelector("[data-cfm-views-workbench]")),horizontalOverflow:document.documentElement.scrollWidth>document.documentElement.clientWidth})',
    returnByValue: true,
  }, sessionId);
  const value = evaluated.result?.value ?? {};
  if (!value.editorFound || value.url?.includes('wp-login.php')) {
    throw new Error('Authenticated Views editor was not available at ' + (value.url ?? 'unknown URL') + '.');
  }

  const shot = await command('Page.captureScreenshot', { format: 'png' }, sessionId);
  mkdirSync(dirname(screenshot), { recursive: true });
  writeFileSync(screenshot, Buffer.from(shot.data, 'base64'));
  const screenshotBytes = statSync(screenshot).size;
  if (!screenshotBytes) throw new Error('Screenshot was empty.');

  await cleanup();
  console.log(JSON.stringify({
    status: 'PASS',
    endpoint,
    websocket: version.webSocketDebuggerUrl,
    browser: version.Browser,
    pages: targets.targetInfos.filter((item) => item.type === 'page').length,
    result: value,
    consoleErrors,
    pageErrors,
    screenshot,
    screenshotBytes,
  }));
} catch (error) {
  await cleanup();
  console.error(JSON.stringify({
    status: 'FAILED',
    endpoint,
    error: String(error),
    consoleErrors,
    pageErrors,
  }));
  process.exitCode = 2;
}
