const NavMenuModule = require("./nav-menu");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-nav-menu.default",
    function ($scope) {
      new NavMenuModule({
        $element: $scope,
      });
    }
  );
};
