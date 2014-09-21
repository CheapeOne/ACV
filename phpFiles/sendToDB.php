<?php

	include 'viewFunctions.php';

	//$action_to_execute = $_GET['action'];


	$userFiller = "toppest";
	$passwordFiller = "yell";
	$emailFiller = "derp@gmail.com";
	$action_to_execute = "addUser";
	$fillerIP = "128.61.92.135";
	$fillerLocation = "Atlanta, GA";
	$fillerUID = '1752698682';

	$action_to_execute = $_GET['action'];

	switch($action_to_execute) {


		case "isValidLogin":
			echo isValidLogin($_GET['email'], $_GET['password']);
			break;

		case "initAnonUID":
			//echo initAnonUID($_GET['username'], $_GET['password'], $_GET['email']);
			echo initAnonUID($fillerIP, $fillerLocation);
			break;

		case "signup":
			//remember to un-hardcode this
			echo "hit"; 
			echo signup($_SERVER['REMOTE_ADDR'], "Atlanta", '0', $_GET['user_to_add'], $_GET['email'], $_GET['password']);
			break;

		case "getLoginInfo":
			echo getLoginInfo($_GET['email'], $_GET['password']);
			break;


		//so on and so forth
	}

?>