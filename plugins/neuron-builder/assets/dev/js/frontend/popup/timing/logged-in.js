import TimingBase from "./base";

export class LoggedIn extends TimingBase {
  getName() {
    return "logged_in";
  }

  check() {
    var userConfig = elementorFrontend.config.user;

    if (!userConfig) {
      return true;
    }

    if ("all" === this.getTimingSetting("users")) {
      return false;
    }

    var userRolesInHideList = this.getTimingSetting("roles").filter(function(
      role
    ) {
      return -1 !== userConfig.roles.indexOf(role);
    });
    return !userRolesInHideList.length;
  }
}

export default LoggedIn;
