<?php

include 'basicDB.php';

$db_host = 'localhost';
$db_username = 'root';
$db_password = 'yuhclickyuh';
$db_name = 'acvdatabase';

$user_table ='users';
$registered_user_table = 'registeredusers';
$question_table = 'questions';
$answer_table;
$user_answer;
$user_post;


		//$selectQuery = $dbconn->query('SELECT * FROM table');
		//$results = $selectQuery->fetchAll(PDO::FETCH_ASSOC)

		//$selectQuery = $dbconn->prepare("SELECT * FROM table WHERE id=:id AND name=:name");
		//$selectQuery->execute(array(':name' => $name, ':id' => $id));
		//$rows = $selectQuery->fetchAll(PDO::FETCH_ASSOC);

//=== Login: ===
function isValidLogin($email,$password){
	//variables needed - $email,$password
    //The following query returns one or zero rows
	global $db_user, $db_password, $db_host, $db_name;
	
	connectToDB($db_user, $db_password, $db_host, $db_name);
	$loginQuery = $dbconn->prepare('Select password from (Users as U inner join RegisteredUsers as R on U.UID = R.UID) where email = :email');
	$loginQuery->execute(array(':email'=> $email)));
	$results = $selectQuery->fetch(PDO::FETCH_ASSOC);
    
    if $results:
        if $password == $results:
            return True;
        else:
			//password check failed
            return False;
    else:
		//username check failed
        return False;
}

//== Signup: ==
function signup(){
    //variables needed - $username,$email,$password,$IP,$sessionGeo - username can be a blank string
    //The following query returns one or zero rows

	global $db_user, $db_password, $db_host, $db_name;

	connectToDB($db_user, $db_password, $db_host, $db_name);
    $existingUserQuery  = $dbconn->query('Select email from RegisteredUsers where email = :email');
	$existingUserQuery->execute(array(':email'=> $email)));
	$results = $selectQuery->fetch(PDO::FETCH_ASSOC);

    if $results:
		//already a user
        return false;
    else:
		//add as a user
        insertUserQuery = INSERT INTO RegisterdUsers ()
}

//== View Local Questions: ==
function viewLocalQuestions($UID){
    global $db_user, $db_password, $db_host, $db_name;

	//variables needed - $UID
	connectToDB($db_user, $db_password, $db_host, $db_name);
	$viewLocalQuery = $dbconn->prepare('Select * from (Questions Q inner join Users U on U.UID = Q.UID) where (UID = :UID and location like :sessionGeo)'); //a little more logic needed here in the like clause
	$viewLocalQuery->execute(array(':UID'=> $UID,':sessionGeo'=>$sessionGeo)));
	$results = $selectQuery->fetchAll(PDO::FETCH_ASSOC);
}

//== View Question Answers: ==
function viewAnswersToQuestion($QID){
 	global $db_user, $db_password, $db_host, $db_name;

	connectToDB($db_user, $db_password, $db_host, $db_name);
	$viewLocalQuery = $dbconn->prepare('Select * from Questions where QID = :QID');
	$viewLocalQuery->execute(array(':QID'=> $QID)));
	$results = $selectQuery->fetchAll(PDO::FETCH_ASSOC);
}
//== View Your Answers: ==
function viewUserAnswers(){
	global $db_user, $db_password, $db_host, $db_name;

    //variables needed - $UID
    Select * from Answers where UID = $UID
}

//== View self profile: ==
function viewProfile($UID){
	global $db_user, $db_password, $db_host, $db_name;

    //variables needed- $UID
    Select * from RegisteredUsers where UID = $UID

//== View other profile: ==
    //variables needed - $otherUID
	global $db_user, $db_password, $db_host, $db_name;

    Select * from RegisteredUsers where UID = $otherUID

//== Set/Change Username: ==
    //LITERALLY SO EASY
    //variables needed - $NEWusername
    global $db_user, $db_password, $db_host, $db_name;

	Update RegisteredUsers set username = $NEWusername

//== Add Question: == 

//== Add Answer: ==

//== Rate Answers: ==
function rateAnswer(){
    //variables needed - $UID 
    Select AID from Rated where UID = $UID
}

?>