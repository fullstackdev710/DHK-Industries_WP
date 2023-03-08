export default class ThemeBuilderModalLayout extends elementorModules.common
  .views.modal.Layout {
  getModalOptions() {
    return {
      id: "neuron-publish__modal",
      hide: {
        onButtonClick: false,
      },
    };
  }

  getLogoOptions() {
    return {
      title: neuron.translate("publish_settings"),
    };
  }

  initModal() {
    super.initModal();

    this.modal.addButton({
      name: "publish",
      text: neuron.translate("save_and_close"),
      callback: function callback() {
        return $e.run("theme-builder-publish/save");
      },
    });

    this.modal.addButton({
      name: "next",
      text: neuron.translate("next"),
      callback: function callback() {
        return $e.run("theme-builder-publish/next");
      },
    });

    var $publishButton = this.modal.getElements("publish");

    this.modal
      .getElements("next")
      .addClass("elementor-button-success")
      .add($publishButton)
      .addClass("elementor-button")
      .removeClass("dialog-button");
  }
}
