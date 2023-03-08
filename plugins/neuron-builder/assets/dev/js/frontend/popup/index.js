import PopupDocument from "./document";

export class Popup extends elementorModules.Module {
  constructor() {
    super();

    var self = this;

    elementorFrontend.hooks.addAction(
      "elementor/frontend/documents-manager/init-classes",
      self.addDocumentClass
    );

    elementorFrontend.hooks.addAction(
      "frontend/element_ready/neuron-form.default",
      require("./form-actions")
    );

    elementorFrontend.on("components:init", function () {
      return self.onElementorFrontendComponentsInit();
    });

    if (
      !elementorFrontend.isEditMode() &&
      !elementorFrontend.isWPPreviewMode()
    ) {
      this.setViewsAndSessions();
    }
  }

  addDocumentClass(documentsManager) {
    documentsManager.addDocumentClass("popup", PopupDocument);
  }

  setViewsAndSessions() {
    var pageViews = elementorFrontend.storage.get("pageViews") || 0;

    elementorFrontend.storage.set("pageViews", pageViews + 1);
    var activeSession = elementorFrontend.storage.get("activeSession", {
      session: true,
    });

    if (!activeSession) {
      elementorFrontend.storage.set("activeSession", true, {
        session: true,
      });

      var sessions = elementorFrontend.storage.get("sessions") || 0;
      elementorFrontend.storage.set("sessions", sessions + 1);
    }
  }

  showPopup(settings) {
    var popup = elementorFrontend.documentsManager.documents[settings.id];

    if (!popup) {
      return;
    }

    var modal = popup.getModal();

    if (settings.toggle && modal.isVisible()) {
      modal.hide();
    } else {
      popup.showModal();
    }
  }

  closePopup(settings, event) {
    var popupID = jQuery(event.target)
      .parents('[data-elementor-type="popup"]')
      .data("elementorId");

    if (!popupID) {
      return;
    }

    var document = elementorFrontend.documentsManager.documents[popupID];

    document.getModal().hide();

    if (settings.do_not_show_again) {
      document.disable();
    }
  }

  onElementorFrontendComponentsInit() {
    elementorFrontend.utils.urlActions.addAction(
      "popup:open",
      this.showPopup.bind(this)
    );
    elementorFrontend.utils.urlActions.addAction(
      "popup:close",
      this.closePopup.bind(this)
    );
  }
}

export default Popup;
