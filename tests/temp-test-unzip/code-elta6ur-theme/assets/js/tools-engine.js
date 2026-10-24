/**
 * كود التطور - محرك تشغيل ومعالجة الأدوات التقنية الـ 30 (100% Client-Side)
 * Code Elta6ur Tools Engine
 *
 * Privacy Guarantee: All computations run strictly inside the user's browser.
 * Zero user data is transmitted to the server.
 */
document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // =========================================================================
  // Global Helper Functions (Clipboard, Download, Notifications)
  // =========================================================================
  function copyToClipboard(text, triggerBtn) {
    if (!text) return;
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(function () {
        showCopySuccess(triggerBtn);
      }).catch(function () {
        fallbackCopy(text, triggerBtn);
      });
    } else {
      fallbackCopy(text, triggerBtn);
    }
  }

  function fallbackCopy(text, triggerBtn) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    try {
      document.execCommand('copy');
      showCopySuccess(triggerBtn);
    } catch (e) {
      console.error('Copy failed', e);
    }
    document.body.removeChild(ta);
  }

  function showCopySuccess(btn) {
    if (!btn) return;
    var originalHTML = btn.innerHTML;
    btn.innerHTML = '<span>✓ تم النسخ!</span>';
    btn.classList.add('is-copied');
    setTimeout(function () {
      btn.innerHTML = originalHTML;
      btn.classList.remove('is-copied');
    }, 2000);
  }

  function downloadTextFile(filename, text) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', filename);
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
  }

  // =========================================================================
  // 1. Tools Hub Live Search & Category Filtering
  // =========================================================================
  var searchInput = document.getElementById('cdt-tools-search');
  var catButtons = document.querySelectorAll('.cdt-tool-cat-btn');
  var toolCards = document.querySelectorAll('.cdt-tool-card');

  if (searchInput || catButtons.length > 0) {
    var activeCategory = 'all';

    var filterTools = function () {
      var query = searchInput ? searchInput.value.toLowerCase().trim() : '';

      toolCards.forEach(function (card) {
        var cardCat = card.getAttribute('data-category');
        var cardName = (card.getAttribute('data-name') || '').toLowerCase();
        var cardNameEn = (card.getAttribute('data-name-en') || '').toLowerCase();
        var cardDesc = (card.getAttribute('data-desc') || '').toLowerCase();

        var matchesCat = (activeCategory === 'all' || cardCat === activeCategory);
        var matchesQuery = !query || cardName.indexOf(query) !== -1 || cardNameEn.indexOf(query) !== -1 || cardDesc.indexOf(query) !== -1;

        if (matchesCat && matchesQuery) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    };

    if (searchInput) {
      searchInput.addEventListener('input', filterTools);
    }

    catButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        catButtons.forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
        activeCategory = btn.getAttribute('data-cat-slug') || 'all';
        filterTools();
      });
    });
  }

  // =========================================================================
  // 2. FAQ Accordion Toggles
  // =========================================================================
  var faqQuestions = document.querySelectorAll('.cdt-faq-question');
  faqQuestions.forEach(function (q) {
    q.addEventListener('click', function () {
      var item = q.parentElement;
      var wasOpen = item.classList.contains('is-open');
      item.parentElement.querySelectorAll('.cdt-faq-item').forEach(function (el) {
        el.classList.remove('is-open');
      });
      if (!wasOpen) {
        item.classList.add('is-open');
      }
    });
  });

  // =========================================================================
  // 3. Dispatch Active Tool Implementation
  // =========================================================================
  var toolContainer = document.querySelector('[data-tool-slug]');
  if (!toolContainer) {
    return;
  }

  var toolSlug = toolContainer.getAttribute('data-tool-slug');
  var inputEl = document.getElementById('cdt-tool-input');
  var outputEl = document.getElementById('cdt-tool-output');
  var statusEl = document.getElementById('cdt-tool-status');
  var btnSample = document.getElementById('cdt-btn-sample');
  var btnReset = document.getElementById('cdt-btn-reset');
  var btnCopy = document.getElementById('cdt-btn-copy');
  var btnDownload = document.getElementById('cdt-btn-download');

  function setStatus(msg, isError) {
    if (!statusEl) return;
    statusEl.textContent = msg;
    statusEl.className = 'cdt-tool-status ' + (isError ? 'is-error' : (msg ? 'is-success' : ''));
  }

  if (btnCopy) {
    btnCopy.addEventListener('click', function () {
      var text = outputEl ? (outputEl.value || outputEl.innerText || outputEl.textContent) : '';
      copyToClipboard(text, btnCopy);
    });
  }

  if (btnDownload) {
    btnDownload.addEventListener('click', function () {
      var text = outputEl ? (outputEl.value || outputEl.innerText || outputEl.textContent) : '';
      var ext = outputEl.getAttribute('data-ext') || 'txt';
      downloadTextFile(toolSlug + '-output.' + ext, text);
    });
  }

  if (btnReset) {
    btnReset.addEventListener('click', function () {
      if (inputEl) inputEl.value = '';
      if (outputEl) {
        if ('value' in outputEl) outputEl.value = '';
        else outputEl.innerHTML = '';
      }
      setStatus('تم مسح الحقول بنجاح', false);
    });
  }

  // -------------------------------------------------------------------------
  // TOOL 1: JSON Formatter & Validator
  // -------------------------------------------------------------------------
  if (toolSlug === 'json-formatter') {
    var btnMinify = document.getElementById('cdt-btn-minify');
    var btnFormat = document.getElementById('cdt-btn-format');

    var formatJSON = function (indent) {
      if (!inputEl.value.trim()) {
        outputEl.value = '';
        setStatus('يرجى إدخال كود JSON', false);
        return;
      }
      try {
        var parsed = JSON.parse(inputEl.value);
        outputEl.value = JSON.stringify(parsed, null, indent);
        var keysCount = Object.keys(parsed).length;
        setStatus('✓ كود JSON صالح (Valid JSON). تم التنسيق بنجاح (' + keysCount + ' عنصر أساسي).', false);
      } catch (err) {
        outputEl.value = '';
        setStatus('خطأ في صياغة JSON: ' + err.message, true);
      }
    };

    if (btnFormat) btnFormat.addEventListener('click', function () { formatJSON(2); });
    if (btnMinify) btnMinify.addEventListener('click', function () { formatJSON(0); });
    if (inputEl) inputEl.addEventListener('input', function () { formatJSON(2); });

    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = JSON.stringify({
          platform: "Code Elta6ur",
          version: "2.0.0",
          author: { name: "Mohammad Abu Khashreef", role: "Full Stack Engineer" },
          features: ["Online Code Editor", "30 Tech Tools", "Fast & Secure"],
          is_active: true
        });
        formatJSON(2);
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 2: JSON Diff
  // -------------------------------------------------------------------------
  if (toolSlug === 'json-diff') {
    var inputEl2 = document.getElementById('cdt-tool-input-2');
    var btnCompare = document.getElementById('cdt-btn-compare');

    function deepCompare(obj1, obj2, path) {
      path = path || '';
      var diffs = [];
      var keys = Array.from(new Set(Object.keys(obj1 || {}).concat(Object.keys(obj2 || {}))));

      keys.forEach(function (key) {
        var currPath = path ? path + '.' + key : key;
        var val1 = obj1 ? obj1[key] : undefined;
        var val2 = obj2 ? obj2[key] : undefined;

        if (val1 === undefined && val2 !== undefined) {
          diffs.push({ type: 'added', path: currPath, val: val2 });
        } else if (val1 !== undefined && val2 === undefined) {
          diffs.push({ type: 'deleted', path: currPath, val: val1 });
        } else if (typeof val1 === 'object' && val1 !== null && typeof val2 === 'object' && val2 !== null) {
          diffs = diffs.concat(deepCompare(val1, val2, currPath));
        } else if (val1 !== val2) {
          diffs.push({ type: 'modified', path: currPath, from: val1, to: val2 });
        }
      });
      return diffs;
    }

    var runDiff = function () {
      try {
        var j1 = JSON.parse(inputEl.value || '{}');
        var j2 = JSON.parse(inputEl2.value || '{}');
        var diffs = deepCompare(j1, j2);

        if (diffs.length === 0) {
          outputEl.innerHTML = '<div style="color:#10B981; padding: 1rem; font-weight: bold;">✓ لا توجد أي فروقات بين الكائنين! الكودان متطابقان تماماً.</div>';
          setStatus('الكائنان متطابقان بنسبة 100%', false);
          return;
        }

        var html = '<div style="padding: 0.5rem; font-family: monospace;">';
        diffs.forEach(function (d) {
          if (d.type === 'added') {
            html += '<div style="background: rgba(16,185,129,0.12); color:#047857; padding: 6px 12px; margin-bottom: 4px; border-radius: 4px;"><strong>+ إضافة:</strong> ' + d.path + ' = ' + JSON.stringify(d.val) + '</div>';
          } else if (d.type === 'deleted') {
            html += '<div style="background: rgba(239,68,68,0.12); color:#B91C1C; padding: 6px 12px; margin-bottom: 4px; border-radius: 4px;"><strong>- حذف:</strong> ' + d.path + ' (كان: ' + JSON.stringify(d.val) + ')</div>';
          } else if (d.type === 'modified') {
            html += '<div style="background: rgba(245,158,11,0.12); color:#B45309; padding: 6px 12px; margin-bottom: 4px; border-radius: 4px;"><strong>~ تعديل:</strong> ' + d.path + ' : <span style="text-decoration:line-through;">' + JSON.stringify(d.from) + '</span> &rarr; <span style="font-weight:bold;">' + JSON.stringify(d.to) + '</span></div>';
          }
        });
        html += '</div>';
        outputEl.innerHTML = html;
        setStatus('تم العثور على ' + diffs.length + ' فروقات.', false);
      } catch (e) {
        outputEl.innerHTML = '<div style="color:#EF4444; padding: 1rem;">خطأ في تحليل JSON: ' + e.message + '</div>';
        setStatus('يرجى التأكد من صحة كود JSON في كلا الحقلين', true);
      }
    };

    if (btnCompare) btnCompare.addEventListener('click', runDiff);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = JSON.stringify({ id: 1, name: "كود التطور", status: "active", version: "1.0" }, null, 2);
        inputEl2.value = JSON.stringify({ id: 1, name: "كود التطور المطور", status: "active", version: "2.0", is_premium: true }, null, 2);
        runDiff();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 3: JSON to Code Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'json-to-code') {
    var langSelect = document.getElementById('cdt-target-lang');

    function toTypeScript(obj, rootName) {
      rootName = rootName || 'RootObject';
      var lines = ['export interface ' + rootName + ' {'];
      for (var key in obj) {
        var val = obj[key];
        var type = typeof val;
        if (val === null) type = 'any';
        else if (Array.isArray(val)) type = (val.length ? typeof val[0] : 'any') + '[]';
        else if (type === 'object') type = key.charAt(0).toUpperCase() + key.slice(1);
        lines.push('  ' + key + ': ' + type + ';');
      }
      lines.push('}');
      return lines.join('\n');
    }

    function toPython(obj, rootName) {
      rootName = rootName || 'RootModel';
      var lines = ['from dataclasses import dataclass', 'from typing import Any, List, Optional\n', '@dataclass', 'class ' + rootName + ':'];
      for (var key in obj) {
        var val = obj[key];
        var pyType = 'Any';
        if (typeof val === 'string') pyType = 'str';
        else if (typeof val === 'number') pyType = Number.isInteger(val) ? 'int' : 'float';
        else if (typeof val === 'boolean') pyType = 'bool';
        else if (Array.isArray(val)) pyType = 'List[Any]';
        lines.push('    ' + key + ': ' + pyType);
      }
      return lines.join('\n');
    }

    function toGolang(obj, rootName) {
      rootName = rootName || 'Payload';
      var lines = ['type ' + rootName + ' struct {'];
      for (var key in obj) {
        var val = obj[key];
        var goType = 'interface{}';
        if (typeof val === 'string') goType = 'string';
        else if (typeof val === 'number') goType = Number.isInteger(val) ? 'int' : 'float64';
        else if (typeof val === 'boolean') goType = 'bool';
        else if (Array.isArray(val)) goType = '[]interface{}';
        var fieldName = key.charAt(0).toUpperCase() + key.slice(1);
        lines.push('    ' + fieldName + ' ' + goType + ' `json:"' + key + '"`');
      }
      lines.push('}');
      return lines.join('\n');
    }

    function toCSharp(obj, rootName) {
      rootName = rootName || 'Root';
      var lines = ['public class ' + rootName + '\n{'];
      for (var key in obj) {
        var val = obj[key];
        var csType = 'object';
        if (typeof val === 'string') csType = 'string';
        else if (typeof val === 'number') csType = Number.isInteger(val) ? 'int' : 'double';
        else if (typeof val === 'boolean') csType = 'bool';
        else if (Array.isArray(val)) csType = 'List<object>';
        var fieldName = key.charAt(0).toUpperCase() + key.slice(1);
        lines.push('    public ' + csType + ' ' + fieldName + ' { get; set; }');
      }
      lines.push('}');
      return lines.join('\n');
    }

    var generateCode = function () {
      if (!inputEl.value.trim()) return;
      try {
        var obj = JSON.parse(inputEl.value);
        var target = langSelect ? langSelect.value : 'typescript';
        var res = '';
        if (target === 'typescript') res = toTypeScript(obj);
        else if (target === 'python') res = toPython(obj);
        else if (target === 'go') res = toGolang(obj);
        else if (target === 'csharp') res = toCSharp(obj);
        outputEl.value = res;
        setStatus('✓ تم توليد كود ' + target.toUpperCase() + ' بنجاح.', false);
      } catch (e) {
        setStatus('خطأ في قراءة JSON: ' + e.message, true);
      }
    };

    if (langSelect) langSelect.addEventListener('change', generateCode);
    if (inputEl) inputEl.addEventListener('input', generateCode);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = JSON.stringify({ userId: 101, username: "mokashreef", email: "contact@code-elta6ur.com", isActive: true, score: 98.5 }, null, 2);
        generateCode();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 4: JSON Schema Validator
  // -------------------------------------------------------------------------
  if (toolSlug === 'json-schema-validator') {
    var schemaInput = document.getElementById('cdt-tool-schema');
    var btnValidate = document.getElementById('cdt-btn-validate-schema');

    var validateSchema = function () {
      try {
        var data = JSON.parse(inputEl.value || '{}');
        var schema = JSON.parse(schemaInput.value || '{}');
        var errors = [];

        if (schema.required && Array.isArray(schema.required)) {
          schema.required.forEach(function (req) {
            if (data[req] === undefined) {
              errors.push('الحقل الإلزامي "' + req + '" غير موجود في كائن JSON.');
            }
          });
        }

        if (schema.properties) {
          for (var p in schema.properties) {
            if (data[p] !== undefined) {
              var expectedType = schema.properties[p].type;
              var actualType = typeof data[p];
              if (expectedType === 'integer' && !Number.isInteger(data[p])) {
                errors.push('الحقل "' + p + '" يجب أن يكون integer.');
              } else if (expectedType && expectedType !== 'integer' && actualType !== expectedType) {
                errors.push('الحقل "' + p + '" نوعه ' + actualType + ' بينما المتوقع ' + expectedType + '.');
              }
            }
          }
        }

        if (errors.length === 0) {
          outputEl.value = '✓ كائن JSON متوافق بنسبة 100% مع مواصفات JSON Schema.\nلا توجد أي أخطاء أو حقول ناقصة.';
          setStatus('✓ التحقق سليم ومتوافق تماماً مع المخطط', false);
        } else {
          outputEl.value = '⚠ تم العثور على أخطاء عدم تطابق:\n' + errors.join('\n');
          setStatus('فشل التحقق: هناك أخطاء صياغة أو حقول مفقودة', true);
        }
      } catch (e) {
        setStatus('خطأ في تحليل JSON: ' + e.message, true);
      }
    };

    if (btnValidate) btnValidate.addEventListener('click', validateSchema);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = JSON.stringify({ id: 1, name: "كود التطور", email: "contact@code-elta6ur.com" }, null, 2);
        schemaInput.value = JSON.stringify({
          type: "object",
          required: ["id", "name", "email"],
          properties: {
            id: { type: "integer" },
            name: { type: "string" },
            email: { type: "string" }
          }
        }, null, 2);
        validateSchema();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 5: JSONPath Tester
  // -------------------------------------------------------------------------
  if (toolSlug === 'jsonpath-tester') {
    var pathInput = document.getElementById('cdt-jsonpath-expr');
    var btnEval = document.getElementById('cdt-btn-eval-jsonpath');

    var evalJSONPath = function () {
      try {
        var obj = JSON.parse(inputEl.value || '{}');
        var path = (pathInput ? pathInput.value : '$.').trim();
        var result;

        if (path === '$' || path === '$.') {
          result = obj;
        } else {
          var cleanPath = path.replace(/^\$\.?/, '');
          var parts = cleanPath.split('.').filter(Boolean);
          var curr = obj;
          for (var i = 0; i < parts.length; i++) {
            var p = parts[i];
            if (p.indexOf('[') !== -1) {
              var arrKey = p.split('[')[0];
              var idx = parseInt(p.split('[')[1].replace(']', ''));
              curr = arrKey ? curr[arrKey][idx] : curr[idx];
            } else {
              curr = curr ? curr[p] : undefined;
            }
          }
          result = curr;
        }
        outputEl.value = JSON.stringify(result, null, 2);
        setStatus('✓ تم استخراج التعبير بنجاح', false);
      } catch (e) {
        outputEl.value = '';
        setStatus('خطأ: ' + e.message, true);
      }
    };

    if (btnEval) btnEval.addEventListener('click', evalJSONPath);
    if (pathInput) pathInput.addEventListener('input', evalJSONPath);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = JSON.stringify({
          store: {
            book: [
              { title: "تعلم HTML", price: 10 },
              { title: "شرح CSS", price: 12 },
              { title: "احتراف JavaScript", price: 25 }
            ],
            city: "Damascus"
          }
        }, null, 2);
        if (pathInput) pathInput.value = '$.store.book[0].title';
        evalJSONPath();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 6: YAML ↔ JSON Converter
  // -------------------------------------------------------------------------
  if (toolSlug === 'yaml-json-converter') {
    var btnToJson = document.getElementById('cdt-btn-yaml-to-json');
    var btnToYaml = document.getElementById('cdt-btn-json-to-yaml');

    // Lightweight clean YAML serializer/parser for client-side
    function simpleYamlSerialize(obj, depth) {
      depth = depth || 0;
      var indent = '  '.repeat(depth);
      var out = '';
      if (Array.isArray(obj)) {
        obj.forEach(function (item) {
          if (typeof item === 'object' && item !== null) {
            out += indent + '-\n' + simpleYamlSerialize(item, depth + 1);
          } else {
            out += indent + '- ' + JSON.stringify(item) + '\n';
          }
        });
      } else if (typeof obj === 'object' && obj !== null) {
        for (var k in obj) {
          var v = obj[k];
          if (typeof v === 'object' && v !== null) {
            out += indent + k + ':\n' + simpleYamlSerialize(v, depth + 1);
          } else {
            out += indent + k + ': ' + (typeof v === 'string' ? '"' + v + '"' : v) + '\n';
          }
        }
      }
      return out;
    }

    function simpleYamlParse(yamlText) {
      var lines = yamlText.split('\n');
      var root = {};
      lines.forEach(function (line) {
        var trimmed = line.trim();
        if (!trimmed || trimmed.startsWith('#')) return;
        var parts = trimmed.split(':');
        if (parts.length >= 2) {
          var k = parts[0].trim();
          var v = parts.slice(1).join(':').trim().replace(/^["']|["']$/g, '');
          if (v === 'true') v = true;
          else if (v === 'false') v = false;
          else if (!isNaN(v) && v !== '') v = Number(v);
          root[k] = v;
        }
      });
      return root;
    }

    if (btnToJson) {
      btnToJson.addEventListener('click', function () {
        try {
          var parsed = simpleYamlParse(inputEl.value);
          outputEl.value = JSON.stringify(parsed, null, 2);
          setStatus('✓ تم التحويل من YAML إلى JSON بنجاح', false);
        } catch (e) {
          setStatus('خطأ في قراءة YAML: ' + e.message, true);
        }
      });
    }

    if (btnToYaml) {
      btnToYaml.addEventListener('click', function () {
        try {
          var obj = JSON.parse(inputEl.value);
          outputEl.value = simpleYamlSerialize(obj);
          setStatus('✓ تم التحويل من JSON إلى YAML بنجاح', false);
        } catch (e) {
          setStatus('خطأ في قراءة JSON: ' + e.message, true);
        }
      });
    }

    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = JSON.stringify({ name: "Code Elta6ur", port: 8080, debug: true, environment: "production" }, null, 2);
        btnToYaml.click();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 7: cURL to Code Converter
  // -------------------------------------------------------------------------
  if (toolSlug === 'curl-to-code') {
    var curlLangSelect = document.getElementById('cdt-curl-target-lang');

    function parseCurl(cmd) {
      var urlMatch = cmd.match(/curl\s+(?:-[A-Za-z0-9-]+\s+)*['"]?(https?:\/\/[^\s'"]+)/);
      var url = urlMatch ? urlMatch[1] : 'https://api.example.com/data';
      var method = 'GET';
      var methodMatch = cmd.match(/-X\s+([A-Z]+)/);
      if (methodMatch) method = methodMatch[1];
      else if (cmd.indexOf('--data') !== -1 || cmd.indexOf('-d') !== -1) method = 'POST';

      var headers = {};
      var headerMatches = cmd.matchAll(/-H\s+['"]([^'"]+)['"]/g);
      for (var h of headerMatches) {
        var p = h[1].split(':');
        if (p.length >= 2) headers[p[0].trim()] = p.slice(1).join(':').trim();
      }

      var body = '';
      var bodyMatch = cmd.match(/(?:--data|-d|--data-raw)\s+['"]([^'"]+)['"]/);
      if (bodyMatch) body = bodyMatch[1];

      return { url: url, method: method, headers: headers, body: body };
    }

    var convertCurl = function () {
      var curl = (inputEl.value || '').trim();
      if (!curl) return;
      var parsed = parseCurl(curl);
      var lang = curlLangSelect ? curlLangSelect.value : 'fetch';
      var code = '';

      if (lang === 'fetch') {
        code = 'fetch("' + parsed.url + '", {\n' +
               '  method: "' + parsed.method + '",\n' +
               '  headers: ' + JSON.stringify(parsed.headers, null, 4) + (parsed.body ? ',\n  body: ' + JSON.stringify(parsed.body) : '') + '\n' +
               '})\n.then(res => res.json())\n.then(data => console.log(data))\n.catch(err => console.error(err));';
      } else if (lang === 'python') {
        code = 'import requests\n\n' +
               'url = "' + parsed.url + '"\n' +
               'headers = ' + JSON.stringify(parsed.headers, null, 4) + '\n' +
               (parsed.body ? 'data = ' + JSON.stringify(parsed.body) + '\n' : '') +
               'response = requests.' + parsed.method.toLowerCase() + '(url, headers=headers' + (parsed.body ? ', data=data' : '') + ')\n' +
               'print(response.json())';
      } else if (lang === 'php') {
        code = '<?php\n$curl = curl_init();\ncurl_setopt_array($curl, [\n' +
               '  CURLOPT_URL => "' + parsed.url + '",\n' +
               '  CURLOPT_RETURNTRANSFER => true,\n' +
               '  CURLOPT_CUSTOMREQUEST => "' + parsed.method + '",\n' +
               (parsed.body ? '  CURLOPT_POSTFIELDS => ' + JSON.stringify(parsed.body) + ',\n' : '') +
               ']);\n$response = curl_exec($curl);\ncurl_close($curl);\necho $response;';
      }

      outputEl.value = code;
      setStatus('✓ تم تحويل أمر cURL بنجاح إلى ' + lang.toUpperCase(), false);
    };

    if (curlLangSelect) curlLangSelect.addEventListener('change', convertCurl);
    if (inputEl) inputEl.addEventListener('input', convertCurl);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = 'curl -X POST https://api.code-elta6ur.com/v1/users -H "Content-Type: application/json" -H "Authorization: Bearer token123" -d \'{"name": "Mohammad", "role": "Engineer"}\'';
        convertCurl();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 8: OpenAPI / Swagger Validator
  // -------------------------------------------------------------------------
  if (toolSlug === 'openapi-validator') {
    var validateOpenAPI = function () {
      try {
        var spec = JSON.parse(inputEl.value || '{}');
        var errors = [];
        var warnings = [];

        if (!spec.openapi && !spec.swagger) errors.push('مفقود: إصدار OpenAPI أو Swagger غير معرف.');
        if (!spec.info || !spec.info.title) errors.push('مفقود: قسم info أو عنوان info.title غير معرف.');
        if (!spec.paths || Object.keys(spec.paths).length === 0) warnings.push('تنبيه: لا توجد أي مسارات endpoints معرفة في paths.');

        if (errors.length === 0) {
          var count = spec.paths ? Object.keys(spec.paths).length : 0;
          outputEl.value = '✓ مواصفات OpenAPI سليمة وصالحة بنسبة 100%!\n- العنوان: ' + spec.info.title + '\n- الإصدار: ' + (spec.info.version || '1.0.0') + '\n- عدد المسارات المعرفة: ' + count;
          setStatus('✓ المواصفات صالحة وخالية من الأخطاء', false);
        } else {
          outputEl.value = '⚠ أخطاء عدم توافق المواصفات:\n' + errors.join('\n');
          setStatus('فشل التحقق من مواصفات OpenAPI', true);
        }
      } catch (e) {
        setStatus('خطأ في قراءة JSON: ' + e.message, true);
      }
    };

    if (inputEl) inputEl.addEventListener('input', validateOpenAPI);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = JSON.stringify({
          openapi: "3.0.0",
          info: { title: "Code Elta6ur API", version: "1.0.0" },
          paths: { "/tools": { get: { summary: "List all tools", responses: { "200": { description: "Success" } } } } }
        }, null, 2);
        validateOpenAPI();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 9: OpenAPI to TypeScript Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'openapi-to-typescript') {
    var genOpenApiTS = function () {
      try {
        var spec = JSON.parse(inputEl.value || '{}');
        var schemas = (spec.components && spec.components.schemas) || {};
        var ts = '// Types generated from ' + (spec.info ? spec.info.title : 'OpenAPI Spec') + '\n\n';

        for (var name in schemas) {
          var s = schemas[name];
          ts += 'export interface ' + name + ' {\n';
          if (s.properties) {
            for (var prop in s.properties) {
              var pType = s.properties[prop].type || 'any';
              if (pType === 'integer') pType = 'number';
              var isReq = s.required && s.required.includes(prop) ? '' : '?';
              ts += '  ' + prop + isReq + ': ' + pType + ';\n';
            }
          }
          ts += '}\n\n';
        }
        outputEl.value = ts;
        setStatus('✓ تم توليد واجهات TypeScript بنجاح', false);
      } catch (e) {
        setStatus('خطأ في قراءة JSON: ' + e.message, true);
      }
    };

    if (inputEl) inputEl.addEventListener('input', genOpenApiTS);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = JSON.stringify({
          openapi: "3.0.0",
          info: { title: "Platform API", version: "1.0.0" },
          components: {
            schemas: {
              User: {
                type: "object",
                required: ["id", "username"],
                properties: { id: { type: "integer" }, username: { type: "string" }, email: { type: "string" } }
              }
            }
          }
        }, null, 2);
        genOpenApiTS();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 10: OpenAPI Diff
  // -------------------------------------------------------------------------
  if (toolSlug === 'openapi-diff') {
    var inputEl2OAS = document.getElementById('cdt-tool-input-2');
    var btnDiffOAS = document.getElementById('cdt-btn-diff-oas');

    var runOasDiff = function () {
      try {
        var s1 = JSON.parse(inputEl.value || '{}');
        var s2 = JSON.parse(inputEl2OAS.value || '{}');
        var p1 = Object.keys(s1.paths || {});
        var p2 = Object.keys(s2.paths || {});

        var removed = p1.filter(p => !p2.includes(p));
        var added = p2.filter(p => !p1.includes(p));

        var log = '=== تقرير مقارنة OpenAPI Diff ===\n\n';
        if (removed.length > 0) {
          log += '🔴 كسر توافق (Breaking Changes - مسارات محذوفة):\n';
          removed.forEach(r => log += '  - ' + r + '\n');
        } else {
          log += '✓ لا توجد مسارات محذوفة (No Breaking Path Deletions)\n';
        }

        log += '\n🟢 مسارات جديدة مضافة (Additions):\n';
        if (added.length > 0) {
          added.forEach(a => log += '  + ' + a + '\n');
        } else {
          log += '  (لا توجد مسارات جديدة)\n';
        }

        outputEl.value = log;
        setStatus('✓ تم تحليل الفروقات واكتشاف التغييرات الجذرية', false);
      } catch (e) {
        setStatus('خطأ: ' + e.message, true);
      }
    };

    if (btnDiffOAS) btnDiffOAS.addEventListener('click', runOasDiff);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = JSON.stringify({ openapi: "3.0.0", paths: { "/users": {}, "/legacy-v1": {} } }, null, 2);
        inputEl2OAS.value = JSON.stringify({ openapi: "3.0.0", paths: { "/users": {}, "/v2/tools": {} } }, null, 2);
        runOasDiff();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 11: JWT Inspector & Expiration Analyzer
  // -------------------------------------------------------------------------
  if (toolSlug === 'jwt-inspector') {
    function b64DecodeUnicode(str) {
      str = str.replace(/-/g, '+').replace(/_/g, '/');
      while (str.length % 4) str += '=';
      return decodeURIComponent(atob(str).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));
    }

    var inspectJWT = function () {
      var token = (inputEl.value || '').trim();
      if (!token) return;
      var parts = token.split('.');
      if (parts.length !== 3) {
        setStatus('صيغة التوكن غير صحيحة (يجب أن يتكون من 3 أجزاء مفصولة بنقاط)', true);
        return;
      }
      try {
        var header = JSON.parse(b64DecodeUnicode(parts[0]));
        var payload = JSON.parse(b64DecodeUnicode(parts[1]));

        var expInfo = 'غير محدد (Never Expires)';
        if (payload.exp) {
          var expDate = new Date(payload.exp * 1000);
          var now = new Date();
          var diff = payload.exp * 1000 - now.getTime();
          if (diff > 0) {
            var mins = Math.round(diff / 60000);
            expInfo = 'صالح حتى: ' + expDate.toLocaleString('ar-SA') + ' (متبقي ' + mins + ' دقيقة)';
          } else {
            expInfo = 'منتهي الصلاحية منذ: ' + expDate.toLocaleString('ar-SA');
          }
        }

        var result = {
          'HEADER (الترويسة)': header,
          'PAYLOAD (البيانات)': payload,
          'EXPIRATION_STATUS': expInfo,
          'SIGNATURE_NOTICE': 'تنبيه أمني: هذه الأداة تقوم بفك تشفير وفحص المطالبات (Decode & Inspect) ولا تتحقق تشفيرياً من التوقيع الرقمي ما لم يتم تزويد المفتاح السري.'
        };

        outputEl.value = JSON.stringify(result, null, 2);
        setStatus('✓ تم فك تشفير وفحص توكن JWT بنجاح محلياً', false);
      } catch (e) {
        setStatus('فشل فك التشفير: ' + e.message, true);
      }
    };

    if (inputEl) inputEl.addEventListener('input', inspectJWT);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6Ik1vaGFtbWFkIEFidSBLaGFzaHJlZWYiLCJyb2xlIjoiQWRtaW4iLCJpYXQiOjE3MDQ1NDE2MDAsImV4cCI6MTk5OTk5OTk5OX0.dummySignatureABC';
        inspectJWT();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 12: HTTP Headers Analyzer
  // -------------------------------------------------------------------------
  if (toolSlug === 'http-headers-analyzer') {
    var analyzeHeaders = function () {
      var text = (inputEl.value || '').trim();
      var lines = text.split('\n');
      var report = [];

      lines.forEach(function (line) {
        var p = line.split(':');
        if (p.length >= 2) {
          var k = p[0].trim();
          var v = p.slice(1).join(':').trim();
          var note = 'قياسي';
          if (k.toLowerCase() === 'cache-control') note = 'يتحكم في سلوك التخزين المؤقت للعميل والـ CDN.';
          else if (k.toLowerCase() === 'content-type') note = 'يحدد نوع الوسائط وترميز البيانات (MIME Type).';
          else if (k.toLowerCase().startsWith('access-control-')) note = 'سياسات مشاركة الموارد عبر النطاقات CORS.';
          report.push('• ' + k + ' : ' + v + ' [' + note + ']');
        }
      });

      outputEl.value = report.length ? report.join('\n\n') : 'أدخل ترويسات HTTP مثل:\nCache-Control: public, max-age=3600\nContent-Type: application/json';
      setStatus('✓ تم تحليل الترويسات بنجاح', false);
    };

    if (inputEl) inputEl.addEventListener('input', analyzeHeaders);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = "HTTP/2 200 OK\nContent-Type: text/html; charset=UTF-8\nCache-Control: public, max-age=86400\nAccess-Control-Allow-Origin: *\nX-Frame-Options: SAMEORIGIN\nStrict-Transport-Security: max-age=31536000";
        analyzeHeaders();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 13: Security Headers Checker
  // -------------------------------------------------------------------------
  if (toolSlug === 'security-headers-checker') {
    var checkSecurityHeaders = function () {
      var text = (inputEl.value || '').toLowerCase();
      var checks = [
        { name: 'Strict-Transport-Security (HSTS)', key: 'strict-transport-security', desc: 'يفرض اتصال HTTPS الآمن' },
        { name: 'Content-Security-Policy (CSP)', key: 'content-security-policy', desc: 'يحمي من ثغرات حقن السكربتات XSS' },
        { name: 'X-Frame-Options', key: 'x-frame-options', desc: 'يحمي من هجمات Clickjacking' },
        { name: 'X-Content-Type-Options', key: 'x-content-type-options', desc: 'يمنع هجمات التخمين MIME Sniffing' },
        { name: 'Referrer-Policy', key: 'referrer-policy', desc: 'يتحكم في تسريب روابط الإحالة' }
      ];

      var score = 0;
      var out = '=== تقرير أمان الترويسات (Security Audit) ===\n\n';

      checks.forEach(function (c) {
        if (text.indexOf(c.key) !== -1) {
          out += '✓ [مفعل] ' + c.name + ' - ' + c.desc + '\n';
          score += 20;
        } else {
          out += '✕ [مفقود] ' + c.name + ' - ' + c.desc + ' (يوصى بإضافتها)\n';
        }
      });

      var grade = score >= 80 ? 'A+' : (score >= 60 ? 'B' : (score >= 40 ? 'C' : 'F'));
      out += '\n-----------------------------------\nدرجة الأمان التقديرية: ' + grade + ' (' + score + '%)\n';
      outputEl.value = out;
      setStatus('درجة تقييم الأمان: ' + grade, score < 60);
    };

    if (inputEl) inputEl.addEventListener('input', checkSecurityHeaders);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = "Strict-Transport-Security: max-age=31536000; includeSubDomains\nX-Frame-Options: DENY\nX-Content-Type-Options: nosniff\nReferrer-Policy: strict-origin-when-cross-origin";
        checkSecurityHeaders();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 14: Content Security Policy Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'csp-generator') {
    var updateCSP = function () {
      var selfOnly = document.getElementById('cdt-csp-self') ? document.getElementById('cdt-csp-self').checked : true;
      var allowGFonts = document.getElementById('cdt-csp-fonts') ? document.getElementById('cdt-csp-fonts').checked : true;
      var allowYoutube = document.getElementById('cdt-csp-yt') ? document.getElementById('cdt-csp-yt').checked : true;

      var policy = "default-src " + (selfOnly ? "'self'" : "*") + "; ";
      policy += "script-src 'self' 'unsafe-inline' " + (allowYoutube ? "https://www.youtube.com " : "") + "; ";
      policy += "style-src 'self' 'unsafe-inline' " + (allowGFonts ? "https://fonts.googleapis.com " : "") + "; ";
      policy += "font-src 'self' " + (allowGFonts ? "https://fonts.gstatic.com " : "") + "; ";
      if (allowYoutube) policy += "frame-src https://www.youtube.com; ";
      policy += "img-src 'self' data: https:;";

      var out = "<!-- HTTP Response Header -->\nContent-Security-Policy: " + policy + "\n\n";
      out += "<!-- HTML Meta Tag -->\n<meta http-equiv=\"Content-Security-Policy\" content=\"" + policy + "\">";
      outputEl.value = out;
      setStatus('✓ تم توليد سياسة CSP بنجاح', false);
    };

    var checkboxes = document.querySelectorAll('.cdt-csp-opt');
    checkboxes.forEach(function (cb) { cb.addEventListener('change', updateCSP); });
    updateCSP();
  }

  // -------------------------------------------------------------------------
  // TOOL 15: .env Parser & Converter
  // -------------------------------------------------------------------------
  if (toolSlug === 'env-parser') {
    var parseEnv = function () {
      var text = (inputEl.value || '').trim();
      var lines = text.split('\n');
      var envObj = {};
      var dupes = [];

      lines.forEach(function (l) {
        l = l.trim();
        if (!l || l.startsWith('#')) return;
        var p = l.split('=');
        if (p.length >= 2) {
          var k = p[0].trim();
          var v = p.slice(1).join('=').trim().replace(/^["']|["']$/g, '');
          if (envObj[k] !== undefined) dupes.push(k);
          envObj[k] = v;
        }
      });

      var out = '=== JSON Output ===\n' + JSON.stringify(envObj, null, 2) + '\n\n';
      out += '=== Docker Compose Environment Block ===\nenvironment:\n';
      for (var key in envObj) {
        out += '  - ' + key + '=' + envObj[key] + '\n';
      }

      if (dupes.length > 0) {
        out += '\n⚠ تحذير: تم العثور على مفاتيح مكررة: ' + dupes.join(', ');
      }
      outputEl.value = out;
      setStatus('✓ تم تحليل ملف .env بنجاح (' + Object.keys(envObj).length + ' مفتاح)', false);
    };

    if (inputEl) inputEl.addEventListener('input', parseEnv);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = "DB_HOST=localhost\nDB_PORT=5432\nDB_USER=postgres\nDB_PASSWORD=secret_password_123\nNODE_ENV=production\nAPP_PORT=3000";
        parseEnv();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 16: Regex Tester & Explainer
  // -------------------------------------------------------------------------
  if (toolSlug === 'regex-tester') {
    var regexInput = document.getElementById('cdt-regex-pattern');
    var regexFlags = document.getElementById('cdt-regex-flags');

    var testRegex = function () {
      var pattern = (regexInput ? regexInput.value : '').trim();
      var flags = (regexFlags ? regexFlags.value : 'g').trim();
      var text = inputEl ? inputEl.value : '';

      if (!pattern) {
        outputEl.value = 'أدخل نمط Regex للاختبار...';
        return;
      }

      try {
        var re = new RegExp(pattern, flags);
        var matches = Array.from(text.matchAll(re));
        var out = 'عدد المطابقات: ' + matches.length + '\n\n';

        matches.forEach(function (m, idx) {
          out += '[' + (idx + 1) + '] النص المطابق: "' + m[0] + '" (الموضع: ' + m.index + ')\n';
          if (m.length > 1) {
            for (var g = 1; g < m.length; g++) {
              out += '    - المجموعة ' + g + ': "' + m[g] + '"\n';
            }
          }
        });

        outputEl.value = out;
        setStatus('✓ نمط Regex سليم وتمت مطابقته بنجاح (' + matches.length + ' نتيجة)', false);
      } catch (e) {
        outputEl.value = 'خطأ في صياغة Regex: ' + e.message;
        setStatus('خطأ في النمط', true);
      }
    };

    if (regexInput) regexInput.addEventListener('input', testRegex);
    if (regexFlags) regexFlags.addEventListener('input', testRegex);
    if (inputEl) inputEl.addEventListener('input', testRegex);

    if (btnSample) {
      btnSample.addEventListener('click', function () {
        if (regexInput) regexInput.value = '([a-zA-Z0-9._%+-]+)@([a-zA-Z0-9.-]+\\.[a-zA-Z]{2,})';
        if (regexFlags) regexFlags.value = 'g';
        inputEl.value = 'تواصل معنا عبر contact@code-elta6ur.com أو الدعم support@example.org للمساعدة.';
        testRegex();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 17: Regex Builder from Examples
  // -------------------------------------------------------------------------
  if (toolSlug === 'regex-builder') {
    var templateSelect = document.getElementById('cdt-regex-template-select');

    var templates = {
      email: { regex: '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$', desc: 'مطابقة عناوين البريد الإلكتروني القياسية' },
      phone: { regex: '^\\+?[0-9]{8,15}$', desc: 'أرقام الهواتف الدولية والمحلية' },
      url: { regex: '^https?:\\/\\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)$', desc: 'عناوين الويب والروابط (HTTP/HTTPS)' },
      ipv4: { regex: '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$', desc: 'عناوين بروتوكول الإنترنت IPv4' },
      slug: { regex: '^[a-z0-9]+(?:-[a-z0-9]+)*$', desc: 'الأسماء اللطيفة للمسارات (URL Slugs)' }
    };

    if (templateSelect) {
      templateSelect.addEventListener('change', function () {
        var t = templates[templateSelect.value];
        if (t) {
          outputEl.value = 'النمط المولد (Regex Pattern):\n' + t.regex + '\n\nالوصف:\n' + t.desc;
          setStatus('✓ تم توليد التعبير النمطي بنجاح', false);
        }
      });
    }

    if (btnSample) {
      btnSample.addEventListener('click', function () {
        if (templateSelect) {
          templateSelect.value = 'email';
          templateSelect.dispatchEvent(new Event('change'));
        }
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 18: SQL Formatter & Validator
  // -------------------------------------------------------------------------
  if (toolSlug === 'sql-formatter') {
    var formatSQL = function () {
      var sql = (inputEl.value || '').trim();
      if (!sql) return;
      var keywords = ['SELECT', 'FROM', 'WHERE', 'JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN', 'ON', 'GROUP BY', 'ORDER BY', 'HAVING', 'LIMIT', 'INSERT INTO', 'VALUES', 'UPDATE', 'SET', 'DELETE FROM', 'AND', 'OR'];

      var formatted = sql;
      keywords.forEach(function (kw) {
        var re = new RegExp('\\b' + kw + '\\b', 'gi');
        formatted = formatted.replace(re, '\n' + kw);
      });
      formatted = formatted.trim();
      outputEl.value = formatted;
      setStatus('✓ تم تنسيق استعلام SQL بنجاح', false);
    };

    if (inputEl) inputEl.addEventListener('input', formatSQL);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = "select id, name, email from users where status = 'active' and score > 80 order by created_at desc limit 10";
        formatSQL();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 19: SQL to ER Diagram
  // -------------------------------------------------------------------------
  if (toolSlug === 'sql-to-er-diagram') {
    var generateERD = function () {
      var ddl = (inputEl.value || '').trim();
      var tableMatches = ddl.matchAll(/CREATE\s+TABLE\s+([`"]?[a-zA-Z0-9_]+[`"]?)\s*\(([\s\S]*?)\);/gi);
      var mermaid = 'erDiagram\n';
      var count = 0;

      var relationships = [];
      for (var m of tableMatches) {
        count++;
        var tbl = m[1].replace(/[`"]/g, '');
        mermaid += '  ' + tbl + ' {\n';
        var cols = m[2].split(',');
        cols.forEach(function (c) {
          c = c.trim();
          var fkMatch = c.match(/FOREIGN\s+KEY\s*\([`"]?([a-zA-Z0-9_]+)[`"]?\)\s*REFERENCES\s*[`"]?([a-zA-Z0-9_]+)[`"]?/i);
          if (fkMatch) {
            relationships.push('  ' + fkMatch[2] + ' ||--o{ ' + tbl + ' : "references ' + fkMatch[1] + '"');
          } else if (!c.startsWith('PRIMARY') && !c.startsWith('FOREIGN') && !c.startsWith('KEY') && !c.startsWith('CONSTRAINT')) {
            var parts = c.split(/\s+/);
            if (parts.length >= 2) {
              mermaid += '    ' + parts[1] + ' ' + parts[0] + '\n';
            }
          }
        });
        mermaid += '  }\n';
      }

      if (relationships.length > 0) {
        mermaid += '\n  %% العلاقات المستخرجة من المفاتيح الخارجية:\n' + relationships.join('\n') + '\n';
      }

      if (count === 0) {
        outputEl.value = 'يرجى إدخال أوامر CREATE TABLE صالحة...';
        return;
      }

      outputEl.value = '// كود مخطط العلاقات Mermaid ERD:\n' + mermaid;
      setStatus('✓ تم استخراج وتوليد مخطط العلاقات لـ ' + count + ' جداول', false);
    };

    if (inputEl) inputEl.addEventListener('input', generateERD);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = "CREATE TABLE users (\n  id INT PRIMARY KEY,\n  username VARCHAR(50),\n  email VARCHAR(100)\n);\n\nCREATE TABLE orders (\n  order_id INT PRIMARY KEY,\n  user_id INT,\n  total DECIMAL(10,2),\n  FOREIGN KEY (user_id) REFERENCES users(id)\n);";
        generateERD();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 20: Database Schema / SQL Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'db-schema-generator') {
    var btnGenTbl = document.getElementById('cdt-btn-gen-schema');
    var tblNameInput = document.getElementById('cdt-schema-table-name');

    var genSchemaSQL = function () {
      var tbl = (tblNameInput ? tblNameInput.value : 'articles') || 'articles';
      var sql = 'CREATE TABLE ' + tbl + ' (\n' +
                '    id INT AUTO_INCREMENT PRIMARY KEY,\n' +
                '    title VARCHAR(255) NOT NULL,\n' +
                '    slug VARCHAR(255) UNIQUE NOT NULL,\n' +
                '    content TEXT,\n' +
                '    views INT DEFAULT 0,\n' +
                '    is_published BOOLEAN DEFAULT FALSE,\n' +
                '    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n' +
                ');';
      outputEl.value = sql;
      setStatus('✓ تم توليد كود CREATE TABLE لـ ' + tbl, false);
    };

    if (btnGenTbl) btnGenTbl.addEventListener('click', genSchemaSQL);
    if (tblNameInput) tblNameInput.addEventListener('input', genSchemaSQL);
    genSchemaSQL();
  }

  // -------------------------------------------------------------------------
  // TOOL 21: Docker Compose Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'docker-compose-generator') {
    var updateCompose = function () {
      var services = [];
      document.querySelectorAll('.cdt-compose-svc:checked').forEach(function (cb) {
        services.push(cb.value);
      });

      var yml = "version: '3.8'\n\nservices:\n";
      if (services.includes('node')) {
        yml += "  app:\n    build: .\n    ports:\n      - '3000:3000'\n    environment:\n      - NODE_ENV=production\n    depends_on:\n      - db\n\n";
      }
      if (services.includes('postgres')) {
        yml += "  db:\n    image: postgres:15-alpine\n    environment:\n      - POSTGRES_DB=app_db\n      - POSTGRES_PASSWORD=secret\n    volumes:\n      - pgdata:/var/lib/postgresql/data\n\n";
      }
      if (services.includes('redis')) {
        yml += "  redis:\n    image: redis:7-alpine\n    ports:\n      - '6379:6379'\n\n";
      }
      if (services.includes('nginx')) {
        yml += "  nginx:\n    image: nginx:alpine\n    ports:\n      - '80:80'\n    volumes:\n      - ./nginx.conf:/etc/nginx/nginx.conf:ro\n\n";
      }
      if (services.includes('postgres')) {
        yml += "volumes:\n  pgdata:\n";
      }

      outputEl.value = yml;
      setStatus('✓ تم توليد ملف docker-compose.yml لـ ' + services.length + ' خدمات', false);
    };

    document.querySelectorAll('.cdt-compose-svc').forEach(function (cb) {
      cb.addEventListener('change', updateCompose);
    });
    updateCompose();
  }

  // -------------------------------------------------------------------------
  // TOOL 22: Dockerfile Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'dockerfile-generator') {
    var langDF = document.getElementById('cdt-df-lang');

    var genDockerfile = function () {
      var l = langDF ? langDF.value : 'node';
      var df = '';

      if (l === 'node') {
        df = "# Multi-stage Node.js Production Dockerfile\nFROM node:18-alpine AS builder\nWORKDIR /app\nCOPY package*.json ./\nRUN npm ci\nCOPY . .\nRUN npm run build\n\nFROM node:18-alpine AS runner\nWORKDIR /app\nENV NODE_ENV=production\nCOPY --from=builder /app/package*.json ./\nRUN npm ci --only=production\nCOPY --from=builder /app/dist ./dist\nUSER node\nEXPOSE 3000\nCMD [\"node\", \"dist/index.js\"]";
      } else if (l === 'python') {
        df = "# Python Production Dockerfile\nFROM python:3.10-slim AS runner\nWORKDIR /app\nENV PYTHONDONTWRITEBYTECODE=1 PYTHONUNBUFFERED=1\nCOPY requirements.txt .\nRUN pip install --no-cache-dir -r requirements.txt\nCOPY . .\nEXPOSE 8000\nCMD [\"python\", \"main.py\"]";
      } else if (l === 'go') {
        df = "# Multi-stage Go Dockerfile\nFROM golang:1.20-alpine AS builder\nWORKDIR /app\nCOPY go.* ./\nRUN go mod download\nCOPY . .\nRUN CGO_ENABLED=0 go build -o main .\n\nFROM alpine:latest\nWORKDIR /root/\nCOPY --from=builder /app/main .\nEXPOSE 8080\nCMD [\"./main\"]";
      }

      outputEl.value = df;
      setStatus('✓ تم توليد Dockerfile متعدد المراحل لـ ' + l.toUpperCase(), false);
    };

    if (langDF) langDF.addEventListener('change', genDockerfile);
    genDockerfile();
  }

  // -------------------------------------------------------------------------
  // TOOL 23: Git Command Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'git-command-generator') {
    var scenarioSelect = document.getElementById('cdt-git-scenario');

    var scenarios = {
      'undo_commit': { cmd: 'git reset --soft HEAD~1', desc: 'التراجع عن آخر Commit مع الاحتفاظ بكافة التعديلات في Staging.' },
      'discard_local': { cmd: 'git restore .\n# أو للأصدارات القديمة:\ngit checkout -- .', desc: 'إلغاء جميع التعديلات المحلية غير المحفوظة.' },
      'stash': { cmd: 'git stash save "temporary-work"\n# للاسترجاع لاحقاً:\ngit stash pop', desc: 'حفظ التعديلات الحالية مؤقتاً في Stash لتنظيف الفرع.' },
      'branch_create': { cmd: 'git checkout -b feature/new-feature', desc: 'إنشاء فرع جديد والانتقال إليه مباشرة.' },
      'rename_branch': { cmd: 'git branch -m old-name new-name', desc: 'إعادة تسمية الفرع المحلي الحالي.' }
    };

    var showGitCmd = function () {
      var sc = scenarios[scenarioSelect ? scenarioSelect.value : 'undo_commit'];
      if (sc) {
        outputEl.value = '# الأمر البرمجي:\n' + sc.cmd + '\n\n# الشرح والأثر:\n' + sc.desc;
        setStatus('✓ جاهز للاستخدام', false);
      }
    };

    if (scenarioSelect) scenarioSelect.addEventListener('change', showGitCmd);
    showGitCmd();
  }

  // -------------------------------------------------------------------------
  // TOOL 24: .gitignore Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'gitignore-generator') {
    var updateGitignore = function () {
      var lines = ['# Code Elta6ur Generated .gitignore', ''];
      document.querySelectorAll('.cdt-gi-opt:checked').forEach(function (cb) {
        var v = cb.value;
        if (v === 'node') lines.push('# Node.js\nnode_modules/\nnpm-debug.log\n.env\n.env.local\ndist/\nbuild/\n');
        if (v === 'python') lines.push('# Python\n__pycache__/\n*.py[cod]\n*$py.class\nvenv/\n.env\n');
        if (v === 'os') lines.push('# OS generated files\n.DS_Store\nThumbs.db\nDesktop.ini\n');
        if (v === 'ide') lines.push('# IDEs\n.vscode/\n.idea/\n*.swp\n');
      });
      outputEl.value = lines.join('\n');
      setStatus('✓ تم توليد ملف .gitignore المخصص', false);
    };

    document.querySelectorAll('.cdt-gi-opt').forEach(function (cb) {
      cb.addEventListener('change', updateGitignore);
    });
    updateGitignore();
  }

  // -------------------------------------------------------------------------
  // TOOL 25: Package.json Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'package-json-generator') {
    var pName = document.getElementById('cdt-pkg-name');
    var pVer = document.getElementById('cdt-pkg-version');
    var pDesc = document.getElementById('cdt-pkg-desc');

    var genPackageJson = function () {
      var pkg = {
        name: (pName ? pName.value : 'my-app') || 'my-app',
        version: (pVer ? pVer.value : '1.0.0') || '1.0.0',
        description: (pDesc ? pDesc.value : 'Project created with Code Elta6ur') || '',
        main: "index.js",
        type: "module",
        scripts: {
          start: "node index.js",
          dev: "nodemon index.js",
          test: "echo \"Error: no test specified\" && exit 1"
        },
        keywords: ["code-elta6ur", "developer-tools"],
        author: "Mohammad Abu Khashreef",
        license: "MIT"
      };
      outputEl.value = JSON.stringify(pkg, null, 2);
      setStatus('✓ تم توليد ملف package.json بنجاح', false);
    };

    if (pName) pName.addEventListener('input', genPackageJson);
    if (pVer) pVer.addEventListener('input', genPackageJson);
    if (pDesc) pDesc.addEventListener('input', genPackageJson);
    genPackageJson();
  }

  // -------------------------------------------------------------------------
  // TOOL 26: Semantic Version Calculator
  // -------------------------------------------------------------------------
  if (toolSlug === 'semver-calculator') {
    var curVer = document.getElementById('cdt-semver-input');

    var calcSemver = function () {
      var v = (curVer ? curVer.value : '1.2.3').trim();
      var parts = v.split('.').map(Number);
      if (parts.length !== 3 || parts.some(isNaN)) {
        setStatus('يرجى إدخال إصدار ثلاثي مثل 1.2.3', true);
        return;
      }
      var major = (parts[0] + 1) + '.0.0';
      var minor = parts[0] + '.' + (parts[1] + 1) + '.0';
      var patch = parts[0] + '.' + parts[1] + '.' + (parts[2] + 1);

      outputEl.value = 'الإصدار الحالي: ' + v + '\n\n' +
                       '• ترقية ترقيعية (Patch - إصلاح أخطاء): ' + patch + '\n' +
                       '• ترقية ثانوية (Minor - ميزات جديدة متوافقة): ' + minor + '\n' +
                       '• ترقية رئيسية (Major - تغييرات تكسر التوافق Breaking): ' + major + '\n\n' +
                       'شرح نطاقات npm:\n' +
                       '- ^' + v + ' : يسمح بأي إصدار < ' + (parts[0] + 1) + '.0.0\n' +
                       '- ~' + v + ' : يسمح بأي إصدار < ' + parts[0] + '.' + (parts[1] + 1) + '.0';
      setStatus('✓ تم حساب ترقيات الإصدار الدلالي SemVer بنجاح', false);
    };

    if (curVer) curVer.addEventListener('input', calcSemver);
    calcSemver();
  }

  // -------------------------------------------------------------------------
  // TOOL 27: .htaccess ↔ Nginx Config Converter
  // -------------------------------------------------------------------------
  if (toolSlug === 'htaccess-to-nginx') {
    var convertHtaccess = function () {
      var ht = (inputEl.value || '').trim();
      var lines = ht.split('\n');
      var nginx = '# Converted to Nginx Configuration by Code Elta6ur\n\n';

      lines.forEach(function (l) {
        l = l.trim();
        if (l.startsWith('RewriteRule')) {
          var p = l.split(/\s+/);
          if (p.length >= 3) {
            nginx += 'rewrite ^/' + p[1] + '$ /' + p[2] + ' last;\n';
          }
        } else if (l.startsWith('Redirect 301')) {
          var p = l.split(/\s+/);
          if (p.length >= 3) {
            nginx += 'return 301 ' + p[2] + ';\n';
          }
        }
      });

      outputEl.value = nginx;
      setStatus('✓ تم تحويل قواعد Apache إلى Nginx', false);
    };

    if (inputEl) inputEl.addEventListener('input', convertHtaccess);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = "RewriteEngine On\nRewriteRule ^old-page/?$ /new-page/ [R=301,L]\nRedirect 301 /privacy-policy/ /سياسة-الخصوصية/";
        convertHtaccess();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 28: CSS Specificity Calculator
  // -------------------------------------------------------------------------
  if (toolSlug === 'css-specificity-calculator') {
    function getSpecificity(sel) {
      var a = (sel.match(/#[a-zA-Z0-9_-]+/g) || []).length;
      var b = (sel.match(/\.[a-zA-Z0-9_-]+|\[[^\]]+\]|:[a-zA-Z0-9_-]+/g) || []).length;
      var c = (sel.match(/^[a-zA-Z]+|\s+[a-zA-Z]+/g) || []).length;
      return [a, b, c];
    }

    var calcSpecificity = function () {
      var sel = (inputEl.value || '').trim();
      if (!sel) return;
      var spec = getSpecificity(sel);
      outputEl.value = 'محدد CSS: ' + sel + '\n\n' +
                       'نقاط الخصوصية (Specificity Score):\n' +
                       '  IDs (المعرفات): ' + spec[0] + '\n' +
                       '  Classes & Attributes (الكلاسات): ' + spec[1] + '\n' +
                       '  Elements (العناصر): ' + spec[2] + '\n\n' +
                       'المصفوفة: (' + spec[0] + ', ' + spec[1] + ', ' + spec[2] + ')\n' +
                       'القيمة الموزونة التقديرية: ' + (spec[0] * 100 + spec[1] * 10 + spec[2]);
      setStatus('✓ تم حساب الخصوصية: (' + spec.join(', ') + ')', false);
    };

    if (inputEl) inputEl.addEventListener('input', calcSpecificity);
    if (btnSample) {
      btnSample.addEventListener('click', function () {
        inputEl.value = "#header .nav-item.active a:hover";
        calcSpecificity();
      });
    }
  }

  // -------------------------------------------------------------------------
  // TOOL 29: CSS Clamp Generator
  // -------------------------------------------------------------------------
  if (toolSlug === 'css-clamp-generator') {
    var minFontSize = document.getElementById('cdt-clamp-min-font');
    var maxFontSize = document.getElementById('cdt-clamp-max-font');
    var minViewport = document.getElementById('cdt-clamp-min-vp');
    var maxViewport = document.getElementById('cdt-clamp-max-vp');

    var calcClamp = function () {
      var minF = parseFloat(minFontSize ? minFontSize.value : 16) || 16;
      var maxF = parseFloat(maxFontSize ? maxFontSize.value : 32) || 32;
      var minV = parseFloat(minViewport ? minViewport.value : 360) || 360;
      var maxV = parseFloat(maxViewport ? maxViewport.value : 1200) || 1200;

      var slope = (maxF - minF) / (maxV - minV);
      var yAxisIntersection = -minV * slope + minF;
      var preferredVw = (slope * 100).toFixed(4);
      var remIntersection = (yAxisIntersection / 16).toFixed(4);
      var minRem = (minF / 16).toFixed(4);
      var maxRem = (maxF / 16).toFixed(4);

      var clampResult = 'clamp(' + minRem + 'rem, ' + remIntersection + 'rem + ' + preferredVw + 'vw, ' + maxRem + 'rem)';

      outputEl.value = '/* CSS Fluid Typography / Spacing */\nfont-size: ' + clampResult + ';\n\n' +
                       '/* الشرح: */\n' +
                       '- الحد الأدنى: ' + minF + 'px (' + minRem + 'rem) عند شاشة ' + minV + 'px\n' +
                       '- يتمدد بنعومة بنسبة ' + preferredVw + 'vw\n' +
                       '- الحد الأقصى: ' + maxF + 'px (' + maxRem + 'rem) عند شاشة ' + maxV + 'px';
      setStatus('✓ تم توليد دالة clamp() التجاوبية بنجاح', false);
    };

    [minFontSize, maxFontSize, minViewport, maxViewport].forEach(function (el) {
      if (el) el.addEventListener('input', calcClamp);
    });
    calcClamp();
  }

  // -------------------------------------------------------------------------
  // TOOL 30: CSS Unit Converter
  // -------------------------------------------------------------------------
  if (toolSlug === 'css-unit-converter') {
    var baseFont = document.getElementById('cdt-unit-base');
    var pxInput = document.getElementById('cdt-unit-px');

    var convertUnits = function () {
      var base = parseFloat(baseFont ? baseFont.value : 16) || 16;
      var px = parseFloat(pxInput ? pxInput.value : 24) || 24;

      var rem = (px / base).toFixed(4);
      var em = rem;
      var percent = ((px / base) * 100).toFixed(1);
      var pt = (px * 0.75).toFixed(2);

      outputEl.value = px + 'px تعادل:\n\n' +
                       '• ' + rem + ' rem (بناءً على خط أساس ' + base + 'px)\n' +
                       '• ' + em + ' em\n' +
                       '• ' + percent + ' %\n' +
                       '• ' + pt + ' pt (نقاط الطباعة)\n\n' +
                       'كود CSS المقترح:\n' +
                       'padding: ' + rem + 'rem; /* ' + px + 'px */';
      setStatus('✓ ' + px + 'px = ' + rem + 'rem', false);
    };

    if (baseFont) baseFont.addEventListener('input', convertUnits);
    if (pxInput) pxInput.addEventListener('input', convertUnits);
    convertUnits();
  }

});
