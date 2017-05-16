var Canvas = {
  /**
   * @param {htmlELement} canvas
   * @param {string} color
   * @param {int} x
   * @param {int} y
   * @param {int} width
   * @param {int} height
   */
  drawRect: function (canvas, color = "rgba(0,0,0,0.9)", x = 0, y = 0, width = 60, height = 60) {
    if (canvas.getContext) {
      let context = canvas.getContext('2d');
      context.fillStyle = color;
      context.fillRect(x, y, width, height);
    }
    return this;
  },
  /**
   * @param {htmlELement} canvas
   * @param {string} color
   * @param {int} x
   * @param {int} y
   * @param {int} side
   */
  drawSquare: function(canvas, color = "rgba(0,0,0,0.9)", x = 0, y = 0, side = 60) {
    return this.drawRect(canvas, color, x, y, side, side);
  },
  /**
   * @param {htmlELement} canvas
   * @param {string} text
   * @param {string} font_size
   * @param {string} font_family
   */
  drawText: function (canvas, text, color, font_size, font_family, x = 0, y = 0) {
    if (canvas.getContext) {
      let context = canvas.getContext('2d');
      context.font = font_size + " " + font_family;
      context.fillStyle = color;
      context.fillText(text, x, y);
    }
    return this;
  }
};

var logo = {
  draw: function (canvas) {
    let blue = "hsla(200, 60%, 50%, 0.85)";
    let red = "hsla(350, 60%, 50%, 0.85)";
    let green = "hsla(140, 60%, 50%, 0.85)";
    Canvas
      .drawSquare(canvas, red, 10, 65, 15)
      .drawSquare(canvas, green, 15, 50, 25)
      .drawSquare(canvas, blue, 25, 30, 40)
    ;
    return this;
  }
}

$(document).ready(function () {
  let canvas = document.getElementById('logo');
  if (canvas) {
    logo.draw(canvas);
  }
});
