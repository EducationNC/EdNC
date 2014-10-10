(function() {
  var app = angular.module('ednc', ['ngRoute', 'ngResource']);

  // set up configuration variables
  app.run(['$rootScope', function($rootScope){
    $rootScope.api = WPAPI.api_url;
    $rootScope.nonce = WPAPI.api_nonce;
    $rootScope.dir = WPAPI.template_url;
  }]);

  // example controller
  app.controller('example', ['$scope', '$http', function($scope, $http){
    $http.get(
      $scope.api + '/posts'
    ).success(function(data, status, headers, config){
      $scope.postdata = data;
      console.log('success');
    }).error(function(data, status, headers, config){
      console.log('error');
    });
  }]);
})();
