export class CustomAssetsBase extends elementorModules.ViewModule {
  showAlertDialog(id, message) {
    var onConfirm =
      arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
    var onHide =
      arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
    var alertData = {
      id: id,
      message: message,
    };

    if (onConfirm) {
      alertData.onConfirm = onConfirm;
    }

    if (onHide) {
      alertData.onHide = onHide;
    }

    if (!this.alertWidget) {
      this.alertWidget = elementorCommon.dialogsManager.createWidget(
        "alert",
        alertData
      );
    }

    this.alertWidget.show();
  }

  onDialogDismiss() {
    this.elements.$publishButton.removeClass("disabled");

    this.elements.$publishButtonSpinner.removeClass("is-active");
  }

  handleSubmit(event) {
    var _this = this;

    if (this.fileWasUploaded) {
      return;
    }

    var hasValue = this.checkInputsForValues();

    if (hasValue) {
      this.fileWasUploaded = true;
      this.elements.$postForm.submit();
      return;
    }

    event.preventDefault();

    this.showAlertDialog(
      "noData",
      this.getSettings("notice"),
      function () {
        return _this.onDialogDismiss();
      },
      function () {
        return _this.onDialogDismiss();
      }
    );
    return false;
  }

  bindEvents() {
    this.elements.$postForm.on("submit", this.handleSubmit.bind(this));
  }
}

export default CustomAssetsBase;
