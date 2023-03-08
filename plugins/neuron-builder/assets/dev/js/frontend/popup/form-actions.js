var PopupFormActions = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        form: ".m-neuron-form"
      }
    };
  },
  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors"),
      elements = {};
    elements.$form = this.$element.find(selectors.form);
    return elements;
  },
  bindEvents: function bindEvents() {
    this.elements.$form.on("submit_success", this.handleFormAction);
  },
  handleFormAction: function handleFormAction(event, response) {
    if ("undefined" === typeof response.data.popup) {
      return;
    }

    var popupSettings = response.data.popup;

    if ("open" === popupSettings.action) {
      return neuronFrontend.modules.popup.showPopup(popupSettings);
    }

    setTimeout(function() {
      return neuronFrontend.modules.popup.closePopup(popupSettings, event);
    }, 1000);
  }
});

module.exports = function($scope) {
  new PopupFormActions({
    $element: $scope
  });
};
