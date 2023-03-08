export default class extends elementorModules.editor.utils.Module {
  addPanelMenuItem() {
    const menu = elementor.modules.layouts.panel.pages.menu.Menu;

    menu.addItem(
      {
        name: "neuron-site-editor",
        icon: "eicon-theme-builder",
        title: neuron.translate("neuron_theme_builder"),
        type: "page",
        callback: () => {
          window.location.href = neuron.config.theme_builder_url;
        },
      },
      "style",
      "editor-preferences"
    );
  }

  onInit() {
    super.onInit();

    elementorCommon.elements.$window.on("elementor:loaded", () => {
      if (!elementor.config.initial_document.panel.support_kit) {
        return;
      }

      if (!elementor.config.user.can_edit_kit) {
        return;
      }

      elementor.on("panel:init", () => {
        this.addPanelMenuItem();
      });
    });
  }
}
