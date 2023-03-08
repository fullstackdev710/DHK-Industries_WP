const ElementEditorModule = require("../utils/module");

const Shortcode = ElementEditorModule.extend({
  lastRemovedModelId: false,
  collectionEventsAttached: false,

  formFieldEvents: {
    ADD: "add",
    SORT: "sort",
    DUPLICATE: "duplicate",
    CHANGE: "change"
  },

  getExistId: function getExistId(id) {
    var exist = this.getEditorControlView("form_fields").collection.filter(
      function(model) {
        return id === model.get("custom_id");
      }
    );

    return exist.length > 1;
  },

  getFormFieldsView: function getFormFieldsView() {
    return this.getEditorControlView("form_fields");
  },

  onFieldUpdate: function onFieldUpdate(collection, update) {
    if (!update.add) {
      return;
    }
    var event = this.formFieldEvents.ADD;
    var addedModel = update.changes.added[0];
    if (update.at) {
      if (
        this.lastRemovedModelId &&
        addedModel.attributes.custom_id === this.lastRemovedModelId
      ) {
        event = this.formFieldEvents.SORT;
      } else {
        event = this.formFieldEvents.DUPLICATE;
      }
      this.lastRemovedModelId = false;
    }
    this.updateIdAndShortcode(addedModel, event);
  },

  onFieldChanged: function onFieldChanged(model) {
    if (!_.isUndefined(model.changed.custom_id)) {
      this.updateIdAndShortcode(model, this.formFieldEvents.CHANGE);
    }
  },

  onFieldRemoved: function onFieldRemoved(model) {
    this.lastRemovedModelId = model.attributes.custom_id;
    this.getFormFieldsView().children.each(this.updateShortcode);
  },

  updateIdAndShortcode: function updateIdAndShortcode(model, event) {
    var _this = this;

    var view = this.getFormFieldsView().children.findByModel(model);

    _.defer(function() {
      _this.updateId(view, event);
      _this.updateShortcode(view);
    });
  },

  getFieldId: function getFieldId(model, event) {
    if (
      event === this.formFieldEvents.ADD ||
      event === this.formFieldEvents.DUPLICATE
    ) {
      return model.get("_id");
    }
    var customId = model.get("custom_id");
    return customId ? customId : model.get("_id");
  },

  updateId: function updateId(view, event) {
    var id = this.getFieldId(view.model, event),
      sanitizedId = id.replace(/[^\w]/, "_"),
      fieldIndex = 1,
      isNew =
        event === this.formFieldEvents.ADD ||
        event === this.formFieldEvents.DUPLICATE;
    var IdView = view.children.filter(function(childrenView) {
      return "custom_id" === childrenView.model.get("name");
    });

    while (sanitizedId !== id || this.getExistId(id) || isNew) {
      if (sanitizedId !== id) {
        id = sanitizedId;
      } else if (isNew || this.getExistId(id)) {
        id = "field_" + fieldIndex;
        sanitizedId = id;
      }

      view.model.attributes.custom_id = id;
      IdView[0].render();
      IdView[0].$el.find("input").focus();
      fieldIndex++;
      isNew = false;
    }
  },

  updateShortcode: function updateShortcode(view) {
    var template = _.template('[field id="<%= id %>"]')({
      title: view.model.get("field_label"),
      id: view.model.get("custom_id")
    });

    view.$el
      .find(".elementor-form-field-shortcode")
      .focus(function() {
        this.select();
      })
      .val(template);
  },

  onSectionActive: function onSectionActive() {
    var controlView = this.getEditorControlView("form_fields");

    controlView.children.each(this.updateShortcode);

    if (!controlView.collection.shortcodeEventsAttached) {
      controlView.collection
        .on("change", this.onFieldChanged)
        .on("update", this.onFieldUpdate)
        .on("remove", this.onFieldRemoved);
      controlView.collection.shortcodeEventsAttached = true;
    }
  },

  onInit: function onInit() {
    this.addSectionListener("section_form_fields", this.onSectionActive);
  }
});

module.exports = Shortcode;
