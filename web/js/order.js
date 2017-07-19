$(document).ready(function () {
  // Radio Change Event
  $('#address-form').find('input[type=radio]').on('change', function () {
    let id = $(this).val();
    $('#user-addresses').find('.address').addClass('hide');
    $('#user-addresses').find('#address-' + id).removeClass('hide');
    $('#no-address').addClass('hide');
  });
});
