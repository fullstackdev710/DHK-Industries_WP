import MotionFX from "./motion-fx";

export default class MotionFXHandler extends elementorModules.frontend.handlers
  .Base {
  __construct() {
    var self = this;

    super.__construct.apply(self, arguments);

    this.toggle = elementorFrontend.debounce(this.toggle, 200);
  }

  bindEvents() {
    elementorFrontend.elements.$window.on("resize", this.toggle);
  }

  unbindEvents() {
    elementorFrontend.elements.$window.off("resize", this.toggle);
  }

  initEffects() {
    this.effects = {
      translateY: {
        interaction: "scroll",
        actions: ["translateY"],
      },
      translateX: {
        interaction: "scroll",
        actions: ["translateX"],
      },
      rotateZ: {
        interaction: "scroll",
        actions: ["rotateZ"],
      },
      scale: {
        interaction: "scroll",
        actions: ["scale"],
      },
      opacity: {
        interaction: "scroll",
        actions: ["opacity"],
      },
      blur: {
        interaction: "scroll",
        actions: ["blur"],
      },
      mouseTrack: {
        interaction: "mouseMove",
        actions: ["translateXY"],
      },
      tilt: {
        interaction: "mouseMove",
        actions: ["tilt"],
      },
    };
  }

  prepareOptions(name) {
    var _this = this;

    var elementSettings = this.getElementSettings(),
      type = "motion_fx" === name ? "element" : "background",
      interactions = {};

    jQuery.each(elementSettings, function (key, value) {
      var keyRegex = new RegExp("^" + name + "_(.+?)_effect"),
        keyMatches = key.match(keyRegex);

      if (!keyMatches || !value) {
        return;
      }

      var options = {},
        effectName = keyMatches[1];
      jQuery.each(elementSettings, function (subKey, subValue) {
        var subKeyRegex = new RegExp(name + "_" + effectName + "_(.+)"),
          subKeyMatches = subKey.match(subKeyRegex);

        if (!subKeyMatches) {
          return;
        }

        var subFieldName = subKeyMatches[1];

        if ("effect" === subFieldName) {
          return;
        }

        if ("object" === typeof subValue) {
          subValue = Object.keys(subValue.sizes).length
            ? subValue.sizes
            : subValue.size;
        }

        options[subKeyMatches[1]] = subValue;
      });
      var effect = _this.effects[effectName],
        interactionName = effect.interaction;

      if (!interactions[interactionName]) {
        interactions[interactionName] = {};
      }

      effect.actions.forEach(function (action) {
        return (interactions[interactionName][action] = options);
      });
    });
    var $element = this.$element,
      $dimensionsElement;
    var elementType = this.getElementType();

    if ("element" === type && "section" !== elementType) {
      $dimensionsElement = $element;
      var childElementSelector;

      if ("column" === elementType) {
        childElementSelector = elementorFrontend.config.legacyMode
          .elementWrappers
          ? ".elementor-column-wrap"
          : ".elementor-widget-wrap";
      } else {
        childElementSelector = ".elementor-widget-container";
      }

      $element = $element.find("> " + childElementSelector);
    }

    var options = {
      type: type,
      interactions: interactions,
      $element: $element,
      $dimensionsElement: $dimensionsElement,
      refreshDimensions: this.isEdit,
      range: elementSettings[name + "_range"],
      classes: {
        element: "elementor-motion-effects-element",
        parent: "elementor-motion-effects-parent",
        backgroundType: "elementor-motion-effects-element-type-background",
        container: "elementor-motion-effects-container",
        layer: "elementor-motion-effects-layer",
        perspective: "elementor-motion-effects-perspective",
      },
    };

    if (
      !options.range &&
      "fixed" === this.getCurrentDeviceSetting("_position")
    ) {
      options.range = "page";
    }

    if ("background" === type && "column" === this.getElementType()) {
      options.addBackgroundLayerTo = " > .elementor-element-populated";
    }

    return options;
  }

  activate(name) {
    var options = this.prepareOptions(name);

    if (jQuery.isEmptyObject(options.interactions)) {
      return;
    }

    this[name] = new MotionFX(options);
  }

  deactivate(name) {
    if (this[name]) {
      this[name].destroy();
      delete this[name];
    }
  }

  toggle() {
    var _this = this;

    var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
      elementSettings = this.getElementSettings();

    ["motion_fx", "background_motion_fx"].forEach(function (name) {
      var devices = elementSettings[name + "_devices"],
        isCurrentModeActive =
          !devices || -1 !== devices.indexOf(currentDeviceMode);

      if (
        isCurrentModeActive &&
        (elementSettings[name + "_motion_fx_scrolling"] ||
          elementSettings[name + "_motion_fx_mouse"])
      ) {
        if (_this[name]) {
          _this.refreshInstance(name);
        } else {
          _this.activate(name);
        }
      } else {
        _this.deactivate(name);
      }
    });
  }

  refreshInstance(instanceName) {
    var instance = this[instanceName];

    if (!instance) {
      return;
    }

    var preparedOptions = this.prepareOptions(instanceName);
    instance.setSettings(preparedOptions);
    instance.refresh();
  }

  onInit() {
    var _this = this;

    super.onInit();

    _this.initEffects();
    _this.toggle();
  }

  onElementChange(propertyName) {
    var _this = this;

    if (/motion_fx_((scrolling)|(mouse)|(devices))$/.test(propertyName)) {
      this.toggle();
      return;
    }

    var propertyMatches = propertyName.match(".*?motion_fx");

    if (propertyMatches) {
      var instanceName = propertyMatches[0];
      this.refreshInstance(instanceName);

      if (!this[instanceName]) {
        this.activate(instanceName);
      }
    }

    if (/^_position/.test(propertyName)) {
      ["motion_fx", "background_motion_fx"].forEach(function (instanceName) {
        _this.refreshInstance(instanceName);
      });
    }
  }

  onDestroy() {
    var _this = this;

    super.onDestroy();

    ["motion_fx", "background_motion_fx"].forEach(function (name) {
      _this.deactivate(name);
    });
  }
}
