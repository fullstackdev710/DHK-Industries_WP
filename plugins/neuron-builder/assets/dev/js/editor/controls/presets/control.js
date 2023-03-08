const Presets = elementor.modules.controls.BaseData.extend({
  ui: function ui() {
    var ui = elementor.modules.controls.BaseMultiple.prototype.ui.apply(
      this,
      arguments
    );

    ui.presetItems = ".neuron-element-presets";
    ui.presetItem = ".neuron-element-presets-item";

    return ui;
  },

  events: function events() {
    return _.extend(
      elementor.modules.controls.BaseMultiple.prototype.events.apply(
        this,
        arguments
      ),
      {
        "click @ui.presetItem ": "onPresetClick",
      }
    );
  },

  onReady: function onReady() {
    window.neuronPresets = window.neuronPresets || {};

    this.loadPresets(this.container.settings.get("widgetType"));

    elementor.channels.data.bind(
      "neuron:element:after:reset:style",
      this.onElementResetStyle.bind(this)
    );
  },

  onElementResetStyle: function onElementResetStyle() {
    if (this.isRendered) {
      this.render();
    }
  },

  onPresetClick: function onPresetClick(e) {
    var $preset = jQuery(e.currentTarget);
    $preset.siblings(".neuron-element-presets-item").removeClass("active");
    $preset.addClass("active");

    var preset = _.find(this.getPresets(), { id: $preset.data("preset-id") });

    this.applyPreset(this.elementDefaultSettings(), preset);
    this.selectPreset(preset.id);
  },

  applyPreset: function applyPreset() {
    var settings =
      arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var preset = arguments[1];

    for (var setting in preset.widget.settings) {
      if (this.model.get("name") === setting) {
        continue;
      }

      var control = this.container.settings.controls[setting];

      if (typeof control === "undefined") {
        continue;
      }

      settings[setting] = preset.widget.settings[setting];
    }

    this.container.settings.set(settings);
  },

  elementDefaultSettings: function elementDefaultSettings() {
    var self = this,
      controls = self.container.settings.controls,
      settings = {};

    jQuery.each(controls, function (controlName, control) {
      if (controlName === "neuron_presets") {
        return;
      }

      settings[controlName] = control.default;
    });

    return settings;
  },

  loadPresets: function loadPresets(widget) {
    var _this = this;

    if (this.isPresetDataLoaded()) {
      if (this.getPresets().length === 0) {
        return;
      }

      this.insertPresets();

      if (this.ui.presetItem.length === 0) {
        this.render();
      }

      return;
    }

    this.ui.presetItems.addClass("loading");

    wp.ajax
      .post("neuron_element_presets", { neuron_element: widget })
      .done(function (data) {
        _this.ui.presetItems.removeClass("loading");
        _this.setPresets(data);
        _this.insertPresets();
        _this.render();
      })
      .fail(function () {
        _this.ui.presetItems.removeClass("loading");
        _this.setPresets([]);
      });
  },

  insertPresets: function insertPresets() {
    var value = this.getControlValue();
    this.setValue({
      selectedId: value ? value.selectedId : null,
      presets: this.getPresets(),
    });
  },

  selectPreset: function selectPreset(id) {
    var value = this.getControlValue();
    value.selectedId = id;
    this.setValue(value);
  },

  getPresets: function getPresets() {
    if (!window.neuronPresets) {
      return [];
    }

    return (
      window.neuronPresets[this.container.settings.get("widgetType")] || []
    );
  },

  setPresets: function setPresets(presets) {
    window.neuronPresets[this.container.settings.get("widgetType")] = presets;
  },

  isPresetDataLoaded: function isPresetDataLoaded() {
    if (window.neuronPresets[this.container.settings.get("widgetType")]) {
      return true;
    }

    return false;
  },

  onBeforeDestroy: function onBeforeDestroy() {
    elementor.channels.data.unbind("neuron:element:after:reset:style");
  },
});

module.exports = Presets;
