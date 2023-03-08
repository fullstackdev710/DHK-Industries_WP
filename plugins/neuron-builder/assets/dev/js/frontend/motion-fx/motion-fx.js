var Scroll = require("./scroll/");
var MouseMove = require("./mouse-move/");
var Actions = require("./actions/");

export default class MotionFX extends elementorModules.ViewModule {
  getDefaultSettings() {
    return {
      type: "element",
      $element: null,
      $dimensionsElement: null,
      addBackgroundLayerTo: null,
      interactions: {},
      refreshDimensions: false,
      range: "viewport",
      classes: {
        element: "motion-fx-element",
        parent: "motion-fx-parent",
        backgroundType: "motion-fx-element-type-background",
        container: "motion-fx-container",
        layer: "motion-fx-layer",
        perspective: "motion-fx-perspective",
      },
    };
  }

  bindEvents() {
    this.onWindowResize = this.onWindowResize.bind(this);
    elementorFrontend.elements.$window.on("resize", this.onWindowResize);
  }

  unbindEvents() {
    elementorFrontend.elements.$window.off("resize", this.onWindowResize);
  }

  addBackgroundLayer() {
    var settings = this.getSettings();

    this.elements.$motionFXContainer = jQuery("<div>", {
      class: settings.classes.container,
    });

    this.elements.$motionFXLayer = jQuery("<div>", {
      class: settings.classes.layer,
    });

    this.updateBackgroundLayerSize();

    this.elements.$motionFXContainer.prepend(this.elements.$motionFXLayer);
    var $addBackgroundLayerTo = settings.addBackgroundLayerTo
      ? this.$element.find(settings.addBackgroundLayerTo)
      : this.$element;

    $addBackgroundLayerTo.prepend(this.elements.$motionFXContainer);
  }

  removeBackgroundLayer() {
    this.elements.$motionFXContainer.remove();
  }

  updateBackgroundLayerSize() {
    var settings = this.getSettings(),
      speed = {
        x: 0,
        y: 0,
      },
      mouseInteraction = settings.interactions.mouseMove,
      scrollInteraction = settings.interactions.scroll;

    if (mouseInteraction && mouseInteraction.translateXY) {
      speed.x = mouseInteraction.translateXY.speed * 10;
      speed.y = mouseInteraction.translateXY.speed * 10;
    }

    if (scrollInteraction) {
      if (scrollInteraction.translateX) {
        speed.x = scrollInteraction.translateX.speed * 10;
      }

      if (scrollInteraction.translateY) {
        speed.y = scrollInteraction.translateY.speed * 10;
      }
    }

    this.elements.$motionFXLayer.css({
      width: 100 + speed.x + "%",
      height: 100 + speed.y + "%",
    });
  }

  defineDimensions() {
    var $dimensionsElement =
        this.getSettings("$dimensionsElement") || this.$element,
      elementOffset = $dimensionsElement.offset();
    var dimensions = {
      elementHeight: $dimensionsElement.outerHeight(),
      elementWidth: $dimensionsElement.outerWidth(),
      elementTop: elementOffset.top,
      elementLeft: elementOffset.left,
    };
    dimensions.elementRange = dimensions.elementHeight + innerHeight;
    this.setSettings("dimensions", dimensions);

    if ("background" === this.getSettings("type")) {
      this.defineBackgroundLayerDimensions();
    }
  }

  defineBackgroundLayerDimensions() {
    var dimensions = this.getSettings("dimensions");
    dimensions.layerHeight = this.elements.$motionFXLayer.height();
    dimensions.layerWidth = this.elements.$motionFXLayer.width();
    dimensions.movableX = dimensions.layerWidth - dimensions.elementWidth;
    dimensions.movableY = dimensions.layerHeight - dimensions.elementHeight;
    this.setSettings("dimensions", dimensions);
  }

  initInteractionsTypes() {
    this.interactionsTypes = {
      scroll: Scroll.default,
      mouseMove: MouseMove.default,
    };
  }

  prepareSpecialActions() {
    var settings = this.getSettings(),
      hasTiltEffect = !!(
        settings.interactions.mouseMove && settings.interactions.mouseMove.tilt
      );
    this.elements.$parent.toggleClass(
      settings.classes.perspective,
      hasTiltEffect
    );
  }

  cleanSpecialActions() {
    var settings = this.getSettings();
    this.elements.$parent.removeClass(settings.classes.perspective);
  }

  runInteractions() {
    var _this = this;

    var settings = this.getSettings();
    this.prepareSpecialActions();

    jQuery.each(settings.interactions, function (interactionName, actions) {
      _this.interactions[interactionName] = new _this.interactionsTypes[
        interactionName
      ]({
        motionFX: _this,
        callback: function callback() {
          for (
            var _len = arguments.length, args = new Array(_len), _key = 0;
            _key < _len;
            _key++
          ) {
            args[_key] = arguments[_key];
          }

          jQuery.each(actions, function (actionName, actionData) {
            var _this$actions;

            return (_this$actions = _this.actions).runAction.apply(
              _this$actions,
              [actionName, actionData].concat(args)
            );
          });
        },
      });

      _this.interactions[interactionName].run();
    });
  }

  destroyInteractions() {
    this.cleanSpecialActions();
    jQuery.each(this.interactions, function (interactionName, interaction) {
      return interaction.destroy();
    });
    this.interactions = {};
  }

  refresh() {
    this.actions.setSettings(this.getSettings());

    if ("background" === this.getSettings("type")) {
      this.updateBackgroundLayerSize();
      this.defineBackgroundLayerDimensions();
    }

    this.actions.refresh();
    this.destroyInteractions();
    this.runInteractions();
  }

  destroy() {
    this.destroyInteractions();
    this.actions.refresh();
    var settings = this.getSettings();
    this.$element.removeClass(settings.classes.element);
    this.elements.$parent.removeClass(settings.classes.parent);

    if ("background" === settings.type) {
      this.$element.removeClass(settings.classes.backgroundType);
      this.removeBackgroundLayer();
    }
  }

  onInit() {
    super.onInit();

    var settings = this.getSettings();
    this.$element = settings.$element;
    this.elements.$parent = this.$element.parent();
    this.$element.addClass(settings.classes.element);
    this.elements.$parent = this.$element.parent();
    this.elements.$parent.addClass(settings.classes.parent);

    if ("background" === settings.type) {
      this.$element.addClass(settings.classes.backgroundType);
      this.addBackgroundLayer();
    }

    this.defineDimensions();

    settings.$targetElement =
      "element" === settings.type
        ? this.$element
        : this.elements.$motionFXLayer;

    this.interactions = {};
    this.actions = new Actions.default(settings);
    this.initInteractionsTypes();
    this.runInteractions();
  }

  onWindowResize() {
    this.defineDimensions();
  }
}
