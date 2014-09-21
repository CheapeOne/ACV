<?php


session_start();

	if ($_SESSION) {

		if (isset($_SESSION['user']) && (time() <= $_SESSION['deathTime'])) {

				$arr = array (
					"username" => $_SESSION['user']
				);

				echo json_encode($arr);

		} 

		else {

				if ($_SESSION['deathTime'] != null && time() > $_SESSION['deathTime']) {

					session_destroy();
				}
			

				$arr = array (
					"username" => ""
				);

				echo json_encode($arr);
		}
	}
	else {

		$arr = array (
		"username" => ""
		);

	echo json_encode($arr);
	}

?>