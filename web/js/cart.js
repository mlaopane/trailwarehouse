var Cart = {
  count         : parseInt($('#cart-count').html()),
  updateItemQty : function() {
    $('[data-action]').on('click', function (event) {
      let $button = $(this);
      let $input = $button.parent().find('input');
      let quantity = parseInt($input.val());
      let action = $button.data('action');
      switch (action) {
        case 'decrease':
          if (quantity > 1) {
            $input.val(quantity - 1);
          }
          break;
        case 'increase':
          if (quantity < 20) {
            $input.val(quantity + 1);
          }
          break;
        default:
          break;
      }
    });
    $('.input-quantity').on('change', function (event) {
      let quantity = parseInt($(this).val());
      if (quantity < 1) {
        $(this).val(1)
      }
      if (quantity > 20) {
        $(this).val(20)
      }
    });
  }
}

$(function () {
  Cart.updateItemQty();
});
