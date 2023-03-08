export class ThemeBuilderPreviewBreak extends $e.modules.hookData.Dependency {
  getCommand() {
    return "editor/documents/preview";
  }

  getId() {
    return "neuron-theme-builder-preview-break";
  }

  getConditions(args) {
    // If preview is forced, do not break it.
    if (args.force) {
      return false;
    }

    return !!elementor.documents.get(args.id).config.theme_builder;
  }

  apply() {
    return false; // HookBreak.
  }
}

export default ThemeBuilderPreviewBreak;
