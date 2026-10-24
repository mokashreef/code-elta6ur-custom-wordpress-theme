/**
 * كود التطور - مشغل الفيديو المنبثق
 * Code Elta6ur YouTube Video Modal Player
 */
document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  var videoModal = document.querySelector('.cdt-video-modal');
  var videoIframe = document.querySelector('.cdt-video-modal iframe');
  var videoClose = document.querySelector('.cdt-video-modal-close');
  var triggers = document.querySelectorAll('.cdt-video-trigger');

  if (!videoModal || !videoIframe) {
    return;
  }

  var openVideo = function (videoId) {
    if (!videoId) return;
    videoIframe.src = 'https://www.youtube.com/embed/' + encodeURIComponent(videoId) + '?autoplay=1&rel=0';
    videoModal.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  };

  var closeVideo = function () {
    videoModal.classList.remove('is-active');
    videoIframe.src = '';
    document.body.style.overflow = '';
  };

  triggers.forEach(function (trigger) {
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      var videoId = trigger.getAttribute('data-video-id');
      if (videoId) {
        openVideo(videoId);
      }
    });
  });

  if (videoClose) {
    videoClose.addEventListener('click', closeVideo);
  }

  videoModal.addEventListener('click', function (e) {
    if (e.target === videoModal) {
      closeVideo();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && videoModal.classList.contains('is-active')) {
      closeVideo();
    }
  });
});
