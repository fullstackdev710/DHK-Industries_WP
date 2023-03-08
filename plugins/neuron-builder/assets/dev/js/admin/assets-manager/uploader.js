module.exports = {
  $btn: null,
  fileId: null,
  fileUrl: null,
  fileFrame: [],
  selectors: {
    uploadBtnClass: "elementor-upload-btn",
    clearBtnClass: "elementor-upload-clear-btn",
    uploadBtn: ".elementor-upload-btn",
    clearBtn: ".elementor-upload-clear-btn",
    inputURLField: '.elementor-field-file input[type="text"]',
  },
  hasValue: function hasValue() {
    return "" !== jQuery(this.fileUrl).val();
  },
  setLabels: function setLabels($el) {
    if (!this.hasValue()) {
      $el.val($el.data("upload_text"));
    } else {
      $el.val($el.data("remove_text"));
    }
  },
  setFields: function setFields(el) {
    var self = this;
    self.fileUrl = jQuery(el).prev();
    self.fileId = jQuery(self.fileUrl).prev();
  },
  setUploadParams: function setUploadParams(ext, name) {
    var uploader = this.fileFrame[name].uploader.uploader;
    uploader.param("uploadType", ext);
    uploader.param("uploadTypeCaller", "elementor-admin-font-upload");
    uploader.param("post_id", this.getPostId());
  },
  setUploadMimeType: function setUploadMimeType(frame, ext) {
    // Set {ext} as only allowed upload extensions
    var oldExtensions =
        _wpPluploadSettings.defaults.filters.mime_types[0].extensions,
      self = this;
    frame.on("ready", function () {
      _wpPluploadSettings.defaults.filters.mime_types[0].extensions = ext;
    });
    frame.on("close", function () {
      // restore allowed upload extensions
      _wpPluploadSettings.defaults.filters.mime_types[0].extensions = oldExtensions;
      self.replaceButtonClass(self.$btn);
    });
  },
  replaceButtonClass: function replaceButtonClass(el) {
    if (this.hasValue()) {
      jQuery(el)
        .removeClass(this.selectors.uploadBtnClass)
        .addClass(this.selectors.clearBtnClass);
    } else {
      jQuery(el)
        .removeClass(this.selectors.clearBtnClass)
        .addClass(this.selectors.uploadBtnClass);
    }

    this.setLabels(el);
  },
  uploadFile: function uploadFile(el) {
    var _this = this;

    var self = this,
      $el = jQuery(el),
      mime = $el.attr("data-mime_type") || "",
      ext = $el.attr("data-ext") || false,
      name = $el.attr("id"); // If the media frame already exists, reopen it.

    if ("undefined" !== typeof self.fileFrame[name]) {
      if (ext) {
        self.setUploadParams(ext, name);
      }

      self.fileFrame[name].open();
      return;
    } // Create the media frame.

    self.fileFrame[name] = wp.media({
      library: {
        type: mime.split(","),
      },
      title: $el.data("box_title"),
      button: {
        text: $el.data("box_action"),
      },
      multiple: false,
    }); // When an file is selected, run a callback.

    self.fileFrame[name].on("select", function () {
      // We set multiple to false so only get one image from the uploader
      var attachment = self.fileFrame[name]
        .state()
        .get("selection")
        .first()
        .toJSON(); // Do something with attachment.id and/or attachment.url here

      jQuery(self.fileId).val(attachment.id);
      jQuery(self.fileUrl).val(attachment.url);
      self.replaceButtonClass(el);
      self.updatePreview(el);
    });
    self.fileFrame[name].on("open", function () {
      var selectedId = _this.fileId.val();

      if (!selectedId) {
        return;
      }

      var selection = self.fileFrame[name].state().get("selection");
      selection.add(wp.media.attachment(selectedId));
    });
    self.setUploadMimeType(self.fileFrame[name], ext); // Finally, open the modal

    self.fileFrame[name].open();

    if (ext) {
      self.setUploadParams(ext, name);
    }
  },
  updatePreview: function updatePreview(el) {
    var self = this,
      $ul = jQuery(el).parent().find("ul"),
      $li = jQuery("<li>"),
      showUrlType = jQuery(el).data("preview_anchor") || "full";
    $ul.html("");

    if (self.hasValue() && "none" !== showUrlType) {
      var anchor = jQuery(self.fileUrl).val();

      if ("full" !== showUrlType) {
        anchor = anchor.substring(anchor.lastIndexOf("/") + 1);
      }

      $li.html(
        '<a href="' +
          jQuery(self.fileUrl).val() +
          '" download>' +
          anchor +
          "</a>"
      );
      $ul.append($li);
    }
  },
  setup: function setup() {
    var self = this;
    jQuery(self.selectors.uploadBtn + ", " + self.selectors.clearBtn).each(
      function () {
        self.setFields(jQuery(this));
        self.updatePreview(jQuery(this));
        self.setLabels(jQuery(this));
        self.replaceButtonClass(jQuery(this));
      }
    );
  },
  getPostId: function getPostId() {
    return jQuery("#post_ID").val();
  },
  handleUploadClick: function handleUploadClick(event) {
    event.preventDefault();
    var $element = jQuery(event.target);

    if ("text" === $element.attr("type")) {
      return $element
        .next()
        .removeClass(this.selectors.clearBtnClass)
        .addClass(this.selectors.uploadBtnClass)
        .click();
    }

    this.$btn = $element;
    this.setFields($element);
    this.uploadFile($element);
  },
  init: function init() {
    var self = this;

    var self = this,
      _this$selectors = this.selectors,
      uploadBtn = _this$selectors.uploadBtn,
      inputURLField = _this$selectors.inputURLField,
      clearBtn = _this$selectors.clearBtn,
      handleUpload = function handleUpload(event) {
        return self.handleUploadClick(event);
      };

    jQuery(document).on("click", uploadBtn, handleUpload);
    jQuery(document).on("click", inputURLField, function (event) {
      if ("" !== event.target.value) {
        handleUpload(event);
      }
    });
    jQuery(document).on("click", clearBtn, function (event) {
      event.preventDefault();
      var $element = jQuery(this);
      self.setFields($element);
      jQuery(self.fileUrl).val("");
      jQuery(self.fileId).val("");
      self.updatePreview($element);
      self.replaceButtonClass($element);
    });
    this.setup();
    jQuery(document).on("onRepeaterNewRow", function () {
      self.setup();
    });
  },
};
