module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        wrapper: ".m-neuron-search-form",
        container: ".m-neuron-search-form__container",
        icon: ".m-neuron-search-form__icon",
        input: ".m-neuron-search-form__input",
        submit: ".m-neuron-search-form__submit",
        closeButton: ".dialog-close-button",
      },
      classes: {
        isFocus: "m-neuron-search-form--focus",
      },
    };
  },
  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors"),
      elements = {};

    elements.$wrapper = this.$element.find(selectors.wrapper);
    elements.$container = this.$element.find(selectors.container);
    elements.$input = this.$element.find(selectors.input);
    elements.$icon = this.$element.find(selectors.icon);
    elements.$submit = this.$element.find(selectors.submit);
    elements.$closeButton = this.$element.find(selectors.closeButton);

    return elements;
  },
  bindEvents: function bindEvents() {
    var self = this,
      $input = self.elements.$input,
      $wrapper = self.elements.$wrapper,
      classes = this.getSettings("classes");

    // Apply focus style on wrapper element when input is focused
    $input.on({
      focus: function focus() {
        $wrapper.addClass(classes.isFocus);
      },
      blur: function blur() {
        $wrapper.removeClass(classes.isFocus);
      },
    });
  },
});
