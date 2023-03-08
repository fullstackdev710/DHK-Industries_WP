import TriggersBase from './base';

export class ScrollingTo extends TriggersBase {
  getName() {
    return 'scrolling_to';
  }

  run() {
    var $targetElement;

    try {
      $targetElement = jQuery(this.getTriggerSetting('selector'));
    } catch (e) {
      return;
    }

    this.waypointInstance = elementorFrontend.waypoint($targetElement, this.callback)[0];
  }

  destroy() {
    if (this.waypointInstance) {
      this.waypointInstance.destroy();
    }
  }
}

export default ScrollingTo;