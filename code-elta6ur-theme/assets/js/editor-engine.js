/**
 * كود التطور - محرك محرر الأكواد والتشغيل المعزول
 * Code Elta6ur Online Code Editor Engine
 */
document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  var editorContainer = document.getElementById('cdt-editor-container');
  if (!editorContainer) {
    return;
  }

  var codeTextarea = document.getElementById('cdt-code-textarea');
  var terminalOutput = document.getElementById('cdt-terminal-output');
  var terminalStatus = document.getElementById('cdt-terminal-status');
  var statusDot = document.getElementById('cdt-status-dot');
  var langSelect = document.getElementById('cdt-editor-lang-select');
  var stdinInput = document.getElementById('cdt-terminal-stdin');
  var stdinBox = document.getElementById('cdt-terminal-stdin-box');

  var btnRun = document.getElementById('cdt-btn-run');
  var btnCopyCode = document.getElementById('cdt-btn-copy-code');
  var btnDownloadCode = document.getElementById('cdt-btn-download-code');
  var btnResetCode = document.getElementById('cdt-btn-reset-code');
  var btnFullscreen = document.getElementById('cdt-btn-fullscreen');
  var btnClearTerminal = document.getElementById('cdt-btn-clear-terminal');

  var tabCodeBtn = document.getElementById('cdt-tab-code');
  var tabTermBtn = document.getElementById('cdt-tab-terminal');
  var editorPane = document.querySelector('.cdt-editor-pane');
  var terminalPane = document.querySelector('.cdt-terminal-pane');

  var currentLangSlug = editorContainer.getAttribute('data-lang') || 'python';
  var initialCode = codeTextarea ? codeTextarea.value : '';

  // Local Persistence (Client-side only)
  var storageKey = 'cdt_editor_code_' + currentLangSlug;
  try {
    var savedCode = localStorage.getItem(storageKey);
    if (savedCode !== null && savedCode.trim() !== '' && codeTextarea) {
      codeTextarea.value = savedCode;
    }
  } catch (e) {
    // Graceful fallback if localStorage is disabled
  }

  // Auto-save debounced input
  var saveTimer = null;
  if (codeTextarea) {
    codeTextarea.addEventListener('input', function () {
      clearTimeout(saveTimer);
      saveTimer = setTimeout(function () {
        try {
          localStorage.setItem(storageKey, codeTextarea.value);
        } catch (e) {}
      }, 400);
    });
  }

  // 1. Mobile Tabs Switcher
  if (tabCodeBtn && tabTermBtn && editorPane && terminalPane) {
    tabCodeBtn.addEventListener('click', function () {
      tabCodeBtn.classList.add('is-active');
      tabTermBtn.classList.remove('is-active');
      editorPane.classList.remove('is-hidden');
      terminalPane.classList.add('is-hidden');
    });

    tabTermBtn.addEventListener('click', function () {
      tabTermBtn.classList.add('is-active');
      tabCodeBtn.classList.remove('is-active');
      terminalPane.classList.remove('is-hidden');
      editorPane.classList.add('is-hidden');
    });
  }

  // 2. Language Switcher
  if (langSelect) {
    langSelect.addEventListener('change', function () {
      var nextLang = langSelect.value;
      if (nextLang) {
        window.location.href = window.cdtEditorData && window.cdtEditorData.editorBaseUrl
          ? window.cdtEditorData.editorBaseUrl + nextLang + '/'
          : '/editor/' + nextLang + '/';
      }
    });
  }

  // 3. Stdin Toggle
  var tabStdinToggle = document.getElementById('cdt-tab-stdin-toggle');
  if (tabStdinToggle && stdinBox) {
    tabStdinToggle.addEventListener('click', function () {
      stdinBox.classList.toggle('is-active');
      tabStdinToggle.classList.toggle('is-active');
    });
  }

  // Helper: Async script loader
  function loadScriptAsync(src) {
    return new Promise(function (resolve, reject) {
      if (document.querySelector('script[src="' + src + '"]')) {
        resolve();
        return;
      }
      var s = document.createElement('script');
      s.src = src;
      s.async = true;
      s.onload = function () { resolve(); };
      s.onerror = function () { reject(new Error('تعذر تحميل المكتبة: ' + src)); };
      document.head.appendChild(s);
    });
  }

  // 4. Client-side Isolated Web Worker Execution (JavaScript)
  function runBrowserJavaScript(code, isFromTs) {
    btnRun.classList.add('is-loading');
    btnRun.setAttribute('disabled', 'disabled');
    setTerminalStatus('جاري التشغيل في المتصفح...', 'running');
    if (!isFromTs) {
      terminalOutput.textContent = 'تشغيل JavaScript داخل محرك المتصفح المعزول (Web Worker)...\n';
    }

    var startTime = performance.now();
    var workerScript = [
      'var logs = [];',
      'function formatArg(a) {',
      '  if (a === null) return "null";',
      '  if (a === undefined) return "undefined";',
      '  if (typeof a === "object") {',
      '    try { return JSON.stringify(a, null, 2); } catch(e) { return String(a); }',
      '  }',
      '  return String(a);',
      '}',
      'console.log = function() { logs.push(Array.from(arguments).map(formatArg).join(" ")); };',
      'console.info = console.log;',
      'console.warn = function() { logs.push("[تحذير] " + Array.from(arguments).map(formatArg).join(" ")); };',
      'console.error = function() { logs.push("[خطأ] " + Array.from(arguments).map(formatArg).join(" ")); };',
      'self.onmessage = function(e) {',
      '  try {',
      '    var result = (new Function(e.data))();',
      '    if (result !== undefined && logs.length === 0) {',
      '      logs.push(formatArg(result));',
      '    }',
      '    self.postMessage({ success: true, logs: logs });',
      '  } catch (err) {',
      '    self.postMessage({ success: false, error: (err && err.stack) ? err.stack : String(err), logs: logs });',
      '  }',
      '};'
    ].join('\n');

    var blob = new Blob([workerScript], { type: 'application/javascript' });
    var blobUrl = URL.createObjectURL(blob);
    var worker = null;
    try {
      worker = new Worker(blobUrl);
    } catch (e) {
      URL.revokeObjectURL(blobUrl);
      btnRun.classList.remove('is-loading');
      btnRun.removeAttribute('disabled');
      terminalOutput.textContent = '[خطأ بيئة المتصفح]\nتعذر تشغيل Web Worker: ' + e.message;
      setTerminalStatus('خطأ', 'error');
      return;
    }

    var timedOut = false;
    var timer = setTimeout(function () {
      timedOut = true;
      if (worker) {
        worker.terminate();
      }
      URL.revokeObjectURL(blobUrl);
      btnRun.classList.remove('is-loading');
      btnRun.removeAttribute('disabled');
      terminalOutput.textContent = '[مهلة التنفيذ]\nتم إيقاف البرنامج لتجاوزه المهلة المسموح بها (3 ثوانٍ) لمنع تجميد المتصفح.';
      terminalOutput.className = 'cdt-terminal-output is-error';
      setTerminalStatus('انتهت المهلة', 'error');
    }, 3000);

    worker.onmessage = function (e) {
      if (timedOut) return;
      clearTimeout(timer);
      worker.terminate();
      URL.revokeObjectURL(blobUrl);
      btnRun.classList.remove('is-loading');
      btnRun.removeAttribute('disabled');

      var dur = Math.round(performance.now() - startTime);
      var data = e.data;
      var out = '';

      if (data.logs && data.logs.length > 0) {
        out += data.logs.join('\n');
      }

      if (!data.success) {
        out += (out ? '\n\n' : '') + '[Runtime Error]:\n' + data.error;
        terminalOutput.textContent = out;
        terminalOutput.className = 'cdt-terminal-output is-error';
        setTerminalStatus(dur + 'ms | خطأ', 'error');
      } else {
        if (!out.trim()) {
          out = '✓ تم تنفيذ الكود بنجاح (لا توجد مخرجات console.log).';
        }
        terminalOutput.textContent = out;
        terminalOutput.className = 'cdt-terminal-output';
        setTerminalStatus(dur + 'ms | Exit: 0', 'success');
      }
    };

    worker.onerror = function (err) {
      if (timedOut) return;
      clearTimeout(timer);
      worker.terminate();
      URL.revokeObjectURL(blobUrl);
      btnRun.classList.remove('is-loading');
      btnRun.removeAttribute('disabled');
      terminalOutput.textContent = '[خطأ تنفيذ فوري]: ' + (err.message || 'فشل تشغيل السكربت.');
      terminalOutput.className = 'cdt-terminal-output is-error';
      setTerminalStatus('فشل', 'error');
    };

    worker.postMessage(code);
  }

  // 4b. TypeScript In-Browser Transpiler & Execution
  function runBrowserTypeScript(code) {
    btnRun.classList.add('is-loading');
    btnRun.setAttribute('disabled', 'disabled');
    setTerminalStatus('جاري ترجمة TypeScript...', 'running');
    terminalOutput.textContent = 'جاري ترجمة وفحص كود TypeScript في المتصفح...\n';

    function regexStripTs(ts) {
      return ts
        .replace(/interface\s+\w+[\s\S]*?\{[\s\S]*?\}/g, '')
        .replace(/type\s+\w+\s*=[\s\S]*?;/g, '')
        .replace(/:\s*[A-Za-z0-9_\[\]<>|&]+(?=[\s=,)\{;])/g, '')
        .replace(/as\s+[A-Za-z0-9_\[\]<>]+/g, '')
        .replace(/\b(public|private|protected|readonly)\s+/g, '');
    }

    if (window.ts && window.ts.transpileModule) {
      try {
        var jsOut = window.ts.transpileModule(code, {
          compilerOptions: { target: 99, removeComments: false }
        }).outputText;
        runBrowserJavaScript(jsOut, true);
        return;
      } catch (e) {}
    }

    loadScriptAsync('https://cdn.jsdelivr.net/npm/typescript@5.4.5/lib/typescript.min.js')
      .then(function () {
        if (window.ts && window.ts.transpileModule) {
          var js = window.ts.transpileModule(code, {
            compilerOptions: { target: 99, removeComments: false }
          }).outputText;
          runBrowserJavaScript(js, true);
        } else {
          runBrowserJavaScript(regexStripTs(code), true);
        }
      })
      .catch(function () {
        // Fast offline fallback
        runBrowserJavaScript(regexStripTs(code), true);
      });
  }

  // 4c. Python In-Browser Execution via Pyodide WebAssembly
  var pyodideInstance = null;
  var pyodideLoadingPromise = null;

  function initPyodide() {
    if (pyodideInstance) {
      return Promise.resolve(pyodideInstance);
    }
    if (pyodideLoadingPromise) {
      return pyodideLoadingPromise;
    }
    pyodideLoadingPromise = loadScriptAsync('https://cdn.jsdelivr.net/pyodide/v0.26.4/full/pyodide.js')
      .then(function () {
        terminalOutput.textContent += 'تهيئة محرك CPython WebAssembly المعزول...\n';
        return window.loadPyodide({
          indexURL: 'https://cdn.jsdelivr.net/pyodide/v0.26.4/full/'
        });
      })
      .then(function (loaded) {
        pyodideInstance = loaded;
        return loaded;
      });
    return pyodideLoadingPromise;
  }

  function runBrowserPython(code, stdin) {
    btnRun.classList.add('is-loading');
    btnRun.setAttribute('disabled', 'disabled');
    setTerminalStatus('جاري تشغيل Python (WebAssembly)...', 'running');
    terminalOutput.textContent = 'تشغيل بايثون داخل متصفحك عبر CPython 3.12 WebAssembly (100% Client-Side)...\n';

    var startTime = performance.now();

    initPyodide()
      .then(function (py) {
        var pyStdout = '';
        var pyStderr = '';

        py.setStdout({
          batched: function (str) {
            pyStdout += str + '\n';
          }
        });

        py.setStderr({
          batched: function (str) {
            pyStderr += str + '\n';
          }
        });

        var setupCode = '';
        if (stdin && stdin.trim() !== '') {
          var escStdin = JSON.stringify(stdin);
          setupCode = 'import sys, io\nsys.stdin = io.StringIO(' + escStdin + ')\n';
        }

        return py.runPythonAsync(setupCode + code)
          .then(function () {
            var dur = Math.round(performance.now() - startTime);
            btnRun.classList.remove('is-loading');
            btnRun.removeAttribute('disabled');

            var finalTxt = '';
            if (pyStdout) finalTxt += pyStdout;
            if (pyStderr) finalTxt += (finalTxt ? '\n' : '') + '[تحذير]:\n' + pyStderr;

            if (!finalTxt.trim()) {
              finalTxt = '✓ تم تنفيذ سكربت بايثون بنجاح (Exit Code: 0 - لا توجد مخرجات print).';
            }

            terminalOutput.textContent = finalTxt;
            terminalOutput.className = 'cdt-terminal-output';
            setTerminalStatus(dur + 'ms | Exit: 0', 'success');
          });
      })
      .catch(function (err) {
        var dur = Math.round(performance.now() - startTime);
        btnRun.classList.remove('is-loading');
        btnRun.removeAttribute('disabled');
        terminalOutput.textContent = '[Python Error]:\n' + (err.message || String(err));
        terminalOutput.className = 'cdt-terminal-output is-error';
        setTerminalStatus(dur + 'ms | خطأ', 'error');
      });
  }

  // 4d. SQL In-Browser Execution via SQLite WebAssembly (sql.js)
  var sqlJsInstance = null;
  var sqlLoadingPromise = null;

  function initSql() {
    if (sqlJsInstance) {
      return Promise.resolve(sqlJsInstance);
    }
    if (sqlLoadingPromise) {
      return sqlLoadingPromise;
    }
    sqlLoadingPromise = loadScriptAsync('https://cdnjs.cloudflare.com/ajax/libs/sql.js/1.12.0/sql-wasm.js')
      .then(function () {
        terminalOutput.textContent += 'تهيئة محرك SQLite WebAssembly...\n';
        return window.initSqlJs({
          locateFile: function (file) {
            return 'https://cdnjs.cloudflare.com/ajax/libs/sql.js/1.12.0/' + file;
          }
        });
      })
      .then(function (SQL) {
        sqlJsInstance = SQL;
        return SQL;
      });
    return sqlLoadingPromise;
  }

  function formatSqlTable(columns, values) {
    if (!values || values.length === 0) {
      return '(0 صفوف - لا توجد نتائج)';
    }
    var colWidths = columns.map(function (col, idx) {
      var max = String(col).length;
      values.forEach(function (row) {
        var val = row[idx] === null ? 'NULL' : String(row[idx]);
        if (val.length > max) max = val.length;
      });
      return max + 2;
    });

    var sep = '+' + colWidths.map(function (w) { return '-'.repeat(w); }).join('+') + '+';
    var header = '|' + columns.map(function (col, idx) {
      return ' ' + String(col).padEnd(colWidths[idx] - 1);
    }).join('|') + '|';

    var rows = values.map(function (row) {
      return '|' + row.map(function (val, idx) {
        var v = val === null ? 'NULL' : String(val);
        return ' ' + v.padEnd(colWidths[idx] - 1);
      }).join('|') + '|';
    });

    return [sep, header, sep].concat(rows).concat([sep, '(' + values.length + ' صفوف مسترجعة)']).join('\n');
  }

  function runBrowserSQL(code) {
    btnRun.classList.add('is-loading');
    btnRun.setAttribute('disabled', 'disabled');
    setTerminalStatus('جاري تنفيذ استعلامات SQL...', 'running');
    terminalOutput.textContent = 'إنشاء قاعدة بيانات معزولة (SQLite In-Memory) وتنفيذ الاستعلامات...\n';

    var startTime = performance.now();

    initSql()
      .then(function (SQL) {
        var db = new SQL.Database();
        var dur = Math.round(performance.now() - startTime);

        try {
          var results = db.exec(code);
          btnRun.classList.remove('is-loading');
          btnRun.removeAttribute('disabled');

          if (!results || results.length === 0) {
            terminalOutput.textContent = '✓ تم تنفيذ أوامر SQL بنجاح في قاعدة البيانات المؤقتة.\n(CREATE/INSERT/UPDATE تم تنفيذها بنجاح)';
            terminalOutput.className = 'cdt-terminal-output';
            setTerminalStatus(dur + 'ms | نجاح', 'success');
            return;
          }

          var out = '';
          results.forEach(function (res, i) {
            if (i > 0) out += '\n\n';
            out += formatSqlTable(res.columns, res.values);
          });

          terminalOutput.textContent = out;
          terminalOutput.className = 'cdt-terminal-output';
          setTerminalStatus(dur + 'ms | Exit: 0', 'success');
        } catch (sqlErr) {
          btnRun.classList.remove('is-loading');
          btnRun.removeAttribute('disabled');
          terminalOutput.textContent = '[SQL Syntax / Runtime Error]:\n' + sqlErr.message;
          terminalOutput.className = 'cdt-terminal-output is-error';
          setTerminalStatus(dur + 'ms | خطأ SQL', 'error');
        } finally {
          db.close();
        }
      })
      .catch(function (err) {
        btnRun.classList.remove('is-loading');
        btnRun.removeAttribute('disabled');
        terminalOutput.textContent = '[خطأ تحميل محرك SQLite]:\n' + err.message;
        terminalOutput.className = 'cdt-terminal-output is-error';
        setTerminalStatus('خطأ', 'error');
      });
  }

  // 5. Code Execution Handler
  function executeCode() {
    if (!btnRun || btnRun.classList.contains('is-loading')) {
      return;
    }

    var code = codeTextarea ? codeTextarea.value : '';
    if (!code.trim()) {
      setTerminalStatus('يرجى كتابة كود لتشغيله', 'error');
      return;
    }

    var stdin = stdinInput ? stdinInput.value : '';

    // Auto-switch to terminal tab on mobile
    if (window.innerWidth <= 860 && tabTermBtn) {
      tabTermBtn.click();
    }

    // 5a. Client-Side In-Browser Runners (Zero server load, instant execution)
    if (currentLangSlug === 'javascript') {
      runBrowserJavaScript(code);
      return;
    }
    if (currentLangSlug === 'typescript') {
      runBrowserTypeScript(code);
      return;
    }
    if (currentLangSlug === 'sql') {
      runBrowserSQL(code);
      return;
    }

    // 5b. Server-Side Execution for System & Compiled Languages
    btnRun.classList.add('is-loading');
    btnRun.setAttribute('disabled', 'disabled');
    setTerminalStatus('جاري التنفيذ في البيئة المعزولة...', 'running');
    terminalOutput.textContent = 'جاري بناء وتشغيل الكود في الـ Sandbox المعزول...\n';

    var endpoint = (window.cdtEditorData && window.cdtEditorData.apiEndpoint)
      ? window.cdtEditorData.apiEndpoint
      : '/wp-json/cdt/v1/execute';

    fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        language: currentLangSlug,
        code: code,
        stdin: stdin
      })
    })
    .then(function (res) {
      return res.json().then(function (data) {
        return { status: res.status, ok: res.ok, data: data };
      });
    })
    .then(function (response) {
      btnRun.classList.remove('is-loading');
      btnRun.removeAttribute('disabled');

      if (!response.ok || !response.data.success) {
        var err = response.data.error || 'حدث خطأ أثناء تشغيل الكود.';
        terminalOutput.textContent = '[خطأ التنفيذ]\n' + err;
        terminalOutput.className = 'cdt-terminal-output is-error';
        setTerminalStatus('فشل التنفيذ', 'error');
        return;
      }

      var res = response.data;
      var outText = '';

      if (res.compile && res.compile.output) {
        outText += '[مخرجات المترجم (Compiler)]:\n' + res.compile.output + '\n';
      }

      if (res.run) {
        if (res.run.stdout) {
          outText += res.run.stdout;
        }
        if (res.run.stderr) {
          outText += '\n[Standard Error (stderr)]:\n' + res.run.stderr;
        }
      }

      if (!outText.trim()) {
        outText = '✓ تم تنفيذ البرنامج بنجاح بدون مخرجات نصية (Exit Code: 0).';
      }

      terminalOutput.textContent = outText;
      terminalOutput.className = 'cdt-terminal-output' + (res.run && res.run.code !== 0 ? ' is-error' : '');

      var dur = res.duration_ms ? res.duration_ms + 'ms' : '';
      var exitCode = res.run ? 'Exit: ' + res.run.code : '0';
      setTerminalStatus(dur + ' | ' + exitCode, (res.run && res.run.code !== 0) ? 'error' : 'success');
    })
    .catch(function (err) {
      btnRun.classList.remove('is-loading');
      btnRun.removeAttribute('disabled');
      terminalOutput.textContent = '[خطأ الاتصال]\nتعذر الاتصال بخادم تشغيل الأكواد: ' + err.message;
      terminalOutput.className = 'cdt-terminal-output is-error';
      setTerminalStatus('خطأ شبكة', 'error');
    });
  }

  function setTerminalStatus(text, state) {
    if (terminalStatus) terminalStatus.textContent = text;
    if (statusDot) {
      statusDot.className = 'cdt-status-dot is-' + state;
    }
  }

  if (btnRun) {
    btnRun.addEventListener('click', executeCode);
  }

  // 5. Copy Code
  if (btnCopyCode && codeTextarea) {
    btnCopyCode.addEventListener('click', function () {
      var text = codeTextarea.value;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function () {
          var orig = btnCopyCode.innerHTML;
          btnCopyCode.innerHTML = '<span>✓ تم النسخ!</span>';
          setTimeout(function () { btnCopyCode.innerHTML = orig; }, 2000);
        });
      }
    });
  }

  // 6. Download Code File
  if (btnDownloadCode && codeTextarea) {
    btnDownloadCode.addEventListener('click', function () {
      var ext = editorContainer.getAttribute('data-ext') || 'txt';
      var filename = 'main.' + ext;
      var element = document.createElement('a');
      element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(codeTextarea.value));
      element.setAttribute('download', filename);
      element.style.display = 'none';
      document.body.appendChild(element);
      element.click();
      document.body.removeChild(element);
    });
  }

  // 7. Reset Code
  if (btnResetCode && codeTextarea) {
    btnResetCode.addEventListener('click', function () {
      if (confirm('هل أنت متأكد من رغبتك في إعادة تعيين الكود البرمجي واستعادة النموذج الافتراضي؟')) {
        codeTextarea.value = initialCode;
        try {
          localStorage.removeItem(storageKey);
        } catch (e) {}
        setTerminalStatus('تمت إعادة التعيين', 'idle');
      }
    });
  }

  // 8. Clear Terminal
  if (btnClearTerminal && terminalOutput) {
    btnClearTerminal.addEventListener('click', function () {
      terminalOutput.textContent = 'تم مسح الطرفية. جاهز لتشغيل جديد.';
      setTerminalStatus('جاهز', 'idle');
    });
  }

  // 9. Fullscreen Toggle
  if (btnFullscreen) {
    btnFullscreen.addEventListener('click', function () {
      var ws = document.querySelector('.cdt-editor-workspace');
      if (ws) {
        ws.classList.toggle('is-fullscreen');
        if (ws.classList.contains('is-fullscreen')) {
          btnFullscreen.innerHTML = '<span>تصغير الشاشة</span>';
        } else {
          btnFullscreen.innerHTML = '<span>ملء الشاشة</span>';
        }
      }
    });
  }

  // 10. Global Keyboard Shortcuts
  document.addEventListener('keydown', function (e) {
    // Ctrl+Enter or Cmd+Enter to Run
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
      e.preventDefault();
      executeCode();
    }
    // Escape to exit fullscreen
    if (e.key === 'Escape') {
      var ws = document.querySelector('.cdt-editor-workspace');
      if (ws && ws.classList.contains('is-fullscreen')) {
        ws.classList.remove('is-fullscreen');
        if (btnFullscreen) btnFullscreen.innerHTML = '<span>ملء الشاشة</span>';
      }
    }
  });

  // 11. Tab Key Handling inside Fallback Textarea
  if (codeTextarea) {
    codeTextarea.addEventListener('keydown', function (e) {
      if (e.key === 'Tab') {
        e.preventDefault();
        var start = this.selectionStart;
        var end = this.selectionEnd;
        this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
        this.selectionStart = this.selectionEnd = start + 4;
      }
    });
  }

});
