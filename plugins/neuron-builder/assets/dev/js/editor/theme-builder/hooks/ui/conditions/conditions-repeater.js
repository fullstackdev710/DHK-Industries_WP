module.exports = elementor.modules.controls.Repeater.extend({
  childView: require("./repeater-row"),
  updateActiveRow: function updateActiveRow() {},
  initialize: function initialize() {
    elementor.modules.controls.Repeater.prototype.initialize.apply(
      this,
      arguments
    );
    this.config = elementor.config.document.theme_builder;
    this.updateConditionsOptions(this.config.settings.template_type);
  },
  checkConflicts: function checkConflicts(model) {
    var modelId = model.get("_id"),
      rowId = "elementor-condition-id-" + modelId,
      errorMessageId = "elementor-conditions-conflict-message-" + modelId,
      $error = jQuery("#" + errorMessageId); // On render - the row isn't exist, so don't cache it.

    jQuery("#" + rowId).removeClass("elementor-error");
    $error.remove();
    neuron.ajax.addRequest("theme_builder_conditions_check_conflicts", {
      unique_id: rowId,
      data: {
        condition: model.toJSON({
          remove: ["default"]
        })
      },
      success: function success(data) {
        if (!_.isEmpty(data)) {
          jQuery("#" + rowId)
            .addClass("elementor-error")
            .after(
              '<div id="' +
                errorMessageId +
                '" class="elementor-conditions-conflict-message">' +
                data +
                "</div>"
            );
        }
      }
    });
  },
  updateConditionsOptions: function updateConditionsOptions(templateType) {
    var self = this,
      conditionType = self.config.types[templateType].condition_type,
      options = {};

    _([conditionType]).each(function(conditionId, conditionIndex) {
      var conditionConfig = self.config.conditions[conditionId],
        group = {
          label: conditionConfig.label,
          options: {}
        };
      group.options[conditionId] = conditionConfig.all_label;

      _(conditionConfig.sub_conditions).each(function(subConditionId) {
        group.options[subConditionId] =
          self.config.conditions[subConditionId].label;
      });

      options[conditionIndex] = group;
    });

    var fields = this.model.get("fields");
    fields[1].default = conditionType;

    if ("general" === conditionType) {
      fields[1].groups = options;
    } else {
      fields[2].groups = options;
    }
  },
  onRender: function onRender() {
    this.ui.btnAddRow.text(neuron.translate("add_condition"));
    var self = this;
    this.collection.each(function(model) {
      self.checkConflicts(model);
    });
  },
  // Overwrite the original + checkConflicts.
  onRowControlChange: function onRowControlChange(model) {
    this.checkConflicts(model);
  }
});
