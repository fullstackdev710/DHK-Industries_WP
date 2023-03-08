import TriggersBase from "./base";

export class Click extends TriggersBase {
  checkClick = Click.prototype.checkClick.bind(this);
  clicksCount = 0;

  getName() {
    return "click";
  }

  checkClick() {
    this.clicksCount++;

    if (this.clicksCount === this.getTriggerSetting("times")) {
      this.callback();
    }
  }

  run() {
    elementorFrontend.elements.$body.on("click", this.checkClick);
  }

  destroy() {
    elementorFrontend.elements.$body.off("click", this.checkClick);
  }
}

export default Click;
