import ThemeBuilderModule from "./theme-builder/module";
import PopupModule from "./popup/module";
import GlobalWidget from "./global-widget";
import MotionFX from "./motion-fx";

const Neuron = Marionette.Application.extend({
  config: {},

  modules: {},

  helpers: {},

  initModules: function initModules() {
    var QueryControl = require("./controls/query/base"),
      Forms = require("./widgets/form"),
      Posts = require("./posts"),
      CustomCSS = require("./controls/custom-css"),
      // Presets = require("./controls/presets/"),
      ShareButton = require("./widgets/share-button"),
      FlipBox = require("./widgets/flip-box"),
      AssetsManager = require("./assets-manager"),
      KitManager = require("./kit-manager");

    this.modules = {
      queryControl: new QueryControl(),
      forms: new Forms(),
      posts: new Posts(),
      flipBox: new FlipBox(),
      shareButtons: new ShareButton(),
      // presets: new Presets(), // TODO
    };

    if (!neuron.config.is_elementor_pro_active) {
      this.modules.queryControl = new QueryControl();
      this.modules.customCSS = new CustomCSS.default();
      this.modules.globalWidget = new GlobalWidget();
      this.modules.motionFX = new MotionFX();
      this.modules.themeBuilder = new ThemeBuilderModule();
      this.modules.popup = new PopupModule();
      this.modules.assetsManager = new AssetsManager();
      this.modules.kitManager = new KitManager.default();
    }
  },

  initHelpers: function initHelpers() {
    this.helpers = {
      marionetteTitle: require("./helpers/marionette-title"),
      getPosts: require("./helpers/posts"),
    };
  },

  ajax: {
    prepareArgs: function prepareArgs(args) {
      args[0] = "neuron_" + args[0];

      return args;
    },

    send: function send() {
      return elementorCommon.ajax.send.apply(
        elementorCommon.ajax,
        this.prepareArgs(arguments)
      );
    },

    addRequest: function addRequest() {
      return elementorCommon.ajax.addRequest.apply(
        elementorCommon.ajax,
        this.prepareArgs(arguments)
      );
    },
  },

  translate: function translate(stringKey, templateArgs) {
    return elementorCommon.translate(
      stringKey,
      null,
      templateArgs,
      this.config.i18n
    );
  },

  onStart: function onStart() {
    var _this = this;

    this.config = NeuronEditorConfig;

    this.initModules();

    this.initHelpers();

    jQuery(window).on("elementor:init", function () {
      return _this.onElementorInit();
    });
  },

  onElementorInit: function onElementorInit() {
    var self = this;

    elementor.on("preview:loaded", function () {
      return self.onElementorPreviewLoaded();
    });
  },

  onElementorPreviewLoaded: function onElementorPreviewLoaded() {
    elementor.$preview[0].contentWindow.neuron = this;
  },
});

window.neuron = new Neuron();

neuron.start();
