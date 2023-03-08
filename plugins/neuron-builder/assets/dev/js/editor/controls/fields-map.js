module.exports = elementor.modules.controls.Repeater.extend({
  onBeforeRender: function onBeforeRender() {
    this.$el.hide();
  },

  updateMap: function updateMap(fields) {
    var self = this,
      savedMapObject = {};

    self.collection.each(function(model) {
      savedMapObject[model.get("remote_id")] = model.get("local_id");
    });

    self.collection.reset();

    _.each(fields, function(field) {
      var model = {
        remote_id: field.remote_id,
        remote_label: field.remote_label,
        remote_type: field.remote_type ? field.remote_type : "",
        remote_required: field.remote_required ? field.remote_required : false,
        local_id: savedMapObject[field.remote_id]
          ? savedMapObject[field.remote_id]
          : ""
      };

      self.collection.add(model);
    });

    self.render();
  },

  onRender: function onRender() {
    elementor.modules.controls.Base.prototype.onRender.apply(this, arguments);

    var self = this;

    self.children.each(function(view) {
      var localFieldsControl = view.children.last(),
        options = {
          "": "- " + elementor.translate("None") + " -"
        },
        label = view.model.get("remote_label");

      if (view.model.get("remote_required")) {
        label += '<span class="elementor-required">*</span>';
      }

      _.each(self.elementSettingsModel.get("form_fields").models, function(
        model,
        index
      ) {
        // If it's an email field, add only email fields from thr form
        var remoteType = view.model.get("remote_type");

        if ("text" !== remoteType && remoteType !== model.get("field_type")) {
          return;
        }

        options[model.get("custom_id")] =
          model.get("field_label") || "Field #" + (index + 1);
      });

      localFieldsControl.model.set("label", label);
      localFieldsControl.model.set("options", options);
      localFieldsControl.render();

      view.$el.find(".elementor-repeater-row-tools").hide();
      view.$el
        .find(".elementor-repeater-row-controls")
        .removeClass("elementor-repeater-row-controls")
        .find(".elementor-control")
        .css({
          paddingBottom: 0
        });
    });

    self.$el.find(".elementor-button-wrapper").remove();

    if (self.children.length) {
      self.$el.show();
    }
  }
});
