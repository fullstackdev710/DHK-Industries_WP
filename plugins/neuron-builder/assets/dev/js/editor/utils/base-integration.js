const ElementEditorModule = require("./module");

const BaseIntegration = ElementEditorModule.extend({
  __construct: function __construct() {
    this.cache = {};
    ElementEditorModule.prototype.__construct.apply(this, arguments);
  },

  getName: function getName() {
    return "";
  },

  getCacheKey: function getCacheKey(args) {
    return JSON.stringify({
      service: this.getName(),
      data: args
    });
  },

  fetchCache: function fetchCache(type, cacheKey, requestArgs) {
    var _this = this;

    return neuron.ajax.addRequest("forms_panel_action_data", {
      unique_id: "integrations_" + this.getName(),
      data: requestArgs,
      success: function success(data) {
        _this.cache[type] = _.extend({}, _this.cache[type]);
        _this.cache[type][cacheKey] = data[type];
      }
    });
  },

  updateOptions: function updateOptions(name, options) {
    var controlView = this.getEditorControlView(name);

    if (controlView) {
      this.getEditorControlModel(name).set("options", options);

      controlView.render();
    }
  },

  onInit: function onInit() {
    this.addSectionListener("section_" + this.getName(), this.onSectionActive);
  },

  onSectionActive: function onSectionActive() {
    this.onApiUpdate();
  },

  onApiUpdate: function onApiUpdate() {}
});

module.exports = BaseIntegration;
