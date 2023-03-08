module.exports = elementorModules.editor.utils.Module.extend({
  onElementorPreviewLoaded: function onElementorPreviewLoaded() {
    elementor.addControlView("neuron_presets", require("./control"));
  },
});
