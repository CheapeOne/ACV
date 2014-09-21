<?php

if (!function_exists('connectToDB')) {
include "basicDB.php";
}

$db_host = "localhost";
$db_user = "root";
$db_password = "yuhclickyuh";
$db_name = "acvdatabase";

$user_table ="users";
$registered_user_table = "registeredusers";
$question_table = "questions";
$answer_table;
$user_answer;
$user_post;


		//$selectQuery = $dbconn->query("SELECT * FROM table");
		//$results = $selectQuery->fetchAll(PDO::FETCH_ASSOC)

		//$selectQuery = $dbconn->prepare("SELECT * FROM table WHERE id=:id AND name=:name");
		//$selectQuery->execute(array(":name" => $name, ":id" => $id));
		//$rows = $selectQuery->fetchAll(PDO::FETCH_ASSOC);

//=== Login: ===
function isValidLogin($email,$password){
	//variables needed - $email,$password
    //The following query returns one or zero rows
	global $db_user, $db_password, $db_host, $db_name;
	
	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);
	$loginQuery = $dbconn->prepare("Select password from (Users as U inner join RegisteredUsers as R on U.UID = R.UID) where email = :email");
	$loginQuery->execute(array(":email"=> $email));
	$results = $loginQuery->fetch(PDO::FETCH_ASSOC);
    
    if ($results) {
        if (hash("sha256", $password) == $results["password"]) {
            return true;
        }
        else {
			//password check failed
            return false;//hash("sha256", $password)." vs. ".$results["password"] ;
        }
    }
    else {
	//username check failed
    return false;
    }
}

function getLoginInfo($email,$password){
	global $db_user, $db_password, $db_host, $db_name;

	if (isValidLogin($email,$password)){
		$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);
		$userInfoQuery = $dbconn->prepare("Select * from (Users as U inner join RegisteredUsers as R on U.UID = R.UID) where email = :email");
		$userInfoQuery->execute(array(":email"=> 'derp@gmail.com'));
		$results = $userInfoQuery->fetch(PDO::FETCH_ASSOC);		
		echo $results; //will return data in the form ["col1"=>rowdata,"col2"=>rowdata,..."colX"=>rowdata]
		}
	else{
		return false;
	}
}

/* USERTYPES:
0 - Anon
1 - User
2 - Mod
3 - Admin
*/

//if user isn"t logged in (session variables aren"t set), then 
function initAnonUID($ip,$geolocation){
	global $db_user, $db_password, $db_host, $db_name;

	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);//
    $existingAnonQuery  = $dbconn->prepare("Select UID from Users where usertype = 0 and sessionip = :ip");
	$existingAnonQuery->execute(array(":ip"=> $ip));
	$results = $existingAnonQuery->fetch(PDO::FETCH_ASSOC);
	
	$usertype = 0;
	//check users table for ip with usertype 0
	//if ip is in table, return uid
	//else, insert a new user with autogenerated userid and usertype 0

	if (!$results) {
		$allUIDsQuery  = $dbconn->query("Select UID from Users");
		$results = $allUIDsQuery->fetchAll(PDO::FETCH_ASSOC);

		$UID = mt_rand();

		while (in_array($UID,$results)){
			$UID = mt_rand();
			}	
	}
	else{
		$UID = $results['UID'];
	}
    $insertUserStatement  = $dbconn->prepare("insert into users (sessionip,sessiongeo,uid,usertype) values (:ip,:sessiongeo,:uid,:usertype)");
	$insertUserStatement->execute(array(":ip"=> $ip,":sessiongeo"=>$geolocation,":uid"=>$UID,":usertype"=>$usertype));	

return $UID;
}
	
//== Signup: ==
function signup($UID,$IP,$sessionGeo,$userType,$username,$email,$password){
    //variables needed - $username,$email,$password,$IP,$sessionGeo - username can be a blank string
    //The following query returns one or zero rows
	global $db_user, $db_password, $db_host, $db_name;

	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);
    $existingUserQuery  = $dbconn->prepare("Select email from RegisteredUsers where email = :email");
	$existingUserQuery->execute(array(":email"=> $email));
	$results = $existingUserQuery->fetch(PDO::FETCH_ASSOC);

    if ($results) {
		//already a user
        return false;
	}
    else{
		//add as a user
        $insertUserQuery = $dbconn->query("INSERT INTO registeredUsers (uid,email,userName,userRank) values ('$UID', '$email', '$username', 0)");
	}
}
/*
//== View Local Questions: ==
function viewLocalQuestions($UID){
    global $db_user, $db_password, $db_host, $db_name;

	//variables needed - $UID
	connectToDB($db_user, $db_password, $db_host, $db_name);
	$viewLocalQuery = $dbconn->prepare("Select * from (Questions Q inner join Users U on U.UID = Q.UID) where (UID = :UID and location like :sessionGeo)"); //a little more logic needed here in the like clause
	$viewLocalQuery->execute(array(":UID"=> $UID,":sessionGeo"=>$sessionGeo));
	$results = $selectQuery->fetchAll(PDO::FETCH_ASSOC);
}

//== View Question Answers: ==
function viewAnswersToQuestion($QID){
 	global $db_user, $db_password, $db_host, $db_name;

	connectToDB($db_user, $db_password, $db_host, $db_name);
	$viewLocalQuery = $dbconn->prepare("Select * from Questions where QID = :QID");
	$viewLocalQuery->execute(array(":QID"=> $QID));
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
*/
?>