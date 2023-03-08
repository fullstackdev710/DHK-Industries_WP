module.exports = elementorModules.editor.views.ControlsStack.extend({
  activeTab: "content",
  template: _.noop,

  getNamespaceArray: function () {
    return ["popup", "display-settings"];
  },

  className: function () {
    let classes = this.model.get("classes") + " neuron-popup__display-settings";

    return classes;
  },

  toggleGroup: function (groupName, $groupElement) {
    $groupElement.toggleClass("elementor-active", !!this.model.get(groupName));
  },

  onRenderTemplate: function () {
    this.activateFirstSection();
  },

  onRender: function () {
    var self = this;

    var name = this.getOption("name");
    var $groupWrapper;

    self.listenTo(self.model, "change", self.onModelChange);

    this.children.each(function (child) {
      var type = child.model.get("type");

      if ("heading" !== type) {
        if ($groupWrapper) {
          $groupWrapper.append(child.$el);
        }

        return;
      }

      var groupName = child.model.get("name").replace("_heading", "");

      $groupWrapper = jQuery("<div>", {
        id: "neuron-popup__"
          .concat(name, "-controls-group--")
          .concat(groupName),
        class: "neuron-popup__display-settings_controls_group",
      });

      var $imageWrapper = jQuery("<div>", {
        class: "",
      });

      $imageWrapper.html();
      $groupWrapper.html($imageWrapper);
      child.$el.before($groupWrapper);
      $groupWrapper.append(child.$el);

      self.toggleGroup(groupName, $groupWrapper);
    });
  },

  onModelChange: function () {
    var changedControlName = Object.keys(this.model.changed)[0],
      changedControlView = this.getControlViewByName(changedControlName);

    if ("switcher" !== changedControlView.model.get("type")) {
      return;
    }

    this.toggleGroup(changedControlName, changedControlView.$el.parent());
  },
});
