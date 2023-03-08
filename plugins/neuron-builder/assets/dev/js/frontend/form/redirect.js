module.exports = elementorModules.frontend.handlers.Base.extend({
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        form: ".m-neuron-form"
      }
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings("selectors"),
      elements = {};

    elements.$form = this.$element.find(selectors.form);

    return elements;
  },

  bindEvents: function bindEvents() {
    this.elements.$form.on("form_destruct", this.handleSubmit);
  },

  handleSubmit: function handleSubmit(event, response) {
    if ("undefined" !== typeof response.data.redirect_url) {
      location.href = response.data.redirect_url;
    }
  }
});
