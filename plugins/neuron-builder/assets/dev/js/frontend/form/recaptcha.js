const Recaptcha = function($element) {
  var $element = $element.find(".neuron-g-recaptcha:last");
  var captchaIds = [];

  if (!$element.length) {
    return;
  }

  var addRecaptcha = function addRecaptcha($elementRecaptcha) {
    var $form = $elementRecaptcha.parents("form"),
      settings = $elementRecaptcha.data(),
      isV2 = "v3" !== settings.type;

    captchaIds.forEach(function(id) {
      return grecaptcha.reset(id);
    });
    var widgetId = grecaptcha.render($elementRecaptcha[0], settings);
    $form.on("reset error", function() {
      grecaptcha.reset(widgetId);
    });
    if (isV2) {
      $elementRecaptcha.data("widgetId", widgetId);
    } else {
      captchaIds.push(widgetId);
      $form.find('button[type="submit"]').on("click", function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
          grecaptcha
            .execute(widgetId, { action: $elementRecaptcha.data("action") })
            .then(function(token) {
              $form.find('[name="g-recaptcha-response"]').remove();
              $form.append(
                jQuery("<input>", {
                  type: "hidden",
                  value: token,
                  name: "g-recaptcha-response"
                })
              );
              $form.submit();
            });
        });
      });
    }
  };

  var onRecaptchaApiReady = function onRecaptchaApiReady(callback) {
    if (window.grecaptcha && window.grecaptcha.render) {
      callback();
    } else {
      // If not ready check again by timeout..
      setTimeout(function() {
        onRecaptchaApiReady(callback);
      }, 350);
    }
  };

  onRecaptchaApiReady(function() {
    addRecaptcha($element);
  });
};

module.exports = Recaptcha;
