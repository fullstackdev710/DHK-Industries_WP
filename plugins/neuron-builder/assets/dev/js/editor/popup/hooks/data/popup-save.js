export class PopupSave extends $e.modules.hookData.After {
  getCommand() {
    return "document/save/save";
  }

  getId() {
    return "neuron-popup-save";
  }

  getConditions() {
    return "popup" === elementor.config.document.type;
  }

  apply() {
    var settings = {};

    jQuery.each(
      neuron.modules.popup.displaySettingsTypes,
      function (type, data) {
        settings[type] = data.model.toJSON({
          remove: ["default"],
        });
      }
    );

    neuron.ajax.addRequest("popup_save_display_settings", {
      data: {
        settings: settings,
      },
    });
  }
}

export default PopupSave;
