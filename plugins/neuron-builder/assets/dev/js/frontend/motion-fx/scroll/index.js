import MotionFXBase from "../base";
import ScrollHandler from "../scroll-handler";

export default class Scroll extends MotionFXBase {
  run() {
    if (pageYOffset === this.windowScrollTop) {
      return false;
    }

    this.onScrollMovement();
    this.windowScrollTop = pageYOffset;
  }

  onScrollMovement() {
    this.updateMotionFxDimensions();
    this.updateAnimation();
  }

  updateMotionFxDimensions() {
    var motionFXSettings = this.motionFX.getSettings();

    if (motionFXSettings.refreshDimensions) {
      this.motionFX.defineDimensions();
    }
  }

  updateAnimation() {
    var passedRangePercents =
      "page" === this.motionFX.getSettings("range")
        ? ScrollHandler.getPageScrollPercentage()
        : ScrollHandler.getElementViewportPercentage(
            this.motionFX.elements.$parent
          );
    this.runCallback(passedRangePercents);
  }
}
