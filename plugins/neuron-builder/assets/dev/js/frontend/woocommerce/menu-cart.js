var MiniCartHandler = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        sidebar: ".m-neuron-menu-cart__sidebar",
        products: ".m-neuron-menu-cart__products",
        icon: ".m-neuron-menu-cart__icon",
        toggle: ".m-neuron-menu-cart__toggle",
        closeButton: ".m-neuron-menu-cart__close-button",
        bottom: ".m-neuron-menu-cart__bottom",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors"),
      elements = {};

    elements.$sidebar = this.$element.find(selectors.sidebar);
    elements.$products = this.$element.find(selectors.products);
    elements.$icon = this.$element.find(selectors.icon);
    elements.$toggle = this.$element.find(selectors.toggle);
    elements.$closeButton = this.$element.find(selectors.closeButton);
    elements.$bottom = this.$element.find(selectors.bottom);

    return elements;
  },

  refreshFragments: function refreshFragments() {
    if (!elementorFrontend.isEditMode()) {
      return;
    }

    jQuery(document.body).trigger("wc_fragment_refresh");
  },

  bindEvents: function bindEvents() {
    var parentElements = this.elements,
      $sidebar = parentElements.$sidebar,
      $toggle = parentElements.$toggle,
      $closeButton = parentElements.$closeButton,
      $addToCart = jQuery(
        "a.add_to_cart_button, .elementor-product-simple .single_add_to_cart_button, .elementor-product-variable .single_add_to_cart_button"
      ),
      self = this,
      refreshFrag = 0;

    $toggle.on("click", function (event) {
      event.preventDefault();

      if (refreshFrag === 0) {
        self.refreshFragments();
      }

      refreshFrag++;

      $sidebar.toggleClass("active");
      $sidebar.removeClass("m-neuron-menu-cart__sidebar--hidden");

      jQuery("body").toggleClass("m-neuron-menu-cart__overlay--open");
    });

    $toggle.on("mouseover", function (event) {
      event.preventDefault();

      if (refreshFrag === 0) {
        self.refreshFragments();
      }

      refreshFrag++;
    });

    $closeButton.on("click", function (event) {
      event.preventDefault();

      if ($sidebar.hasClass("active")) {
        $toggle.click();
      }
    });

    $addToCart.on("click", function (event) {
      if (!$sidebar.hasClass("active")) {
        $toggle.click();
      }

      jQuery("body").addClass("m-neuron-menu-cart__overlay--open");
    });

    elementorFrontend.elements.$document.keyup(function (event) {
      var ESC_KEY = 27;
      if (ESC_KEY === event.keyCode) {
        if ($sidebar.hasClass("active")) {
          $toggle.click();
        }
      }
    });

    // Overlay Click
    document.addEventListener("click", function (e) {
      var click = jQuery(
        ".m-neuron-menu-cart__sidebar, .m-neuron-menu-cart__toggle, a.add_to_cart_button, .single_add_to_cart_button"
      );

      // If the target of the click isn't the container
      if ($sidebar.hasClass("active")) {
        if (!click.is(e.target) && click.has(e.target).length === 0) {
          jQuery("body").removeClass("m-neuron-menu-cart__overlay--open");
          $sidebar.removeClass("active");
        }
      }
    });

    this.sidebarOverflow();
  },

  sidebarOverflow: function sidebarOverflow() {
    if (this.getElementSettings("style") != "sidebar") {
      return;
    }

    var $products = this.elements.$products,
      $bottom = this.elements.$bottom;

    if ($products && $bottom) {
      $products.css("padding-bottom", $bottom.innerHeight() + 60);
    }
  },
});

module.exports = function ($scope) {
  new MiniCartHandler({
    $element: $scope,
  });
};
