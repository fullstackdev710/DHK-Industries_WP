const SearchBarModule = require("./search-form");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-search-form.default",
    function ($scope) {
      new SearchBarModule({
        $element: $scope,
      });
    }
  );
};
