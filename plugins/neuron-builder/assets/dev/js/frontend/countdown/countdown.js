module.exports = elementorModules.frontend.handlers.Base.extend({
  cacheElements: function cacheElements() {
    var $countDown = this.$element.find(".neuron-countdown-wrapper");
    this.cache = {
      $countDown: $countDown,
      timeInterval: null,
      elements: {
        $countdown: $countDown.find(".neuron-countdown-wrapper"),
        $daysSpan: $countDown.find(".neuron-countdown-days"),
        $hoursSpan: $countDown.find(".neuron-countdown-hours"),
        $minutesSpan: $countDown.find(".neuron-countdown-minutes"),
        $secondsSpan: $countDown.find(".neuron-countdown-seconds"),
      },
      data: {
        id: this.$element.data("id"),
        endTime: new Date($countDown.data("date") * 1000),
      },
    };
  },

  getTime: function getTime() {
    var dueDate = this.elements.$countdown.data("date"),
      timeRemaining = dueDate - new Date(),
      seconds = Math.floor((timeRemaining / 1000) % 60),
      minutes = Math.floor((timeRemaining / 1000 / 60) % 60),
      hours = Math.floor((timeRemaining / (1000 * 60 * 60)) % 24),
      days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));

    if (days < 0 || hours < 0 || minutes < 0) {
      seconds = minutes = hours = days = 0;
    }

    return {
      total: timeRemaining,
      parts: {
        days: days,
        hours: hours,
        minutes: minutes,
        seconds: seconds,
      },
    };
  },

  updateClock: function updateClock() {
    var self = this,
      timeRemaining = this.getTimeRemaining(this.cache.data.endTime);
    jQuery.each(timeRemaining.parts, function (timePart) {
      var $element = self.cache.elements["$" + timePart + "Span"];
      var partValue = this.toString();

      if (1 === partValue.length) {
        partValue = 0 + partValue;
      }

      if ($element.length) {
        $element.text(partValue);
      }
    });

    if (timeRemaining.total <= 0) {
      clearInterval(this.cache.timeInterval);
      this.runActions();
    }
  },

  initializeClock: function initializeClock() {
    var self = this;
    this.updateClock();
    this.cache.timeInterval = setInterval(function () {
      self.updateClock();
    }, 1000);
  },

  getTimeRemaining: function getTimeRemaining(endTime) {
    var timeRemaining = endTime - new Date();
    var seconds = Math.floor((timeRemaining / 1000) % 60),
      minutes = Math.floor((timeRemaining / 1000 / 60) % 60),
      hours = Math.floor((timeRemaining / (1000 * 60 * 60)) % 24),
      days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));

    if (days < 0 || hours < 0 || minutes < 0) {
      seconds = minutes = hours = days = 0;
    }

    return {
      total: timeRemaining,
      parts: {
        days: days,
        hours: hours,
        minutes: minutes,
        seconds: seconds,
      },
    };
  },

  onInit: function onInit() {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    this.cacheElements();

    this.initializeClock();
  },
});
