import TriggersBase from "./base";

export class Scrolling extends TriggersBase {
  checkScroll = Scrolling.prototype.checkScroll.bind(this);
  lastScrollOffset = 0;

  getName() {
    return "scrolling";
  }

  checkScroll() {
    var scrollDirection = scrollY > this.lastScrollOffset ? "down" : "up",
      requestedDirection = this.getTriggerSetting("direction");
    this.lastScrollOffset = scrollY;

    if (scrollDirection !== requestedDirection) {
      return;
    }

    if ("up" === scrollDirection) {
      this.callback();
      return;
    }

    var fullScroll =
        elementorFrontend.elements.$document.height() - innerHeight,
      scrollPercent = (scrollY / fullScroll) * 100;

    if (scrollPercent >= this.getTriggerSetting("offset")) {
      this.callback();
    }
  }

  run() {
    elementorFrontend.elements.$window.on("scroll", this.checkScroll);
  }

  destroy() {
    elementorFrontend.elements.$window.off("scroll", this.checkScroll);
  }
}

export default Scrolling;
