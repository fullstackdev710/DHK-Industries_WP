module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        quickView: ".m-neuron-product__quick-view a",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $quickView: this.$element.find(selectors.quickView),
    };
  },

  handleQuickViewCart: function handleQuickViewCart(event) {
    var $ = jQuery;

    event.preventDefault();

    var $thisbutton = $(
        ".m-neuron__quick-view--product-add-to-cart .single_add_to_cart_button"
      ),
      $form = $thisbutton.closest("form.cart"),
      id = $thisbutton.val(),
      product_qty = $form.find("input[name=quantity]").val() || 1,
      product_id = $form.find("input[name=product_id]").val() || id,
      variation_id = $form.find("input[name=variation_id]").val() || 0;

    var data = {
      action: "neuron_woocommerce_ajax_add_to_cart",
      product_id: product_id,
      product_sku: "",
      quantity: product_qty,
      variation_id: variation_id,
    };

    $.ajax({
      type: "post",
      url: wc_add_to_cart_params.ajax_url,
      data: data,

      beforeSend: function (response) {
        $thisbutton.wrapInner("<span></span>");
        $thisbutton.removeClass("added").addClass("loading");
      },

      complete: function (response) {
        $thisbutton.addClass("added").removeClass("loading");
        $thisbutton.find("span").contents().unwrap();
      },

      success: function (response) {
        if (response.error & response.product_url) {
          window.location = response.product_url;

          return;
        } else {
          $(document.body).trigger("added_to_cart", [
            response.fragments,
            response.cart_hash,
            $thisbutton,
          ]);
        }
      },
    });
  },

  appendInput: function appendInput() {
    if (jQuery(".quantity-nav").length) {
      return;
    }

    return jQuery(
      '<div class="quantity-nav quantity-nav--up">+</div><div class="quantity-nav quantity-nav--down">-</div>'
    ).insertAfter(".quantity input");
  },

  quantityFunction: function quantityFunction() {
    if (jQuery(".quantity-nav").length == 0) {
      return;
    }

    jQuery(".quantity").each(function () {
      var spinner = jQuery(this),
        input = spinner.find('input[type="number"]'),
        btnUp = spinner.find(".quantity-nav--up"),
        btnDown = spinner.find(".quantity-nav--down"),
        min = input.attr("min");

      btnUp.on("click", function () {
        var oldValue = input.val() ? parseFloat(input.val()) : 0;
        var newVal = oldValue + 1;
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

      btnDown.on("click", function () {
        var oldValue = parseFloat(input.val());
        if (oldValue <= min) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue - 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });
    });
  },

  initQuantity: function initQuantity() {
    this.appendInput();
    this.quantityFunction();
  },

  quickView: function quickView() {
    if (this.getElementSettings("quick_view") != "yes") {
      return;
    }

    var $ = jQuery,
      $quickView = this.elements.$quickView,
      self = this;

    $quickView.on("click", function (event) {
      event.preventDefault();

      var data = {
        action: "neuron_woocommerce_quick_view",
        product_id: $(this).data("product_id"),
      };

      $.ajax({
        type: "post",
        url: wc_add_to_cart_params.ajax_url,
        data: data,

        beforeSend: function () {
          if ($(".m-neuron__quick-view").length > 0) {
            return;
          }

          var output =
            '<div class="m-neuron__quick-view woocommerce"><div class="m-neuron__quick-view--overlay"></div><div class="m-neuron__quick-view--loader"></div><div class="m-neuron__quick-view--wrapper"><div class="m-neuron__quick-view--close"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 6.00003L18.7742 18.7742" stroke="#121212" stroke-width="1.5" stroke-linecap="square"/><path d="M6 18.7742L18.7742 6.00001" stroke="#121212" stroke-width="1.5" stroke-linecap="square"/></svg></div></div></div>';

          $("body").append(output);
          $("body").addClass("neuron-quick-view--open");
        },

        success: function (response) {
          if ($(".m-neuron__quick-view--wrapper").length > 0) {
            $(".m-neuron__quick-view--wrapper")
              .append(response)
              .addClass("active");
          }

          // Quantity Input
          self.initQuantity();

          // Ajax Cart
          $(
            ".m-neuron__quick-view--product-add-to-cart .single_add_to_cart_button"
          ).on("click", self.handleQuickViewCart);

          // Close Icon
          self.quickViewExit();

          // Fit Images
          self.fitImages();

          // Init Slider
          self.quickViewSlider();
        },
      });
    });
  },

  quickViewExit: function quickViewExit() {
    var $ = jQuery;

    this.quickViewExitOutside();

    $(".m-neuron__quick-view--close").on("click", function () {
      $(this)
        .closest(".m-neuron__quick-view")
        .fadeOut(300, function () {
          $(this).remove();
        });
    });
  },

  quickViewExitOutside: function quickViewExitOutside() {
    var $ = jQuery;

    $("body").on("click", function (event) {
      if ($(".m-neuron__quick-view--wrapper").hasClass("active")) {
        if (!$(".m-neuron__quick-view--wrapper").find($(event.target)).length) {
          $(".m-neuron__quick-view").fadeOut(300, function () {
            $(this).remove();
          });
        }
      }
    });
  },

  fitImages: function fitImages() {
    objectFitPolyfill(".m-neuron__quick-view--product-thumbnail img");
  },

  quickViewSlider: function quickViewSlider() {
    var $ = jQuery,
      $slider = $(".m-neuron__quick-view .neuron-slides-wrapper");

    if ($slider.length > 0) {
      var swiperOptions = {
        resistance: true,
        resistanceRatio: 0,
        grabCursor: true,
        initialSlide: 0,
        slidesPerView: 1,
        slidesPerGroup: 1,
        speed: 500,
        effect: "slide",
        spaceBetween: 0,
        keyboard: {
          enabled: true,
          onlyInViewport: false,
        },
        pagination: {
          el: $(".swiper-pagination--quick-view"),
          type: "bullets",
          clickable: true,
        },
      };

      var quickViewSlider = new Swiper($slider, swiperOptions);
    }
  },

  run: function run() {
    this.quickView();
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    this.run();
  },
});
