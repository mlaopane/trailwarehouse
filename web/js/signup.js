var $container = $('#trailwarehouse_appbundle_member_coordinates');
var template = $container.attr('data-prototype')
    .replace('__name__label__', '')
;
$container.append(template);
