/**
 * فحص التجاوب عبر متصفح كروم الحقيقي (Headless Chrome DevTools Protocol)
 * Automated Real-Browser Multi-Device Responsive Testing Suite
 */

const http = require('http');
const fs = require('fs');
const path = require('path');
const { spawn } = require('child_process');

const PORT = 8989;
const CHROME_PORT = 9223;
const ROOT_DIR = path.resolve(__dirname, '..');

// 1. Static HTTP Server
const MIME_TYPES = {
  '.html': 'text/html; charset=utf-8',
  '.css': 'text/css; charset=utf-8',
  '.js': 'application/javascript; charset=utf-8',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.svg': 'image/svg+xml'
};

const server = http.createServer((req, res) => {
  let reqPath = decodeURI(req.url.split('?')[0]);
  if (reqPath === '/') reqPath = '/tests/html-fixtures/front-page.html';
  const filePath = path.join(ROOT_DIR, reqPath);

  if (fs.existsSync(filePath) && fs.statSync(filePath).isFile()) {
    const ext = path.extname(filePath).toLowerCase();
    res.writeHead(200, { 'Content-Type': MIME_TYPES[ext] || 'application/octet-stream' });
    fs.createReadStream(filePath).pipe(res);
  } else {
    res.writeHead(404, { 'Content-Type': 'text/plain' });
    res.end('404 Not Found: ' + reqPath);
  }
});

server.listen(PORT, async () => {
  console.log(`[HTTP] Static file server listening at http://127.0.0.1:${PORT}`);
  await runTests();
});

// Helper for CDP JSON RPC
function createCDPClient(wsUrl) {
  return new Promise((resolve, reject) => {
    const ws = new WebSocket(wsUrl);
    let msgId = 1;
    const callbacks = new Map();

    ws.onopen = () => {
      resolve({
        send: (method, params = {}) => {
          return new Promise((res, rej) => {
            const id = msgId++;
            callbacks.set(id, { res, rej });
            ws.send(JSON.stringify({ id, method, params }));
          });
        },
        close: () => ws.close()
      });
    };

    ws.onmessage = (event) => {
      const data = JSON.parse(event.data);
      if (data.id && callbacks.has(data.id)) {
        const { res, rej } = callbacks.get(data.id);
        callbacks.delete(data.id);
        if (data.error) rej(data.error);
        else res(data.result);
      }
    };

    ws.onerror = reject;
  });
}

