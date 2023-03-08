export class PopupAddLibraryTab extends $e.modules.hookUI.After {
  getCommand() {
    return "editor/documents/open";
  }

  getId() {
    return "neuron-popup-add-library-tab";
  }

  getConditions(args) {
    var document = elementor.documents.get(args.id);
    return "popup" === document.config.type;
  }

  apply() {
    $e.components.get("library").addTab(
      "templates/popups",
      {
        title: neuron.translate("popups"),
        filter: {
          source: "remote",
          type: "popup"
        }
      },
      1
    );
  }
}

export default PopupAddLibraryTab;
