/**
 * تفاعلية صفحات الحسابات ولوحة التحكم الشخصية
 * Code Elta6ur Accounts & Dashboard UI Engine
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    // 1. Password Visibility Toggle
    const toggleBtns = document.querySelectorAll('.cdt-password-toggle');
    toggleBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const input = this.parentElement.querySelector('input');
        if (!input) return;
        const isPassword = input.getAttribute('type') === 'password';
        input.setAttribute('type', isPassword ? 'text' : 'password');
        this.setAttribute('aria-label', isPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
      });
    });

    // 2. Dashboard Tab Navigation
    const tabButtons = document.querySelectorAll('.cdt-tab-link[data-tab]');
    const tabPanels = document.querySelectorAll('.cdt-tab-panel');

    if (tabButtons.length > 0) {
      function activateTab(tabId) {
        tabButtons.forEach(function (btn) {
          const isActive = btn.getAttribute('data-tab') === tabId;
          btn.classList.toggle('is-active', isActive);
          btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        tabPanels.forEach(function (panel) {
          const isMatch = panel.getAttribute('id') === 'cdt-tab-' + tabId;
          panel.classList.toggle('is-active', isMatch);
        });

        // Update URL param without refreshing
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabId);
        window.history.replaceState({}, '', url.toString());
      }

      tabButtons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          const targetTab = this.getAttribute('data-tab');
          if (targetTab) {
            activateTab(targetTab);
          }
        });
      });

      // Activate tab from URL query if present
      const currentUrl = new URL(window.location.href);
      const urlTab = currentUrl.searchParams.get('tab');
      if (urlTab && document.getElementById('cdt-tab-' + urlTab)) {
        activateTab(urlTab);
      }
    }
  });
})();
