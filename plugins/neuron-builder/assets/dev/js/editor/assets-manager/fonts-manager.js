module.exports = elementorModules.Module.extend({
  _enqueuedFonts: [],
  _enqueuedTypekit: false,

  onFontChange: function onFontChange(fontType, font) {
    if ("custom" !== fontType && "typekit" !== fontType) {
      return;
    }

    if (-1 !== this._enqueuedFonts.indexOf(font)) {
      return;
    }

    if ("typekit" === fontType && this._enqueuedTypekit) {
      return;
    }

    this.getCustomFont(fontType, font);
  },
  getCustomFont: function getCustomFont(fontType, font) {
    neuron.ajax.addRequest("assets_manager_panel_action_data", {
      unique_id: "font_" + fontType + font,
      data: {
        service: "font",
        type: fontType,
        font: font,
      },
      success: function success(data) {
        if (data.font_face) {
          elementor.$previewContents
            .find("style:last")
            .after('<style type="text/css">' + data.font_face + "</style>");
        }

        if (data.font_url) {
          elementor.$previewContents
            .find("link:last")
            .after(
              '<link href="' +
                data.font_url +
                '" rel="stylesheet" type="text/css">'
            );
        }
      },
    });

    this._enqueuedFonts.push(font);

    if ("typekit" === fontType) {
      this._enqueuedTypekit = true;
    }
  },
  onInit: function onInit() {
    elementor.channels.editor.on(
      "font:insertion",
      this.onFontChange.bind(this)
    );
  },
});
