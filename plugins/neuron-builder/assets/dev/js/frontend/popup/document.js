import PopupTiming from "./timing/";
import PopupTriggers from "./triggers/";
import "babel-polyfill";

export class PopupDocument extends elementorModules.frontend.Document {
  bindEvents() {
    var openSelector = this.getDocumentSettings("open_selector");

    if (openSelector) {
      elementorFrontend.elements.$body.on(
        "click",
        openSelector,
        this.showModal.bind(this)
      );
    }
  }

  startTiming() {
    var timing = new PopupTiming(this.getDocumentSettings("timing"), this);

    if (timing.check()) {
      this.initTriggers();
    }
  }

  initTriggers() {
    this.triggers = new PopupTriggers(
      this.getDocumentSettings("triggers"),
      this
    );
  }

  showModal(avoidMultiple) {
    var settings = this.getDocumentSettings();

    if (!this.isEdit) {
      if (!elementorFrontend.isWPPreviewMode()) {
        if (this.getStorage("disable")) {
          return;
        }

        if (
          avoidMultiple &&
          neuronFrontend.modules.popup.popupPopped &&
          settings.avoid_multiple_popups
        ) {
          return;
        }
      }

      this.$element = jQuery(this.elementHTML);
      this.elements.$elements = this.$element.find(
        this.getSettings("selectors.elements")
      );
    }

    var modal = this.getModal(),
      $closeButton = modal.getElements("closeButton");

    modal.setMessage(this.$element).show();

    if (!this.isEdit) {
      if (settings.close_button_delay) {
        $closeButton.hide();
        clearTimeout(this.closeButtonTimeout);
        this.closeButtonTimeout = setTimeout(function () {
          return $closeButton.show();
        }, settings.close_button_delay * 1000);
      }

      super.runElementsHandlers();
    }

    this.setEntranceAnimation();

    if (!settings.timing || !settings.timing.times_count) {
      this.countTimes();
    }

    neuronFrontend.modules.popup.popupPopped = true;
  }

  setEntranceAnimation() {
    var $widgetContent = this.getModal().getElements("widgetContent"),
      settings = this.getDocumentSettings(),
      newAnimation = elementorFrontend.getCurrentDeviceSetting(
        settings,
        "entrance_animation"
      );

    if (this.currentAnimation) {
      $widgetContent.removeClass(this.currentAnimation);
    }

    this.currentAnimation = newAnimation;

    if (!newAnimation) {
      return;
    }

    // Overlay
    if (
      $widgetContent.closest(".dialog-widget").find(".dialog-overlay").length ==
        0 &&
      settings.overlay == "yes"
    ) {
      var overlay = '<div class="dialog-overlay"></div>',
        $location = $widgetContent.closest(".dialog-widget");
      jQuery(overlay).hide().appendTo($location).show();
    }

    $widgetContent
      .closest(".dialog-widget")
      .addClass("neuron-popup-modal--overlay-animation");

    var animationDuration = settings.entrance_animation_duration.size;

    $widgetContent.addClass(newAnimation);

    setTimeout(function () {
      return $widgetContent.removeClass(newAnimation);
    }, animationDuration * 1000);
  }

  setExitAnimation() {
    var self = this;

    var modal = this.getModal(),
      settings = this.getDocumentSettings(),
      $widgetContent = modal.getElements("widgetContent"),
      newAnimation = elementorFrontend.getCurrentDeviceSetting(
        settings,
        "exit_animation"
      ),
      animationDuration = newAnimation
        ? settings.entrance_animation_duration.size
        : 0;

    setTimeout(function () {
      if (newAnimation) {
        $widgetContent.removeClass(
          newAnimation + " h-neuron-animation--reverse"
        );
      }

      if (!self.isEdit) {
        self.$element.remove();

        modal.getElements("widget").addClass("neuron-popup-modal-hide");

        setTimeout(() => {
          modal.getElements("widget").hide();
          modal
            .getElements("widget")
            .removeClass(
              "neuron-popup-modal-hide neuron-popup-modal--overlay-animation"
            );
        }, animationDuration * 1000 + 1);
      }
    }, animationDuration * 1000);

    if (newAnimation) {
      $widgetContent.addClass(newAnimation + " h-neuron-animation--reverse");
    }
  }

