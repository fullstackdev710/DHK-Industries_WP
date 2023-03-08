const TableOfContentsModule = require("./table-of-contents");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-table-of-contents.default",
    function ($scope) {
      new TableOfContentsModule.default({
        $element: $scope,
      });
    }
  );
};
