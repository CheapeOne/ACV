<?php

include 'basicDB.php';

$host = 'localhost';
$username = root;
$password = 'yuhclickyuh';
$dbName = 'acvdatabase';
$user_table ='users';
$registered_user_table = 'registeredusers';
$question_table = 'questions';
$answer_table;
$user_answer;
$user_post;


function addQuestion($question_to_add, $content) {

	global $host, $username, $password, $dbName, $user_table, $registered_user_table, $question_table;
	global $answer_table, $user_answer, $user_post;


	connectToDB($username, $password, $host, $dbName); // make sure this doesn't need to be called multiple times
	
	$countQuery = "SELECT COUNT(QID) FROM questions";
	$count = mysql_fetch_array(mysql_query($countQuery))[0] ;//we fetch an array of counts for each column and return the count of column 0

	$addQuestionQuery = "INSERT INTO questions (QID, UID, title, _timestamp, numRating, content, category, location) VALUES ('$count', 7, '$question_to_add', 0, 0, '$content', 0, 0)";
	$status = mysql_query($addQuestionQuery);
	
	if ($status == false) {// if the query failed, for whatever reason, let us know.
		file_put_contents("out", mysql_error());
		return false;
	}
	file_put_contents("out","true");
	return true;
}

function addUser($new_username, $new_user_password, $new_user_email) {

	global $host, $username, $password, $dbName, $user_table, $registered_user_table, $question_table;
	global $answer_table, $user_answer, $user_post;

	connectToDB($username, $password, $host, $dbName);
	
	$countQuery = "SELECT COUNT(id) FROM $registered_user_table";
	$count = mysql_fetch_array(mysql_query($countQuery))[0];//we fetch an array of counts for each column and return the count of column 0

	$addQuestionQuery = "INSERT INTO $user_table (UID, password, userType, sessionGeo, sessionIP) VALUES ('$new_username', '$new_user_password', 0, 0, 0)";
	$status = mysql_query($addQuestionQuery);

	if ($status == false) {// if the query failed, for whatever reason, let us know.

		return false;
	}

	return true;
}



?>