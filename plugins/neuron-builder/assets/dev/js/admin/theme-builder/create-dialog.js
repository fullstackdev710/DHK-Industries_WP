module.exports = function () {
  var selectors = {
    templateTypeInput: "#elementor-new-template__form__template-type",
    locationWrapper: "#elementor-new-template__form__location__wrapper",
    postTypeWrapper: "#elementor-new-template__form__post-type__wrapper",
  };
  var elements = {
    $templateTypeInput: null,
    $locationWrapper: null,
    $postTypeWrapper: null,
  };

  var setElements = function setElements() {
    jQuery.each(selectors, function (key, selector) {
      key = "$" + key;
      elements[key] = elementorNewTemplate.layout
        .getModal()
        .getElements("content")
        .find(selector);
    });
  };

  var setLocationFieldVisibility = function setLocationFieldVisibility() {
    elements.$locationWrapper.toggle(
      "section" === elements.$templateTypeInput.val()
    );
    elements.$postTypeWrapper.toggle(
      "single" === elements.$templateTypeInput.val()
    );
  };

  var run = function run() {
    setElements();
    setLocationFieldVisibility();
    elements.$templateTypeInput.change(setLocationFieldVisibility);
  };

  this.init = function () {
    if (!window.elementorNewTemplate) {
      return;
    }

    elementorNewTemplate.layout.getModal();
    run();
  };

  jQuery(setTimeout.bind(window, this.init));
};
