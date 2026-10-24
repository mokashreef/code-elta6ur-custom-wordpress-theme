/**
 * كود التطور - إدارة إرسال نموذج التواصل عبر AJAX
 * Code Elta6ur Contact Form AJAX Handler
 */
document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  var form = document.getElementById('cdt-contact-form');
  if (!form) {
    return;
  }

  var submitBtn = document.getElementById('cdt-contact-submit-btn');
  var btnText = submitBtn ? submitBtn.querySelector('.cdt-btn-text') : null;
  var alertBox = document.getElementById('cdt-contact-alert');

  var showAlert = function (msg, isSuccess) {
    if (!alertBox) return;
    alertBox.textContent = msg;
    alertBox.style.display = 'block';
    if (isSuccess) {
      alertBox.style.background = '#ECFDF5';
      alertBox.style.color = '#065F46';
      alertBox.style.border = '1px solid #A7F3D0';
    } else {
      alertBox.style.background = '#FEF2F2';
      alertBox.style.color = '#991B1B';
      alertBox.style.border = '1px solid #FECACA';
    }
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  };

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    var nameInput = document.getElementById('cdt-contact-name');
    var emailInput = document.getElementById('cdt-contact-email');
    var msgInput = document.getElementById('cdt-contact-message');

    if (!nameInput || !nameInput.value.trim() || !emailInput || !emailInput.value.trim() || !msgInput || !msgInput.value.trim()) {
      var reqMsg = (window.cdtContactData && window.cdtContactData.reqFields) || 'يرجى ملء كافة الحقول المطلوبة (الاسم، البريد، والرسالة).';
      showAlert(reqMsg, false);
      return;
    }

    // Disable button & show sending state
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.7';
    }
    var origText = btnText ? btnText.textContent : '';
    if (btnText) {
      btnText.textContent = (window.cdtContactData && window.cdtContactData.sending) || 'جاري الإرسال...';
    }

    var formData = new FormData(form);
    var ajaxUrl = (window.cdtContactData && window.cdtContactData.ajaxUrl) || '/wp-admin/admin-ajax.php';

    fetch(ajaxUrl, {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data && data.success) {
          showAlert(data.data.message || (window.cdtContactData && window.cdtContactData.success) || 'تم الإرسال بنجاح!', true);
          form.reset();
        } else {
          var errMsg = (data && data.data && data.data.message) || (window.cdtContactData && window.cdtContactData.error) || 'حدث خطأ أثناء الإرسال.';
          showAlert(errMsg, false);
        }
      })
      .catch(function () {
        var errNetwork = (window.cdtContactData && window.cdtContactData.error) || 'تعذر الاتصال بالخادم، يرجى مراسلتنا عبر تيليجرام أو واتساب.';
        showAlert(errNetwork, false);
      })
      .finally(function () {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.style.opacity = '1';
        }
        if (btnText) {
          btnText.textContent = origText;
        }
      });
  });
});
