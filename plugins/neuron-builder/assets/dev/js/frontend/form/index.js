module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-form.default",
    require("./form")
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-form.default",
    require("./recaptcha")
  );

  elementorFrontend.elementsHandler.attachHandler(
    "neuron-form",
    require("./data-time-field-base")
  );

  elementorFrontend.elementsHandler.attachHandler(
    "neuron-form",
    require("./time")
  );
};
