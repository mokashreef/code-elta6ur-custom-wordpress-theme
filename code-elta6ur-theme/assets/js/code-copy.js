/**
 * كود التطور - نسخ الأكواد البرمجية بنقرة واحدة
 * Code Elta6ur One-Click Code Snippet Copy
 */
document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  var preBlocks = document.querySelectorAll('.cdt-entry-content pre, pre.wp-block-code');
  var copyText = (window.cdtL10n && window.cdtL10n.copy) ? window.cdtL10n.copy : 'نسخ الكود';
  var copiedText = (window.cdtL10n && window.cdtL10n.copied) ? window.cdtL10n.copied : 'تم النسخ!';

  preBlocks.forEach(function (pre) {
    if (pre.querySelector('.cdt-code-copy-btn')) {
      return;
    }

    var copyBtn = document.createElement('button');
    copyBtn.className = 'cdt-code-copy-btn';
    copyBtn.type = 'button';
    copyBtn.setAttribute('aria-label', copyText);
    copyBtn.innerHTML = '<span>' + copyText + '</span>';

    pre.style.position = 'relative';
    pre.appendChild(copyBtn);

    copyBtn.addEventListener('click', function () {
      var code = pre.querySelector('code') || pre;
      var textToCopy = code.innerText || code.textContent;

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(textToCopy).then(function () {
          copyBtn.innerHTML = '<span>' + copiedText + '</span>';
          copyBtn.classList.add('is-copied');

          setTimeout(function () {
            copyBtn.innerHTML = '<span>' + copyText + '</span>';
            copyBtn.classList.remove('is-copied');
          }, 2000);
        }).catch(function () {
          fallbackCopy(textToCopy, copyBtn);
        });
      } else {
        fallbackCopy(textToCopy, copyBtn);
      }
    });
  });

  function fallbackCopy(text, button) {
    var textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.select();
    try {
      document.execCommand('copy');
      button.innerHTML = '<span>' + copiedText + '</span>';
      setTimeout(function () {
        button.innerHTML = '<span>' + copyText + '</span>';
      }, 2000);
    } catch (err) {
      console.error('Failed to copy text', err);
    }
    document.body.removeChild(textArea);
  }
});
