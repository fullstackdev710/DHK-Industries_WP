const PostsHandler = require("./posts");

module.exports = PostsHandler.extend({
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
        post: ".m-neuron-portfolio",
        postThumbnail: ".m-neuron-post__thumbnail",
        title: ".m-neuron-portfolio__title",
        category: ".m-neuron-portfolio__category",
        price: ".m-neuron-portfolio__price",
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
      $title: this.$element.find(selectors.title),
      $category: this.$element.find(selectors.category),
      $price: this.$element.find(selectors.price),
      $filter: this.$element.find(selectors.filter),
      $filterActive: this.$element.find(selectors.filterActive),
      $filtersDropdown: this.$element.find(selectors.filtersDropdown),
      $filterCarret: this.$element.find(selectors.filterCarret),
      $showMore: this.$element.find(selectors.showMore),
    };
  },

  destroyTooltip: function destroyTooltip() {
    var $body = elementorFrontend.elements.$document.find("body");

    if ($body.find("#tooltip-caption")) {
      $body.find("#tooltip-caption").remove();
    }
  },

  runTooltipEffect: function runTooltipEffect() {
    var $body = elementorFrontend.elements.$document.find("body");

    if (this.getElementSettings("hover_animation") != "tooltip") {
      this.destroyTooltip();
      return;
    } else if ($body.find("#tooltip-caption").length > 0) {
      return;
    }

    this.tooltipEffect();
  },

  tooltipMarkup: function tooltipMarkup() {
    return '<div id="tooltip-caption" class="m-neuron-portfolio--hover-tooltip"><div class="m-neuron-portfolio--hover-tooltip__inner"><span class="m-neuron-portfolio--hover-tooltip__subtitle"></span><h4 class="m-neuron-portfolio--hover-tooltip__title"></h4></div></div>';
  },

  tooltipEffect: function tooltipEffect() {
    var $body = elementorFrontend.elements.$document.find("body"),
      elements = this.elements,
      $postsContainer = elements.$postsContainer,
      $ = jQuery,
      tooltipAnimation = this.getElementSettings("tooltip_animation");

    if ($body.find("#tooltip-caption").length <= 0) {
      $($body).append(this.tooltipMarkup());
    }

    var $tooltipCaption = $("#tooltip-caption"),
      $tooltipTitle = $tooltipCaption.find(
        ".m-neuron-portfolio--hover-tooltip__title"
      ),
      $tooltipSubtitle = $tooltipCaption.find(
        ".m-neuron-portfolio--hover-tooltip__subtitle"
      );

    $postsContainer.on("mousemove", function (e) {
      $tooltipCaption.css({
        top: e.clientY,
        left: e.clientX,
      });
    });

    $postsContainer
      .find(".m-neuron-portfolio__thumbnail--link")
      .on("mouseover", function (e) {
        $tooltipTitle.text($(this).find(".m-neuron-portfolio__title").text());

        if ($(this).find(".m-neuron-portfolio__price").length) {
          $tooltipSubtitle.text(
            $(this).find(".m-neuron-portfolio__price").text()
          );
        } else {
          $tooltipSubtitle.text(
            $(this).find(".m-neuron-portfolio__category").text()
          );
        }

        if ($tooltipSubtitle.text().length <= 0) {
          $tooltipSubtitle.hide();
        } else {
          $tooltipSubtitle.show();
        }

        setTimeout(function () {
          $tooltipCaption
            .addClass("active")
            .attr("data-id", $postsContainer.data("id"));

          if (tooltipAnimation != "none" && tooltipAnimation != "undefined") {
            $tooltipCaption.addClass(tooltipAnimation + " animated");
          }
        }, 1);
      })
      .on("mouseout", function (e) {
        $tooltipCaption.removeClass("active");

        if (tooltipAnimation != "none" && tooltipAnimation != "undefined") {
          $tooltipCaption.removeClass(tooltipAnimation).removeClass("animated");
        }
      });
  },

  fixedMarkup: function fixedMarkup() {
    return '<div id="fixed-caption" class="m-neuron-portfolio--hover-fixed"></div>';
  },

  destroyFixed: function destroyFixed() {
    var $body = elementorFrontend.elements.$document.find("body");

    if ($body.find("#fixed-caption")) {
      $body.find("#fixed-caption").remove();
    }
  },

  runFixedEffect: function runFixedEffect() {
    var $body = elementorFrontend.elements.$document.find("body");

    if (this.getElementSettings("hover_animation") != "fixed") {
      this.destroyFixed();
      return;
    } else if ($body.find("#fixed-caption").length > 0) {
      return;
    }

    this.fixedEffect();
  },

  fixedEffect: function fixedEffect() {
    var $body = elementorFrontend.elements.$document.find("body"),
      elements = this.elements,
      $postsContainer = elements.$postsContainer,
      $ = jQuery,
      fixedPosts = [];

    if ($body.find("#fixed-caption").length <= 0) {
      $($body).append(this.fixedMarkup());
    }

    $postsContainer
      .find(".m-neuron-portfolio__thumbnail--link")
      .each(function () {
        fixedPosts.push($(this).parents("article").data("id"));

        $subtitleSelector = ".m-neuron-portfolio__category";
        if ($(this).find(".m-neuron-portfolio__price").length > 0) {
          $subtitleSelector = ".m-neuron-portfolio__price";
        }

        $("#fixed-caption").append(
          '<article data-id="' +
            $(this).parents("article").data("id") +
            '"><div class="m-neuron-portfolio--hover-fixed__inner"><h4 class="m-neuron-portfolio--hover-fixed__title">' +
            $(this).find(".m-neuron-portfolio__title").text() +
            '</h4><span class="m-neuron-portfolio--hover-fixed__subtitle">' +
            $(this).find($subtitleSelector).text() +
            "</span></div></article>"
        );
      });

    $postsContainer
      .find(".m-neuron-portfolio__thumbnail--link")
      .on("mouseover", function (e) {
        var $selectorID = $(this).parents("article").data("id");

        $("#fixed-caption article").each(function () {
          if ($selectorID == $(this).data("id")) {
            $(this).addClass("active");
            $(".m-neuron-portfolio--hover-fixed").addClass("active");
          }
        });
      })
      .on("mouseout", function (e) {
        var $selectorID = $(this).parents("article").data("id");

        $("#fixed-caption article").each(function () {
          if ($selectorID == $(this).data("id")) {
            $(this).removeClass("active");
            $(".m-neuron-portfolio--hover-fixed").removeClass("active");
          }
        });
      });
  },

  run: function run() {
    PostsHandler.prototype.run.apply(this, arguments);

    this.runTooltipEffect();
    this.runFixedEffect();
  },

  onElementChange: function onElementChange(propertyName) {
    PostsHandler.prototype.run.apply(this, arguments);

    var self = this;

    setTimeout(self.runTooltipEffect);
    setTimeout(self.runFixedEffect);
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
