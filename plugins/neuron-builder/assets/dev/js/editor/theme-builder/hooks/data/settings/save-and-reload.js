export class ThemeBuilderSaveAndReload extends $e.modules.hookData.After {
  getCommand() {
    return "document/elements/settings";
  }

  getId() {
    return "neuron-theme-builder-save-and-reload";
  }

  getContainerType() {
    return "document";
  }

  getConditions(args) {
    return args.settings && args.settings.page_template;
  }

  apply() {
    $e.run("document/save/auto", {
      force: true,
      onSuccess: function onSuccess() {
        elementor.reloadPreview();
        elementor.once("preview:loaded", function() {
          $e.route("panel/page-settings/settings");
        });
      }
    });
  }
}

export default ThemeBuilderSaveAndReload;
