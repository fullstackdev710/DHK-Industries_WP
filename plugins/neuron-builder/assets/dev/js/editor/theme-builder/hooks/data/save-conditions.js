export class ThemeBuilderSaveConditions extends $e.modules.hookData.After {
  getCommand() {
    return "document/save/save";
  }

  getId() {
    return "neuron-theme-builder-save-conditions";
  }

  getConditions() {
    return !!elementor.config.document.theme_builder;
  }

  apply() {
    var conditionsModel = neuron.modules.themeBuilder.conditionsModel;

    neuron.ajax.addRequest("theme_builder_save_conditions", {
      data: conditionsModel.toJSON({
        remove: ["default"],
      }),
      success: function success() {
        elementor.config.document.theme_builder.settings.conditions =
          conditionsModel.get("conditions");
      },
    });
  }
}

export default ThemeBuilderSaveConditions;
