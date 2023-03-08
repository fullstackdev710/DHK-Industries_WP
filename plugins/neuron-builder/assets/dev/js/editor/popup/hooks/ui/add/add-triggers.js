export class PopupAddTriggers extends $e.modules.hookUI.After {
  getCommand() {
    return "editor/documents/open";
  }

  getId() {
    return "neuron-popup-add-triggers";
  }

  getConditions(args) {
    var document = elementor.documents.get(args.id);
    return "popup" === document.config.type;
  }

  apply() {
    if (elementor.panel) {
      this.addUI();
    } else {
      // First open, the panel is not available yet.
      elementor.on("preview:loaded", this.addUI.bind(this));
    }
  }

  addUI() {
    this.addPanelFooterSubmenuItems();
    this.addPublishTabs();
  }

  addPublishTabs() {
    var config = elementor.config.document.displaySettings,
      component = $e.components.get("theme-builder-publish"),
      module = neuron.modules.popup;

    jQuery.each(module.displaySettingsTypes, function (type, data) {
      // Init models for editor save.
      data.model = new elementorModules.editor.elements.models.BaseSettings(
        config[type].settings,
        {
          controls: config[type].controls,
        }
      );
      component.addTab(type, {
        View: require("./popup-display"),
        viewOptions: {
          name: type,
          id: "neuron-popup-".concat(type, "__controls"),
          model: data.model,
          controls: data.model.controls,
        },
        name: type,
        title: neuron.translate(type),
        description: neuron.translate(
          "popup_publish_screen_".concat(type, "_description")
        ),
        image:
          neuron.config.urls.modules +
          "popup/assets/images/".concat(type, "-tab.svg"),
      });
    });
  }

  addPanelFooterSubmenuItems() {
    var component = $e.components.get("theme-builder-publish"),
      displaySettingsTypes = neuron.modules.popup.displaySettingsTypes;
    jQuery.each(displaySettingsTypes, function (type, data) {
      elementor
        .getPanelView()
        .footer.currentView.addSubMenuItem("saver-options", {
          before: "save-template",
          name: type,
          icon: data.icon,
          title: neuron.translate(type),
          callback: function callback() {
            return $e.route(component.getTabRoute(type));
          },
        });
    });
  }
}

export default PopupAddTriggers;
