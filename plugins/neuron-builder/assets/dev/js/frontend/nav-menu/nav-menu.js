module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        menu: ".m-neuron-nav-menu",
        mobileMenu: ".m-neuron-nav-menu--mobile",
        megaMenu: ".m-neuron-nav-menu--mega-menu",
        hamburger: ".m-neuron-nav-menu__hamburger",
        subArrow: ".sub-arrow",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $menu: this.$element.find(selectors.menu),
      $mobileMenu: this.$element.find(selectors.mobileMenu),
      $megaMenu: this.$element.find(selectors.megaMenu),
      $hamburger: this.$element.find(selectors.hamburger),
      $subArrow: this.$element.find(selectors.subArrow),
    };
  },

  subMenu: function subMenu() {
    var $ = jQuery,
      timeout,
      $menu = this.elements.$menu;

    $menu.find("li.menu-item-has-children").each(function () {
      var verticalMenu = $(this).closest(".m-neuron-nav-menu--vertical"),
        subMenu = $(this).children(".sub-menu");

      if (verticalMenu.length && !subMenu.hasClass("sub-menu--vertical")) {
        subMenu.addClass("sub-menu--vertical");
      }
    });

    if (
      $menu
        .closest(".elementor-widget-neuron-nav-menu")
        .hasClass("m-neuron-nav-menu--vertical")
    ) {
      return;
    }

    $menu.find("li.menu-item-has-children").on({
      mouseenter: function () {
        clearTimeout(timeout);

        var subMenu = $(this).children(".sub-menu"),
          parentSubMenu = $(this).parents(".sub-menu"),
          windowWidth = $(window).width();

        if (
          (parentSubMenu.length && parentSubMenu.hasClass("sub-menu--left")) ||
          windowWidth - (subMenu.offset().left + subMenu.outerWidth() + 1) < 0
        ) {
          subMenu.addClass("sub-menu--left");
        }

        subMenu.addClass("active");
      },
      mouseleave: function () {
        var subMenu = $(this).children(".sub-menu");
        subMenu.removeClass("active");

        timeout = setTimeout(
          function () {
            subMenu.removeClass("sub-menu-left");
          }.bind(this),
          250
        );
      },
    });
  },

  carretIndicator: function carretIndicator(
    selector = "li.menu-item-has-children"
  ) {
    var $ = jQuery,
      $menu = this.elements.$menu;

    $menu.find(selector).each(function (index, value) {
      var item = $(value).find("a")[0];

      $(item).append("<span class='sub-arrow'><i class='fa'></i></span>");
    });
  },

  megaMenu: function megaMenu() {
    this.carretIndicator("li.m-neuron-nav-menu--mega-menu__item");

    this.calculateMegaMenu();
  },

  calculateMegaMenu: function calculateMegaMenu() {
    var $megaMenu = this.elements.$megaMenu,
      $ = jQuery;

    $megaMenu.each(function (index, value) {
      var width = jQuery(window).outerWidth(),
        offsetLeft = $(this)
          .closest(".m-neuron-nav-menu--mega-menu__item")
          .offset().left;

      $(value).css({
        width: width,
        left: -offsetLeft,
      });
    });
  },

  mobileMenu: function mobileMenu() {
    var $ = jQuery,
      $mobileMenu = this.elements.$mobileMenu,
      $hamburger = this.elements.$hamburger;

    $mobileMenu.find("li.menu-item-has-children").each(function (index, value) {
      var item = $(value).find("a")[0];

      $(item).append("<a class='sub-arrow'><i class='fa'></i></a>");
    });

    $hamburger.on("click", function (event) {
      event.preventDefault();

      $(this)
        .parent()
        .siblings(".m-neuron-nav-menu__list")
        .toggleClass("active");
    });
  },

  subArrow: function subArrow() {
    var $ = jQuery;

    $(".sub-arrow").on("click", function (event) {
      event.preventDefault();

      $(this).parent().siblings("ul").slideToggle();
    });
  },

  fullWidth: function fullWidth() {
    var $ = jQuery,
      $mobileMenu = this.elements.$mobileMenu,
      $element = $(this.$element[0]),
      isFirefox = !(window.mozInnerScreenX == null);

    if ($element.hasClass("m-neuron-nav-menu--stretch")) {
      var $elementorContainer = $mobileMenu.closest(".elementor-container"),
        columnPadding;

      if (isFirefox) {
        columnPadding = parseInt(
          $mobileMenu.closest(".elementor-widget-wrap").css("padding-left")
        );
      } else {
        columnPadding = parseInt(
          $mobileMenu.closest(".elementor-widget-wrap").css("padding")
        );
      }

      var columnLeftMargin = parseInt(
        $mobileMenu.closest(".elementor-widget-container").css("marginLeft")
      );

      columnLeftMargin = columnLeftMargin ? columnLeftMargin : 0;

      if ($elementorContainer.length) {
        var containerOffset = $elementorContainer.offset().left;
        var mobileMenuOffset = $mobileMenu.offset().left;
      }

      var width = $elementorContainer.outerWidth() - columnPadding * 2;
      var offset =
        containerOffset - mobileMenuOffset + columnPadding + columnLeftMargin;

      $mobileMenu.find(".m-neuron-nav-menu__list").css({
        width: width,
        left: offset,
      });
    }
  },

  activeOnScroll: function activeOnScroll() {
    var activeOnScroll = this.getElementSettings("active_scroll"),
      $menu = this.elements.$menu,
      $ = jQuery;

    if (activeOnScroll != "yes") {
      return;
    }

    var scrollPosition = $(document).scrollTop();

    $menu.find("li a").each(function () {
      var currentLink = $(this);

      if (!currentLink.attr("href").includes("#")) {
        return;
      }

      var ref = currentLink.attr("href").split("#");

      ref = typeof ref[1] !== "undefined" ? ref[1] : ref[0];
      var refElement = $("#" + ref);

      if (refElement.length !== 0) {
        if (
          refElement.position().top <= scrollPosition &&
          refElement.position().top + refElement.outerHeight() > scrollPosition
        ) {
          currentLink.parents("li").addClass("current-menu-item");
        } else {
          currentLink.parents("li").removeClass("current-menu-item");
        }
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
      $menu = this.elements.$menu,
      uniqueSelector = ".m-neuron-nav-menu#" + $menu.attr("id") + "> ul > li";

    if (animation != "yes" || !animationType) {
      return;
    }

    var box = document.querySelectorAll(uniqueSelector);

    const config = {
      root: null, // sets the framing element to the viewport
      rootMargin: "0px",
      threshold: 0.1,
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

  bindEvents: function bindEvents() {
    this.onWindowResize = this.onWindowResize.bind(this);

    elementorFrontend.elements.$window.on("resize", this.onWindowResize);
    elementorFrontend.elements.$window.on("scroll", this.onWindowScroll);
  },

  onWindowScroll: function onWindowScroll() {
    this.activeOnScroll();
  },

  onWindowResize: function onWindowResize() {
    this.fullWidth();
    this.calculateMegaMenu();
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    this.subMenu();
    this.carretIndicator();
    this.megaMenu();
    this.mobileMenu();
    this.subArrow();
    this.fullWidth();

    if (!elementorFrontend.isEditMode()) {
      this.loadingAnimations();
    }
  },
});
