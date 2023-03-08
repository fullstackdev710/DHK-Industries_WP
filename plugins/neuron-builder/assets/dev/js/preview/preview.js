class NeuronPreview extends elementorModules.ViewModule {
  __construct(args) {
    var _this = this;
    super.__construct.apply(self, args);

    elementorFrontend.on("components:init", function () {
      return _this.onFrontendComponentsInit();
    });
  }

  createDocumentsHandles() {
    var _this = this;

    jQuery.each(
      elementorFrontend.documentsManager.documents,
      function (index, document) {
        var $documentElement = document.$element;

        if ($documentElement.hasClass("elementor-edit-mode")) {
          return;
        }

        var $existingHandle = document.$element.children(
          ".elementor-document-handle"
        );

        if ($existingHandle.length) {
          return;
        }

        var $handle = jQuery("<div>", {
            class: "elementor-document-handle",
          }),
          $handleIcon = jQuery("<i>", {
            class: "eicon-edit",
          }),
          documentTitle = $documentElement.data("elementor-title"),
          $handleTitle = jQuery("<div>", {
            class: "elementor-document-handle__title",
          }).text(neuron.translate("edit_element", [documentTitle]));

        $handle.append($handleIcon, $handleTitle);

        $handle.on("click", function () {
          return _this.onDocumentHandleClick(document);
        });

        $documentElement.prepend($handle);
      }
    );
  }

  onDocumentHandleClick(document) {
    elementorCommon.api.internal("panel/state-loading");
    elementorCommon.api
      .run("editor/documents/switch", {
        id: document.getSettings("id"),
      })
      .finally(function () {
        return elementorCommon.api.internal("panel/state-ready");
      });
  }

  onFrontendComponentsInit() {
    var _this = this;

    this.createDocumentsHandles();
    elementor.on("document:loaded", function () {
      return _this.createDocumentsHandles();
    });
  }
}

window.neuronPreview = new NeuronPreview();
