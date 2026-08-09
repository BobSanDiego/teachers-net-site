import { mkdirSync, statSync, writeFileSync } from 'node:fs';
import { dirname } from 'node:path';

const options = Object.fromEntries(process.argv.slice(2).map((arg) => {
  const match = arg.match(/^--([^=]+)=(.*)$/);
  return match ? [match[1], match[2]] : [arg.replace(/^--/, ''), 'true'];
}));

const endpoint = options.endpoint ?? 'http://127.0.0.1:9222';
const mode = options.mode ?? 'blank';
const viewsUrl = options.url ?? 'https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17';
const screenshot = options.screenshot;
const keepTarget = options['keep-target'] === 'true';
const replaceViewsTargets = options['replace-views-targets'] === 'true';
const timeoutMs = Number(options.timeout ?? 8000);
const stages = [];
const startedAt = Date.now();
let socket;
let targetId;
let sessionId;
let targetCreated = false;
let nextId = 0;
const pending = new Map();

const record = (stage, status, stageStarted, detail = {}) => {
  stages.push({ stage, status, elapsedMs: Date.now() - stageStarted, ...detail });
};

const within = async (stage, operation) => {
  const stageStarted = Date.now();
  try {
    const value = await operation();
    record(stage, 'PASS', stageStarted);
    return value;
  } catch (error) {
    record(stage, 'FAIL', stageStarted, { error: String(error) });
    throw error;
  }
};

const command = (method, params = {}, commandSessionId = undefined, commandTimeout = timeoutMs) => new Promise((resolve, reject) => {
  const id = ++nextId;
  const timer = setTimeout(() => {
    pending.delete(id);
    reject(new Error(`${method} timeout after ${commandTimeout}ms`));
  }, commandTimeout);
  pending.set(id, {
    resolve: (value) => { clearTimeout(timer); resolve(value); },
    reject: (error) => { clearTimeout(timer); reject(error); },
  });
  socket.send(JSON.stringify({ id, method, params, ...(commandSessionId ? { sessionId: commandSessionId } : {}) }));
});

const closeSocket = () => {
  for (const item of pending.values()) item.reject(new Error('CDP socket closed'));
  pending.clear();
  if (socket && socket.readyState < WebSocket.CLOSING) {
    try { socket.close(); } catch { /* The process still exits after the bounded attach timeout. */ }
  }
};

const cleanup = async () => {
  if (socket?.readyState === WebSocket.OPEN) {
    if (sessionId) await command('Target.detachFromTarget', { sessionId }, undefined, 1500).catch(() => {});
    if (targetCreated && !keepTarget && targetId) await command('Target.closeTarget', { targetId }, undefined, 1500).catch(() => {});
  }
  closeSocket();
};

try {
  const version = await within('http-version', async () => {
    const response = await fetch(`${endpoint}/json/version`, { signal: AbortSignal.timeout(timeoutMs) });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return response.json();
  });
  if (!version.webSocketDebuggerUrl) throw new Error('Missing browser WebSocket URL');

  socket = new WebSocket(version.webSocketDebuggerUrl);
  socket.addEventListener('message', (event) => {
    const message = JSON.parse(String(event.data));
    if (!message.id || !pending.has(message.id)) return;
    const item = pending.get(message.id);
    pending.delete(message.id);
    message.error ? item.reject(new Error(JSON.stringify(message.error))) : item.resolve(message.result);
  });
  socket.addEventListener('error', () => {
    for (const item of pending.values()) item.reject(new Error('CDP WebSocket error'));
    pending.clear();
  });
  await within('socket-open', () => new Promise((resolve, reject) => {
    const timer = setTimeout(() => { closeSocket(); reject(new Error(`WebSocket timeout after ${timeoutMs}ms`)); }, timeoutMs);
    socket.addEventListener('open', () => { clearTimeout(timer); resolve(); }, { once: true });
    socket.addEventListener('error', () => { clearTimeout(timer); reject(new Error('WebSocket open failed')); }, { once: true });
  }));

  const browserVersion = await within('browser-command', () => command('Browser.getVersion'));
  const targets = await within('target-enumeration', () => command('Target.getTargets'));

  if (mode === 'views' && replaceViewsTargets) {
    const stale = targets.targetInfos.filter((target) => target.type === 'page' && target.url.includes('page=cfm-views'));
    await within('stale-views-target-cleanup', async () => {
      for (const target of stale) await command('Target.closeTarget', { targetId: target.targetId }, undefined, 2000);
    });
  }

  const initialUrl = mode === 'views' ? viewsUrl : 'about:blank';
  const created = await within('target-create', () => command('Target.createTarget', { url: initialUrl }));
  targetId = created.targetId;
  targetCreated = true;
  const attached = await within('target-attach', () => command('Target.attachToTarget', { targetId, flatten: true }));
  sessionId = attached.sessionId;
  await within('runtime-enable', () => command('Runtime.enable', {}, sessionId));
  await within('page-enable', () => command('Page.enable', {}, sessionId));
  const arithmetic = await within('runtime-evaluate', () => command('Runtime.evaluate', {
    expression: '1+1', returnByValue: true,
  }, sessionId));
  if (arithmetic.result?.value !== 2) throw new Error(`Unexpected arithmetic result: ${arithmetic.result?.value}`);

  let inspected;
  await within('dom-query', async () => {
    const deadline = Date.now() + timeoutMs;
    do {
      inspected = await command('Runtime.evaluate', {
        expression: `({title:document.title,url:location.href,ready:document.readyState,tag:document.documentElement?.tagName||null,editor:Boolean(document.querySelector('[data-cfm-views-workbench]'))})`,
        returnByValue: true,
      }, sessionId, Math.min(3000, timeoutMs));
      const value = inspected.result?.value;
      if (value?.tag && value.ready === 'complete' && (mode !== 'views' || value.editor || value.url.includes('wp-login.php'))) return;
      await new Promise((resolve) => setTimeout(resolve, 250));
    } while (Date.now() < deadline);
    throw new Error('DOM did not reach an inspectable state');
  });

  const dom = inspected.result?.value ?? {};
  if (mode === 'views' && (!dom.editor || dom.url.includes('wp-login.php'))) {
    throw new Error(`Authenticated Views editor unavailable at ${dom.url}`);
  }

  const shot = await within('screenshot-command', () => command('Page.captureScreenshot', { format: 'png' }, sessionId));
  if (!screenshot) throw new Error('A screenshot path is required');
  await within('screenshot-persistence', async () => {
    mkdirSync(dirname(screenshot), { recursive: true });
    writeFileSync(screenshot, Buffer.from(shot.data, 'base64'));
    if (statSync(screenshot).size === 0) throw new Error('Screenshot was empty');
  });

  await within('target-detach', async () => {
    await command('Target.detachFromTarget', { sessionId });
    sessionId = undefined;
  });
  if (!keepTarget) {
    await within('target-close', async () => {
      await command('Target.closeTarget', { targetId });
      targetCreated = false;
    });
  }
  closeSocket();
  const screenshotBytes = statSync(screenshot).size;
  console.log(JSON.stringify({
    status: 'READY', endpoint, mode, browser: browserVersion.product, targetId,
    keptTarget: keepTarget, dom, screenshot, screenshotBytes,
    totalElapsedMs: Date.now() - startedAt, stages,
  }));
} catch (error) {
  await cleanup();
  console.error(JSON.stringify({
    status: 'FAILED', endpoint, mode, error: String(error),
    totalElapsedMs: Date.now() - startedAt, stages,
  }));
  process.exitCode = 2;
}
