const CountdownModule = require("./countdown");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-countdown.default",
    function ($scope) {
      new CountdownModule({
        $element: $scope,
      });
    }
  );
};
