import CustomAssets from "./custom-assets";

export class CustomFontsManager extends CustomAssets {
  getDefaultSettings() {
    return {
      fields: {
        upload: require("./uploader"),
        repeater: require("./repeater"),
      },
      selectors: {
        editPageClass: "post-type-neuron_font",
        title: "#title",
        repeaterBlock: ".repeater-block",
        repeaterTitle: ".repeater-title",
        removeRowBtn: ".remove-repeater-row",
        editRowBtn: ".toggle-repeater-row",
        closeRowBtn: ".close-repeater-row",
        styleInput: ".font_style",
        weightInput: ".font_weight",
        customFontsMetaBox: "#neuron-font-custommetabox",
        closeHandle: "button.handlediv",
        toolbar: ".elementor-field-toolbar",
        inlinePreview: ".inline-preview",
        fileUrlInput: '.elementor-field-file input[type="text"]',
        postForm: "#post",
        publishButton: "#publish",
        publishButtonSpinner: "#publishing-action > .spinner",
      },
      notice: NeuronConfig.i18n.fontsUploadEmptyNotice,
      fontLabelTemplate:
        '<ul class="row-font-label">' +
        '<li class="row-font-weight">{{weight}}</li>' +
        '<li class="row-font-style">{{style}}</li>' +
        '<li class="row-font-preview">{{preview}}</li>' +
        "{{toolbar}}" +
        "</ul>",
    };
  }

  getDefaultElements() {
    var selectors = this.getSettings("selectors");
    return {
      $postForm: jQuery(selectors.postForm),
      $publishButton: jQuery(selectors.publishButton),
      $publishButtonSpinner: jQuery(selectors.publishButtonSpinner),
      $closeHandle: jQuery(selectors.closeHandle),
      $customFontsMetaBox: jQuery(selectors.customFontsMetaBox),
      $title: jQuery(selectors.title),
    };
  }

  renderTemplate(tpl, data) {
    var re = /{{([^}}]+)?}}/g;
    var match;

    while ((match = re.exec(tpl))) {
      tpl = tpl.replace(match[0], data[match[1]]);
    }

    return tpl;
  }

  ucFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  getPreviewStyle($table) {
    var selectors = this.getSettings("selectors"),
      fontFamily = this.elements.$title.val(),
      style = $table
        .find("select" + selectors.styleInput)
        .first()
        .val(),
      weight = $table
        .find("select" + selectors.weightInput)
        .first()
        .val();

    return {
      style: this.ucFirst(style),
      weight: this.ucFirst(weight),
      styleAttribute:
        "font-family: " +
        fontFamily +
        " ;font-style: " +
        style +
        "; font-weight: " +
        weight +
        ";",
    };
  }

  updateRowLabel(event, $table) {
    var selectors = this.getSettings("selectors"),
      fontLabelTemplate = this.getSettings("fontLabelTemplate"),
      $block = $table.closest(selectors.repeaterBlock),
      $deleteBtn = $block.find(selectors.removeRowBtn).first(),
      $editBtn = $block.find(selectors.editRowBtn).first(),
      $closeBtn = $block.find(selectors.closeRowBtn).first(),
      $toolbar = $table.find(selectors.toolbar).last().clone(),
      previewStyle = this.getPreviewStyle($table);

    if ($editBtn.length > 0) {
      $editBtn.not(selectors.toolbar + " " + selectors.editRowBtn).remove();
    }

    if ($closeBtn.length > 0) {
      $closeBtn.not(selectors.toolbar + " " + selectors.closeRowBtn).remove();
    }

    if ($deleteBtn.length > 0) {
      $deleteBtn.not(selectors.toolbar + " " + selectors.removeRowBtn).remove();
    }

    var toolbarHtml = jQuery('<li class="row-font-actions">').append(
      $toolbar
    )[0].outerHTML;

    return this.renderTemplate(fontLabelTemplate, {
      weight: '<span class="label">Weight:</span>' + previewStyle.weight,
      style: '<span class="label">Style:</span>' + previewStyle.style,
      preview:
        '<span style="' +
        previewStyle.styleAttribute +
        '">The quick brown fox jumps over the lazy dog</span>',
      toolbar: toolbarHtml,
    });
  }

  onRepeaterToggleVisible(event, $btn, $table) {
    var selectors = this.getSettings("selectors"),
      $previewElement = $table.find(selectors.inlinePreview),
      previewStyle = this.getPreviewStyle($table);
    $previewElement.attr("style", previewStyle.styleAttribute);
  }

  onRepeaterNewRow(event, $btn, $block) {
    var selectors = this.getSettings("selectors");
    $block.find(selectors.removeRowBtn).first().remove();
    $block.find(selectors.editRowBtn).first().remove();
    $block.find(selectors.closeRowBtn).first().remove();
  }

  maybeToggle(event) {
    event.preventDefault();
    var selectors = this.getSettings("selectors");

    if (
      jQuery(this).is(":visible") &&
      !jQuery(event.target).hasClass(selectors.editRowBtn)
    ) {
      jQuery(this).find(selectors.editRowBtn).click();
    }
  }

  onInputChange(event) {
    var $el = jQuery(event.target).next(),
      fields = this.getSettings("fields");
    fields.upload.setFields($el);
    fields.upload.setLabels($el);
    fields.upload.replaceButtonClass($el);
  }

  bindEvents() {
    var selectors = this.getSettings("selectors");

    jQuery(document)
      .on("repeaterComputedLabel", this.updateRowLabel.bind(this))
      .on("onRepeaterToggleVisible", this.onRepeaterToggleVisible.bind(this))
      .on("onRepeaterNewRow", this.onRepeaterNewRow.bind(this))
      .on("click", selectors.repeaterTitle, this.maybeToggle.bind(this))
      .on("input", selectors.fileUrlInput, this.onInputChange.bind(this));

    elementorModules.ViewModule.prototype.bindEvents.apply(this, arguments);
  }

  checkInputsForValues() {
    var selectors = this.getSettings("selectors");
    var hasValue = false; // Check the file inputs for a value

    jQuery(selectors.fileUrlInput).each(function (index, element) {
      if ("" !== jQuery(element).val()) {
        hasValue = true;
        return false; // If a value was found, break the loop
      }
    });
    return hasValue;
  }

  removeCloseHandle() {
    this.elements.$closeHandle.remove();
    this.elements.$customFontsMetaBox
      .removeClass("closed")
      .removeClass("postbox");
  }

  titleRequired() {
    this.elements.$title.prop("required", true);
  }

  onInit() {
    var settings = this.getSettings();

    if (!jQuery("body").hasClass(settings.selectors.editPageClass)) {
      return;
    }

    elementorModules.ViewModule.prototype.onInit.apply(this, arguments);

    this.removeCloseHandle();
    this.titleRequired();

    settings.fields.upload.init();
    settings.fields.repeater.init();
  }
}

export default CustomFontsManager;
