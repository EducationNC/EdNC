(function() {
  var app = angular.module('ednc', []);

  // set up configuration variables
  app.run(['$rootScope', function($rootScope) {
    $rootScope.api = WPAPI.api_url;
    $rootScope.nonce = WPAPI.api_nonce;
    $rootScope.dir = WPAPI.template_url;
  }]);

  // example controller
  app.controller('example', ['$scope', '$http', function($scope, $http) {
    $http.get(
      $scope.api + '/posts?filter[posts_per_page]=6'
    ).success(function(data, status, headers, config) {
      $scope.postdata = data;
      // console.log('success');
    }).error(function(data, status, headers, config) {
      // console.log('error');
    });
  }]);

  // Underwriter Service
  app.factory("UnderwriterService", ['$http', function($http) {

    var api = WPAPI.api_url,
        nonce = WPAPI.api_nonce;

    // Set up the functions for getting data about underwriters
    return {
      single: function() {
        return $http.get(
          api + '/posts?type=underwriter&filter[posts_per_page]=1&filter[orderby]=rand&_wp_json_nonce=' + nonce
        ).success(function(data, status, headers, config) {
          return data;
        });
      },

      meta: function(id) {
        return $http.get(
          api + '/posts/' + id + '/meta?_wp_json_nonce=' + nonce
        ).success(function(data, status, headers, config) {
          return data;
        });
      }
    }

  }]);

  // controller for underwriter block
  app.controller("underwriter", ["$scope", "UnderwriterService", function($scope, UnderwriterService) {
    // First, call service to get single underwriter
    UnderwriterService.single()
      .then(function(response) {
        // Then, call service to get underwriter metadata
        UnderwriterService.meta(response.data[0]['ID'])
          .then(function(response) {
            $scope.postmeta = response.data;
            console.log($scope.postmeta);
          });
      });
  }]);

})();
