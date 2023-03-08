const ProductsHandler = require("./products");

module.exports = () => {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-products.default",
    function ($scope) {
      new ProductsHandler({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-archive-products.default",
    function ($scope) {
      new ProductsHandler({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-product-related.default",
    function ($scope) {
      new ProductsHandler({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-product-upsell.default",
    function ($scope) {
      new ProductsHandler({
        $element: $scope,
      });
    }
  );
};
