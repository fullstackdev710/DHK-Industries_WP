module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        postNavigation: ".o-post-navigation",
        postNavigationImage: ".o-post-navigation__hover-image",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $postNavigation: this.$element.find(selectors.postNavigation),
      $postNavigationImage: this.$element.find(selectors.postNavigationImage),
    };
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    var elementSettings = this.getElementSettings(),
      elements = this.elements,
      $postNavigation = elements.$postNavigation,
      $postNavigationImage = elements.$postNavigationImage,
      thumbnailHover = elementSettings.thumbnail_hover;

    if (thumbnailHover == "yes") {
      var $link = $postNavigation.find(".o-post-navigation__link"),
        $ = jQuery;

      $link.each(function () {
        if ($(this).data("img")) {
          $(this)
            .on("mouseover", function () {
              $postNavigationImage
                .css("background-image", "url(" + $(this).data("img") + ")")
                .addClass("active");
            })
            .on("mouseout", function () {
              $postNavigationImage.removeClass("active");
              // .css("background-image", "");
            });
        }
      });
    }
  },
});
