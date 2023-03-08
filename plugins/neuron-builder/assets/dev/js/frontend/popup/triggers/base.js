export class TriggersBase extends elementorModules.Module {
  constructor(settings, callback) {
    super(settings);

    this.callback = callback;
  }

  getTriggerSetting(settingKey) {
    return this.getSettings(this.getName() + "_" + settingKey);
  }
}

export default TriggersBase;
