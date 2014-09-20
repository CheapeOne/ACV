angular.module('ACVApp.controllers', []).
  controller('homeController', function($scope) {
  	$scope.homeMessage = "WELCOME YUH"
   
  }).
  controller('404Controller', function($scope) {

  	$scope.message = "NOTHING TO SEE HERE";
   
  });
