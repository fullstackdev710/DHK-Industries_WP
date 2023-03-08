import TimingBase from "./base";

export class PageViews extends TimingBase {
  getName() {
    return "page_views";
  }

  check() {
    var pageViews = elementorFrontend.storage.get("pageViews"),
      name = this.getName();
    var initialPageViews = this.document.getStorage(name + "_initialPageViews");

    if (!initialPageViews) {
      this.document.setStorage(name + "_initialPageViews", pageViews);
      initialPageViews = pageViews;
    }

    return pageViews - initialPageViews >= this.getTimingSetting("views");
  }
}

export default PageViews;
