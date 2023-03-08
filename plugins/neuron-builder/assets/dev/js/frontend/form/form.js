var FormSender = require("./form-sender"),
  Form = FormSender.extend();

var RedirectAction = require("./redirect");

module.exports = function($scope) {
  new Form({
    $element: $scope
  });
  new RedirectAction({
    $element: $scope
  });
};
