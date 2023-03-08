module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        slider: ".neuron-slides-wrapper",
        slide: ".swiper-slide",
        slideBackground: ".swiper-slide--background",
        activeSlide: ".swiper-slide-active",
        activeDuplicate: ".swiper-slide-duplicate-active",
      },
      classes: {
        animated: "animated",
        kenBurnsActive: "h-kenBurnsNeuron--active",
      },
      attributes: {
        dataSliderOptions: "slider_options",
        dataAnimation: "animation",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    var elements = {
      $slider: this.$element.find(selectors.slider),
    };

    elements.$mainSwiperSlides = elements.$slider.find(selectors.slide);

    return elements;
  },

  getSlidesCount: function getSlidesCount() {
    return this.elements.$mainSwiperSlides.length;
  },

  getInitialSlide: function getInitialSlide() {
    var editSettings = this.getEditSettings();

    return editSettings.activeItemIndex ? editSettings.activeItemIndex - 1 : 0;
  },

  getSpaceBetween(device) {
    var propertyName = "space_between";

    if (device && "desktop" !== device) {
      propertyName += "_" + device;
    }

    return parseInt(this.getElementSettings(propertyName).size || 0);
  },

  getSwiperOptions: function getSwiperOptions() {
    var elementSettings = this.getElementSettings(),
      settings = this.getSettings(),
      spacing = 0,
      self = this;

    // Space Between
    if (this.getWidgetType() == "neuron-slides") {
      spacing = 0;

      if (
        jQuery(self.elements.$mainSwiperSlides[0])
          .find(".swiper-slide--background")
          .hasClass("h-kenBurnsNeuron")
      ) {
        jQuery(self.elements.$mainSwiperSlides[0])
          .find(".swiper-slide--background")
          .addClass(settings.classes.kenBurnsActive);
      }
    } else {
      spacing = this.getSpaceBetween("mobile");
    }

    var elementorBreakpoints = elementorFrontend.config.breakpoints;

    // Swiper Options
    var swiperOptions = {
      resistance: true,
      resistanceRatio: 0,
      grabCursor: true,
      initialSlide: this.getInitialSlide(),
      slidesPerView: this.getSlidesPerView("mobile"),
      slidesPerGroup: this.getSlidesToScroll("mobile"),
      loop: "yes" === elementSettings.infinite,
      speed: elementSettings.transition_speed,
      effect: elementSettings.transition ? elementSettings.transition : "slide",
      centeredSlides: "yes" === elementSettings.centered_slides,
      watchSlidesVisibility: true,
      spaceBetween: spacing,
      keyboard: {
        enabled: elementSettings.keyboard_navigation,
        onlyInViewport: false,
      },
      on: {
        slideChange: function slideChange() {
          if (self.$activeImageBg) {
            self.$activeImageBg.removeClass(settings.classes.kenBurnsActive);
          }

          self.activeItemIndex = self.swipers.main
            ? self.swipers.main.activeIndex
            : self.getInitialSlide();

          if (!self.swipers.main) {
            self.$activeImageBg = jQuery(
              self.elements.$mainSwiperSlides[0]
            ).children(settings.selectors.slideBackground);
          } else {
            self.$activeImageBg = jQuery(
              self.swipers.main.slides[self.activeItemIndex]
            ).children(settings.selectors.slideBackground);
          }

          self.$activeImageBg.addClass(settings.classes.kenBurnsActive);
        },
      },
    };

    swiperOptions.breakpoints = {};
    var breakpoints = {};

    if (this.getWidgetType() != "neuron-slides") {
      // Desktop
      breakpoints[elementorBreakpoints.lg] = {
        slidesPerView: this.getSlidesPerView("desktop"),
        slidesPerGroup: this.getSlidesToScroll("desktop"),
        spaceBetween: this.getSpaceBetween("desktop"),
      };

      // Tablet
      breakpoints[elementorBreakpoints.md - 1] = {
        slidesPerView: this.getSlidesPerView("tablet"),
        slidesPerGroup: this.getSlidesToScroll("tablet"),
        spaceBetween: this.getSpaceBetween("tablet"),
      };

      swiperOptions.breakpoints = breakpoints;
    } else {
      swiperOptions.slidesPerView = 1;
    }

    // Show Arrows
    var showArrows =
        "arrows" === elementSettings.navigation ||
        "arrows-dots" === elementSettings.navigation,
      pagination =
        "dots" === elementSettings.navigation ||
        "arrows-dots" === elementSettings.navigation;

    if (showArrows) {
      swiperOptions.navigation = {
        prevEl: this.$element.find(".neuron-swiper-button--prev"),
        nextEl: this.$element.find(".neuron-swiper-button--next"),
      };
    }

    // Dots Pagination
    var paginationType = elementSettings.dots_style;

    if (pagination) {
      swiperOptions.pagination = {
        el: this.$element.find(".swiper-pagination"),
        type: paginationType,
        clickable: true,
      };
    }

    if (paginationType == "numbers") {
      delete swiperOptions.pagination.type;

      swiperOptions.pagination.renderBullet = function (index, className) {
        return (
          '<span class="swiper-pagination-numbers ' +
          className +
          '">' +
          (index + 1) +
          "</span>"
        );
      };
    }

    if (paginationType == "fraction") {
      swiperOptions.pagination.renderFraction = function (
        currentClass,
        totalClass
      ) {
        return (
          '<span class="swiper-pagination-fraction-number ' +
          currentClass +
          '"></span> ' +
          elementSettings.dots_fraction_divider +
          ' <span class="swiper-pagination-fraction-number ' +
          totalClass +
          '"></span>'
        );
      };
    }

    if (paginationType == "scrollbar") {
      swiperOptions.scrollbar = {
        el: this.$element.find(".swiper-scrollbar"),
        hide: false,
      };
    }

    if (!this.isEdit && elementSettings.autoplay == "yes") {
      swiperOptions.autoplay = {
        delay: elementSettings.autoplay_speed,
        disableOnInteraction: !!elementSettings.pause_on_hover,
      };
    }

    if (true === swiperOptions.loop) {
      swiperOptions.loopedSlides = this.getSlidesCount();
    }

    if ("fade" === swiperOptions.effect) {
      swiperOptions.fadeEffect = { crossFade: true };
    }

    return swiperOptions;
  },

  getDeviceSlidesPerView: function getDeviceSlidesPerView(option) {
    var number = "slides_per_view" + ("desktop" === option ? "" : "_" + option);

    if (this.getElementSettings(number) == "auto") {
      return "auto";
    }

    return Math.min(
      this.getSlidesCount(),
      +this.getElementSettings(number) ||
        this.getSettings("slidesPerView")[option]
    );
  },

  getSlidesPerView: function getSlidesPerView(option) {
    if (this.getWidgetType() == "neuron-slides") {
      return 1;
    }

    return this.getDeviceSlidesPerView(option)
      ? this.getDeviceSlidesPerView(option)
      : 1;
  },

  getDeviceSlidesToScroll: function getDeviceSlidesToScroll(e) {
    var t = "slides_to_scroll" + ("desktop" === e ? "" : "_" + e);
    return Math.min(this.getSlidesCount(), +this.getElementSettings(t) || 1);
  },

  getSlidesToScroll: function getSlidesToScroll(e) {
    return this.getDeviceSlidesToScroll(e)
      ? this.getDeviceSlidesToScroll(e)
      : 1;
  },

  initSlider: function initSlider() {
    var $slider = this.elements.$slider;

    if (!$slider.length) {
      return;
    }

    this.swipers = {};
    var self = this;

    if (1 >= this.getSlidesCount()) {
      return;
    }

    if ("undefined" === typeof Swiper) {
      const asyncSwiper = elementorFrontend.utils.swiper;

      new asyncSwiper($slider, self.getSwiperOptions()).then(
        (newSwiperInstance) => {
          mySwiper = newSwiperInstance;
        }
      );
    } else {
      this.swipers.main = new Swiper($slider, self.getSwiperOptions());
    }
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    if (2 > this.getSlidesCount()) {
      return;
    }

    this.removeFlickr();

    this.initSlider();
  },

  removeFlickr: function removeFlickr() {
    var $slider = this.elements.$slider;

    if ($slider.closest(".neuron-swiper").length > 0) {
      var $parentSlider = $slider.closest(".neuron-swiper");

      if ($parentSlider.hasClass("neuron-swiper--prevent-flickr")) {
        $parentSlider.removeClass("neuron-swiper--prevent-flickr");
      }
    }
  },

  onElementChange: function onElementChange(propertyName) {
    if (1 >= this.getSlidesCount()) {
      return;
    }

    if (0 === propertyName.indexOf("width")) {
      this.swipers.main.update();
    }
  },

  onEditSettingsChange: function onEditSettingsChange(propertyName) {
    if (1 >= this.getSlidesCount()) {
      return;
    }

    if ("activeItemIndex" === propertyName) {
      this.swipers.main.slideToLoop(
        this.getEditSettings("activeItemIndex") - 1
      );
    }
  },
});
