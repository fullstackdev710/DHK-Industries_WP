module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      classes: {
        fitHeight: "neuron-fit-height",
        hasItemRatio: "l-neuron-grid--item-ratio",
      },
      selectors: {
        postsContainer: ".l-neuron-grid",
        postsSliderWrapper: ".neuron-slides-wrapper",
        postsSlider: ".neuron-slides",
        post: ".m-neuron-gallery__item",
        postThumbnail: ".m-neuron-gallery__thumbnail",
        postThumbnailImage: ".m-neuron-gallery__thumbnail img",
        overlay: ".m-neuron-gallery__overlay",
        filter: ".m-neuron-filters__item",
        filterActive: ".m-neuron-filters--dropdown__active",
        filtersDropdown: ".m-neuron-filters--dropdown",
        filterCarret: ".m-neuron-filters--dropdown-carret",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $postsContainer: this.$element.find(selectors.postsContainer),
      $postsSliderWrapper: this.$element.find(selectors.postsSliderWrapper),
      $postsSlider: this.$element.find(selectors.postsSlider),
      $posts: this.$element.find(selectors.post),
      $postThumbnail: this.$element.find(selectors.postThumbnail),
      $filter: this.$element.find(selectors.filter),
      $filterActive: this.$element.find(selectors.filterActive),
      $filtersDropdown: this.$element.find(selectors.filtersDropdown),
      $filterCarret: this.$element.find(selectors.filterCarret),
    };
  },

  bindEvents: function bindEvents() {
    var cid = this.getModelCID();
    elementorFrontend.addListenerOnce(cid, "resize", this.onWindowResize);
  },

  setColsCountSettings: function setColsCountSettings() {
    var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
      settings = this.getElementSettings(),
      colsCount;

    switch (currentDeviceMode) {
      case "mobile":
        colsCount = settings["columns_mobile"];
        break;
      case "tablet":
        colsCount = settings["columns_tablet"];
        break;
      default:
        colsCount = settings["columns"];
    }

    this.setSettings("colsCount", colsCount);
  },

  fitImages: function fitImages() {
    if (this.isMasonryEnabled() || this.isMetroEnabled()) {
      return;
    }

    objectFitPolyfill(
      this.$element.find(this.getSettings("selectors.postThumbnailImage"))
    );
  },

  imageAnimation: function imageAnimation() {
    if (
      this.getElementSettings("image_animation_hover") &&
      this.getElementSettings("image_animation_hover") != "none"
    ) {
      this.elements.$postThumbnail.addClass(
        "m-neuron-gallery__thumbnail--" +
          this.getElementSettings("image_animation_hover")
      );
    }
  },

  isMasonryEnabled: function isMasonryEnabled() {
    return this.getElementSettings("layout") == "masonry";
  },

  isMetroEnabled: function isMetroEnabled() {
    return this.getElementSettings("layout") == "metro";
  },

  initMasonry: function initMasonry() {
    this.runMasonry();
  },

  initMetro: function initMetro() {
    this.runMetro();
  },

  reCalculate: function reCalculate() {
    var gridSelector =
      ".l-neuron-grid[data-masonry-id='" +
      this.elements.$postsContainer.data("masonry-id") +
      "']";

    var $grid = jQuery(gridSelector);

    if (this.isMasonryEnabled() || this.isMetroEnabled()) {
      if ($grid.data("packery")) {
        $grid.packery("destroy");

        this.runCalculation();
      }
    }
  },

  runCalculation: function runCalculation() {
    var masonrySelector =
      ".l-neuron-grid[data-masonry-id='" +
      this.elements.$postsContainer.data("masonry-id") +
      "']";

    var $grid = jQuery(masonrySelector);

    if ($grid.length) {
      $grid.imagesLoaded(function () {
        $grid.packery({
          itemSelector: ".l-neuron-grid__item",
        });
      });
    }
  },

  runMasonry: function runMasonry() {
    if (
      this.getElementSettings("carousel") == "yes" ||
      !this.isMasonryEnabled()
    ) {
      return;
    }

    this.runCalculation();
  },

  runMetro: function runMetro() {
    var elements = this.elements,
      hasMetro = this.isMetroEnabled();

    if (hasMetro) {
      elements.$postsContainer.toggleClass("l-neuron-grid--metro", hasMetro);

      this.runCalculation();
    }
  },

  destroyTooltip: function destroyTooltip() {
    var $body = elementorFrontend.elements.$document.find("body");

    if ($body.find("#a-tooltip-caption")) {
      $body.find("#a-tooltip-caption").remove();
    }
  },

  runTooltipEffect: function runTooltipEffect() {
    var $body = elementorFrontend.elements.$document.find("body");

    if (this.getElementSettings("hover_animation") != "tooltip") {
      this.destroyTooltip();
      return;
    } else if ($body.find("#a-tooltip-caption").length > 0) {
      return;
    }

    this.tooltipEffect();
  },

  tooltipMarkup: function tooltipMarkup() {
    return '<div id="a-tooltip-caption" class="m-neuron-gallery--tooltip"><div class="m-neuron-gallery--tooltip__inner"></div></div>';
  },

  tooltipEffect: function tooltipEffect() {
    var $body = elementorFrontend.elements.$document.find("body"),
      elements = this.elements,
      $postsContainer = elements.$postsContainer,
      $ = jQuery;

    if ($body.find("#a-tooltip-caption").length <= 0) {
      $($body).append(this.tooltipMarkup());
    }

    var $tooltipCaption = $("#a-tooltip-caption"),
      $tooltipTitle = $tooltipCaption.find(".m-neuron-gallery--tooltip__inner");

    $postsContainer.on("mousemove", function (e) {
      $tooltipCaption.css({
        top: e.clientY,
        left: e.clientX,
      });
    });

    $postsContainer
      .find(".m-neuron-gallery__thumbnail--link")
      .on("mouseover", function (e) {
        var title = $(this).find(".m-neuron-gallery__overlay--detail"),
          titleMarkup = "";

        if (title) {
          title.each(function () {
            titleMarkup +=
              "<span class='m-neuron-gallery--tooltip__detail'>" +
              $(this).text() +
              "</span>";
          });

          $tooltipTitle.html(titleMarkup);

          setTimeout(function () {
            $tooltipCaption
              .addClass("active")
              .attr("data-id", $postsContainer.data("id"));
          }, 1);
        }
      })
      .on("mouseout", function (e) {
        $tooltipCaption.removeClass("active");
      });
  },

  fixedMarkup: function fixedMarkup() {
    return '<div id="a-fixed-caption" class="m-neuron-gallery--fixed"></div>';
  },

  destroyFixed: function destroyFixed() {
    var $body = elementorFrontend.elements.$document.find("body");

    if ($body.find("#a-fixed-caption")) {
      $body.find("#a-fixed-caption").remove();
    }
  },

  runFixedEffect: function runFixedEffect() {
    var $body = elementorFrontend.elements.$document.find("body");

    if (this.getElementSettings("hover_animation") != "fixed") {
      this.destroyFixed();
      return;
    } else if ($body.find("#a-fixed-caption").length > 0) {
      return;
    }

    this.fixedEffect();
  },

  fixedEffect: function fixedEffect() {
    var $body = elementorFrontend.elements.$document.find("body"),
      elements = this.elements,
      $postsContainer = elements.$postsContainer,
      $ = jQuery;

    if ($body.find("#a-fixed-caption").length <= 0) {
      $($body).append(this.fixedMarkup());
    }

    $postsContainer
      .find(".m-neuron-gallery__thumbnail--link")
      .each(function () {
        var title = $(this).find(".m-neuron-gallery__overlay--detail"),
          titleMarkup = "";

        if (title) {
          title.each(function () {
            titleMarkup +=
              "<span class='m-neuron-gallery--fixed__detail'>" +
              $(this).text() +
              "</span>";
          });

          $("#a-fixed-caption").append(
            '<article data-id="' +
              $(this).parents(".m-neuron-gallery__item").data("id") +
              '"><div class="m-neuron-gallery--fixed__inner">' +
              titleMarkup +
              "</div></article>"
          );
        }
      });

    $postsContainer
      .find(".m-neuron-gallery__thumbnail--link")
      .on("mouseover", function (e) {
        var selectorID = $(this).parents(".m-neuron-gallery__item").data("id");

        $("#a-fixed-caption article").each(function () {
          if (selectorID == $(this).data("id")) {
            $(this).addClass("active");
            $(".m-neuron-gallery__overlay--hover-animation-fixed").addClass(
              "active"
            );
          }
        });
      })
      .on("mouseout", function (e) {
        var selectorID = $(this).parents(".m-neuron-gallery__item").data("id");

        $("#a-fixed-caption article").each(function () {
          if (selectorID == $(this).data("id")) {
            $(this).removeClass("active");
            $(".m-neuron-gallery__overlay--hover-animation-fixed").removeClass(
              "active"
            );
          }
        });
      });
  },

  initLoading: function initLoading() {
    imagesLoaded(this.elements.$postsContainer, this.loadingAnimations);
  },

  runDropdownFilters: function runDropdownFilters() {
    var $ = jQuery,
      self = this,
      closeOnClick = this.getElementSettings("filters_close_click");

    $(this.elements.$filterActive).on("click", function () {
      $(self.elements.$filtersDropdown).toggleClass("active");

      self.checkFilterCarret();
    });

    $(this.elements.$filter).on("click", function () {
      if (closeOnClick == "yes") {
        $(self.elements.$filtersDropdown).toggleClass("active");
      }

      $(self.elements.$filterActive).find("span").text($(this).text());
      self.checkFilterCarret();
    });
  },

  runFilters: function runFilters() {
    if (elementorFrontend.isEditMode()) {
      return;
    }

    var $ = jQuery,
      self = this,
      filter = "",
      $grid = this.elements.$postsContainer,
      $posts = this.elements.$posts,
      activeItem = "",
      animation = this.getElementSettings("animation"),
      animationType = this.getCurrentDeviceSetting("neuron_animations"),
      animationDelay = parseInt(
        this.getCurrentDeviceSetting("animation_delay")
      ),
      hasDelayAnimation =
        animation == "yes" && animationDelay != 0 && !!animationType;

    if ($grid.length > 0) {
      $(this.elements.$filter).on("click", function () {
        filter = $(this).data("filter");

        // Recalculation -- Height Change -- TODO: Change later
        jQuery(window).trigger("resize");

        // Active
        $(this).addClass("active").siblings().removeClass("active");

        $posts.removeClass("active").hide();

        // All
        if (filter == "*") {
          setTimeout(() => {
            $posts.show();

            self.reCalculate();
          }, 300);

          return;
        }

        setTimeout(() => {
          $posts.each(function () {
            if (!$(this).hasClass(filter)) {
              $(this).hide();
            } else {
              if (hasDelayAnimation) {
                $(this).attr("data-delay", "100");
              }

              $(this).show();

              activeItem = {
                term: filter,
                id: $(this).data("id"),
              };

              self.getFilteredItemElements['"' + activeItem.id + '"'] =
                activeItem;
            }
          });

          // Recalculate
          self.reCalculate();
        }, 200);
      });
    }
  },

  loadingAnimations: function loadingAnimations() {
    var elementSettings = this.getElementSettings(),
      animation = elementSettings.animation,
      animationType = this.getCurrentDeviceSetting("neuron_animations"),
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
      uniqueSelector =
        ".l-neuron-grid[data-masonry-id='" +
        this.elements.$postsContainer.data("masonry-id") +
        "'] .l-neuron-grid__item",
      self = this;

    if (animation != "yes" || !animationType) {
      return;
    }

    // Carousel
    if (this.getElementSettings("carousel") == "yes") {
      uniqueSelector =
        ".neuron-slides-wrapper[data-animation-id='" +
        this.elements.$postsSliderWrapper.data("animation-id") +
        "'] .swiper-slide";
    }

    // Observer
    var box = document.querySelectorAll(uniqueSelector);

    const config = {
      root: null, // sets the framing element to the viewport
      rootMargin: "0px",
      threshold: 0.05,
    };

    let observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (item) {
        if (item.intersectionRatio > 0.05) {
          setTimeout(() => {
            item.target.classList.add(
              ...[animationType, animationDuration, "active"]
            );
          }, item.target.dataset.delay);
        }
      });
    }, config);

    box.forEach(function (item) {
      item.dataset.delay = animationSeconds;

      observer.observe(item);

      if (animationSeconds !== 0 && animationSeconds !== "") {
        animationSeconds += animationDelay;

        if (animationDelayReset < animationSeconds) {
          animationSeconds = animationDelay;
        }
      }
    });
  },

  shouldReload: function shouldReload(propertyName) {
    var shouldReload = [
      "columns",
      "columns_gap",
      "row_gap",
      "spacing_offset",
      "image_width",
      "neuron_animations",
    ];

    if (shouldReload.includes(propertyName)) {
      return true;
    }

    return;
  },

  run: function run() {
    setTimeout(this.fitImages, 0);

    this.runFilters();
    this.initMasonry();
    this.initMetro();

    // Hover Animations
    this.runTooltipEffect();
    this.runFixedEffect();

    // Image Animation
    this.imageAnimation();

    if (!elementorFrontend.isEditMode()) {
      this.initLoading();
    }
  },

  onElementChange: function onElementChange(propertyName) {
    var self = this;

    setTimeout(self.fitImages);

    if (self.shouldReload(propertyName)) {
      setTimeout(self.reCalculate);
    }

    // Animations
    setTimeout(self.runTooltipEffect);
    setTimeout(self.runFixedEffect);

    if (self.shouldReload(propertyName)) {
      setTimeout(self.initLoading);
    }
  },

  onWindowResize: function onWindowResize() {
    this.fitImages();
    this.reCalculate();
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    this.getFilteredItemElements = [];

    this.run();
  },
});
