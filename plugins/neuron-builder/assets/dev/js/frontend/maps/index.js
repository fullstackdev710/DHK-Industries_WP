const MapsModule = require("./maps");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-maps.default",
    function ($scope) {
      new MapsModule({
        $element: $scope,
      });
    }
  );
};
