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
        post: ".m-neuron-post",
        postThumbnail: ".m-neuron-post__thumbnail",
        postThumbnailImage: ".m-neuron-post__thumbnail img",
        filter: ".m-neuron-filters__item",
        filterActive: ".m-neuron-filters--dropdown__active",
        filtersDropdown: ".m-neuron-filters--dropdown",
        filterCarret: ".m-neuron-filters--dropdown-carret",
        showMore: "#load-more-posts",
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
      $showMore: this.$element.find(selectors.showMore),
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

  initLoading: function initLoading() {
    imagesLoaded(this.elements.$postsContainer, this.loadingAnimations);
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
        "m-neuron-post__thumbnail--" +
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

  checkFilterCarret: function checkFilterCarret() {
    var self = this;

    if (self.elements.$filtersDropdown.hasClass("active")) {
      self.elements.$filterCarret.addClass("active");
    } else {
      self.elements.$filterCarret.removeClass("active");
    }
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

  runFilters: function runFilters($newPosts = "") {
    if (elementorFrontend.isEditMode()) {
      return;
    }

    var $ = jQuery,
      self = this,
      filter = "",
      $grid = this.elements.$postsContainer,
      $posts = this.elements.$posts,
      $loadMoreButton = this.elements.$showMore,
      loadMorePosts = $loadMoreButton.data("text"),
      activeItem = "",
      itemSelector =
        ".l-neuron-grid[data-masonry-id='" +
        this.elements.$postsContainer.data("masonry-id") +
        "'] .l-neuron-grid__item",
      animation = this.getElementSettings("animation"),
      animationType = this.getCurrentDeviceSetting("neuron_animations"),
      animationDelay = parseInt(
        this.getCurrentDeviceSetting("animation_delay")
      ),
      hasDelayAnimation =
        animation == "yes" && animationDelay != 0 && !!animationType;

    if ($grid.length > 0) {
      // New Posts
      if ($newPosts.length > 0) {
        $posts = $(itemSelector);
      }

      $(this.elements.$filter).on("click", function () {
        filter = $(this).data("filter");

        // Recalculation -- Height Change -- TODO: Change later for better solution
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

        $loadMoreButton.data("filter", filter);

        // Show More
        if ($(this).data("all") === true) {
          $loadMoreButton.parent().hide();
        } else {
          $loadMoreButton
            .html(loadMorePosts)
            .prop("disabled", false)
            .parent()
            .show();
        }

        // Grid Height
        setTimeout(() => {
          self.gridHeight($grid);
        }, 301);
      });
    }
  },

  gridHeight: function gridHeight($grid) {
    var isMetroMasonry = this.isMetroEnabled() || this.isMasonryEnabled();

    if ($grid.length > 0 && !isMetroMasonry) {
      $grid.css("min-height", "");
      $grid.css({ "min-height": $grid.height() });
    }
  },

  loadedPosts: function loadedPosts($grid) {
    var data = [],
      $ = jQuery;

    $grid.find(".m-neuron-post").map(function (i, val) {
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

    var $grid = this.elements.$postsContainer,
      filter = $button.data("filter"),
      self = this,
      $loadMoreButton = this.elements.$showMore,
      loadMorePosts = $loadMoreButton.data("text"),
      excludedQuery = $loadMoreButton.data("exclude"),
      loadingText = $loadMoreButton.data("loading"),
      layout = this.getElementSettings("layout");

    if (filter && filter !== "*") {
      data.termType = filter.split("-")[0];
      data.filter = filter.substring(filter.indexOf("-") + 1);
    }

    var filteredItems = this.getFilteredItemElements;

    if (filteredItems) {
      data.exclude = this.loadedPosts($grid);
    }

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
        var $data = jQuery(data).find(
          ".l-neuron-grid[data-masonry-id='" +
            $grid.data("masonry-id") +
            "'] .m-neuron-post"
        );
        var $hasMore = jQuery(data).find("#load-more-posts").length;

        if ($data.length > 0) {
          if (layout == "metro") {
            $data.addClass("l-neuron-grid__item-metro-full");
          }

          $button.html(loadMorePosts).prop("disabled", false);

          if (self.isMetroEnabled() || self.isMasonryEnabled()) {
            $grid.packery().append($data).packery("appended", $data).packery();
          } else {
            $grid.append($data);
          }

          self.runFilters($data);

          $data.imagesLoaded(function () {
            self.loadingAnimations($data);
            self.fitImages();
            self.gridHeight($grid);
          });
        }

        if (!$hasMore) {
          $button.parent().hide();
          var filterClass = filter;

          if (filterClass == "*") {
            $button
              .parents(".elementor-widget-container")
              .find("li")
              .attr("data-all", true);
          } else {
            $button
              .parents(".elementor-widget-container")
              .find('li[data-filter="' + filterClass + '"]')
              .attr("data-all", true);
          }
        }
      },
      error: function () {
        $button.html("No More Posts");
      },
    });
  },

  loadingAnimations: function loadingAnimations($newPosts = "") {
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
        "'] .l-neuron-grid__item";

    if (animation != "yes" || !animationType) {
      return;
    }

    // New Posts
    if ($newPosts.length > 0) {
      $posts = $newPosts;
    } else {
      $posts = this.elements.$posts;
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

  yithFilters: function yithFilters() {
    var self = this,
      $posts = "",
      $grid = this.elements.$postsContainer,
      itemSelector =
        ".l-neuron-grid[data-masonry-id='" +
        this.elements.$postsContainer.data("masonry-id") +
        "'] .l-neuron-grid__item";

    jQuery(document).on("yith-wcan-ajax-filtered", function (response) {
      $posts = jQuery(itemSelector);
      $grid = $posts.closest(".l-neuron-grid");

      if (!self.isMasonryEnabled()) {
        $grid.addClass("l-neuron-grid--item-ratio");
      } else {
        self.runCalculation();
      }

      $posts.imagesLoaded(function () {
        self.loadingAnimations($posts);
        self.fitImages();
        self.gridHeight($grid);
      });
    });
  },

  run: function run() {
    setTimeout(this.fitImages, 0);

    this.runFilters();
    this.runDropdownFilters();
    this.initMasonry();
    this.initMetro();
    this.imageAnimation();
    this.loadMorePosts();

    if (!elementorFrontend.isEditMode()) {
      this.initLoading();
    }
  },

  onElementChange: function onElementChange(propertyName) {
    var self = this;

    setTimeout(self.fitImages);
    setTimeout(self.reCalculate);
    setTimeout(self.initLoading);
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

    this.yithFilters();
  },
});
