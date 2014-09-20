angular.module('ACVApp.controllers', []).
  controller('homeController', function($scope, $http) {
  	$scope.homeMessage = "WELCOME YUH";
  	
  	$scope.myAction = "addQuestion";
  	$scope.myQuestionTitle;
  	$scope.myQuestionBody;
  	$scope.dbUrl = "phpFiles/sendToDB.php";
   
  	$scope.postQuestion = function() {
 
                var request = $http({
                method: "post",
                url: $scope.dbUrl,
                params: {
                	action: $scope.myAction,
                	question_to_add: $scope.myQuestionTitle,
                	question_body: $scope.myQuestionBody
                },
                data:  {
                        question: $scope.question
                },
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                });
 
                /* Check whether the HTTP Request is Successfull or not. */
                request.success(function (data) {
                console.log("POST literally worked");
                console.log($scope.question);
                });
        };

  }).
  controller('404Controller', function($scope) {

  	$scope.message = "NOTHING TO SEE HERE";
   
  }).
  controller('loginController', function($scope, $http) {

  	$scope.loginMessage = "Signup Page";

  	$scope.myAlias;
  	$scope.myEmail;
  	$scope.myPassword;
  	$scope.dbUrl = "phpFiles/sendToDB.php";
  	$scope.myAction = "addUser";

   	$scope.addUser = function() {
 
                var request = $http({
                method: "post",
                url: $scope.dbUrl,
                params: {
                	action: $scope.myAction,
                	user_to_add: $scope.myAlias,
                	email: $scope.myEmail,
                	password: $scope.myPassword
                },
                data:  {
                        question: $scope.question
                },
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                });
 
                /* Check whether the HTTP Request is Successfull or not. */
                request.success(function (data) {
                console.log("POST literally worked");
                console.log($scope.question);
                });
        };
  });
