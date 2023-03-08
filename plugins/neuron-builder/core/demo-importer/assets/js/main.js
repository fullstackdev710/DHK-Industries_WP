jQuery(function ($) {
  "use strict";

  var onceReload = false;

  /**
   * ---------------------------------------
   * ------------- Events ------------------
   * ---------------------------------------
   */

  /**
   * No or Single predefined demo import button click.
   */
  $(".js-ocdi-import-data").on("click", function () {
    // Reset response div content.
    $(".js-ocdi-ajax-response").empty();

    // Prepare data for the AJAX call
    var data = new FormData();

    data.append("action", "ocdi_import_demo_data");
    data.append("security", ocdi.ajax_nonce);
    data.append("selected", $("#ocdi__demo-import-files").val());

    if ($("#ocdi__content-file-upload").length) {
      data.append("content_file", $("#ocdi__content-file-upload")[0].files[0]);
    }

    if ($("#ocdi__widget-file-upload").length) {
      data.append("widget_file", $("#ocdi__widget-file-upload")[0].files[0]);
    }

    if ($("#ocdi__customizer-file-upload").length) {
      data.append(
        "customizer_file",
        $("#ocdi__customizer-file-upload")[0].files[0]
      );
    }

    // AJAX call to import everything (content, widgets, before/after setup)
    ajaxCall(data);
  });

  /**
   * Grid Layout import button click.
   */
  $(".js-ocdi-gl-import-data").on("click", function () {
    var selectedImportID = $(this).val();
    var $itemContainer = $(this).closest(".js-ocdi-gl-item");

    gridLayoutImport(selectedImportID, $itemContainer);
  });

  /**
   * Grid Layout categories navigation.
   */
  (function () {
    $("select[name=demo-importer-filters]").on("change", function (event) {
      event.preventDefault();

      // Remove 'active' class from the previous nav list items.
      $(this).parent().siblings().removeClass("active");

      // Add the 'active' class to this nav list item.
      $(this).parent().addClass("active");

      var category = $(this).children("option:selected").val().slice(1);

      $(".ocdi__gl-item").each(function () {
        if ($(this).data("categories").toLowerCase() != category) {
          $(this).hide();
        } else {
          $(this).show();
        }
      });

      if (category == "all") {
        $(".ocdi__gl-item").show();
      }
    });
  })();

  /**
   * Grid Layout search functionality.
   */
  $(".neuron-admin__demo-importer--search input").on("keyup", function (event) {
    if (0 < $(this).val().length) {
      // Hide all items.
      $(".ocdi__gl-item-container").find(".js-ocdi-gl-item").hide();

      // Show just the ones that have a match on the import name.
      $(".ocdi__gl-item-container")
        .find(
          '.js-ocdi-gl-item[data-name*="' + $(this).val().toLowerCase() + '"]'
        )
        .show();
    } else {
      $(".ocdi__gl-item-container").find(".js-ocdi-gl-item").show();
    }
  });

  function gridLayoutImport(selectedImportID, $itemContainer) {
    // Importing
    $itemContainer.find(".js-ocdi-gl-import-data").addClass("loading");

    $itemContainer
      .find(".js-ocdi-gl-import-data")
      .find("span:not(.ab-icon)")
      .text("Importing");

    // Prepare data for the AJAX call
    var data = new FormData();
    data.append("action", "ocdi_import_demo_data");
    data.append("security", ocdi.ajax_nonce);
    data.append("selected", selectedImportID);

    // AJAX call to import everything (content, widgets, before/after setup)
    ajaxCall(data, $itemContainer);
  }

  /**
   * The main AJAX call, which executes the import process.
   *
   * @param FormData data The data to be passed to the AJAX call.
   */
  function ajaxCall(data, $itemContainer = "") {
    var $ = jQuery;

    $.ajax({
      method: "POST",
      url: ocdi.ajax_url,
      data: data,
      contentType: false,
      processData: false,
      beforeSend: function () {},
    })
      .done(function (response) {
        if (
          "undefined" !== typeof response.status &&
          "newAJAX" === response.status
        ) {
          ajaxCall(data, $itemContainer);
        } else if (
          "undefined" !== typeof response.status &&
          "customizerAJAX" === response.status
        ) {
          // Fix for data.set and data.delete, which they are not supported in some browsers.
          var newData = new FormData();
          newData.append("action", "ocdi_import_customizer_data");
          newData.append("security", ocdi.ajax_nonce);

          // Set the wp_customize=on only if the plugin filter is set to true.
          if (true === ocdi.wp_customize_on) {
            newData.append("wp_customize", "on");
          }

          ajaxCall(newData, $itemContainer);
        } else if (
          "undefined" !== typeof response.status &&
          "afterAllImportAJAX" === response.status
        ) {
          // Fix for data.set and data.delete, which they are not supported in some browsers.
          var newData = new FormData();
          newData.append("action", "ocdi_after_import_data");
          newData.append("security", ocdi.ajax_nonce);
          ajaxCall(newData, $itemContainer);
        } else if ("undefined" !== typeof response.message) {
          // Trigger custom event, when OCDI import is complete.
          $(document).trigger("ocdiImportComplete");

          // Add Imported
          $(".js-ocdi-gl-import-data").removeClass("loading");

          $itemContainer.addClass("active").siblings().removeClass("active");
        }
      })
      .fail(function (error) {
        console.log(error);

        if (onceReload == true) {
          return;
        }

        setTimeout(() => {
          window.location.reload();

          onceReload = true;
        }, 10);
      });
  }
});