  initModal() {
    var self = this;

    var modal;

    this.getModal = () => {
      if (!modal) {
        var settings = self.getDocumentSettings(),
          id = self.getSettings("id"),
          triggerPopupEvent = function triggerPopupEvent(eventType) {
            return elementorFrontend.elements.$document.trigger(
              "elementor/popup/" + eventType,
              [id, self]
            );
          };

        var classes = "neuron-popup-modal";

        if (settings.classes) {
          classes += " " + settings.classes;
        }

        const modalProperties = {
          id: "neuron-popup-modal-" + id,
          className: classes,
          closeButton: true,
          closeButtonClass: "n-icon-close",
          preventScroll: settings.prevent_scroll,
          onShow: function onShow() {
            return triggerPopupEvent("show");
          },
          onHide: function onHide() {
            return triggerPopupEvent("hide");
          },
          effects: {
            hide: function hide() {
              if (settings.timing && settings.timing.times_count) {
                self.countTimes();
              }

              self.setExitAnimation();
            },
            show: "show",
          },
          hide: {
            auto: !!settings.close_automatically,
            autoDelay: settings.close_automatically * 1000,
            onBackgroundClick: !settings.prevent_close_on_background_click,
            onOutsideClick: !settings.prevent_close_on_background_click,
            onEscKeyPress: !settings.prevent_close_on_esc_key,
            ignore: ".flatpickr-calendar",
          },
          position: {
            enable: false,
          },
        };

        modal = elementorFrontend
          .getDialogsManager()
          .createWidget("lightbox", modalProperties);

        modal.getElements("widgetContent").addClass("animated");

        var $closeButton = modal.getElements("closeButton");

        if (self.isEdit) {
          $closeButton.off("click");

          modal.hide = function () {};
        }

        self.setCloseButtonPosition();
      }

      return modal;
    };
  }

  setCloseButtonPosition() {
    var modal = this.getModal(),
      closeButtonPosition = this.getDocumentSettings("close_button_position"),
      $closeButton = modal.getElements("closeButton");
    $closeButton.appendTo(
      modal.getElements(
        "outside" === closeButtonPosition ? "widget" : "widgetContent"
      )
    );
  }

  disable() {
    this.setStorage("disable", true);
  }

  setStorage(key, value, options) {
    elementorFrontend.storage.set(
      "popup_".concat(this.getSettings("id"), "_").concat(key),
      value,
      options
    );
  }

  getStorage(key, options) {
    return elementorFrontend.storage.get(
      "popup_".concat(this.getSettings("id"), "_").concat(key),
      options
    );
  }

  countTimes() {
    var displayTimes = this.getStorage("times") || 0;
    this.setStorage("times", displayTimes + 1);
  }

  runElementsHandlers() {}

  async onInit() {
    super.onInit();

    if (!window.DialogsManager) {
      await elementorFrontend.utils.assetsLoader.load("script", "dialog");
    }

    this.initModal();

    if (this.isEdit) {
      this.showModal();
      return;
    }

    this.$element.show().remove();

    this.elementHTML = this.$element[0].outerHTML;

    if (elementorFrontend.isEditMode()) {
      return;
    }

    if (
      elementorFrontend.isWPPreviewMode() &&
      elementorFrontend.config.post.id === this.getSettings("id")
    ) {
      this.showModal();
      return;
    }

    this.startTiming();
  }

  onSettingsChange(model) {
    var changedKey = Object.keys(model.changed)[0];

    if (-1 !== changedKey.indexOf("entrance_animation")) {
      this.setEntranceAnimation();
    }

    if ("exit_animation" === changedKey) {
      this.setExitAnimation();
    }

    if ("close_button_position" === changedKey) {
      this.setCloseButtonPosition();
    }
  }
}

export default PopupDocument;
