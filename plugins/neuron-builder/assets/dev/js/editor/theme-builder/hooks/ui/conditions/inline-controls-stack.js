export class inlineControlsStack extends elementorModules.editor.views
  .ControlsStack {
  activeTab = "content";
  activeSection = "settings";

  initialize() {
    this.collection = new Backbone.Collection(_.values(this.options.controls));
  }

  filter(model) {
    if ("section" === model.get("type")) {
      return true;
    }

    var section = model.get("section");
    return !section || section === this.activeSection;
  }

  childViewOptions() {
    return {
      elementSettingsModel: this.model
    };
  }
}

export default inlineControlsStack;
