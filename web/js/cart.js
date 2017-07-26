var Cart = {
  count : parseInt($('#cart-count').html()),
  updateItemQty : function(quantity, $td)
  {
    $td.find('[data-action=update]').removeClass('invisible');
    $td.find('[name=input-quantity]').val(quantity);
    $td.find('[name=hidden-quantity]').val(quantity)
  },
  changeQty : function()
  {
    /* When clicking on an element with the 'data-action' attribute */
    $('[data-action]').on('click', function (event) {
      let $trigger = $(this);
      let $td      = $trigger.closest('td')
      let action   = $trigger.data('action');
      let quantity = parseInt($td.find('[name=input-quantity]').val());

      switch (action)
      {
        case 'decrease':
          if (quantity > 1) {
            Cart.updateItemQty(quantity - 1, $td);
          }
          break;

        case 'increase':
          if (quantity < 50) {
            Cart.updateItemQty(quantity + 1, $td);
          }
          break;

        case 'update':
          $button.addClass('invisible');
          let ajax_params = {
            dataType : 'json',
            method   : 'POST',
            url      : '...Something...',
            success  : function (response) {
              console.log(response);
            },
            error    : function (response, status) {
              console.log(response, status);
            },
          }
          $.ajax(ajax_params);
          break;

        default:
          console.info('Unauthorized action !');
          break;
      }

    });

    /* */
    $('.input-quantity').on('change', function (event) {
      let $td      = $(this).closest('td')
      let quantity = parseInt($(this).val());

      if (quantity < 1) {
        $(this).val(1)
      }
      else if (quantity > 50) {
        $(this).val(50)
      }
      else {
        $td.find('[data-action=update]').removeClass('invisible');
        Cart.updateItemQty(quantity, $td);
      }
    });
  } // END updateItemQty
}

$(function () {
  Cart.changeQty();
});
