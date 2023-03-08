const Form = elementorModules.editor.utils.Module.extend({
  onElementorInit: function onElementorInit() {
    var Recaptcha = require("../forms/recaptcha"),
      Mailchimp = require("../forms/mailchimp"),
      Shortcode = require("../forms/shortcode"),
      FieldsMap = require("../controls/fields-map");

    this.mailchimp = new Mailchimp("form");
    this.shortcode = new Shortcode("form");
    this.recaptcha = new Recaptcha("form");

    // Form fields
    var TimeField = require("../fields/time-field"),
      DateField = require("../fields/date-field"),
      AcceptanceField = require("../fields/acceptance-field"),
      UploadField = require("../fields/upload-field"),
      TelField = require("../fields/tel-field");

    this.Fields = {
      time: new TimeField("form"),
      date: new DateField("form"),
      tel: new TelField("form"),
      acceptance: new AcceptanceField("form"),
      upload: new UploadField("form")
    };

    elementor.addControlView("Fields_map", FieldsMap);
  }
});

module.exports = Form;
