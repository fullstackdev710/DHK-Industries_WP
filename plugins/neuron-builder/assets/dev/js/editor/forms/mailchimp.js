const BaseIntegrationModule = require("../utils/base-integration");

const Mailchimp = BaseIntegrationModule.extend({
  getName: function getName() {
    return "mailchimp";
  },

  onElementChange: function onElementChange(setting) {
    switch (setting) {
      case "mailchimp_api_key_source":
      case "mailchimp_api_key":
        this.onApiUpdate();
        break;
      case "mailchimp_list":
        this.onMailchimpListUpdate();
        break;
    }
  },

  onApiUpdate: function onApiUpdate() {
    var self = this,
      controlView = self.getEditorControlView("mailchimp_api_key"),
      GlobalApiKeycontrolView = self.getEditorControlView(
        "mailchimp_api_key_source"
      );

    if (
      "default" !== GlobalApiKeycontrolView.getControlValue() &&
      "" === controlView.getControlValue()
    ) {
      self.updateOptions("mailchimp_list", []);
      self.getEditorControlView("mailchimp_list").setValue("");
      return;
    }

    self.addControlSpinner("mailchimp_list");
    var cacheKey = this.getCacheKey({
      type: "lists",
      controls: [
        controlView.getControlValue(),
        GlobalApiKeycontrolView.getControlValue(),
      ],
    });

    self.getMailchimpCache("lists", "lists", cacheKey).done(function (data) {
      self.updateOptions("mailchimp_list", data.lists);
      self.updatMailchimpList();
    });
  },

  onMailchimpListUpdate: function onMailchimpListUpdate() {
    this.updateOptions("mailchimp_groups", []);
    this.getEditorControlView("mailchimp_groups").setValue("");
    this.updatMailchimpList();
  },

  updatMailchimpList: function updatMailchimpList() {
    var self = this,
      controlView = self.getEditorControlView("mailchimp_list");

    if (!controlView.getControlValue()) {
      return;
    }

    self.addControlSpinner("mailchimp_groups");
    this.getCacheKey({
      type: "list_details",
      controls: [controlView.getControlValue()],
    });

    self
      .getMailchimpCache(
        "list_details",
        "list_details",
        controlView.getControlValue(),
        {
          mailchimp_list: controlView.getControlValue(),
        }
      )
      .done(function (data) {
        self.updateOptions("mailchimp_groups", data.list_details.groups);
        self
          .getEditorControlView("mailchimp_fields_map")
          .updateMap(data.list_details.fields);
      });
  },

  getMailchimpCache: function getMailchimpCache(
    type,
    action,
    cacheKey,
    requestArgs
  ) {
    if (_.has(this.cache[type], cacheKey)) {
      var data = {};
      data[type] = this.cache[type][cacheKey];
      return jQuery.Deferred().resolve(data);
    }

    requestArgs = _.extend({}, requestArgs, {
      service: "mailchimp",
      mailchimp_action: action,
      api_key: this.getEditorControlView("mailchimp_api_key").getControlValue(),
      use_global_api_key: this.getEditorControlView(
        "mailchimp_api_key_source"
      ).getControlValue(),
    });

    return this.fetchCache(type, cacheKey, requestArgs);
  },

  onSectionActive: function onSectionActive() {
    BaseIntegrationModule.prototype.onSectionActive.apply(this, arguments);

    this.onApiUpdate();
  },
});

module.exports = Mailchimp;
