module.exports = elementorModules.editor.utils.Module.extend({
  onElementorPreviewLoaded: function onElementorPreviewLoaded() {
    elementor.addControlView("Query", require("./query"));
  }
});
