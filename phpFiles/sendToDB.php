<?php

	include 'insertFunctions.php';

	$action_to_execute = $_GET['action'];



	switch($action_to_execute) {

		case "addQuestion":
			echo addQuestion($_GET['question_to_add'], $_GET['question_body']);//echo true or false to see if it worked or not
			break;

		case "addUser":
			echo addUser($_GET['user_to_add'], $_GET['password'], $_GET['email']);
			break;

		//so on and so forth
	}

?>