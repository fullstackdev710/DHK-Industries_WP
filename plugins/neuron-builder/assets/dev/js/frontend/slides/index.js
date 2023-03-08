const SlidesModule = require("./slides");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-slides.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-posts.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-portfolio.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-products.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-product-related.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-product-upsell.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-categories.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-archive-posts.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-woo-archive-products.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-testimonial-carousel.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-gallery.default",
    function ($scope) {
      new SlidesModule({
        $element: $scope,
      });
    }
  );
};
