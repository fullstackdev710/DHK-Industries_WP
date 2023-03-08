import ThemeBuilderComponent from "./component";

export default class ThemeBuilderModule extends elementorModules.editor.utils
  .Module {
  __construct(args) {
    super.__construct.apply(self, args);

    Object.defineProperty(neuron.config, "theme_builder", {
      get: function get() {
        return elementor.config.document.theme_builder;
      },
    });
  }

  onElementorLoaded() {
    this.component = $e.components.register(
      new ThemeBuilderComponent({
        manager: this,
      })
    );

    elementor.on("document:loaded", this.onDocumentLoaded.bind(this));
    elementor.on("document:unload", this.onDocumentUnloaded.bind(this));

    this.onApplyPreview = this.onApplyPreview.bind(this);
    this.onSectionPreviewSettingsActive = this.onSectionPreviewSettingsActive.bind(
      this
    );
  }

  onDocumentLoaded(document) {
    if (!document.config.theme_builder) {
      return;
    }

    elementor
      .getPanelView()
      .on("set:page:page_settings", this.updatePreviewIdOptions);

    elementor.channels.editor.on(
      "neuronThemeBuilder:ApplyPreview",
      this.onApplyPreview
    );

    elementor.channels.editor.on(
      "page_settings:preview_settings:activated",
      this.onSectionPreviewSettingsActive
    );
  }

  onDocumentUnloaded(document) {
    if (!document.config.theme_builder) {
      return;
    }

    elementor
      .getPanelView()
      .off("set:page:page_settings", this.updatePreviewIdOptions);

    elementor.channels.editor.off(
      "neuronThemeBuilder:ApplyPreview",
      this.onApplyPreview
    );

    elementor.channels.editor.off(
      "page_settings:preview_settings:activated",
      this.onSectionPreviewSettingsActive
    );
  }

  saveAndReload() {
    $e.run("document/save/auto", {
      force: true,
      onSuccess: function onSuccess() {
        elementor.dynamicTags.cleanCache();
        elementor.reloadPreview();
      },
    });
  }

  onApplyPreview() {
    this.saveAndReload();
  }

  onSectionPreviewSettingsActive() {
    this.updatePreviewIdOptions(true);
  }

  updatePreviewIdOptions(render) {
    var previewType = elementor.settings.page.model.get("preview_type");

    if (!previewType) {
      return;
    }

    previewType = previewType.split("/");

    var currentView = elementor.getPanelView().getCurrentPageView(),
      controlModel = currentView.collection.findWhere({
        name: "preview_id",
      });

    if ("author" === previewType[1]) {
      controlModel.set({
        autocomplete: {
          object: "author",
        },
      });
    } else if ("taxonomy" === previewType[0]) {
      controlModel.set({
        autocomplete: {
          object: "tax",
          query: {
            taxonomy: previewType[1],
          },
        },
      });
    } else if ("single" === previewType[0]) {
      controlModel.set({
        autocomplete: {
          object: "post",
          query: {
            post_type: previewType[1],
          },
        },
      });
    } else {
      controlModel.set({
        autocomplete: {
          object: "",
        },
      });
    }

    if (true === render) {
      // Can be model.
      var controlView = currentView.children.findByModel(controlModel);
      controlView.render();
      controlView.$el.toggle(!!controlModel.get("autocomplete").object);
    }
  }
}
