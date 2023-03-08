const ShareButtonsModule = require("./share-buttons");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-share-buttons.default",
    function ($scope) {
      new ShareButtonsModule({
        $element: $scope,
      });
    }
  );
};
