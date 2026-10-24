/**
 * كود التطور - التفاعلات والتنقل وقوائم الهواتف
 * Code Elta6ur Navigation & UI Interactions
 */
document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Sticky Header Effect
  var header = document.querySelector('.cdt-site-header');
  if (header) {
    var checkScroll = function () {
      if (window.scrollY > 30) {
        header.classList.add('is-scrolled');
      } else {
        header.classList.remove('is-scrolled');
      }
    };
    window.addEventListener('scroll', checkScroll, { passive: true });
    checkScroll();
  }

  // 2. Mobile Navigation Drawer
  var mobileToggle = document.querySelector('.cdt-mobile-toggle');
  var mobileDrawer = document.querySelector('.cdt-mobile-drawer');
  var mobileOverlay = document.querySelector('.cdt-mobile-overlay');
  var mobileClose = document.querySelector('.cdt-mobile-close');

  var openMobileMenu = function () {
    if (mobileDrawer && mobileOverlay) {
      mobileDrawer.classList.add('is-active');
      mobileOverlay.classList.add('is-active');
      document.body.style.overflow = 'hidden';
    }
  };

  var closeMobileMenu = function () {
    if (mobileDrawer && mobileOverlay) {
      mobileDrawer.classList.remove('is-active');
      mobileOverlay.classList.remove('is-active');
      document.body.style.overflow = '';
    }
  };

  if (mobileToggle) {
    mobileToggle.addEventListener('click', openMobileMenu);
  }
  if (mobileClose) {
    mobileClose.addEventListener('click', closeMobileMenu);
  }
  if (mobileOverlay) {
    mobileOverlay.addEventListener('click', closeMobileMenu);
  }

  // 3. Search Modal
  var searchToggle = document.querySelector('.cdt-search-toggle');
  var searchModal = document.querySelector('.cdt-search-modal');
  var searchClose = document.querySelector('.cdt-search-modal-close');
  var searchInput = document.querySelector('.cdt-search-modal input[type="search"]');

  var openSearchModal = function () {
    if (searchModal) {
      searchModal.classList.add('is-active');
      document.body.style.overflow = 'hidden';
      if (searchInput) {
        setTimeout(function () { searchInput.focus(); }, 100);
      }
    }
  };

  var closeSearchModal = function () {
    if (searchModal) {
      searchModal.classList.remove('is-active');
      document.body.style.overflow = '';
    }
  };

  if (searchToggle) {
    searchToggle.addEventListener('click', openSearchModal);
  }
  if (searchClose) {
    searchClose.addEventListener('click', closeSearchModal);
  }
  if (searchModal) {
    searchModal.addEventListener('click', function (e) {
      if (e.target === searchModal) {
        closeSearchModal();
      }
    });
  }

  // 4. Back to Top Button
  var backToTop = document.querySelector('.cdt-back-to-top');
  if (backToTop) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 400) {
        backToTop.classList.add('is-visible');
      } else {
        backToTop.classList.remove('is-visible');
      }
    }, { passive: true });

    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // 5. Global Keyboard Shortcuts (Escape to close modals)
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeSearchModal();
      closeMobileMenu();
    }
  });
});
