import TriggersBase from "./base";

export class ExitIntent extends TriggersBase {
  detectExitIntent = ExitIntent.prototype.detectExitIntent.bind(this);

  getName() {
    return "exit_intent";
  }

  detectExitIntent(event) {
    if (event.clientY <= 0) {
      this.callback();
    }
  }

  run() {
    elementorFrontend.elements.$window.on("mouseleave", this.detectExitIntent);
  }

  destroy() {
    elementorFrontend.elements.$window.off("mouseleave", this.detectExitIntent);
  }
}

export default ExitIntent;
