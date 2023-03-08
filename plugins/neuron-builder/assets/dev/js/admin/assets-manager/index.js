module.exports = function () {
  var CustomFontsManager = require("./font-manager");

  this.fontManager = new CustomFontsManager.default();
};
