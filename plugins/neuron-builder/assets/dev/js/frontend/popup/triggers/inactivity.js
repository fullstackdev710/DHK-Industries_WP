import TriggersBase from "./base";

export class InActivity extends TriggersBase {
  restartTimer = InActivity.prototype.restartTimer.bind(this);

  getName() {
    return "inactivity";
  }

  run() {
    this.startTimer();
    elementorFrontend.elements.$document.on(
      "keypress mousemove",
      this.restartTimer
    );
  }

  startTimer() {
    this.timeOut = setTimeout(
      this.callback,
      this.getTriggerSetting("time") * 1000
    );
  }

  clearTimer() {
    clearTimeout(this.timeOut);
  }

  restartTimer() {
    this.clearTimer();
    this.startTimer();
  }

  destroy() {
    this.clearTimer();
    elementorFrontend.elements.$document.off(
      "keypress mousemove",
      this.restartTimer
    );
  }
}

export default InActivity;
