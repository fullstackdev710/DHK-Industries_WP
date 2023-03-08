export class ThemeBuilderUpdatePreviewOptions extends $e.modules.hookData
  .After {
  getCommand() {
    return "document/elements/settings";
  }

  getId() {
    return "neuron-theme-builder-update-preview-options";
  }

  getContainerType() {
    return "document";
  }

  getConditions(args) {
    return args.settings && args.settings.preview_type;
  }

  apply(args) {
    var _args$containers = args.containers,
      containers =
        _args$containers === void 0 ? [args.container] : _args$containers,
      themeBuilder = neuron.modules.themeBuilder;

    $e.run("document/elements/settings", {
      containers: containers,
      settings: {
        preview_id: "",
        preview_search_term: ""
      }
    });

    if ($e.routes.is("panel/page-settings/settings")) {
      themeBuilder.updatePreviewIdOptions(true);
    }
  }
}

export default ThemeBuilderUpdatePreviewOptions;
