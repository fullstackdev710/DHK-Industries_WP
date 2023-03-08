const ShareButton = elementorModules.editor.utils.Module.extend({
  config: neuron.config.shareButtonsNetworks,

  networksClassDictionary: {
    google: "fab fa-google-plus",
    pocket: "fab fa-get-pocket",
    email: "fas fa-envelope",
    facebook: "fab fa-facebook-f",
  },

  getNetworkClass: function getNetworkClass(networkName) {
    var networkClass =
      this.networksClassDictionary[networkName] || "fab fa-" + networkName;

    return networkClass;
  },

  getNetworkTitle: function getNetworkTitle(buttonSettings) {
    return buttonSettings.title || this.config[buttonSettings.network].title;
  },

  hasCounter: function hasCounter(networkName, settings) {
    return (
      "icon" !== settings.view &&
      "yes" === settings.show_counter &&
      this.config[networkName].has_counter
    );
  },
});

module.exports = ShareButton;
