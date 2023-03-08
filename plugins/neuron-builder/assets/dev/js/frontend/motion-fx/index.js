import MotionFXHandler from "./module";

export default class MotionFXModule extends elementorModules.Module {
  __construct() {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/global",
      function ($element) {
        elementorFrontend.elementsHandler.addHandler(MotionFXHandler, {
          $element: $element,
        });
      }
    );
  }
}
