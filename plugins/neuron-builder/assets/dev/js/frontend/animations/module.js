module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        postsSliderWrapper: ".neuron-slides-wrapper",
      },
      classes: {
        active: "active",
      },
      attributes: {
        dataDelay: "delay",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $postsSliderWrapper: this.$element.find(selectors.postsSliderWrapper),
    };
  },

  loadingAnimations: function loadingAnimations() {
    var animation = this.getElementSettings("animation"),
      animationType = this.getCurrentDeviceSetting("neuron_animations"),
      animationDelay = parseInt(
        this.getCurrentDeviceSetting("animation_delay")
      ),
      animationSeconds = parseInt(animationDelay),
      animationDelayReset = parseInt(
        this.getCurrentDeviceSetting("animation_delay_reset")
      ),
      uniqueSelector =
        ".neuron-slides-wrapper[data-animation-id='" +
        this.elements.$postsSliderWrapper.data("animation-id") +
        "'] .swiper-slide",
      self = this;

    if (animation != "yes" || !animationType) {
      return;
    }

    // Observer
    var box = document.querySelectorAll(uniqueSelector);

    if (self.getWidgetType() == "neuron-slides") {
      box = document.querySelectorAll(
        uniqueSelector + " .neuron-slide-animation"
      );
    }

    const config = {
      root: null, // sets the framing element to the viewport
      rootMargin: "0px",
      threshold: 0.1,
    };

    let observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (item) {
        if (item.intersectionRatio > 0.1) {
          setTimeout(() => {
            item.target.classList.add(...[animationType, "active"]);
          }, item.target.dataset.delay);
        }
      });
    }, config);

    box.forEach(function (item) {
      item.dataset.delay = animationSeconds;

      observer.observe(item);

      if (self.getWidgetType() == "neuron-slides") {
        animationSeconds += animationDelay;

        if (animationSeconds > animationDelay * 3) {
          animationSeconds = animationDelay;
        }
      } else {
        if (animationSeconds !== 0 && animationSeconds !== "") {
          animationSeconds += animationDelay;

          if (animationDelayReset < animationSeconds) {
            animationSeconds = animationDelay;
          }
        }
      }
    });
  },

  initLoading: function initLoading() {
    this.loadingAnimations();
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    if (!elementorFrontend.isEditMode()) {
      this.initLoading();
    }
  },
});
