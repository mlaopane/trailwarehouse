app.controller('familyCtrl', ['$scope', '$http', '$filter', function($scope, $http, $filter) {
  page = this;

  page.add_in_progress = false;

  /**
   * Get products by Family
   * @param {int} family_id
   * @param {array}
   */
  page.getProductsByFamily = function(family_id) {
    // Request URL
    let url = page.API_URL + 'family/' + family_id;
    let products = [];
    // AJAX Request
    $http.get(url).then(function(response) {
      products = response.data;
      page.colors = page.extractColors(products);
      // Get the product using 1 color from page.colors
      for (let index in page.colors) {
        page.getProductsByColor(family_id, page.colors[index].id);
        break;
      }
    });
    return products;
  }

  /**
   * Get products by
   *
   * @param {int} family_id
   * @param {int} color_id
   */
  page.getProductsByColor = function(family_id, color_id) {
    // IF product doesn't exist OR it is not the active color
    if (!page.product || page.product.color.id != color_id) {
      page.loading = true;
      // Request URL
      let url = page.API_URL +
        'family/' + family_id +
        '/' + 'color/' + color_id
      ;
      let products = [];
      // AJAX Request
      $http.get(url).then(function(response) {
        page.loading = false;
        products = response.data;
        page.sizes = page.extractSizes(products);
        page.product = products[0];
      });
      return products;
    }
  }

  /**
   * Get a product by
   * @param {int} family_id
   * @param {int} color_id
   * @param {int} size_id
   */
  page.getProductBy = function(family_id, color_id, size_id) {

    // IF product and sizes are initialized
    if (page.product != null && page.sizes != null) {

      // IF the size exists for the active AND it's not the active size
      if (page.sizes[size_id] != null && page.product.size.id != size_id) {
        // Set the active size to update the view ASAP
        page.product.size.id = size_id;
        // Request URL
        let url = page.API_URL
          + 'family/' + family_id
          + '/' + 'color/' + color_id
          + '/' + 'size/' + size_id
        // AJAX Request
        $http.get(url).then(function(response) {
          page.product = response.data[0];
        });
        return page.product;
      }
    }

  }

  /**
   * Extract Colors
   * @param {array} products
   * @return {array}
   */
  page.extractColors = function(products) {
    let colors = [];
    for (product of products) {
      if (product.stock > 0 && !colors[product.color.id]) {
        colors[product.color.id] = product.color;
      }
    }
    return colors;
  }

  /**
   * Extract Sizes
   * @param {array} products
   * @return {array}
   */
  page.extractSizes = function(products) {
    let sizes = [];
    for (product of products) {
      if (product.stock > 0) {
        sizes[product.size.id] = product.size;
      }
    }
    return sizes;
  }

  /**
   *
   *
   */
  page.addToCart = function(product, quantity) {
    page.add_in_progress = true;
    let url = page.SHOP_URL + 'cart/add';
    let data = {
      product : product,
      quantity : quantity,
    }
    $http.post(url, data).then(function(response) {
      page.add_in_progress = false
      console.log(JSON.parse(response.data));
    });
  }

  $(function() {
    page.getProductsByFamily(page.family_id);
  })

}]);
