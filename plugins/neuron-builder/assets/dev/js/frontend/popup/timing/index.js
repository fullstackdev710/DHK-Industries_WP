import PageViews from "./page-views";
import Sessions from "./sessions";
import Url from "./url";
import Sources from "./sources";
import LoggedIn from "./logged-in";
import Devices from "./devices";
import Times from "./times";

export class PopupTiming extends elementorModules.Module {
  constructor(settings, document) {
    super(settings);

    this.document = document;

    this.timingClasses = {
      page_views: PageViews,
      sessions: Sessions,
      url: Url,
      sources: Sources,
      logged_in: LoggedIn,
      devices: Devices,
      times: Times
    };
  }

  check() {
    var _this = this;

    var settings = this.getSettings();
    var checkPassed = true;

    jQuery.each(this.timingClasses, function(key, TimingClass) {
      if (!settings[key]) {
        return;
      }

      var timing = new TimingClass(settings, _this.document);

      if (!timing.check()) {
        checkPassed = false;
      }
    });

    return checkPassed;
  }
}

export default PopupTiming;
