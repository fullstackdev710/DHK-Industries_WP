export default class MotionFX extends elementorModules.editor.utils.Module {
  onElementorInit() {
    elementor.on("navigator:init", this.onNavigatorInit.bind(this));
  }

  onNavigatorInit() {
    elementor.navigator.indicators.motionFX = {
      icon: "flash",
      title: neuron.translate("motion_effects"),
      settingKeys: [
        "motion_fx_motion_fx_scrolling",
        "motion_fx_motion_fx_mouse",
        "background_motion_fx_motion_fx_scrolling",
        "background_motion_fx_motion_fx_mouse",
      ],
      section: "section_effects",
    };
  }
}
