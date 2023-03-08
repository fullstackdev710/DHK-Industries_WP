module.exports = function MarionetteTitle(title) {
  if (!title) {
    return "Title";
  }

  // Replace Dash
  title = title.replace("-", " ");

  // Capitalize
  title = capitalizeFirstLetter(title);

  return title;
};

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
