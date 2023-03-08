module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        column: ".a-neuron-clickable-col",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $column: this.$element.find(selectors.column),
    };
  },

  initColumnLinking() {
    var $ = jQuery;

    $(document).on(
      "click",
      "body:not(.elementor-editor-active) .a-neuron-clickable-col",
      function (e) {
        var wrapper = $(this),
          url = wrapper.data("column-clickable");

        if (url) {
          if ($(e.target).filter("a, a *, .no-link, .no-link *").length) {
            return true;
          }

          // smooth scroll
          if (url.match("^#")) {
            var hash = url;

            $("html, body").animate(
              {
                scrollTop: $(hash).offset().top,
              },
              800,
              function () {
                window.location.hash = hash;
              }
            );

            return true;
          }

          window.open(url, wrapper.data("column-clickable-blank"));
          return false;
        }
      }
    );
  },

  onInit: function onInit() {
    this.initColumnLinking();
  },
});
