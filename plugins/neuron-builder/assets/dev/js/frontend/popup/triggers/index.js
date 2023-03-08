import PageLoad from "./page-load";
import Scrolling from "./scrolling";
import ScrollingTo from "./scrolling-to";
import Click from "./click";
import InActivity from "./inactivity";
import ExitIntent from "./exit_intent";

export class PopupTriggers extends elementorModules.Module {
  constructor(settings, document) {
    super(settings);

    this.document = document;

    this.triggers = [];

    this.triggerClasses = {
      page_load: PageLoad,
      scrolling: Scrolling,
      scrolling_to: ScrollingTo,
      click: Click,
      inactivity: InActivity,
      exit_intent: ExitIntent
    };

    this.runTriggers();

    return this;
  }

  runTriggers() {
    var _this = this;

    var settings = this.getSettings();

    jQuery.each(this.triggerClasses, function(key, TriggerClass) {
      if (!settings[key]) {
        return;
      }

      var trigger = new TriggerClass(settings, function() {
        return _this.onTriggerFired();
      });

      trigger.run();

      _this.triggers.push(trigger);
    });
  }

  destroyTriggers() {
    this.triggers.forEach(function(trigger) {
      return trigger.destroy();
    });
    this.triggers = [];
  }

  onTriggerFired() {
    this.document.showModal(true);
    this.destroyTriggers();
  }
}

export default PopupTriggers;
