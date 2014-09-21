angular.module('ACVApp.controllers', []).


  controller('homeController', function($scope, $http, $rootScope) {
  	
  	$scope.dbUrl = "phpFiles/sendToDB.php";
  	$scope.getLocationUrl = "phpFiles/geo/geolocator.php";
  	$scope.getSessionUrl = "phpFiles/sessions/getSession.php";
  	$scope.setSessionUrl = "phpFiles/sessions/setSession.php";
  	$scope.killSessionUrl = "phpFiles/sessions/killSession.php";

  	$scope.myLocation = "Atlanta, Georgia, US";

  	$scope.Math = window.Math;
    
  	/*	
		Event Listeners
  	*/

  	$rootScope.$on("setSession", function(){
  		if($rootScope.userAlias == null){
  			alert("Cannot create Session, no one is signed in!");
  		}
  		else{
  			$scope.setSession();
  		}
  	});

  	$rootScope.$on("killSession", function(){
  		$scope.killSession();
  	});

  	$scope.getLocation = function() {
 
        var request = $http({
        method: "get",
        url: $scope.getLocationUrl,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {
        	console.log("Get Location probably literally worked");
        	$scope.myLocation = data;



        });
    };

    $scope.getSession = function() {

        var request = $http({
        method: "get",
        url: $scope.getSessionUrl,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {
        	console.log("Session probably literally got");
        	var sessionData = angular.fromJson(data)
        	console.log(angular.fromJson(data)["username"]);


        	if(angular.fromJson(data)["username"] != ""){

        		$rootScope.userAlias = angular.fromJson(data)["username"];
        		$rootScope.$emit("userLogin");
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
        	console.log("Session probably literally set");
        });
    };

    $scope.killSession = function() {
 
        var request = $http({
        method: "post",
        url: $scope.killSessionUrl,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {
        	console.log("Session probably literally killed");
        });
    };

    $scope.myQuestionTitle;
  	$scope.myQuestionBody = "";
  	$scope.questions;
  	$scope.currentTime;

  	$scope.myQuestionLimit = 15;

  	$scope.postQuestion = function() {
 
        var request = $http({
        method: "get",
        url: $scope.dbUrl,
        params: {
        	action: "addQuestion",
        	question_to_add: $scope.myQuestionTitle,
        	question_body: $scope.myQuestionBody
        },
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {

        	console.log("Question literally maybe worked");
        	console.log("SWEET SWEET QUESATIONS "+$scope.myQuestionTitle);
        	console.log(data);
        	if(data == false){
        		alert("Post Question Failed");
        	}
        	else{
        		$('#questionField').val('');
        		$scope.getQuestions();
        	}
        });
    };

    $scope.getQuestions = function() {
 
 		$scope.currentTime = Date.now();
        var request = $http({
        method: "get",
        url: $scope.dbUrl,
        params: {
        	action: "viewQuestions",
        	questionLimit: $scope.myQuestionLimit
        },
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        /* Check whether the HTTP Request is Successfull or not. */
        request.success(function (data) {

        	if(data == false){
        		alert("No Questions Found! Be the first today!");
        	}else{
        		console.log("Got Q's");
        		$scope.questions = data;
        	}
        });
    };

    $scope.incrementQuestions = function(increment) {
    	$scope.myQuestionLimit += increment;
    };

  }).

  controller('navController', function($scope, $http, $rootScope) {

  	$scope.loggedIn = false;
  	$scope.navAlias = "";

  	$rootScope.$on("userLogin", function(){
  		$scope.navAlias = $rootScope.userAlias;
  		$scope.loggedIn = true;
  	});

  	// Set saved user values to null, destroy current session
  	$scope.logout = function() {

  		alert($rootScope.userAlias+" logged out!")

  		$scope.loggedIn = false;
  		$scope.navAlias = "";

  		$rootScope.userAlias = null;
  		$rootScope.userEmail = null;
  		$rootScope.userScore = null;	

  		$rootScope.$emit("killSession");
  	};

  }).

  controller('loginController', function($scope, $http, $rootScope) {

  	$scope.myEmail;
  	$scope.myPassword;
  	$scope.dbUrl = "phpFiles/sendToDB.php";

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
        			$rootScope.$emit("setSession");
        		}
        		else{
        			alert("Login messed up!");
        		}
        	}
        });
    };

  }).

  controller('questionController', function($scope, $http, $rootScope) {
  	$scope.time;

  	$scope.convertDate = function(unix_timestamp){

  		$scope.time= Date(unix_timestamp * 1000).format('h:i:s');
  		console.log("TIME IS: "+$scope.time);
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
        			$rootScope.$emit("setSession");
        		}
        		else{
        			alert("Sign up messed up!");
        		}
        	}
        });
    };
  });
