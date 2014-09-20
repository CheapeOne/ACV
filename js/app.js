
var ACVApp = angular.module("ACVApp", [
  'ACVApp.controllers',
  'ngRoute'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.
	when("/404", 
	{
		templateUrl: "pages/404.html",
	}).
	when("/home",
	{
		templateUrl: "pages/home.html",
	}).
	otherwise(
	{
		redirectTo: '/404'
	});
}]);