
app.controller('familyCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    page = this;

    /**
     * Handle the Response from 'Product' AJAX Request
     * @param {Object} response
     */ 
    page.updateProduct = function(response) {
        return page.product = response.data;
    }

    /**
     * Get the best product from this family
     */
    page.getBestProduct = function() {
        // Request URL
        let url = page.API_URL + 'best/' + page.family_id;
        // AJAX Request
        return $http
            .get(url)
            .then(page.updateProduct)
        ;
    }

    /**
     * Get a product by
     * @param {int} family_id
     * @param {int} color_id
     * @param {int} size_id
     */
    page.getProductBy = function(family_id, color_id, size_id) {
        // Request URL
        let url = page.API_URL
            + 'family-' + family_id
            + '/' + 'color-' + color_id
            + '/' + 'size-' + size_id
        ;
        // AJAX Request
        return $http
            .get(url)
            .then(page.updateProduct)
        ;
    }

    $(function() {
        page.getBestProduct();        
    })

}]);