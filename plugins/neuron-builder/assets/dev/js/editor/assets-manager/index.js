module.exports = elementorModules.editor.utils.Module.extend({
  onElementorInit: function onElementorInit() {
    var FontsManager = require("./fonts-manager");

    this.assets = {
      font: new FontsManager(),
    };
  },
});
