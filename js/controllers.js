angular.module('ACVApp.controllers', []).


  controller('homeController', function($scope, $http, $rootScope) {
  	
  	$scope.myQuestionTitle;
  	$scope.myQuestionBody;
  	$scope.dbUrl = "phpFiles/sendToDB.php";
  	$scope.getLocationUrl = "phpFiles/geo/geolocator.php";
  	$scope.getSessionUrl = "phpFiles/sessions/getSession.php";
    
  	$scope.getLocation = function() {
 
        var request = $http({
        method: "get",
        url: $scope.getLocationUrl,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {
        	console.log("Get Location probably literally worked");
        	console.log("Location Data: "+data);

        });
    };

    $scope.getSession = function() {
        console.log("Got inside the getSession");

        var request = $http({
        method: "get",
        url: $scope.getSessionUrl,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        	console.log("Got past headers");

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {
        	console.log("Get Session probably literally worked");
        	//var sessionData = angular.fromJson(data)
        	//console.log(angular.fromJson(data)["username"]);


        	if(angular.fromJson(data)["username"] != ""){

        		$rootScope.userAlias = angular.fromJson(data)["username"];
        		$rootScope.$emit("userLogin");
        	}
        });
    };

    $scope.postQuestion = function() {
 
        var request = $http({
        method: "post",
        url: $scope.dbUrl,
        params: {
        	action: "addQuestion",
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
        });
    };

  }).

  controller('navController', function($scope, $http, $rootScope) {

  	$scope.loggedIn = false;
  	$scope.navAlias = "";

  	$rootScope.$on("userLogin", function(){
  		$scope.navAlias = $rootScope.userAlias;
  		$scope.loggedIn = true;
  	});



  }).

  controller('questionController', function($scope, $http) {

  }).

  controller('loginController', function($scope, $http, $rootScope) {

  	$scope.myEmail;
  	$scope.myPassword;
  	$scope.dbUrl = "phpFiles/sendToDB.php";
  	$scope.setSessionUrl = "phpFiles/sessions/setSession.php";

  	$scope.prahLogin = function() {
  		$scope.myEmail = "tehdude@gmail.com";
  		$scope.myPassword = "helloworld";
  	};

   	$scope.isValidLogin = function() {

        var request = $http({
        method: "post",
        url: $scope.dbUrl,
        params: {
        	action: "getLoginInfo",
        	email: $scope.myEmail,
        	password: $scope.myPassword
        },
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {
        	if(data == false){
        		alert("Incorrect Email or Password");
        	}
        	else{
        		console.log("Login probably literally worked");
        		console.log(data);
        		console.log("email: " + angular.fromJson(data)["email"]);
        		console.log("whole dealarino "+angular.fromJson(data));

        		$rootScope.userAlias = angular.fromJson(data)["userName"];
        		$rootScope.userEmail = angular.fromJson(data)["email"];
        		$rootScope.userScore = angular.fromJson(data)["userRank"];
        		$rootScope.$emit("userLogin");
        		$('#loginModal').modal('hide');

        		if($rootScope.userAlias != null){
        			$scope.setSession();
        		}
        		else{
        			alert("Login messed up!");
        		}
        	}
        });
    };

    $scope.setSession = function() {
 
        var request = $http({
        method: "post",
        url: $scope.setSessionUrl,
        params: {
        	input: $rootScope.userAlias
        },
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {
        	console.log("Session probably literally worked");
        });
    };

  }).

  controller('userController', function($scope, $http, $rootScope) {

  	$scope.myAlias;
  	$scope.myEmail;
  	$scope.myScore;
  	$scope.dbUrl = "phpFiles/sendToDB.php";

  	$rootScope.$on("userLogin", function(){
  		$scope.myAlias = $rootScope.userAlias;
  		$scope.myEmail = $rootScope.userEmail;
  		$scope.myScore = $rootScope.userScore;
  	
  	});

  }).

  controller('signupController', function($scope, $http, $rootScope) {

  	$scope.myAlias;
  	$scope.myEmail;
  	$scope.myPassword;
  	$scope.dbUrl = "phpFiles/sendToDB.php";

   	$scope.signup = function() {
 
        var request = $http({
        method: "post",
        url: $scope.dbUrl,
        params: {
        	action: "signup",
        	user_to_add: $scope.myAlias,
        	email: $scope.myEmail,
        	password: $scope.myPassword
        },
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {
        	console.log("Signup probably literally worked");
        	if(data == "false"){
        		alert("User Already Exists");
        	}
        	else{
        		$rootScope.userAlias =	$scope.myAlias;
  				$rootScope.userEmail =	$scope.myEmail;
  				$rootScope.userScore =	0;

  				$rootScope.$emit("userLogin");
        		$('#signupModal').modal('hide');

        		if($rootScope.userAlias != null){
        			$scope.setSession();
        		}
        		else{
        			alert("Sign up messed up!");
        		}
        	}
        });
    };
  });
