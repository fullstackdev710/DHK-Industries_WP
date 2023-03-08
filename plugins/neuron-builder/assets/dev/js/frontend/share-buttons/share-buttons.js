module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        shareButton: ".a-neuron-share-button",
      },
      classes: {
        shareLinkPrefix: "a-neuron-share-button--",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $shareButton: this.$element.find(selectors.shareButton),
    };
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );
    var elementSettings = this.getElementSettings(),
      classes = this.getSettings("classes"),
      isCustomURL = elementSettings.share_url && elementSettings.share_url.url,
      shareLinkSettings = {
        classPrefix: classes.shareLinkPrefix,
      };

    if (isCustomURL) {
      shareLinkSettings.url = elementSettings.share_url.url;
    } else {
      shareLinkSettings.url = location.href;
      shareLinkSettings.title = elementorFrontend.config.post.title;
      shareLinkSettings.text = elementorFrontend.config.post.excerpt;
      shareLinkSettings.image = elementorFrontend.config.post.featuredImage;
    }
    /**
     * Ad Blockers may block the share script. (/assets/lib/social-share/social-share.js).
     */

    if (!this.elements.$shareButton.shareLink) {
      return;
    }

    this.elements.$shareButton.shareLink(shareLinkSettings);
  },
});
