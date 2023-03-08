module.exports = function getPosts(element, selector = "post") {
  var $element = jQuery(element),
    $posts = $element.find(".l-neuron-grid__item"),
    output = [],
    title = "";

  $posts.each(function () {
    if (selector == "portfolio") {
      title = jQuery(this).find(".m-neuron-portfolio__title").text();
    } else {
      title = jQuery(this)
        .find(".m-neuron-" + selector + "__title a")
        .text();
    }

    var id = jQuery(this).data("id");

    var item = {
      id: id.toString(),
      post: title,
      column: "3",
    };

    output.push(item);
  });

  return output;
};
