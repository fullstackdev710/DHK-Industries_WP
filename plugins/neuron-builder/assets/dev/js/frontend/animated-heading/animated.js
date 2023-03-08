module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        heading: ".a-animated-heading",
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
    if (this.getElementSettings("style") != "animated") {
      return;
    }

    var settings = this.getElementSettings(),
      $dynamic = this.elements.$dynamic,
      animatedWords = "",
      animatedTexts = settings.animated_text,
      animatedType = settings.animated_type,
      animatedClass = "a-animated-heading__text--dynamic";

    if (animatedTexts) {
      animatedTexts = animatedTexts.split("\n");

      jQuery.map(animatedTexts, function (word) {
        if (animatedType == "word") {
          var newWord = "";

          word = word.split(" ");
          word.map(function (val, i) {
            newWord +=
              "<span class='a-animated-heading__text--word-cap'>" +
              val +
              "</span>";
          });

          word = newWord;
        }

        animatedWords +=
          "<span class='" +
          animatedClass +
          "'><span>" +
          word +
          "</span></span>";
      });

      $dynamic.append(animatedWords);

      this.animatedAnimation();
    }
  },

  animatedAnimation: function animatedAnimation() {
    var $dynamic = this.elements.$dynamic,
      $heading = this.elements.$heading,
      uniqueSelector = ".a-animated-heading",
      $ = jQuery,
      elementSettings = this.getElementSettings(),
      animationType = this.getCurrentDeviceSetting("animated_animation"),
      animationDuration = this.getCurrentDeviceSetting(
        "neuron_animations_duration"
      ),
      animationDelay = parseInt(
        this.getCurrentDeviceSetting("animation_delay")
      ),
      animationSeconds = parseInt(animationDelay),
      animationDelayReset = parseInt(
        this.getCurrentDeviceSetting("animation_delay_reset")
      ),
      $animatedSelector = $dynamic.find(
        ".a-animated-heading__text--dynamic > span"
      );

    if (elementSettings.animated_type == "word") {
      $animatedSelector = $dynamic.find(".a-animated-heading__text--word-cap");
    }

    if ($heading.data("id")) {
      uniqueSelector =
        ".a-animated-heading[data-id='" + $heading.data("id") + "']";
    }

    // Curtain
    if (animationType.includes("h-neuron-animation--curtain")) {
      $dynamic
        .find(".a-animated-heading__text--dynamic")
        .css("overflow", "hidden");
    }

    $(() => {
      var observer = new IntersectionObserver(
        function (entries) {
          if (entries[0].isIntersecting === true) {
            $animatedSelector.each(function () {
              setTimeout(() => {
                $(this)
                  .addClass(animationType)
                  .addClass(animationDuration)
                  .addClass("active");
              }, animationSeconds);

              if (animationSeconds !== 0 && animationSeconds !== "") {
                animationSeconds += animationDelay;

                if (animationDelayReset <= animationSeconds) {
                  animationSeconds = animationDelay;
                }
              }
            });
          }
        },
        { threshold: [0] }
      );

      observer.observe(document.querySelector(uniqueSelector));
    });
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    this.run();
  },
});
