module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        quantity: ".quantity",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $quantity: this.$element.find(selectors.quantity),
    };
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

  onElementChange: function onElementChange(propertyName) {
    if ("show_quantity" === propertyName) {
      this.initQuantity();
    }
  },

  onInit: function onInit() {
    this.initQuantity();
  },
});
