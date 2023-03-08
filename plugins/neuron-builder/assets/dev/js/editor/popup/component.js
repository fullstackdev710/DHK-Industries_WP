import * as hooks from "./hooks/";

export default class PopupComponent extends $e.modules.ComponentBase {
  getNamespace() {
    return "document/popup";
  }
  defaultHooks() {
    return this.importHooks(hooks);
  }
}
