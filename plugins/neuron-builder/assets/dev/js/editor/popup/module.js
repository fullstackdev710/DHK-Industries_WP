import PopupComponent from "./component";

export default class PopupModule extends elementorModules.editor.utils.Module {
  displaySettingsTypes = {
    triggers: {
      icon: "eicon-click"
    },
    timing: {
      icon: "eicon-cog"
    }
  };

  onElementorLoaded() {
    this.component = $e.components.register(
      new PopupComponent({
        manager: this
      })
    );
  }
}
