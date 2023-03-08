import MotionFXBase from "../base";

export default class MouseMoveInteraction extends MotionFXBase {
  bindEvents() {
    if (!MouseMoveInteraction.mouseTracked) {
      elementorFrontend.elements.$window.on(
        "mousemove",
        MouseMoveInteraction.updateMousePosition
      );
      MouseMoveInteraction.mouseTracked = true;
    }
  }

  run() {
    var mousePosition = MouseMoveInteraction.mousePosition,
      oldMousePosition = this.oldMousePosition;

    if (
      oldMousePosition.x === mousePosition.x &&
      oldMousePosition.y === mousePosition.y
    ) {
      return;
    }

    this.oldMousePosition = {
      x: mousePosition.x,
      y: mousePosition.y,
    };

    var passedPercentsX = (100 / innerWidth) * mousePosition.x,
      passedPercentsY = (100 / innerHeight) * mousePosition.y;
    this.runCallback(passedPercentsX, passedPercentsY);
  }

  onInit() {
    this.oldMousePosition = {};

    super.onInit();
  }
}

MouseMoveInteraction.mousePosition = {};

MouseMoveInteraction.updateMousePosition = function (event) {
  MouseMoveInteraction.mousePosition = {
    x: event.clientX,
    y: event.clientY,
  };
};
