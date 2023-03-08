const GalleryHandler = require("./gallery");

module.exports = () => {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/neuron-gallery.default",
    function ($scope) {
      new GalleryHandler({
        $element: $scope,
      });
    }
  );
};
