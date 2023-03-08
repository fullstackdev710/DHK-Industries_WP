export class ThemeBuilderShowConditions extends $e.modules.hookData.Dependency {
  getCommand() {
    return "document/save/default";
  }

  getId() {
    return "neuron-theme-builder-show-conditions";
  }

  getConditions(args) {
    var _args$force = args.force,
      force = _args$force === void 0 ? false : _args$force; // If force save, do not show conditions.

    if (force) {
      return false;
    }

    var showConditions = false;
    var themeBuilder = elementor.config.document.theme_builder;

    if (themeBuilder) {
      var hasConditions = themeBuilder.settings.conditions.length,
        hasLocation = themeBuilder.settings.location,
        isDraft = "draft" === elementor.settings.page.model.get("post_status");

      if (hasLocation && (!hasConditions || isDraft)) {
        showConditions = true;
      }
    }

    return showConditions;
  }

  apply() {
    $e.route("theme-builder-publish/conditions");
    return false; // HookBreak.
  }
}

export default ThemeBuilderShowConditions;
