app.controller('familyCtrl', ['$scope', '$http', '$filter', function($scope, $http, $filter) {
  page = this;

  page.quantities = [];
  page.sizesLoaded = false;
  page.quantitiesLoaded = false;
  page.add_in_progress = false;

  /**
   * Get products by Family
   *
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
      if (page.colors.length <= 0) {
        page.product = products[0];
        page.sizesLoaded = true;
        page.quantitiesLoaded = true;
      }
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
    if (!page.product || (page.product.color.id != color_id && page.colors[color_id] != null) ) {
      page.sizesLoaded = false;
      page.quantitiesLoaded = false;
      // Request URL
      let url = page.API_URL +
      'family/' + family_id +
      '/' + 'color/' + color_id
      ;
      let products = [];
      // AJAX Request
      $http.get(url).then(function(response) {
        products = response.data;
        page.sizes = page.extractSizes(products);
        page.sizesLoaded = true;

        page.product = products[0];
        page.updateQuantities(page.product.stock);
      });
      return products;
    }
  }

  /**
   * Get a product by
   *
   * @param {int} family_id
   * @param {int} color_id
   * @param {int} size_id
   */
  page.getProductBy = function(family_id, color_id, size_id) {

    // IF product and sizes are initialized
    if (page.product != null && page.sizes != null) {

      // IF the size exists AND it's not the active size
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
          page.updateQuantities(page.product.stock);
        });
        return page.product;
      }
    }

  }

  /**
   * Extract Colors
   *
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
   *
   * @param {array} products
   * @return {array}
   */
  page.extractSizes = function(products) {
    let sizes = [];
    for (product of products) {
      if (product.stock > 0 && !sizes[product.color.id]) {
        sizes[product.size.id] = product.size;
      }
    }
    return sizes;
  }

  /**
   * Update Quantities
   *
   * @param {int} stock
   */
  page.updateQuantities = function(stock) {
    page.quantities = [];
    if (stock == 0) {
      page.quantities.push('-');
      page.quantity = 0;
    }
    else {
      page.quantity = 1;
      let max_quantity = (stock >= 10) ? (10) : (stock);
      if (stock) {
        for (let i = 1 ; i <= max_quantity ; i++) {
          page.quantities.push(i);
        }
      }
    }
    page.quantitiesLoaded = true;
  }

  /**
   * Add an Item to the Cart
   *
   * @param {object} product
   * @param {int} quantity
   */
  page.addToCart = function(product, quantity) {
    if (product != null && quantity > 0) {
      page.add_in_progress = true;
      let url = page.CART_URL + 'ajouter-item';
      let data = {
        product_id : product.id,
        quantity : quantity,
      }
      $http.post(url, data).then(function(response) {
        page.add_in_progress = false;
        let add_ok = JSON.parse(response.data);
        if (add_ok) {
          page.item = {
            product  : product,
            quantity : quantity,
            total    : product.price * quantity,
          }
          $('#item-modal').modal('show');
        }
      });
    }
  }

  /**
   *
   */
  page.udpateItem = function(id) {
    let url = page.CART_URL + 'modifier-item';
  }

  /**
   *
   */
  page.removeItem = function(id) {
    let url = page.CART_URL + 'supprimer-item';
    let data = { product_id : id }
    $http.delete(url, data).then(function (response) {
      console.info(response.data);
    });
  }

  /* Images Loaded */
	page.imgLoadedEvents = {
		always: function(instance) {
			// Do stuff
		},
		done: function(instance) {
			angular.element(instance.elements[0]).addClass('loaded');
		},
		fail: function(instance) {
			// Do stuff
		}

	};

  $(function() {
    page.getProductsByFamily(page.family_id);
    page.cart_count = parseInt($('#cart-count').html());
  })

}]);
