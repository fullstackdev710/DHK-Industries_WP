import ScrollHandler from "./scroll-handler";

export class MotionFXBase extends elementorModules.ViewModule {
  __construct(options) {
    this.motionFX = options.motionFX;

    if (!this.intersectionObservers) {
      this.setElementInViewportObserver();
    }
  }

  setElementInViewportObserver() {
    var _this = this;

    this.intersectionObserver = ScrollHandler.scrollObserver({
      callback: function callback(event) {
        if (event.isInViewport) {
          _this.onInsideViewport();
        } else {
          _this.removeAnimationFrameRequest();
        }
      },
    });

    this.intersectionObserver.observe(this.motionFX.elements.$parent[0]);
  }

  runCallback() {
    var callback = this.getSettings("callback");
    callback.apply(void 0, arguments);
  }

  removeIntersectionObserver() {
    if (this.intersectionObserver) {
      this.intersectionObserver.unobserve(this.motionFX.elements.$parent[0]);
    }
  }

  removeAnimationFrameRequest() {
    if (this.animationFrameRequest) {
      cancelAnimationFrame(this.animationFrameRequest);
    }
  }

  destroy() {
    this.removeAnimationFrameRequest();
    this.removeIntersectionObserver();
  }

  onInit() {
    super.onInit();
  }
}

MotionFXBase.prototype.onInsideViewport = function () {
  this.run();

  this.animationFrameRequest = requestAnimationFrame(this.onInsideViewport);
};

export default MotionFXBase;
