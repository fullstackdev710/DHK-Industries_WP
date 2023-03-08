import PopupModule from "../frontend/popup";

const extendDefaultHandlers = (defaultHandlers) => {
  const handlers = {
    popup: PopupModule,
    posts: require("../frontend/posts"),
    products: require("../frontend/products"),
    gallery: require("../frontend/gallery"),
    form: require("../frontend/form"),
    slides: require("../frontend/slides"),
    quantity: require("../frontend/quantity"),
    shareButtons: require("../frontend/share-buttons"),
    maps: require("../frontend/maps"),
    animatedHeading: require("../frontend/animated-heading"),
    countdown: require("../frontend/countdown"),
    sticky: require("../frontend/sticky"),
    themeElements: require("../frontend/theme-elements"),
    clickableColumn: require("../frontend/clickable-column"),
    tableOfContents: require("../frontend/table-of-contents"),
    navMenu: require("../frontend/nav-menu"),
    woocommerce: require("../frontend/woocommerce"),
    navigation: require("../frontend/post-navigation"),
    interactivePosts: require("../frontend/interactive-posts"),
    animations: require("../frontend/animations"),
  };
  return { ...defaultHandlers, ...handlers };
};

neuronFrontend.on("neuron/modules/init:before", () => {
  elementorFrontend.hooks.addFilter(
    "neuron/frontend/handlers",
    extendDefaultHandlers
  );
});
