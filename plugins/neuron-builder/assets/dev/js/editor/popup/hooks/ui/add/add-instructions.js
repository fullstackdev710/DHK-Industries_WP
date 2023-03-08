export class PopupAddInstructions extends $e.modules.hookUI.After {
  getCommand() {
    return "editor/documents/open";
  }

  getId() {
    return "neuron-popup-add-instructions";
  }

  getConditions(args) {
    var document = elementor.documents.get(args.id);
    return (
      "popup" === document.config.type &&
      !elementor.config.user.introduction.popupSettings
    );
  }

  apply() {
    this.__proto__.onPageSettingsClose = this.onPageSettingsClose.bind(this);

    $e.components
      .get("panel/page-settings")
      .on("route/close", this.__proto__.onPageSettingsClose);
  }

  onPageSettingsClose() {
    var introduction = this.getIntroduction();
    introduction.show(
      elementor.getPanelView().footer.currentView.ui.settings[0]
    );

    introduction.setViewed();

    $e.components
      .get("panel/page-settings")
      .off("route/close", this.__proto__.onPageSettingsClose);
  }

  getIntroduction() {
    return new elementorModules.editor.utils.Introduction({
      introductionKey: "popupSettings",
      dialogOptions: {
        id: "neuron-popup-settings-introduction",
        headerMessage: neuron.translate("popup_settings_introduction_title"),
        message: neuron.translate("popup_settings_introduction_message"),
        closeButton: true,
        closeButtonClass: "eicon-close",
        position: {
          my: "left-30 bottom-35",
          at: "right bottom-7",
          autoRefresh: true,
        },
        hide: {
          onOutsideClick: false,
        },
      },
    });
  }
}

export default PopupAddInstructions;
