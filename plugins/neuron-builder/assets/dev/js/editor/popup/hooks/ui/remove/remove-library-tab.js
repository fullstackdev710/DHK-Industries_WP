export class PopupRemoveLibraryTab extends $e.modules.hookUI.After {
  getCommand() {
    return "editor/documents/unload";
  }

  getId() {
    return "neuron-popup-remove-library-tab";
  }

  getConditions(args) {
    var document = args.document;
    return "popup" === document.config.type;
  }

  apply() {
    $e.components.get("library").removeTab("templates/popups");
  }
}

export default PopupRemoveLibraryTab;
