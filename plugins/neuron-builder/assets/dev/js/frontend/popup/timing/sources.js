import TimingBase from "./base";

export class Sources extends TimingBase {
  getName() {
    return "sources";
  }

  check() {
    var sources = this.getTimingSetting("sources");

    if (3 === sources.length) {
      return true;
    }

    var referrer = document.referrer.replace(/https?:\/\/(?:www\.)?/, ""),
      isInternal = 0 === referrer.indexOf(location.host.replace("www.", ""));

    if (isInternal) {
      return -1 !== sources.indexOf("internal");
    }

    if (-1 !== sources.indexOf("external")) {
      return true;
    }

    if (-1 !== sources.indexOf("search")) {
      return /\.(google|yahoo|bing|yandex|baidu)\./.test(referrer);
    }

    return false;
  }
}

export default Sources;
