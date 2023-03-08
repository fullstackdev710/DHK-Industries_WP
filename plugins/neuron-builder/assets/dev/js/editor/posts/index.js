const Posts = elementorModules.editor.utils.Module.extend({
  onElementorInit: function onElementorInit() {
    elementor.channels.editor.on("section:activated", this.onSectionActivated);

    this.oldData = [];
    this.runOnce = false;
  },

  reloadMetro: function reloadMetro(editedElement, type) {
    var controlView = this.getEditorControlView("neuron_metro"),
      $element = editedElement.$el[0],
      data = neuron.helpers.getPosts($element, type);

    if (controlView && data) {
      if (this.oldData) {
        controlView.collection.remove(this.oldData);
      }

      this.oldData = data;

      controlView.collection.add(data);

      controlView.render();

      this.runOnce = true;
    }
  },

  onSectionActivated: function onSectionActivated(sectionName, editor) {
    var editedElement = editor.getOption("editedElementView"),
      self = this;

    if (
      "neuron-posts" !== editedElement.model.get("widgetType") &&
      "neuron-portfolio" !== editedElement.model.get("widgetType") &&
      "neuron-woo-products" !== editedElement.model.get("widgetType") &&
      "neuron-woo-archive-products" !== editedElement.model.get("widgetType") &&
      "neuron-archive-posts" !== editedElement.model.get("widgetType") &&
      "neuron-woo-product-upsell" !== editedElement.model.get("widgetType") &&
      "neuron-woo-product-related" !== editedElement.model.get("widgetType") &&
      "neuron-woo-categories" !== editedElement.model.get("widgetType")
    ) {
      return;
    }

    var isMetroSection = -1 !== ["query_metro_section"].indexOf(sectionName),
      type = "post";

    switch (editedElement.model.get("widgetType")) {
      case "neuron-portfolio":
        type = "portfolio";
        break;
      case "neuron-woo-products":
      case "neuron-woo-archive-products":
      case "neuron-woo-product-related":
      case "neuron-woo-product-upsell":
        type = "product";
        break;
      case "neuron-woo-categories":
        type = "category";
        break;
    }

    elementor.channels.editor.on("neuron:editor:metro:reset", function () {
      self.reloadMetro(editedElement, type);

      editedElement.render();
    });

    if (isMetroSection && this.runOnce != true) {
      self.reloadMetro(editedElement, type);
    }
  },
});

module.exports = Posts;
