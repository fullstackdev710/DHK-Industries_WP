var Wizard = (function ($) {
  var t,
    drawer_opened = "wizard__drawer--open",
    logoUploaded;

  // callbacks from form button clicks.
  var callbacks = {
    install_child: function (btn) {
      var installer = new ChildTheme();
      installer.init(btn);
    },
    install_plugins: function (btn) {
      var plugins = new PluginManager();
      plugins.init(btn);
    },
  };

  function window_loaded() {
    var body = $(".wizard__body"),
      drawer_trigger = $("#wizard__drawer-trigger");

    setTimeout(function () {
      body.addClass("loaded");
    }, 100);

    drawer_trigger.on("click", function () {
      body.toggleClass(drawer_opened);
    });

    $(".wizard__button--proceed:not(.wizard__button--closer)").on(
      "click",
      function (e) {
        e.preventDefault();
        var goTo = this.getAttribute("href");

        body.addClass("exiting");

        setTimeout(function () {
          window.location = goTo;
        }, 400);
      }
    );

    $(".wizard__button--closer").on("click", function (e) {
      body.removeClass(drawer_opened);

      e.preventDefault();
      var goTo = this.getAttribute("href");

      setTimeout(function () {
        body.addClass("exiting");
      }, 600);

      setTimeout(function () {
        window.location = goTo;
      }, 1100);
    });

    $(".wizard__button--next").on("click", function (e) {
      e.preventDefault();

      var loading_button = wizard_loading_button(this);

      if (!loading_button) {
        return false;
      }

      var data_callback = $(this).data("callback");

      if (data_callback && typeof callbacks[data_callback] !== "undefined") {
        callbacks[data_callback](this);
        return false;
      } else {
        return true;
      }
    });

    $(".open-tooltip").on("click", function () {
      $(this).find("span").toggleClass("active");
    });

    $("#file-upload").on("change", function () {
      var fileName = "";
      fileName = $(this).val();
      $(".custom-file-upload span").html(fileName);

      $this = $(this);
      file_data = $(this).prop("files")[0];
      form_data = new FormData();
      form_data.append("file", file_data);
      form_data.append("action", "wizard_file_upload");

      $.ajax({
        url: wizard_params.ajaxurl,
        type: "POST",
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          $this.val("");
          logoUploaded = response;
        },
      });
    });

    // Form Wizard
    $("form.wizard-form").on("submit", function (e) {
      e.preventDefault();

      $("input[type=submit]").addClass("disabled");

      var siteTitle = $(this).find("input.site-title").val(),
        siteTagline = $(this).find("input.site-tagline").val(),
        installChild = document.getElementById("install-child-theme");

      if (installChild.checked) {
        $.ajax({
          type: "POST",
          url: wizard_params.ajaxurl,
          data: {
            action: "wizard_child_theme",
            wpnonce: wizard_params.wpnonce,
          },
          success: function (response) {
            setTimeout(() => {
              updateSiteMeta(siteTitle, siteTagline);
            }, 1000);
          },
        });
      } else {
        updateSiteMeta(siteTitle, siteTagline);
      }
    });

    function updateSiteMeta(siteTitle, siteTagline) {
      return $.ajax({
        type: "POST",
        url: wizard_params.ajaxurl,
        data: {
          action: "update_wordpress_option",
          option: {
            siteLogo: logoUploaded,
            siteTitle: siteTitle,
            siteTagline: siteTagline,
          },
        },
        complete: function () {
          console.log(logoUploaded);
          window.location.href = $("input[type=submit]").data("url");
        },
      });
    }

    $("#default_plugins_elementor, #default_plugins_neuron-builder").on(
      "click",
      function (e) {
        e.preventDefault();
        e.stopPropagation();
      }
    );

    $(document).on("click", function (e) {
      var container = $(".open-tooltip");

      // If the target of the click isn't the container
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.find("span").removeClass("active");
      }
    });

    $(".neuron-admin__card-wizard input[type='submit']").on(
      "click",
      function () {
        $(this).addClass("disabled");
      }
    );
  }

  function ChildTheme() {
    var body = $(".wizard__body");
    var complete,
      notice = $("#child-theme-text");

    function ajax_callback(r) {
      if (typeof r.done !== "undefined") {
        setTimeout(function () {
          notice.addClass("lead");
        }, 0);
        setTimeout(function () {
          notice.addClass("success");
          notice.html(r.message);
        }, 600);

        complete();
      } else {
        notice.addClass("lead error");
        notice.html(r.error);
      }
    }

    function do_ajax() {
      jQuery
        .post(
          wizard_params.ajaxurl,
          {
            action: "wizard_child_theme",
            wpnonce: wizard_params.wpnonce,
          },
          ajax_callback
        )
        .fail(ajax_callback);
    }

    return {
      init: function (btn) {
        complete = function () {
          setTimeout(function () {
            $(".wizard__body").addClass("js--finished");
          }, 1500);

          body.removeClass(drawer_opened);

          setTimeout(function () {
            $(".wizard__body").addClass("exiting");
          }, 3500);

          setTimeout(function () {
            window.location.href = btn.href;
          }, 4000);
        };
        do_ajax();
      },
    };
  }

  function PluginManager() {
    var body = $(".wizard__body");
    var complete;
    var items_completed = 0;
    var current_item = "";
    var $current_node;
    var current_item_hash = "";

    function ajax_callback(response) {
      var currentSpan = $current_node.find("label");
      if (
        typeof response === "object" &&
        typeof response.message !== "undefined"
      ) {
        currentSpan
          .removeClass("installing success error")
          .addClass(response.message.toLowerCase());

        // The plugin is done (installed, updated and activated).
        if (typeof response.done != "undefined" && response.done) {
          find_next();
        } else if (typeof response.url != "undefined") {
          // we have an ajax url action to perform.
          if (response.hash == current_item_hash) {
            currentSpan.removeClass("installing success").addClass("error");
            find_next();
          } else {
            current_item_hash = response.hash;
            jQuery
              .post(response.url, response, ajax_callback)
              .fail(ajax_callback);
          }
        } else {
          // error processing this plugin
          find_next();
        }
      } else {
        // The TGMPA returns a whole page as response, so check, if this plugin is done.
        process_current();
      }
    }

    function process_current() {
      if (current_item) {
        var $check = $current_node.find("input:checkbox");
        if ($check.is(":checked")) {
          jQuery
            .post(
              wizard_params.ajaxurl,
              {
                action: "wizard_plugins",
                wpnonce: wizard_params.wpnonce,
                slug: current_item,
              },
              ajax_callback
            )
            .fail(ajax_callback);
        } else {
          $current_node.addClass("skipping");
          setTimeout(find_next, 300);
        }
      }
    }

    function find_next() {
      if ($current_node) {
        if (!$current_node.data("done_item")) {
          items_completed++;
          $current_node.data("done_item", 1);
        }
        $current_node.find(".spinner").css("visibility", "hidden");
      }
      var $li = $(".wizard__drawer--install-plugins li");
      $li.each(function () {
        var $item = $(this);

        if ($item.data("done_item")) {
          return true;
        }

        current_item = $item.data("slug");
        $current_node = $item;
        process_current();
        return false;
      });
      if (items_completed >= $li.length) {
        // finished all plugins!
        complete();
      }
    }

    return {
      init: function (btn) {
        $(".wizard__drawer--install-plugins").addClass("installing");
        $(".wizard__drawer--install-plugins")
          .find("input")
          .prop("disabled", true);

        $(".wizard__button--loading__text").text("Installing...");

        complete = function () {
          setTimeout(function () {
            $(".wizard__body").addClass("js--finished");
          }, 1000);

          body.removeClass(drawer_opened);

          setTimeout(function () {
            $(".wizard__body").addClass("exiting");
          }, 3000);

          setTimeout(function () {
            window.location.href = btn.href;
          }, 3500);
        };
        find_next();
      },
    };
  }

  function wizard_loading_button(btn) {
    var $button = jQuery(btn);

    if ($button.data("done-loading") == "yes") {
      return false;
    }

    $button.data("done-loading", "yes");

    $button.addClass("wizard__button--loading");

    return {
      done: function () {
        completed = true;
        $button.attr("disabled", false);
      },
    };
  }

  return {
    init: function () {
      t = this;
      $(window_loaded);
    },
    callback: function (func) {
      console.log(func);
      console.log(this);
    },
  };
})(jQuery);

Wizard.init();
