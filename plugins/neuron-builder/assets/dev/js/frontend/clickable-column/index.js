const ColumnModule = require("./column");

module.exports = function () {
  elementorFrontend.hooks.addAction("frontend/element_ready/global", function (
    $scope
  ) {
    if ($scope.attr("data-column-clickable") == undefined) {
      return;
    }

    new ColumnModule({
      $element: $scope,
    });
  });
};
