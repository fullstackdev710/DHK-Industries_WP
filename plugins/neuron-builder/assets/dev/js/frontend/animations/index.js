const AnimationsModule = require("./module");

module.exports = () => {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-testimonial-carousel.default",
    function ($scope) {
      new AnimationsModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-slides.default",
    function ($scope) {
      new AnimationsModule({
        $element: $scope,
      });
    }
  );
};
