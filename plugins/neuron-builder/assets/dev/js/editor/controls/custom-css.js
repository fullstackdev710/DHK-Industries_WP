class CustomCSS extends elementorModules.editor.utils.Module {
  addCustomCss(css, context) {
    if (!context) {
      return;
    }

    var model = context.model,
      customCSS = model.get("settings").get("custom_css");
    var selector = ".elementor-element.elementor-element-" + model.get("id");

    if ("document" === model.get("elType")) {
      selector = elementor.config.document.settings.cssWrapperSelector;
    }

    if (customCSS) {
      css += customCSS.replace(/selector/g, selector);
    }

    return css;
  }

  onElementorInit() {
    elementor.hooks.addFilter("editor/style/styleText", this.addCustomCss);
    elementor.on("navigator:init", this.onNavigatorInit.bind(this));
  }

  onNavigatorInit() {
    elementor.navigator.indicators.customCSS = {
      icon: "code-bold",
      settingKeys: ["custom_css"],
      title: neuron.translate("custom_css"),
      section: "section_custom_css",
    };
  }
}

export default CustomCSS;
