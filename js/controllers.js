angular.module('ACVApp.controllers', []).


  controller('homeController', function($scope, $http) {
  	
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
        console.log("Question literally worked");
        console.log($scope.question);
        });
    };

  }).


  controller('questionController', function($scope, $http) {

  }).


  controller('loginController', function($scope, $http) {

  	$scope.myEmail;
  	$scope.myPassword;
  	$scope.dbUrl = "phpFiles/sendToDB.php";
  	$scope.myAction = "isValidLogin";

   	$scope.loginUser = function() {

        var request = $http({
        method: "post",
        url: $scope.dbUrl,
        params: {
        	action: $scope.myAction,
        	email: $scope.myEmail,
        	password: $scope.myPassword
        },
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {
        	if(data == "false"){
        		alert("Incorrect Email or Password");
        	}
        	else{
        		console.log("Login probably literally worked");
        	}
        });
    };

  }).

  controller('userController', function($scope, $http) {

  	$scope.myEmail;
  	$scope.myPassword;
  	$scope.dbUrl = "phpFiles/sendToDB.php";
  	$scope.myAction = "loginUser";

  }).

  controller('signupController', function($scope, $http) {

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
        console.log("Signup literally worked");
        console.log($scope.question);
        });
    };

  });
