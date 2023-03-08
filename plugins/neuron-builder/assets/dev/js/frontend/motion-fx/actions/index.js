export default class MotionActions extends elementorModules.Module {
  getMovePointFromPassedPercents(movableRange, passedPercents) {
    var movePoint = (passedPercents / movableRange) * 100;
    return +movePoint.toFixed(2);
  }

  getEffectValueFromMovePoint(range, movePoint) {
    return (range * movePoint) / 100;
  }

  getStep(passedPercents, options) {
    if ("element" === this.getSettings("type")) {
      return this.getElementStep(passedPercents, options);
    }

    return this.getBackgroundStep(passedPercents, options);
  }

  getElementStep(passedPercents, options) {
    return -(passedPercents - 50) * options.speed;
  }

  getBackgroundStep(passedPercents, options) {
    var movableRange = this.getSettings(
      "dimensions.movable" + options.axis.toUpperCase()
    );
    return -this.getEffectValueFromMovePoint(movableRange, passedPercents);
  }

  getDirectionMovePoint(passedPercents, direction, range) {
    var movePoint;

    if (passedPercents < range.start) {
      if ("out-in" === direction) {
        movePoint = 0;
      } else if ("in-out" === direction) {
        movePoint = 100;
      } else {
        movePoint = this.getMovePointFromPassedPercents(
          range.start,
          passedPercents
        );

        if ("in-out-in" === direction) {
          movePoint = 100 - movePoint;
        }
      }
    } else if (passedPercents < range.end) {
      if ("in-out-in" === direction) {
        movePoint = 0;
      } else if ("out-in-out" === direction) {
        movePoint = 100;
      } else {
        movePoint = this.getMovePointFromPassedPercents(
          range.end - range.start,
          passedPercents - range.start
        );

        if ("in-out" === direction) {
          movePoint = 100 - movePoint;
        }
      }
    } else if ("in-out" === direction) {
      movePoint = 0;
    } else if ("out-in" === direction) {
      movePoint = 100;
    } else {
      movePoint = this.getMovePointFromPassedPercents(
        100 - range.end,
        100 - passedPercents
      );

      if ("in-out-in" === direction) {
        movePoint = 100 - movePoint;
      }
    }

    return movePoint;
  }

  translateX(actionData, passedPercents) {
    actionData.axis = "x";
    actionData.unit = "px";
    this.transform("translateX", passedPercents, actionData);
  }

  translateY(actionData, passedPercents) {
    actionData.axis = "y";
    actionData.unit = "px";
    this.transform("translateY", passedPercents, actionData);
  }

  translateXY(actionData, passedPercentsX, passedPercentsY) {
    this.translateX(actionData, passedPercentsX);
    this.translateY(actionData, passedPercentsY);
  }

  tilt(actionData, passedPercentsX, passedPercentsY) {
    var options = {
      speed: actionData.speed / 10,
      direction: actionData.direction,
    };
    this.rotateX(options, passedPercentsY);
    this.rotateY(options, 100 - passedPercentsX);
  }

  rotateX(actionData, passedPercents) {
    actionData.axis = "x";
    actionData.unit = "deg";
    this.transform("rotateX", passedPercents, actionData);
  }

  rotateY(actionData, passedPercents) {
    actionData.axis = "y";
    actionData.unit = "deg";
    this.transform("rotateY", passedPercents, actionData);
  }

  rotateZ(actionData, passedPercents) {
    actionData.unit = "deg";
    this.transform("rotateZ", passedPercents, actionData);
  }

  scale(actionData, passedPercents) {
    var movePoint = this.getDirectionMovePoint(
      passedPercents,
      actionData.direction,
      actionData.range
    );
    this.updateRulePart(
      "transform",
      "scale",
      1 + (actionData.speed * movePoint) / 1000
    );
  }

  transform(action, passedPercents, actionData) {
    if (actionData.direction) {
      passedPercents = 100 - passedPercents;
    }

    this.updateRulePart(
      "transform",
      action,
      this.getStep(passedPercents, actionData) + actionData.unit
    );
  }

  opacity(actionData, passedPercents) {
    var movePoint = this.getDirectionMovePoint(
        passedPercents,
        actionData.direction,
        actionData.range
      ),
      level = actionData.level / 10,
      opacity = 1 - level + this.getEffectValueFromMovePoint(level, movePoint);
    this.$element.css({
      opacity: opacity,
      "will-change": "opacity",
    });
  }

  blur(actionData, passedPercents) {
    var movePoint = this.getDirectionMovePoint(
        passedPercents,
        actionData.direction,
        actionData.range
      ),
      blur =
        actionData.level -
        this.getEffectValueFromMovePoint(actionData.level, movePoint);
    this.updateRulePart("filter", "blur", blur + "px");
  }

  updateRulePart(ruleName, key, value) {
    if (!this.rulesVariables[ruleName]) {
      this.rulesVariables[ruleName] = {};
    }

    if (!this.rulesVariables[ruleName][key]) {
      this.rulesVariables[ruleName][key] = true;
      this.updateRule(ruleName);
    }

    var cssVarKey = "--".concat(key);
    this.$element[0].style.setProperty(cssVarKey, value);
  }

  updateRule(ruleName) {
    var value = "";
    jQuery.each(this.rulesVariables[ruleName], function (variableKey) {
      value += "".concat(variableKey, "(var(--").concat(variableKey, "))");
    });
    this.$element.css(ruleName, value);
  }

  runAction(actionName, actionData, passedPercents) {
    if (actionData.affectedRange) {
      if (actionData.affectedRange.start > passedPercents) {
        passedPercents = actionData.affectedRange.start;
      }

      if (actionData.affectedRange.end < passedPercents) {
        passedPercents = actionData.affectedRange.end;
      }
    }

    for (
      var _len = arguments.length,
        args = new Array(_len > 3 ? _len - 3 : 0),
        _key = 3;
      _key < _len;
      _key++
    ) {
      args[_key - 3] = arguments[_key];
    }

    this[actionName].apply(this, [actionData, passedPercents].concat(args));
  }

  refresh() {
    this.rulesVariables = {};
    this.$element.css({
      transform: "",
      filter: "",
      opacity: "",
      "will-change": "",
    });
  }

  onInit() {
    this.$element = this.getSettings("$targetElement");
    this.refresh();
  }
}
