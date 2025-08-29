/**
* PHP Email Form Validation - v3.4
* Author: BootstrapMade.com
* Edited for your project
*/
(function () {
  "use strict";

  let forms = document.querySelectorAll('.php-email-form');

  forms.forEach(function(form) {
    form.addEventListener('submit', function(event) {
      event.preventDefault(); // stop normal form redirect

      let action = form.getAttribute('action');
      let recaptcha = form.getAttribute('data-recaptcha-site-key');

      if (!action) {
        displayError(form, 'The form action property is not set!');
        return;
      }

      // show loading, hide messages
      form.querySelector('.loading').classList.add('d-block');
      form.querySelector('.error-message').classList.remove('d-block');
      form.querySelector('.sent-message').classList.remove('d-block');

      let formData = new FormData(form);

      if (recaptcha) {
        if (typeof grecaptcha !== "undefined") {
          grecaptcha.ready(function() {
            try {
              grecaptcha.execute(recaptcha, { action: 'php_email_form_submit' })
              .then(token => {
                formData.set('recaptcha-response', token);
                php_email_form_submit(form, action, formData);
              });
            } catch (error) {
              displayError(form, error);
            }
          });
        } else {
          displayError(form, 'The reCaptcha javascript API url is not loaded!');
        }
      } else {
        php_email_form_submit(form, action, formData);
      }
    });
  });

  function php_email_form_submit(form, action, formData) {
    fetch(action, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
      if (response.ok) {
        return response.text();
      } else {
        throw new Error(`${response.status} ${response.statusText} ${response.url}`);
      }
    })
    .then(data => {
      form.querySelector('.loading').classList.remove('d-block');
      if (data.trim() === 'OK') {
        form.querySelector('.sent-message').classList.add('d-block');
        form.reset();
      } else {
        throw new Error(data ? data : 'Form submission failed, no response from: ' + action);
      }
    })
    .catch((error) => {
      displayError(form, error);
    });
  }

  function displayError(form, error) {
    form.querySelector('.loading').classList.remove('d-block');
    form.querySelector('.error-message').innerHTML = error;
    form.querySelector('.error-message').classList.add('d-block');
  }

})();
