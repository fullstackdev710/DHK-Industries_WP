const AddToCartModule = require("./add-to-cart");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-add-to-cart.default",
    function ($scope) {
      new AddToCartModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-product-add-to-cart.default",
    function ($scope) {
      new AddToCartModule({
        $element: $scope,
      });
    }
  );
};
