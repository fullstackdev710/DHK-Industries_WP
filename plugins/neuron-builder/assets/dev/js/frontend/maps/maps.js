module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        map: ".map-holder",
      },
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors");

    return {
      $map: this.$element.find(selectors.map),
    };
  },

  styleGoogleMaps: function styleGoogleMaps() {
    var style = this.getElementSettings("style"),
      customStyle = this.getElementSettings("custom_style"),
      mapStyle;

    if (style == "classic") {
      mapStyle = [
        {
          featureType: "administrative",
          elementType: "geometry",
          stylers: [{ visibility: "off" }],
        },
        {
          featureType: "administrative.land_parcel",
          elementType: "labels",
          stylers: [{ visibility: "off" }],
        },
        { featureType: "poi", stylers: [{ visibility: "off" }] },
        {
          featureType: "poi",
          elementType: "labels.text",
          stylers: [{ visibility: "off" }],
        },
        {
          featureType: "road",
          elementType: "labels.icon",
          stylers: [{ visibility: "off" }],
        },
        {
          featureType: "road.highway",
          stylers: [{ color: "#ffffff" }, { weight: 0.5 }],
        },
        {
          featureType: "road.local",
          elementType: "labels",
          stylers: [{ visibility: "off" }],
        },
        { featureType: "transit", stylers: [{ visibility: "off" }] },
        { featureType: "water", stylers: [{ color: "#B4D0EF" }] },
      ];
    } else if (style == "custom" && customStyle) {
      mapStyle = eval(
        customStyle.replace(/(\r\n|\n|\r)/gm, "").replace(/\s/g, "")
      );
    }

    return mapStyle;
  },

  mapOptions: function mapOptions() {
    var settings = this.getElementSettings();

    var myOptions = {
      center: new google.maps.LatLng(
        settings.map_latitude,
        settings.map_longitude
      ),
      zoom: settings.zoom ? parseInt(settings.zoom) : 13,
      scrollwheel: settings.scroll_zoom == "yes" ? true : false,
      mapTypeControl: settings.type == "yes" ? true : false,
      zoomControl: settings.zoom_control == "yes" ? true : false,
      fullscreenControl: settings.fullscreen == "yes" ? true : false,
      streetViewControl: settings.street_view == "yes" ? true : false,
      draggable: settings.draggable == "yes" ? true : false,
    };

    return myOptions;
  },

  initGoogleMaps: function initGoogleMaps() {
    if (typeof google == "undefined") {
      return;
    }

    var map = new google.maps.Map(
        document.getElementById("map-" + this.getID()),
        this.mapOptions()
      ),
      settings = this.getElementSettings(),
      markers = settings.markers,
      infowindow = new google.maps.InfoWindow(),
      bounds = new google.maps.LatLngBounds(),
      i;

    if (markers) {
      markers.forEach(function (element) {
        if (element.retina == "yes") {
          $scaled = new google.maps.Size(
            element.image_width / 2,
            element.image_height / 2
          );
        } else {
          $scaled = new google.maps.Size(
            element.image_width,
            element.image_height
          );
        }

        var icon = {
          url: element.image.url,
          scaledSize: $scaled,
        };

        var markerOptions = {
          position: new google.maps.LatLng(
            element.map_latitude,
            element.map_longitude
          ),
          map: map,
          animation: google.maps.Animation.DROP,
        };

        if (icon.url.length) {
          markerOptions.icon = icon;
        }

        marker = new google.maps.Marker(markerOptions);

        bounds.extend(marker.position);

        if (element.title || element.content) {
          google.maps.event.addListener(
            marker,
            "click",
            (function (marker, i) {
              return function () {
                infowindow.setContent(
                  '<h4 class="mb-0 h-small-bottom-padding">' +
                    element.title +
                    "</h4>" +
                    '<p class="mb-0">' +
                    element.content +
                    "</p>"
                );
                infowindow.open(map, marker);
              };
            })(marker, i)
          );
        }
      });
    }

    if (settings.style != "default") {
      var styledMapType = new google.maps.StyledMapType(
        this.styleGoogleMaps(),
        {
          name: "Styled Map",
        }
      );

      map.mapTypes.set("styled_map", styledMapType);
      map.setMapTypeId("styled_map");
    }
  },

  initMaps: function initMaps() {
    this.initGoogleMaps();
  },

  // onElementChange: function onElementChange(propertyName) {
  //   // this.initMaps();
  // },

  onInit: function onInit() {
    this.initMaps();
  },
});
