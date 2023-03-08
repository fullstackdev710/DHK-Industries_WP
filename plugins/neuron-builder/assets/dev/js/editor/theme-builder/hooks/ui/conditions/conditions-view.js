import { inlineControlsStack } from "./inline-controls-stack";

export class ConditionsView extends inlineControlsStack {
  id = "elementor-theme-builder-conditions-view";
  template = "#tmpl-elementor-theme-builder-conditions-view";
  childViewContainer = "#elementor-theme-builder-conditions-controls";

  childViewOptions() {
    return {
      elementSettingsModel: this.model
    };
  }
}

export default ConditionsView;
