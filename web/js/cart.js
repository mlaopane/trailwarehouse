var Cart = {
  count         : parseInt($('#cart-count').html()),
  updateItemQty : function()
  {
    //
    $('[data-action]').on('click', function (event) {
      let $button  = $(this);
      let $td      = $button.closest('td')
      let $input   = $td.find('input');
      let quantity = parseInt($input.val());
      let action   = $button.data('action');

      switch (action) {

        case 'decrease':
          if (quantity > 1) {
            $td.find('[data-action=update]').removeClass('invisible');
            $input.val(quantity - 1);
            $('.form-item').find('input[name=quantity]').val(quantity - 1)
          }
          break;

        case 'increase':
          if (quantity < 20) {
            $td.find($('[data-action=update]')).removeClass('invisible');
            $input.val(quantity + 1);
            $('.form-item').find('input[name=quantity]').val(quantity + 1)
          }
          break;

        case 'update':
          $button.addClass('invisible');
          let ajax_params = {
            dataType : 'json',
            method   : 'POST',
            url      : '...Something...',
            success  : function (response)
            {
              console.log(reponse);
            },
            error    : function (response, status)
            {
              console.log(reponse, status);
            },
          }
          $.ajax(ajax_params);
          break;

        default:
          console.info('Unauthorized action !');
          break;

      }

    });

    //
    $('.input-quantity').on('change', function (event) {
      let quantity = parseInt($(this).val());
      if (quantity < 1) {
        $(this).val(1)
      }
      if (quantity > 20) {
        $(this).val(20)
      }
    });
  } // END updateItemQty
}

$(function () {
  Cart.updateItemQty();
});
