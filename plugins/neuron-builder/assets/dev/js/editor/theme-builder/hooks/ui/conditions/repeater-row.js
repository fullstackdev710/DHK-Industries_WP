module.exports = elementor.modules.controls.RepeaterRow.extend({
  template: "#tmpl-elementor-theme-builder-conditions-repeater-row",
  childViewContainer:
    ".elementor-theme-builder-conditions-repeater-row-controls",
  id: function id() {
    return "elementor-condition-id-" + this.model.get("_id");
  },
  onBeforeRender: function onBeforeRender() {
    var subNameModel = this.collection.findWhere({
        name: "sub_name"
      }),
      subIdModel = this.collection.findWhere({
        name: "sub_id"
      }),
      subConditionConfig = this.config.conditions[
        this.model.attributes.sub_name
      ];
    subNameModel.attributes.groups = this.getOptions();

    if (subConditionConfig && subConditionConfig.controls) {
      _(subConditionConfig.controls).each(function(control) {
        subIdModel.set(control);
        subIdModel.set("name", "sub_id");
      });
    }
  },
  initialize: function initialize() {
    elementor.modules.controls.RepeaterRow.prototype.initialize.apply(
      this,
      arguments
    );
    this.config = elementor.config.document.theme_builder;
  },
  updateOptions: function updateOptions() {
    if (this.model.changed.name) {
      this.model.set({
        sub_name: "",
        sub_id: ""
      });
    }

    if (this.model.changed.name || this.model.changed.sub_name) {
      this.model.set("sub_id", "", {
        silent: true
      });
      var subIdModel = this.collection.findWhere({
        name: "sub_id"
      });
      subIdModel.set({
        type: "select",
        options: {
          "": "All"
        }
      });
      this.render();
    }

    if (this.model.changed.type) {
      this.setTypeAttribute();
    }
  },
  getOptions: function getOptions() {
    var self = this,
      conditionConfig = self.config.conditions[this.model.get("name")];

    if (!conditionConfig) {
      return;
    }

    var options = {
      "": conditionConfig.all_label
    };

    _(conditionConfig.sub_conditions).each(function(
      conditionId,
      conditionIndex
    ) {
      var subConditionConfig = self.config.conditions[conditionId],
        group;

      if (!subConditionConfig) {
        return;
      }

      if (subConditionConfig.sub_conditions.length) {
        group = {
          label: subConditionConfig.label,
          options: {}
        };
        group.options[conditionId] = subConditionConfig.all_label;

        _(subConditionConfig.sub_conditions).each(function(subConditionId) {
          group.options[subConditionId] =
            self.config.conditions[subConditionId].label;
        }); // Use a sting key - to keep order

        options["key" + conditionIndex] = group;
      } else {
        options[conditionId] = subConditionConfig.label;
      }
    });

    return options;
  },
  setTypeAttribute: function setTypeAttribute() {
    var typeView = this.children.findByModel(
      this.collection.findWhere({
        name: "type"
      })
    );
    typeView.$el.attr(
      "data-elementor-condition-type",
      typeView.getControlValue()
    );
  },
  onRender: function onRender() {
    var nameModel = this.collection.findWhere({
        name: "name"
      }),
      subNameModel = this.collection.findWhere({
        name: "sub_name"
      }),
      subIdModel = this.collection.findWhere({
        name: "sub_id"
      }),
      nameView = this.children.findByModel(nameModel),
      subNameView = this.children.findByModel(subNameModel),
      subIdView = this.children.findByModel(subIdModel),
      conditionConfig = this.config.conditions[this.model.attributes.name],
      subConditionConfig = this.config.conditions[
        this.model.attributes.sub_name
      ],
      typeConfig = this.config.types[this.config.settings.template_type];

    if (
      typeConfig.condition_type === nameView.getControlValue() &&
      "general" !== nameView.getControlValue() &&
      !_.isEmpty(conditionConfig.sub_conditions)
    ) {
      nameView.$el.hide();
    }

    if (
      !conditionConfig ||
      (_.isEmpty(conditionConfig.sub_conditions) &&
        _.isEmpty(conditionConfig.controls)) ||
      !nameView.getControlValue() ||
      "general" === nameView.getControlValue()
    ) {
      subNameView.$el.hide();
    }

    if (
      !subConditionConfig ||
      _.isEmpty(subConditionConfig.controls) ||
      !subNameView.getControlValue()
    ) {
      subIdView.$el.hide();
    } // Avoid set a `single` for a-l-l singular types. (conflicted with 404 & custom cpt like Shops and Events plugins).

    if ("singular" === typeConfig.condition_type) {
      if ("" === subNameView.getControlValue()) {
        subNameView.setValue("post");
      }
    }

    this.setTypeAttribute();
  },
  onModelChange: function onModelChange() {
    elementor.modules.controls.RepeaterRow.prototype.onModelChange.apply(
      this,
      arguments
    );
    this.updateOptions();
  }
});
