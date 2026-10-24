/**
 * نظام التتبع اللحظي للنشاط وقراءة المقالات والنبضات الذكية
 * Code Elta6ur Real-time Activity & Reading Progress Tracker
 */
(function () {
  'use strict';

  if (typeof window.cdtGamificationData === 'undefined' || !window.cdtGamificationData.isLoggedIn) {
    return;
  }

  const config = window.cdtGamificationData;
  let accumulatedSeconds = 0;
  let maxScrollPercent = 0;
  let isPageVisible = document.visibilityState === 'visible';
  let isHeartbeatRunning = false;
  let readingRewarded = false;

  // 1. Toast Notification Renderer
  function showToast(message, points) {
    let container = document.querySelector('.cdt-toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'cdt-toast-container';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = 'cdt-toast';
    toast.innerHTML = `
      <div style="font-size: 1.4rem;">🎉</div>
      <div>
        <div style="font-size: 0.95rem; font-weight: 700; color: #FFFFFF;">${message}</div>
        ${points ? `<div style="font-size: 0.8rem; color: #38BDF8; font-weight: 600;">+${points} نقطة أضيفت لرصيدك</div>` : ''}
      </div>
    `;

    container.appendChild(toast);

    setTimeout(function () {
      toast.classList.add('is-hiding');
      setTimeout(function () {
        toast.remove();
      }, 300);
    }, 4500);
  }

  // 2. Track Scroll Depth & Reading Bar
  function updateScrollProgress() {
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    if (docHeight > 0) {
      const currentScroll = Math.min(100, Math.max(0, Math.round((window.scrollY / docHeight) * 100)));
      if (currentScroll > maxScrollPercent) {
        maxScrollPercent = currentScroll;
      }

      const progressBar = document.querySelector('.cdt-reading-progress-bar');
      if (progressBar) {
        progressBar.style.width = currentScroll + '%';
      }
    }
  }

  window.addEventListener('scroll', updateScrollProgress, { passive: true });

  // 3. Page Visibility State Handling
  document.addEventListener('visibilitychange', function () {
    isPageVisible = document.visibilityState === 'visible';
  });

  // 4. Activity Heartbeat (Every 15 seconds)
  function sendHeartbeat() {
    if (!isPageVisible || isHeartbeatRunning) {
      return;
    }

    isHeartbeatRunning = true;
    const elapsedNow = 15;

    const formData = new URLSearchParams();
    formData.append('action', 'cdt_gamification_heartbeat');
    formData.append('nonce', config.nonce);
    formData.append('post_id', config.postId || 0);
    formData.append('scroll_percent', maxScrollPercent);
    formData.append('elapsed_seconds', elapsedNow);

    fetch(config.ajaxUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
      },
      body: formData.toString(),
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        isHeartbeatRunning = false;
        if (data && data.success && data.data) {
          const resp = data.data;

          // Process new rewards
          if (resp.rewards && resp.rewards.length > 0) {
            resp.rewards.forEach(function (rw) {
              showToast(rw.message, rw.points);
            });
          }

          // Update header points if element exists
          if (resp.balance !== undefined) {
            const balanceBadges = document.querySelectorAll('.cdt-user-points-badge');
            balanceBadges.forEach(function (b) {
              b.textContent = resp.balance;
            });
          }

          if (resp.reading && resp.reading.completed && !readingRewarded) {
            readingRewarded = true;
            const completeBadge = document.querySelector('.cdt-article-completed-badge');
            if (completeBadge) {
              completeBadge.style.display = 'inline-flex';
            }
          }
        }
      })
      .catch(function () {
        isHeartbeatRunning = false;
      });
  }

  // Start periodic timer
  setInterval(sendHeartbeat, 15000);

  // 5. Lesson Completion Button Handler
  document.addEventListener('DOMContentLoaded', function () {
    const completeBtns = document.querySelectorAll('.cdt-complete-lesson-btn');
    completeBtns.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const postId = this.getAttribute('data-post-id');
        if (!postId || this.disabled) return;

        this.disabled = true;
        this.textContent = 'جاري التسجيل...';

        const formData = new URLSearchParams();
        formData.append('action', 'cdt_complete_lesson');
        formData.append('nonce', config.nonce);
        formData.append('post_id', postId);

        const currentBtn = this;

        fetch(config.ajaxUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          },
          body: formData.toString(),
        })
          .then(function (res) {
            return res.json();
          })
          .then(function (data) {
            if (data && data.success) {
              currentBtn.textContent = 'تم إكمال الدرس ✓';
              currentBtn.classList.add('is-completed');
              showToast(data.data.message || 'أحسنت! تم إكمال الدرس بنجاح', 10);

              if (data.data.balance !== undefined) {
                const balanceBadges = document.querySelectorAll('.cdt-user-points-badge');
                balanceBadges.forEach(function (b) {
                  b.textContent = data.data.balance;
                });
              }
            } else {
              currentBtn.disabled = false;
              currentBtn.textContent = 'إكمال الدرس';
              alert(data.data.message || 'حدث خطأ أثناء إكمال الدرس');
            }
          })
          .catch(function () {
            currentBtn.disabled = false;
            currentBtn.textContent = 'إكمال الدرس';
          });
      });
    });
  });
})();
