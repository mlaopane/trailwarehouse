app.controller('familyCtrl', ['$scope', '$http', '$filter', function($scope, $http, $filter) {
  page = this;

  /**
   * Get the best product from this family
   */
  page.getBestProduct = function() {
    // Request URL
    let url = page.API_URL + 'best/' + page.family_id;
    // AJAX Request
    return $http
      .get(url)
      .then(function(response) {
        page.product = response.data;
      });
  }

  /**
   * Get a product by
   * @param {int} family_id
   * @param {int} color_id
   * @param {int} size_id
   */
  page.getProductsByColor = function(family_id, color_id) {
    // Request URL
    let url = page.API_URL
      + 'family/' + family_id
      + '/' + 'color/' + color_id
    // AJAX Request
    return $http
      .get(url)
      .then(function(response) {
        let products = response.data;
        page.sizes = [];
        for (product of products) {
          page.sizes[product.size.id] = product.size;
        }
        page.product = products[0];
      });
  }

  $(function() {
    page.getBestProduct();
  })

}]);
