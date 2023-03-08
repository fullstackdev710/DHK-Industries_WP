module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        fields: ".neuron-date-field",
      },
      classes: {
        useNative: "neuron-use-native",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var defaultSettings = this.getDefaultSettings(),
      selectors = defaultSettings.selectors;

    return {
      $fields: this.$element.find(selectors.fields),
    };
  },

  getPickerOptions: function getPickerOptions(element) {
    var $element = jQuery(element);
    return {
      minDate: $element.attr("min") || null,
      maxDate: $element.attr("max") || null,
      allowInput: true,
    };
  },

  addPicker: function addPicker(element) {
    var thisDefaultSettings = this.getDefaultSettings(),
      classes = thisDefaultSettings.classes,
      $element = jQuery(element);

    if ($element.hasClass(classes.useNative)) {
      return;
    }

    element.flatpickr(this.getPickerOptions(element));
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    var self = this;

    this.elements.$fields.each(function (index, element) {
      return self.addPicker(element);
    });
  },
});
