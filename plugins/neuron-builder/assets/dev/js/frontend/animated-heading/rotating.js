module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        heading: ".a-animated-heading--rotating",
        dynamic: ".a-animated-heading__text--dynamic-wrapper",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $heading: this.$element.find(selectors.heading),
      $dynamic: this.$element.find(selectors.dynamic),
    };
  },

  run: function run() {
    if (this.getElementSettings("style") != "rotating") {
      return;
    }

    var settings = this.getElementSettings(),
      $dynamic = this.elements.$dynamic,
      rotating = settings.rotating_text,
      rotating_animation = settings.rotating_animation,
      rotatingWord = "",
      rotatingClass = "a-animated-heading__text--dynamic";

    if (rotating_animation == "slide-down") {
      rotating = rotating.split("\n");

      jQuery.map(rotating, function (word) {
        rotatingWord +=
          "<span class='" + rotatingClass + "'>" + word + "</span>";
      });

      $dynamic.append(rotatingWord);

      var dynamicTexts = $dynamic.find(".a-animated-heading__text--dynamic"),
        currentHighlight = 0,
        speed = rotating_animation == "typing" ? 5000 : 2000;

      dynamicTexts.eq(currentHighlight).addClass("active");

      setInterval(function () {
        currentHighlight = (currentHighlight + 1) % dynamicTexts.length;

        dynamicTexts
          .removeClass("active")
          .eq(currentHighlight)
          .addClass("active");
      }, speed);
    } else if (rotating_animation == "typing") {
      rotating = rotating.split("\n");

      if (rotating) {
        new Typed(
          ".a-animated-heading--rotating .a-animated-heading__text--dynamic-wrapper",
          {
            cursorChar: settings.cursor_char ? settings.cursor_char : ".",
            backSpeed: settings.back_speed ? settings.back_speed : 60,
            backDelay: settings.back_delay ? settings.back_delay : 510,
            startDelay: settings.start_delay ? settings.start_delay : 310,
            typeSpeed: settings.type_speed ? settings.type_speed : 110,
            loop: settings.loop == "yes" ? true : false,
            strings: rotating,
          }
        );
      }
    }
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    this.run();
  },
});
