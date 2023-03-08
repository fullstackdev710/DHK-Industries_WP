export class TimingBase extends elementorModules.Module {
  constructor(settings, document) {
    super(settings);

    this.document = document;
  }

  getTimingSetting(settingKey) {
    return this.getSettings(this.getName() + "_" + settingKey);
  }
}

export default TimingBase;
