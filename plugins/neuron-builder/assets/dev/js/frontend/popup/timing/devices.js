import TimingBase from "./base";

export class Devices extends TimingBase {
  getName() {
    return "devices";
  }

  check() {
    return (
      -1 !==
      this.getTimingSetting("devices").indexOf(
        elementorFrontend.getCurrentDeviceMode()
      )
    );
  }
}

export default Devices;
