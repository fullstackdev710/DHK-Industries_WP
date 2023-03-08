const StickyModule = require("./module");

module.exports = function () {
  elementorFrontend.hooks.addAction("frontend/element_ready/section", function (
    $scope
  ) {
    new StickyModule({
      $element: $scope,
    });
  });

  elementorFrontend.hooks.addAction("frontend/element_ready/widget", function (
    $scope
  ) {
    new StickyModule({
      $element: $scope,
    });
  });
};
