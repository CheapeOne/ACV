<?php

	session_start();

	if (!(isset($_SESSION['user']) && isset($_SESSION['geolocation']))) {// if these aren't set, we know user isn't logged in, so tell them to do so!

		$arr = array (

		"userName" => null,
		"geolocation" => null

		);

		echo json_encode($arr);
	}

	else {

		$arr = array (

		"userName" => $_SESSION['user'],
		"geolocation" => $_SESSION['geolocation']

		);

		echo json_encode($arr);
	}


?>