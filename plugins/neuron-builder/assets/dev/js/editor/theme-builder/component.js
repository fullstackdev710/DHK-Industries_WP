import ThemeBuilderLayoutView from "./layout-view";
import ThemeBuilderModalLayout from "./modal-layout";
import * as hooks from "./hooks/";

export default class ThemeBuilderComponent extends $e.modules
  .ComponentModalBase {
  getNamespace() {
    return "theme-builder-publish";
  }

  getModalLayout() {
    return ThemeBuilderModalLayout;
  }

  defaultCommands() {
    var _this = this;

    return {
      next: function next() {
        var tabs = Object.keys(_this.tabs),
          next = tabs[_this.currentTabIndex + 1];

        if (next) {
          $e.route(_this.getTabRoute(next));
        }
      },
      save: function save() {
        $e.run("document/save/default", {
          force: true,
        });

        _this.layout.hideModal();
      },
      "preview-settings": function previewSettings() {
        var panel = elementor.getPanelView();
        $e.route("panel/page-settings/settings");

        panel
          .getCurrentPageView()
          .activateSection("preview_settings")
          ._renderChildren();
      },
    };
  }

  defaultHooks() {
    return this.importHooks(hooks);
  }

  getTabsWrapperSelector() {
    return "#neuron-publish__tabs";
  }

  renderTab(tab) {
    var tabs = this.getTabs(),
      keys = Object.keys(tabs),
      tabArgs = tabs[tab];

    this.currentTabIndex = keys.indexOf(tab);

    var isLastTab = !keys[this.currentTabIndex + 1];

    this.layout.modalContent.currentView.screen.show(
      new tabArgs.View(tabArgs.viewOptions)
    );

    this.layout.modal.getElements("next").toggle(!isLastTab);

    this.layout.modal
      .getElements("publish")
      .toggleClass("elementor-button-success", isLastTab);
  }

  activateTab(tab) {
    $e.routes.saveState(this.getNamespace());

    super.activateTab(tab);
  }

  open() {
    super.open();

    if (!this.layoutContent) {
      this.layout.showLogo();

      this.layout.modalContent.show(
        new ThemeBuilderLayoutView({
          component: this,
        })
      );

      this.layoutContent = true;
    }

    return true;
  }
}
