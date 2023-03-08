module.exports = function () {
  var self = this;

  self.cacheElements = function () {
    this.cache = {
      $checkBox: jQuery('input[name="elementor_exclude_user_roles[]"]'),
      $advanced: jQuery("#elementor_advanced_role_manager"),
    };
  };

  self.bindEvents = function () {
    this.cache.$checkBox.on("change", function (event) {
      event.preventDefault();
      self.checkBoxUpdate(jQuery(this));
    });
  };

  self.checkBoxUpdate = function ($element) {
    var role = $element.val();

    if ($element.is(":checked")) {
      self.cache.$advanced.find("div." + role).addClass("hidden");
    } else {
      self.cache.$advanced.find("div." + role).removeClass("hidden");
    }
  };

  self.init = function () {
    if (!jQuery("body").hasClass("elementor_page_elementor-role-manager")) {
      return;
    }

    this.cacheElements();
    this.bindEvents();
  };

  self.init();
};
