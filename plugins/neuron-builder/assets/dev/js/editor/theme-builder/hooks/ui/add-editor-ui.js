import { ConditionsView } from "./conditions/conditions-view";

export class ThemeBuilderAddEditorUI extends $e.modules.hookUI.After {
  getCommand() {
    return "editor/documents/open";
  }

  getId() {
    return "neuron-theme-builder-add-editor-ui";
  }

  getConditions(args) {
    return elementor.documents.get(args.id).config.theme_builder;
  }

  apply() {
    if (elementor.panel) {
      this.addUI();
    } else {
      // First open, the panel is not available yet.
      elementor.once("preview:loaded", this.addUI.bind(this));
    }
  }

  addUI() {
    this.addRepeaterControlView();
    this.addPanelFooterSubmenuItems();
    this.addPublishTabs();
  }

  addRepeaterControlView() {
    elementor.addControlView(
      "Conditions_repeater",
      require("./conditions/conditions-repeater")
    );
  }

  addPublishTabs() {
    var component = $e.components.get("theme-builder-publish"),
      themeBuilderModuleConfig = elementor.config.document.theme_builder,
      settings = themeBuilderModuleConfig.settings;
    component.manager.conditionsModel = new elementorModules.editor.elements.models.BaseSettings(
      settings,
      {
        controls: themeBuilderModuleConfig.template_conditions.controls
      }
    );
    component.addTab("conditions", {
      title: neuron.translate("conditions"),
      View: ConditionsView,
      viewOptions: {
        model: component.manager.conditionsModel,
        controls: component.manager.conditionsModel.controls
      },
      name: "conditions",
      description: neuron.translate("conditions_publish_screen_description"),
      image:
        neuron.config.urls.modules +
        "theme-builder/assets/images/conditions-tab.svg"
    });
  }

  addPanelFooterSubmenuItems() {
    var footerView = elementor.getPanelView().footer.currentView,
      behavior =
        footerView._behaviors[
          Object.keys(footerView.behaviors()).indexOf("saver")
        ];

    footerView.ui.menuConditions = footerView.addSubMenuItem("saver-options", {
      before: "save-template",
      name: "conditions",
      icon: "eicon-flow",
      title: neuron.translate("display_conditions"),
      callback: function callback() {
        return $e.route("theme-builder-publish/conditions");
      }
    });
    footerView.ui.menuConditions.toggle(
      !!elementor.config.document.theme_builder.settings.location
    );
    behavior.ui.buttonPreview
      .tipsy("disable")
      .html(jQuery("#tmpl-elementor-theme-builder-button-preview").html())
      .addClass(
        "elementor-panel-footer-theme-builder-buttons-wrapper elementor-toggle-state"
      );
  }
}

export default ThemeBuilderAddEditorUI;
