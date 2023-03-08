const UploadField = elementorModules.editor.utils.Module.extend({
  renderField: function renderField(inputField, item, i, settings) {
    var itemClasses = _.escape(item.css_classes),
      required = "",
      multiple = "",
      fieldName = "form_field_";

    if (item.required) {
      required = "required";
    }
    if (item.allow_multiple_upload) {
      multiple = ' multiple="multiple"';
      fieldName += "[]";
    }

    return (
      '<input size="1"  type="file" class="elementor-file-field elementor-field elementor-size-' +
      settings.input_size +
      " " +
      itemClasses +
      '" name="' +
      fieldName +
      '" id="form_field_' +
      i +
      '" ' +
      required +
      multiple +
      " >"
    );
  },

  onInit: function onInit() {
    elementor.hooks.addFilter(
      "neuron/forms/content_template/field/upload",
      this.renderField,
      10,
      4
    );
  }
});

module.exports = UploadField;
