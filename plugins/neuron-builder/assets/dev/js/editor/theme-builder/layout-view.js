export default class ThemeBuilderLayoutView extends Marionette.LayoutView {
  id() {
    return "neuron-publish";
  }

  getTemplate() {
    return Marionette.TemplateCache.get("#tmpl-neuron-component-publish");
  }

  regions() {
    return {
      screen: "#neuron-publish__screen",
    };
  }

  templateHelpers() {
    return {
      tabs: this.getOption("component").getTabs(),
    };
  }
}
