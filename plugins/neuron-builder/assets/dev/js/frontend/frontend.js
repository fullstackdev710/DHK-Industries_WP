import MotionFXModule from "./motion-fx";

class NeuronFrontend extends elementorModules.ViewModule {
  onInit() {
    super.onInit();

    this.config = NeuronFrontendConfig;
    this.modules = {};
  }

  bindEvents() {
    jQuery(window).on(
      "elementor/frontend/init",
      this.onElementorFrontendInit.bind(this)
    );
  }

  initModules() {
    let handlers = {
      motionFX: MotionFXModule,
      sticky: require("./sticky"),
    }; // Keep this line before applying filter on the handlers.

    neuronFrontend.trigger("neuron/modules/init:before");

    handlers = elementorFrontend.hooks.applyFilters(
      "neuron/frontend/handlers",
      handlers
    );

    jQuery.each(handlers, (moduleName, ModuleClass) => {
      this.modules[moduleName] = new ModuleClass();
    });

    this.modules.linkActions = {
      addAction: (...args) => {
        elementorFrontend.utils.urlActions.addAction(...args);
      },
    };

    // jQuery.each(handlers, function (moduleName, ModuleClass) {
    //   self.modules[moduleName] = new ModuleClass();
    // });

    // this.modules.linkActions = {
    //   addAction: function addAction() {
    //     elementorFrontend.utils.urlActions.addAction.apply(
    //       NeuronFrontend,
    //       arguments
    //     );
    //   },
    // };
  }

  onElementorFrontendInit() {
    this.initModules();
  }
}

window.neuronFrontend = new NeuronFrontend();

window.onload = function () {
  setTimeout(() => {
    jQuery(window).trigger("resize");
  }, 100);
};
