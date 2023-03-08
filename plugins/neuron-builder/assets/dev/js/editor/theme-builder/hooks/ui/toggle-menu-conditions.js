export class ThemeBuilderToggleMenuConditions extends $e.modules.hookUI.After {
  getCommand() {
    return "document/elements/settings";
  }

  getId() {
    return "neuron-theme-builder-toggle-menu-conditions";
  }

  getContainerType() {
    return "document";
  }

  getConditions(args) {
    return args.settings && args.settings.location;
  }

  apply() {
    var themeBuilder = neuron.modules.themeBuilder;
    themeBuilder.ui.menuConditions.toggle(
      !!elementor.config.document.theme_builder.settings.location
    );
  }
}

export default ThemeBuilderToggleMenuConditions;
