const NavigationModule = require("./module");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-post-navigation.default",
    function ($scope) {
      new NavigationModule({
        $element: $scope,
      });
    }
  );
};
