module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        interactiveImages: ".m-neuron-interactive-posts__images",
        interactiveImage: ".m-neuron-interactive-posts__image",
        interactiveLinks: ".m-neuron-interactive-posts__links",
        interactiveItem: ".m-neuron-interactive-posts__item",
        interactivePosts: ".m-neuron-interactive-posts",
        showMore: "#load-more-posts",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $interactiveImages: this.$element.find(selectors.interactiveImages),
      $interactiveImage: this.$element.find(selectors.interactiveImage),
      $interactiveLinks: this.$element.find(selectors.interactiveLinks),
      $interactiveItem: this.$element.find(selectors.interactiveItem),
      $interactivePosts: this.$element.find(selectors.interactivePosts),
      $showMore: this.$element.find(selectors.showMore),
    };
  },

  onHover: function onHover() {
    var $elements = this.elements,
      $interactiveImages = $elements.$interactiveImages,
      $interactiveLinks = $elements.$interactiveLinks,
      imageCoverage = this.getElementSettings("image_coverage"),
      imageAnimation = this.getCurrentDeviceSetting("image_animation"),
      imageAnimationDuration = this.getCurrentDeviceSetting(
        "image_animation_duration"
      ),
      $ = jQuery;

    $interactiveLinks.on("mouseover", "article", function () {
      var id = $(this).data("id"),
        $item = $interactiveImages.find(
          '.m-neuron-interactive-posts__image[data-id="' + id + '"]'
        );

      $interactiveImages
        .find(".m-neuron-interactive-posts__image")
        .removeClass("active")
        .removeClass(imageAnimationDuration)
        .removeClass(imageAnimation);

      $item.addClass("active");

      if (imageAnimation) {
        $item.addClass(imageAnimationDuration).addClass(imageAnimation);
      }
    });

    if (imageCoverage == "text") {
      $interactiveLinks.on("mousemove", function (e) {
        $interactiveImages.css({
          transform:
            "translateX(" + e.clientX + "px) translateY(" + e.clientY + "px)",
          opacity: 1,
        });
      });
    }
  },

  firstActive: function firstActive() {
    var elementSettings = this.getElementSettings(),
      firstActive = elementSettings.first_active,
      imageAnimation = this.getCurrentDeviceSetting("image_animation"),
      imageAnimationDuration = this.getCurrentDeviceSetting(
        "image_animation_duration"
      ),
      $ = jQuery;

    if (firstActive != "yes" || elementSettings.image_coverage == "text") {
      return;
    }

    var $interactiveImage = this.elements.$interactiveImage;

    if (imageAnimation) {
      $($interactiveImage[0])
        .addClass(imageAnimationDuration)
        .addClass(imageAnimation);
    }

    $($interactiveImage[0]).addClass("active");
  },

  mouseOut: function mouseOut() {
    var elementSettings = this.getElementSettings(),
      mouseOut = elementSettings.mouse_out,
      imageAnimation = this.getCurrentDeviceSetting("image_animation"),
      imageAnimationDuration = this.getCurrentDeviceSetting(
        "image_animation_duration"
      ),
      $ = jQuery;

    if (mouseOut != "yes" && elementSettings.image_coverage != "text") {
      return;
    }

    var $interactiveImage = this.elements.$interactiveImage;

    this.$element
      .find(".m-neuron-interactive-posts__links")
      .on("mouseleave", function () {
        if (imageAnimation) {
          $($interactiveImage)
            .removeClass("active")
            .removeClass(imageAnimationDuration)
            .removeClass(imageAnimation);
        } else {
          $($interactiveImage).removeClass("active");
        }
      });
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
        ".m-neuron-interactive-posts[data-interactive-id='" +
        this.elements.$interactivePosts.data("interactive-id") +
        "'] .m-neuron-interactive-posts__item";

    if (animation != "yes" || !animationType) {
      return;
    }

    // Observer
    var box = document.querySelectorAll(uniqueSelector);

    const config = {
      root: null, // sets the framing element to the viewport
      rootMargin: "0px",
      threshold: 0,
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

  initLoading: function initLoading() {
    imagesLoaded(this.elements.$interactiveItem, this.loadingAnimations);
  },

  loadedPosts: function loadedPosts($grid) {
    var data = [],
      $ = jQuery;

    $grid.find(".m-neuron-interactive-posts__item").map(function (i, val) {
      data[i] = $(this).data("id").toString();
    });

    return data;
  },

  loadMorePosts: function loadMorePosts() {
    var $loadMoreButton = this.elements.$showMore,
      self = this;

    $loadMoreButton.on("click", function (e) {
      e.preventDefault();

      self.loadMore(jQuery(this));
    });
  },

  loadMore: function loadMore($button) {
    var data = {};
    data.exclude = [];

    var $interactiveLinks = this.elements.$interactiveLinks,
      $interactiveImages = this.elements.$interactiveImages,
      $interactivePosts = this.elements.$interactivePosts,
      self = this,
      $loadMoreButton = this.elements.$showMore,
      loadMorePosts = $loadMoreButton.data("text"),
      excludedQuery = $loadMoreButton.data("exclude"),
      loadingText = $loadMoreButton.data("loading");

    data.exclude = this.loadedPosts($interactiveLinks);

    if (excludedQuery) {
      if (typeof excludedQuery == "string") {
        excludedQuery = excludedQuery.split(", ");

        data.exclude = data.exclude.concat(excludedQuery, data.exclude);
      } else {
        data.exclude.push(excludedQuery);
      }
    }

    jQuery.ajax({
      type: "GET",
      url: window.location.href,
      data: data,
      beforeSend: function () {
        $button.html(loadingText).prop("disabled", true);
      },
      success: function (data) {
        var $links = jQuery(data).find(
            ".m-neuron-interactive-posts[data-interactive-id='" +
              $interactivePosts.data("interactive-id") +
              "'] .m-neuron-interactive-posts__item"
          ),
          $images = jQuery(data).find(
            ".m-neuron-interactive-posts[data-interactive-id='" +
              $interactivePosts.data("interactive-id") +
              "'] .m-neuron-interactive-posts__image"
          );

        var $hasMore = jQuery(data).find("#load-more-posts").length;

        if ($links.length > 0) {
          $button.html(loadMorePosts).prop("disabled", false);

          $interactiveLinks.append($links);
          $interactiveImages.append($images);

          $links.imagesLoaded(function () {
            self.loadingAnimations($links);
          });
        }

        if (!$hasMore) {
          $button.parent().hide();
        }
      },
      error: function () {
        $button.html("No More Posts");
      },
    });
  },

  run: function run() {
    this.onHover();

    if (!elementorFrontend.isEditMode()) {
      this.initLoading();
    }

    this.firstActive();
    this.mouseOut();
    this.loadMorePosts();
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    this.run();
  },
});
