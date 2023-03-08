export class PopupRemoveInstructions extends $e.modules.hookUI.After {
  getCommand() {
    return "editor/documents/unload";
  }

  getId() {
    return "neuron-popup-remove-instructions";
  }

  getConditions(args) {
    var document = args.document;

    return (
      "popup" === document.config.type &&
      !elementor.config.user.introduction.popupSettings
    );
  }

  apply() {
    $e.components
      .get("panel/page-settings")
      .off("route/close", this.component.onPageSettingsClose);
  }
}

export default PopupRemoveInstructions;
