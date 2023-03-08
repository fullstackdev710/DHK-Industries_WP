const InteractivePostsModule = require("./module");

module.exports = function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-interactive-posts.default",
    function ($scope) {
      new InteractivePostsModule({
        $element: $scope,
      });
    }
  );
};
