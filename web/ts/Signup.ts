import $ from 'jquery-ts'

interface Signup {
  firstname : string;
  lastname  : string;
  email     : string;
  password  : string;
}

interface Coordinate {
  address : string;
  zipcode : string;
  city    : string;
  type    : string;
}

var SignupForm     : Signup;
var CoordinateForm : Coordinate;

var $container = $('#trailwarehouse_appbundle_member_coordinates');
var template = $container.attr('data-prototype');
