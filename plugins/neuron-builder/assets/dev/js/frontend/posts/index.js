const PostsHandler = require("./posts");
const PortfolioHandler = require("./portfolio");

module.exports = () => {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-posts.default",
    function ($scope) {
      new PostsHandler({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-portfolio.default",
    function ($scope) {
      new PortfolioHandler({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-products.default",
    function ($scope) {
      new PostsHandler({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-categories.default",
    function ($scope) {
      new PostsHandler({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-archive-posts.default",
    function ($scope) {
      new PostsHandler({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-archive-products.default",
    function ($scope) {
      new PostsHandler({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-product-related.default",
    function ($scope) {
      new PostsHandler({
        $element: $scope,
      });
    }
  );
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-product-upsell.default",
    function ($scope) {
      new PostsHandler({
        $element: $scope,
      });
    }
  );
};
