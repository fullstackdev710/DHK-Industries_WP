import TimingBase from "./base";

export class Sessions extends TimingBase {
  getName() {
    return "sessions";
  }

  check() {
    var sessions = elementorFrontend.storage.get("sessions"),
      name = this.getName();
    var initialSessions = this.document.getStorage(name + "_initialSessions");

    if (!initialSessions) {
      this.document.setStorage(name + "_initialSessions", sessions);
      initialSessions = sessions;
    }

    return sessions - initialSessions >= this.getTimingSetting("sessions");
  }
}

export default Sessions;
