export default class ScrollHandler {
  static scrollObserver(obj) {
    var lastScrollY = 0; // Generating threshholds points along the animation height
    // More threshholds points = more trigger points of the callback

    var buildThreshholds = function buildThreshholds() {
      var sensitivityPercentage =
        arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
      var threshholds = [];

      if (sensitivityPercentage > 0 && sensitivityPercentage <= 100) {
        var increment = 100 / sensitivityPercentage;

        for (var i = 0; i <= 100; i += increment) {
          threshholds.push(i / 100);
        }
      } else {
        threshholds.push(0);
      }

      return threshholds;
    };

    var options = {
      root: obj.root || null,
      rootMargin: obj.offset || "0px",
      threshold: buildThreshholds(obj.sensitivity),
    };

    function handleIntersect(entries, observer) {
      var currentScrollY = entries[0].boundingClientRect.y,
        isInViewport = entries[0].isIntersecting,
        intersectionScrollDirection =
          currentScrollY < lastScrollY ? "down" : "up",
        scrollPercentage = Math.abs(
          parseFloat((entries[0].intersectionRatio * 100).toFixed(2))
        );
      obj.callback({
        sensitivity: obj.sensitivity,
        isInViewport: isInViewport,
        scrollPercentage: scrollPercentage,
        intersectionScrollDirection: intersectionScrollDirection,
      });
      lastScrollY = currentScrollY;
    }

    return new IntersectionObserver(handleIntersect, options);
  }

  static getElementViewportPercentage($element) {
    var offsetObj =
      arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    var elementOffset = $element[0].getBoundingClientRect(),
      offsetStart = offsetObj.start || 0,
      offsetEnd = offsetObj.end || 0,
      windowStartOffset = (window.innerHeight * offsetStart) / 100,
      windowEndOffset = (window.innerHeight * offsetEnd) / 100,
      y1 = elementOffset.top - window.innerHeight,
      y2 = elementOffset.top + windowStartOffset + $element.height(),
      startPosition = 0 - y1 + windowStartOffset,
      endPosition = y2 - y1 + windowEndOffset,
      percent = Math.max(0, Math.min(startPosition / endPosition, 1));
    return parseFloat((percent * 100).toFixed(2));
  }

  static getPageScrollPercentage() {
    var offsetObj =
      arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var offsetStart = offsetObj.start || 0,
      offsetEnd = offsetObj.end || 0,
      initialPageHeight =
        document.documentElement.scrollHeight -
        document.documentElement.clientHeight,
      heightOffset = (initialPageHeight * offsetStart) / 100,
      pageRange =
        initialPageHeight +
        heightOffset +
        (initialPageHeight * offsetEnd) / 100,
      scrollPos =
        document.documentElement.scrollTop +
        document.body.scrollTop +
        heightOffset;
    return (scrollPos / pageRange) * 100;
  }
}
