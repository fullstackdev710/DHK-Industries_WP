import TimingBase from "./base";

export class Times extends TimingBase {
  getName() {
    return "times";
  }

  check() {
    var displayTimes = this.document.getStorage("times") || 0;
    return this.getTimingSetting("times") > displayTimes;
  }
}

export default Times;
