var AddToCartHandler = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        addToCart:
          ".elementor-product-simple .single_add_to_cart_button, .elementor-product-variable .single_add_to_cart_button",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $addToCart: this.$element.find(selectors.addToCart),
    };
  },

  handleCart: function (event) {
    var $ = jQuery;

    event.preventDefault();

    var $thisbutton = this.elements.$addToCart,
      $form = $thisbutton.closest("form.cart"),
      id = $thisbutton.val(),
      product_qty = $form.find("input[name=quantity]").val() || 1,
      product_id = $form.find("input[name=product_id]").val() || id,
      variation_id = $form.find("input[name=variation_id]").val() || 0;

    var data = {
      action: "neuron_woocommerce_ajax_add_to_cart",
      product_id: product_id,
      product_sku: "",
      quantity: product_qty,
      variation_id: variation_id,
    };

    $.ajax({
      type: "post",
      url: wc_add_to_cart_params.ajax_url,
      data: data,

      beforeSend: function (response) {
        $thisbutton.wrapInner("<span></span>");
        $thisbutton.removeClass("added").addClass("loading");
      },

      complete: function (response) {
        $thisbutton.addClass("added").removeClass("loading");
        $thisbutton.find("span").contents().unwrap();
      },

      success: function (response) {
        if (response.error & response.product_url) {
          window.location = response.product_url;

          return;
        } else {
          $(document.body).trigger("added_to_cart", [
            response.fragments,
            response.cart_hash,
            $thisbutton,
          ]);
        }
      },
    });
  },

  bindEvents: function bindEvents() {
    this.elements.$addToCart.on("click", this.handleCart);
  },
});

module.exports = function ($scope) {
  new AddToCartHandler({
    $element: $scope,
  });
};
