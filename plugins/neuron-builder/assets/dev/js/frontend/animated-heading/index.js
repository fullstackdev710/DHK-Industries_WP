const HighlightedModule = require("./highlighted");
const RotatingModule = require("./rotating");
const AnimatedModule = require("./animated");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-animated-heading.default",
    function ($scope) {
      new HighlightedModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-animated-heading.default",
    function ($scope) {
      new RotatingModule({
        $element: $scope,
      });
    }
  );

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-animated-heading.default",
    function ($scope) {
      new AnimatedModule({
        $element: $scope,
      });
    }
  );
};
