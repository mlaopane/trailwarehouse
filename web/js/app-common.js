var app = angular.module('app', []);
/*
* The capitalize filter uppercases the 1st character of a string
*/
app.filter('capitalize', function() {
  return function (input) {
    if (typeof input === 'string')
      str = input[0].toUpperCase() + input.substr(1);
    else
      str = input;
    return str;
  }
});

/*
* The nl2br filter transform the newlines to <br> tags
*/
app.filter('nl2br', function($sce) {
  return function (input) {
    if (typeof input === 'string')
      return $sce.trustAsHtml(input.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br>' + '$2'));
    else
      return input;
  }
});

/*
* The sp2nbsp filter transform the spaces into nbsp;
*/
app.filter('sp2nbsp', function($sce) {
  return function (input) {
    if (typeof input === 'string')
      return $sce.trustAsHtml(input.replace(/ /g, "\u00a0"));
    else
      return input;
  }
});

/*
* The us2nbsp filter transform the underscores into nbsp;
*/
app.filter('us2nbsp', function($sce) {
  return function (input) {
    if (typeof input === 'string')
      return $sce.trustAsHtml(input.replace(/_/g, "\u00a0"));
    else
      return input;
  }
});