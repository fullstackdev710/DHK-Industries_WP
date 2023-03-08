(function ($) {
  $(function () {
    var modules = {
      AssetsManager: require("./assets-manager"),
      RoleManager: require("./role-manager"),
      ThemeBuilder: require("./theme-builder"),
    };

    window.neuronAdmin = {
      assetsManager: new modules.AssetsManager(),
      roleManager: new modules.RoleManager(),
      themebuilder: new modules.ThemeBuilder(),
    };

    elementorCommon.elements.$window.trigger("elementor/admin/init");

    $(".neuron-admin-wrapper .nav-tab-wrapper a").on("click", function (event) {
      event.preventDefault();

      var $this = jQuery(this),
        dataID = $this.attr("href");

      $this.addClass("nav-tab-active").siblings().removeClass("nav-tab-active");

      jQuery(dataID).addClass("active").siblings().removeClass("active");
    });

    // Uploading files
    var file_frame;

    jQuery.fn.upload_neuron_hero_image = function (button) {
      var button_id = button.attr("id");
      var field_id = button_id.replace("_button", "");

      // If the media frame already exists, reopen it.
      if (file_frame) {
        file_frame.open();
        return;
      }

      // Create the media frame.
      file_frame = wp.media.frames.file_frame = wp.media({
        title: jQuery(this).data("uploader_title"),
        button: {
          text: jQuery(this).data("uploader_button_text"),
        },
        multiple: false,
      });

      // When an image is selected, run a callback.
      file_frame.on("select", function () {
        var attachment = file_frame.state().get("selection").first().toJSON();
        jQuery("#" + field_id).val(attachment.id);
        jQuery("#neuron_hero_image_box img").attr("src", attachment.url);
        jQuery("#neuron_hero_image_box img").show();
        jQuery("#" + button_id).attr("id", "remove_neuron_hero_image_button");
        jQuery("#remove_neuron_hero_image_button").text("Remove Hero image");
      });

      // Finally, open the modal
      file_frame.open();
    };

    jQuery("#neuron_hero_image_box").on(
      "click",
      "#upload_neuron_hero_image_button",
      function (event) {
        event.preventDefault();
        jQuery.fn.upload_neuron_hero_image(jQuery(this));
      }
    );

    jQuery("#neuron_hero_image_box").on(
      "click",
      "#remove_neuron_hero_image_button",
      function (event) {
        event.preventDefault();
        jQuery("#upload_neuron_hero_image").val("");
        jQuery("#neuron_hero_image_box img").attr("src", "");
        jQuery("#neuron_hero_image_box img").hide();
        jQuery(this).attr("id", "upload_neuron_hero_image_button");
        jQuery("#upload_neuron_hero_image_button").text("Upload Hero image");
      }
    );
  });
})(jQuery);
