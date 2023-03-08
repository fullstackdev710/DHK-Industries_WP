const TelField = elementorModules.editor.utils.Module.extend({
  renderField: function renderField(inputField, item, i, settings) {
    var itemClasses = _.escape(item.css_classes),
      required = "",
      placeholder = "";

    if (item.required) {
      required = "required";
    }

    if (item.placeholder) {
      placeholder = ' placeholder="' + item.placeholder + '"';
    }

    itemClasses = "m-neuron-form__field " + itemClasses;

    return (
      '<input size="1" type="' +
      item.field_type +
      '" class="m-neuron-form__field elementor-field elementor-size-' +
      settings.input_size +
      " " +
      itemClasses +
      '" name="form_field_' +
      i +
      '" id="form_field_' +
      i +
      '" ' +
      required +
      " " +
      placeholder +
      ' pattern="[0-9()-]" >'
    );
  },

  onInit: function onInit() {
    elementor.hooks.addFilter(
      "neuron/forms/content_template/field/tel",
      this.renderField,
      10,
      4
    );
  },
});

module.exports = TelField;
