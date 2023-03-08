import TriggersBase from "./base";

export class PageLoad extends TriggersBase {
  getName() {
    return "page_load";
  }

  run() {
    this.timeout = setTimeout(
      this.callback,
      this.getTriggerSetting("delay") * 1000
    );
  }

  destroy() {
    clearTimeout(this.timeout);
  }
}

export default PageLoad;
