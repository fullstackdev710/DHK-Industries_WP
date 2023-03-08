const FlipBox = elementorModules.editor.utils.Module.extend({
  onElementorInit: function onElementorInit() {
    elementor.channels.editor.on("section:activated", this.onSectionActivated);
  },

  onSectionActivated: function onSectionActivated(sectionName, editor) {
    var editedElement = editor.getOption("editedElementView");

    if ("neuron-flip-box" !== editedElement.model.get("widgetType")) {
      return;
    }

    var isSideBSection =
      -1 !== ["back_section", "back_style_section"].indexOf(sectionName);

    editedElement.$el.toggleClass("m-neuron-flip-box--flipped", isSideBSection);

    var $backLayer = editedElement.$el.find(".m-neuron-flip-box__item--back");

    if (isSideBSection) {
      $backLayer.css("transition", "none");
    }

    if (!isSideBSection) {
      setTimeout(function() {
        $backLayer.css("transition", "");
      }, 10);
    }
  }
});

module.exports = FlipBox;
