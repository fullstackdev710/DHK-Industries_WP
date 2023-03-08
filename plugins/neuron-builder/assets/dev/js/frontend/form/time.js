var DateTimeFieldBase = require("./data-time-field-base.js");

var Time = DateTimeFieldBase.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        fields: ".neuron-time-field",
      },
      classes: {
        useNative: "neuron-use-native",
      },
    };
  },

  getPickerOptions: function getPickerOptions(element) {
    return {
      noCalendar: true,
      enableTime: true,
      allowInput: true,
    };
  },
});

module.exports = Time;
