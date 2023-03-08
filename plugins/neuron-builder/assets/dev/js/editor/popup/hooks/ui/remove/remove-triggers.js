export class PopupRemoveTriggers extends $e.modules.hookUI.After {
  getCommand() {
    return "editor/documents/unload";
  }

  getId() {
    return "neuron-popup-remove-triggers";
  }

  getConditions(args) {
    var document = args.document;
    return "popup" === document.config.type;
  }

  apply() {
    this.removePanelFooterSubmenuItems();
    this.removePublishTabs();
  }

  removePanelFooterSubmenuItems() {
    var displaySettingsTypes = neuron.modules.popup.displaySettingsTypes;
    jQuery.each(displaySettingsTypes, function(type) {
      elementor
        .getPanelView()
        .footer.currentView.removeSubMenuItem("saver-options", {
          name: type
        });
    });
  }

  removePublishTabs() {
    var component = $e.components.get("theme-builder-publish"),
      displaySettingsTypes = neuron.modules.popup.displaySettingsTypes;

    jQuery.each(displaySettingsTypes, function(type) {
      component.removeTab(type);
    });
  }
}

export default PopupRemoveTriggers;
