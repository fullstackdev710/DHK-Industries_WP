export class ThemeBuilderRemoveEditorUI extends $e.modules.hookUI.After {
  getCommand() {
    return "editor/documents/unload";
  }

  getId() {
    return "neuron-theme-builder-remove-editor-ui";
  }

  getConditions(args) {
    var document = args.document;
    return document.config.theme_builder;
  }

  apply() {
    this.removePanelFooterSubmenuItems();
    this.removePublishTabs();
  }

  removePanelFooterSubmenuItems() {
    var footerView = elementor.getPanelView().footer.currentView,
      behavior =
        footerView._behaviors[
          Object.keys(footerView.behaviors()).indexOf("saver")
        ];

    elementor
      .getPanelView()
      .footer.currentView.removeSubMenuItem("saver-options", {
        name: "conditions"
      });
    behavior.ui.buttonPreview
      .tipsy("enable")
      .removeClass(
        "elementor-panel-footer-theme-builder-buttons-wrapper elementor-toggle-state"
      );
  }

  removePublishTabs() {
    var component = $e.components.get("theme-builder-publish");
    component.removeTab("conditions");
  }
}

export default ThemeBuilderRemoveEditorUI;
