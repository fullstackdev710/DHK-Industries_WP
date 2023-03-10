/**
 * Social Share
 *
 * @since 1.0.0
 */
(function($) {
  var NeuronShareLink = function(element, userSettings) {
    var $element,
      settings = {};

    var getNetworkLink = function(networkName) {
      var link = NeuronShareLink.networkTemplates[networkName].replace(
        /{([^}]+)}/g,
        function(fullMatch, pureMatch) {
          return settings[pureMatch];
        }
      );

      if ("email" === networkName && link.indexOf("?subject=&body")) {
        link = link.replace("subject=&", "");
      }

      return encodeURI(link);
    };

    var getNetworkNameFromClass = function(className) {
      var classNamePrefix = className.substr(0, settings.classPrefixLength);

      return classNamePrefix === settings.classPrefix
        ? className.substr(settings.classPrefixLength)
        : null;
    };

    var bindShareClick = function(networkName) {
      $element.on("click", function() {
        openNeuronShareLink(networkName);
      });
    };

    var openNeuronShareLink = function(networkName) {
      var shareWindowParams = "";

      if (settings.width && settings.height) {
        var shareWindowLeft = screen.width / 2 - settings.width / 2,
          shareWindowTop = screen.height / 2 - settings.height / 2;

        shareWindowParams =
          "toolbar=0,status=0,width=" +
          settings.width +
          ",height=" +
          settings.height +
          ",top=" +
          shareWindowTop +
          ",left=" +
          shareWindowLeft;
      }

      var link = getNetworkLink(networkName),
        isPlainLink = /^https?:\/\//.test(link),
        windowName = isPlainLink ? "" : "_self";

      open(link, windowName, shareWindowParams);
    };

    var run = function() {
      $.each(element.classList, function() {
        var networkName = getNetworkNameFromClass(this);

        if (networkName) {
          bindShareClick(networkName);

          return false;
        }
      });
    };

    var initSettings = function() {
      $.extend(settings, NeuronShareLink.defaultSettings, userSettings);

      ["title", "text"].forEach(function(propertyName) {
        settings[propertyName] = settings[propertyName].replace("#", "");
      });

      settings.classPrefixLength = settings.classPrefix.length;
    };

    var initElements = function() {
      $element = $(element);
    };

    var init = function() {
      initSettings();

      initElements();

      run();
    };

    init();
  };

  window.share = NeuronShareLink;

  NeuronShareLink.networkTemplates = {
    twitter: "https://twitter.com/intent/tweet?url={url}&text={text}",
    pinterest: "https://www.pinterest.com/pin/create/button/?url={url}",
    facebook: "https://www.facebook.com/sharer.php?u={url}",
    vk:
      "https://vkontakte.ru/share.php?url={url}&title={title}&description={text}&image={image}",
    linkedin:
      "https://www.linkedin.com/shareArticle?mini=true&url={url}&title={title}&summary={text}&source={url}",
    odnoklassniki:
      "https://connect.ok.ru/offer?url={url}&title={title}&imageUrl={image}",
    tumblr: "https://tumblr.com/share/link?url={url}",
    delicious: "https://del.icio.us/save?url={url}&title={title}",
    google: "https://plus.google.com/share?url={url}",
    digg: "https://digg.com/submit?url={url}",
    reddit: "https://reddit.com/submit?url={url}&title={title}",
    stumbleupon: "https://www.stumbleupon.com/submit?url={url}",
    pocket: "https://getpocket.com/edit?url={url}",
    whatsapp: "https://api.whatsapp.com/send?text=*{title}*\n{text}\n{url}",
    xing: "https://www.xing.com/app/user?op=share&url={url}",
    print: "javascript:print()",
    email: "mailto:?subject={title}&body={text}\n{url}",
    telegram: "https://telegram.me/share/url?url={url}&text={text}",
    skype: "https://web.skype.com/share?url={url}"
  };

  NeuronShareLink.defaultSettings = {
    title: "",
    text: "",
    image: "",
    url: location.href,
    classPrefix: "s_",
    width: 600,
    height: 480
  };

  $.fn.shareLink = function(settings) {
    return this.each(function() {
      $(this).data("shareLink", new NeuronShareLink(this, settings));
    });
  };
})(jQuery);
