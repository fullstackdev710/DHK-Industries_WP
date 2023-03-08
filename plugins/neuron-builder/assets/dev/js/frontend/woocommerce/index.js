module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-menu-cart.default",
    require("./menu-cart")
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-product-add-to-cart.default",
    require("./add-to-cart")
  );

  if (elementorFrontend.isEditMode()) {
    return;
  }

  jQuery(document.body).on(
    "wc_fragments_loaded wc_fragments_refreshed",
    function () {
      jQuery("div.elementor-widget-neuron-woo-menu-cart").each(function () {
        elementorFrontend.elementsHandler.runReadyTrigger(jQuery(this));
      });
    }
  );
};
