const DateField = elementorModules.editor.utils.Module.extend({
  renderField: function renderField(inputField, item, i, settings) {
    var itemClasses = _.escape(item.css_classes),
      required = "",
      min = "",
      max = "",
      placeholder = "";

    if (item.required) {
      required = "required";
    }

    if (item.min_date) {
      min = ' min="' + item.min_date + '"';
    }

    if (item.max_date) {
      max = ' max="' + item.max_date + '"';
    }

    if (item.placeholder) {
      placeholder = ' placeholder="' + item.placeholder + '"';
    }

    if ("yes" === item.use_native_date) {
      itemClasses += " elementor-use-native";
    }

    return (
      '<input size="1"' +
      min +
      max +
      placeholder +
      ' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" type="date" class="m-neuron-form__field elementor-date-field elementor-field elementor-size-' +
      settings.input_size +
      " " +
      itemClasses +
      '" name="form_field_' +
      i +
      '" id="form_field_' +
      i +
      '" ' +
      required +
      " >"
    );
  },

  onInit: function onInit() {
    elementor.hooks.addFilter(
      "neuron/forms/content_template/field/date",
      this.renderField,
      10,
      4
    );
  },
});

module.exports = DateField;
