/**
 * 4images - Modern JavaScript (ES6+)
 * Replaces inline event handlers for better security and maintainability
 * Compatible with all modern browsers (no build tools required)
 */

// Global variables (legacy compatibility)
let captcha_reload_count = 0;
let captcha_image_url = "";

/**
 * Reload CAPTCHA image (replaces inline javascript: URLs)
 */
function new_captcha_image() {
  const captchaImg = document.getElementById('captcha_image');
  const captchaInput = document.getElementById('captcha_input');

  if (!captchaImg) return;

  if (captcha_image_url.indexOf('?') === -1) {
    captchaImg.src = captcha_image_url + '?c=' + captcha_reload_count;
  } else {
    captchaImg.src = captcha_image_url + '&c=' + captcha_reload_count;
  }

  if (captchaInput) {
    captchaInput.value = "";
    captchaInput.focus();
  }

  captcha_reload_count++;
}

/**
 * Open detail window (legacy popup)
 */
function opendetailwindow() {
  window.open('', 'detailwindow', 'toolbar=no,scrollbars=yes,resizable=no,width=680,height=480');
}

/**
 * Initialize on DOM ready
 */
document.addEventListener('DOMContentLoaded', () => {

  // Set captcha_image_url from data attribute if available
  const captchaImg = document.getElementById('captcha_image');
  if (captchaImg && captchaImg.dataset.captchaUrl) {
    captcha_image_url = captchaImg.dataset.captchaUrl;
  }

  // CAPTCHA reload links (replaces href="javascript:new_captcha_image();")
  document.querySelectorAll('[data-captcha-reload]').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      new_captcha_image();
    });
  });

  // Form submit: Disable button to prevent double-submit
  // Usage: <form data-disable-on-submit="buttonName">
  document.querySelectorAll('form[data-disable-on-submit]').forEach(form => {
    form.addEventListener('submit', () => {
      const btnName = form.dataset.disableOnSubmit;
      const btn = form.querySelector(`[name="${btnName}"]`);
      if (btn) {
        btn.disabled = true;
        // Re-enable after 5 seconds (fallback)
        setTimeout(() => { btn.disabled = false; }, 5000);
      }
    });
  });

  // Detail window links (replaces onclick="opendetailwindow();")
  document.querySelectorAll('[data-detail-window]').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      opendetailwindow();
      // If link has href, navigate in popup
      if (link.href) {
        setTimeout(() => {
          window.open(link.href, 'detailwindow');
        }, 100);
      }
    });
  });

});