async function runTests() {
  const chromePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
  console.log(`[Chrome] Launching headless browser from: ${chromePath}`);

  const chrome = spawn(chromePath, [
    '--headless=new',
    `--remote-debugging-port=${CHROME_PORT}`,
    '--disable-gpu',
    '--no-sandbox',
    '--mute-audio',
    'about:blank'
  ]);

  // Wait for Chrome to boot
  await new Promise((r) => setTimeout(r, 1200));

  try {
    const versionRes = await fetch(`http://127.0.0.1:${CHROME_PORT}/json/version`);
    const versionData = await versionRes.json();
    console.log(`[Chrome] Connected successfully to: ${versionData.Browser}`);

    const targetsRes = await fetch(`http://127.0.0.1:${CHROME_PORT}/json/list`);
    const targets = await targetsRes.json();
    const pageTarget = targets.find((t) => t.type === 'page');

    if (!pageTarget) {
      throw new Error('No page target found in Chrome');
    }

    const cdp = await createCDPClient(pageTarget.webSocketDebuggerUrl);
    await cdp.send('Page.enable');
    await cdp.send('DOM.enable');

    const viewports = [
      { name: 'Extra Small Mobile (Galaxy Fold / iPhone 5)', width: 320, height: 700, mobile: true },
      { name: 'Small Mobile (Galaxy S8/S9 / A-Series)', width: 360, height: 740, mobile: true },
      { name: 'Standard iPhone (iPhone X / 11 Pro / 12 Mini / SE)', width: 375, height: 812, mobile: true },
      { name: 'Modern iPhone (iPhone 12 / 13 / 14)', width: 390, height: 844, mobile: true },
      { name: 'Large iPhone (iPhone 8 Plus / XR / 11)', width: 414, height: 896, mobile: true },
      { name: 'Max Flagship (iPhone 14/15 Pro Max)', width: 430, height: 932, mobile: true },
      { name: 'Tablet Portrait (iPad Mini / Air)', width: 768, height: 1024, mobile: true },
      { name: 'Tablet Landscape / Small Desktop (iPad Pro)', width: 1024, height: 768, mobile: false },
      { name: 'Standard Desktop', width: 1280, height: 800, mobile: false }
    ];

    const pages = [
      { name: 'الصفحة الرئيسية (Homepage)', url: `http://127.0.0.1:${PORT}/tests/html-fixtures/front-page.html` },
      { name: 'لوحة الحساب الشخصي (My Account)', url: `http://127.0.0.1:${PORT}/tests/html-fixtures/account.html` },
      { name: 'صفحة الكورس وفهرس الدروس (Course Single)', url: `http://127.0.0.1:${PORT}/tests/html-fixtures/course.html` },
      { name: 'صفحة تسجيل الدخول (Login Page)', url: `http://127.0.0.1:${PORT}/tests/html-fixtures/login.html` }
    ];

    console.log('\n==================================================');
    console.log('STARTING AUTOMATED MULTI-DEVICE RESPONSIVE SUITE');
    console.log('==================================================\n');

    let totalTests = 0;
    let passedTests = 0;
    let failedTests = 0;
    const failures = [];

    for (const page of pages) {
      console.log(`\n📄 Testing Page: ${page.name}`);
      console.log(`   URL: ${page.url}`);

      // Navigate
      await cdp.send('Page.navigate', { url: page.url });
      await new Promise((r) => setTimeout(r, 600));

      for (const vp of viewports) {
        totalTests++;

        // Set device metrics
        await cdp.send('Emulation.setDeviceMetricsOverride', {
          width: vp.width,
          height: vp.height,
          deviceScaleFactor: 1,
          mobile: vp.mobile
        });
        await new Promise((r) => setTimeout(r, 150));

        // Evaluate scrollWidth and check for overflowing elements
        const evalRes = await cdp.send('Runtime.evaluate', {
          expression: `(() => {
            const docWidth = document.documentElement.scrollWidth;
            const winWidth = window.innerWidth;
            const overflow = docWidth - winWidth;
            
            // Find overflowing elements
            const badElements = [];
            const all = document.querySelectorAll('*');
            for (const el of all) {
              // Ignore fixed drawers or modals and their descendants, and intentional scroll containers
              if (el.closest('.cdt-mobile-drawer') || el.closest('.cdt-mobile-overlay') || el.closest('.cdt-search-modal') || el.closest('.cdt-video-modal') || el.classList.contains('cdt-skip-link') || el.closest('.cdt-account-tabs-nav') || el.closest('.cdt-table-responsive') || el.closest('pre')) {
                continue;
              }
              const r = el.getBoundingClientRect();
              if (r.right > winWidth + 1.5 || r.left < -1.5) {
                badElements.push({
                  tag: el.tagName.toLowerCase(),
                  className: el.className || '',
                  id: el.id || '',
                  right: Math.round(r.right),
                  left: Math.round(r.left),
                  width: Math.round(r.width)
                });
              }
            }

            return {
              docWidth,
              winWidth,
              overflow: Math.max(0, overflow),
              badElementsCount: badElements.length,
              sampleBadElements: badElements.slice(0, 3)
            };
          })()`,
          returnByValue: true
        });

        const metrics = evalRes.result.value;
        const isPass = metrics.overflow <= 1 && metrics.badElementsCount === 0;

        if (isPass) {
          passedTests++;
          console.log(`   [PASS] ${vp.width}px (${vp.name}): scrollWidth=${metrics.docWidth}px, innerWidth=${metrics.winWidth}px -> Overflow: 0px`);
        } else {
          failedTests++;
          console.log(`   [FAIL] ${vp.width}px (${vp.name}): scrollWidth=${metrics.docWidth}px, innerWidth=${metrics.winWidth}px -> OVERFLOW: +${metrics.overflow}px!`);
          if (metrics.sampleBadElements.length > 0) {
            console.log(`          Culprits: ${JSON.stringify(metrics.sampleBadElements)}`);
          }
          failures.push({
            page: page.name,
            viewport: `${vp.width}px (${vp.name})`,
            overflow: metrics.overflow,
            elements: metrics.sampleBadElements
          });
        }
      }
    }

    console.log('\n==================================================');
    console.log('RESPONSIVE SUITE SUMMARY');
    console.log('==================================================');
    console.log(`Total Scenarios Tested : ${totalTests}`);
    console.log(`Passed                 : ${passedTests}`);
    console.log(`Failed                 : ${failedTests}`);

    if (failedTests === 0) {
      console.log('\n>>> SUCCESS: ZERO HORIZONTAL OVERFLOW ACROSS ALL RESOLUTIONS! <<<');
    } else {
      console.log('\n>>> DETECTED OVERFLOW IN THE FOLLOWING RESOLUTIONS: <<<');
      console.log(JSON.stringify(failures, null, 2));
    }

    cdp.close();
  } catch (err) {
    console.error('[Test Error]', err);
  } finally {
    chrome.kill();
    server.close();
    process.exit(0);
  }
}
