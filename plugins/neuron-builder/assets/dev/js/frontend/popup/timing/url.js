import TimingBase from "./base";

export class Url extends TimingBase {
  getName() {
    return "url";
  }

  check() {
    var url = this.getTimingSetting("url"),
      action = this.getTimingSetting("action"),
      referrer = document.referrer;

    if ("regex" !== action) {
      return ("hide" === action) ^ (-1 !== referrer.indexOf(url));
    }

    var regexp;

    try {
      regexp = new RegExp(url);
    } catch (e) {
      return false;
    }

    return regexp.test(referrer);
  }
}

export default Url;
