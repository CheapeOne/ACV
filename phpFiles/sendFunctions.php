<?php

include 'basicDB.php';

$host = 'localhost';
$username = root;
$password = 'yuhclickyuh';
$dbName;
$user_table;
$registered_user_table;
$question_table;
$answer_table;
$user_answer;
$user_post;


function addQuestion($question_to_add) {

	global $host, $username, $password, $dbName, $user_table, $registered_user_table, $question_table;
	global $answer_table, $user_answer, $user_post;

	connectToDB($username, $password, $host, $dbName);
	
	$countQuery = "SELECT COUNT(id) FROM $question_table";
	$count = mysql_fetch_array(mysql_query($countQuery))[0];//we fetch an array of counts for each column and return the count of column 0

	$addQuestionQuery = "INSERT INTO $question_table VALUES ($count, $question_to_add)";
	$status = mysql_query($addQuestionQuery);

	if ($status == false) {// if the query failed, for whatever reason, let us know.

		return false;
	}

	return true;
}

function addUser($new_username, $new_user_password) {

	global $host, $username, $password, $dbName, $user_table, $registered_user_table, $question_table;
	global $answer_table, $user_answer, $user_post;

	connectToDB($username, $password, $host, $dbName);
	
	$countQuery = "SELECT COUNT(id) FROM $registered_user_table";
	$count = mysql_fetch_array(mysql_query($countQuery))[0];//we fetch an array of counts for each column and return the count of column 0

	$addQuestionQuery = "INSERT INTO $registered_user_table VALUES ($new_username, $new_user_password)";
	$status = mysql_query($addQuestionQuery);

	if ($status == false) {// if the query failed, for whatever reason, let us know.

		return false;
	}

	return true;
}



?>