<?php


session_start();

	if (isset($_SESSION['user']) && (time() <= $_SESSION['deathTime'])) {

			$arr = array (
				"username" => $_SESSION['user']
			);

			echo json_encode($arr);

	} 

	else {

		if (time() > $_SESSION['deathTime']) {

			session_destroy();
		}

			$arr = array (
				"username" => ""
			);

			echo json_encode($arr);
	}

?>