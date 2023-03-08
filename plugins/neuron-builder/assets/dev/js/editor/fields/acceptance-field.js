const AcceptanceField = elementorModules.editor.utils.Module.extend({
  renderField: function renderField(inputField, item, i, settings) {
    var itemClasses = _.escape(item.css_classes),
      required = "",
      label = "",
      checked = "";

    if (item.required) {
      required = "required";
    }

    if (item.acceptance_text) {
      label =
        '<label for="form_field_' +
        i +
        '">' +
        item.acceptance_text +
        "</label>";
    }

    if (item.checked_by_default) {
      checked = ' checked="checked"';
    }

    return (
      '<div class="m-neuron-form-field__subgroup">' +
      '<span class="m-neuron-form__option">' +
      '<input size="1" type="checkbox"' +
      checked +
      ' class="elementor-acceptance-field elementor-field elementor-size-' +
      settings.input_size +
      " " +
      itemClasses +
      '" name="form_field_' +
      i +
      '" id="form_field_' +
      i +
      '" ' +
      required +
      " > " +
      label +
      "</span></div>"
    );
  },

  onInit: function onInit() {
    elementor.hooks.addFilter(
      "neuron/forms/content_template/field/acceptance",
      this.renderField,
      10,
      4
    );
  }
});

module.exports = AcceptanceField;
