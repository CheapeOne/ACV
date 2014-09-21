<?php

if (!function_exists('connectToDB')) {
include "basicDB.php";
}

$db_host = "localhost";
$db_user = "root";
$db_password = "yuhclickyuh";
$db_name = "acvdatabase";

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
		$userInfoQuery->execute(array(":email"=> $email));
		$results = $userInfoQuery->fetch(PDO::FETCH_ASSOC);		
		//print_r($results);
		return json_encode($results); //will return data in the form ["col1"=>rowdata,"col2"=>rowdata,..."colX"=>rowdata]
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

//if user isn't logged in (session variables aren't set), then 
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
		//generate new user id
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
	$affected_rows = $insertUserStatement->rowCount();
return $UID;
}
	
//== Signup: ==
function signup($ip,$sessionGeo,$userType,$username,$email,$password){
	global $db_user, $db_password, $db_host, $db_name;

	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);

	//generate new user id
	$allUIDsQuery  = $dbconn->query("Select UID from Users");
	$results = $allUIDsQuery->fetchAll(PDO::FETCH_ASSOC);
	$UID = mt_rand();
	while (in_array($UID,$results)){
		$UID = mt_rand();
		}	


    $existingUserQuery  = $dbconn->prepare("Select email from RegisteredUsers where email = :email");
	$existingUserQuery->execute(array(":email"=> $email));
	$results = $existingUserQuery->fetch(PDO::FETCH_ASSOC);

    if ($results) {
		//already a user
        return false;
	}
    else{
		//add as a user
		$insertUserStatement  = $dbconn->prepare("insert into users (sessionip,sessiongeo,UID,usertype) values (:ip,:sessiongeo,:UID,:usertype)");
		$insertUserStatement->execute(array(":ip"=> $ip,":sessiongeo"=>$sessionGeo,":UID"=>$UID,":usertype"=>$userType));
        //add as a REGISTERED user
        $insertRegUserStatement = $dbconn->prepare("INSERT INTO registeredUsers (UID,email,userName,userRank,password) values (:UID,:email,:userName,'0',:password)");
		$insertRegUserStatement->execute(array(":UID"=>$UID,":email"=>$email,":userName"=>$username, ":password"=> hash("sha256", $password)));	
	
	}
}

//== View Local Questions: ==
function viewLocalQuestions($UID){
    global $db_user, $db_password, $db_host, $db_name;

	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);	
	$viewLocalQuery = $dbconn->prepare("Select * from (Questions Q inner join Users U on U.UID = Q.UID) where (UID = :UID and location like sessionGeo)"); //a little more logic needed here in the like clause
	$viewLocalQuery->execute(array(":UID"=> $UID,":sessionGeo"=>$sessionGeo));
	$results = $viewLocalQuery->fetchAll(PDO::FETCH_ASSOC);
	return $results
}

//== View Question Answers: ==
function viewAnswersToQuestion($QID){
 	global $db_user, $db_password, $db_host, $db_name;

	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);	
	$viewQAQuery = $dbconn->prepare("Select * from Questions where QID = :QID");
	$viewQAAnswersQuery->execute(array(":QID"=> $QID));
	$results = $viewAnswersQuery->fetchAll(PDO::FETCH_ASSOC);
	return $results
}
//== View Your Answers: ==
function viewUserAnswers($UID){
	global $db_user, $db_password, $db_host, $db_name;

	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);	
	$viewAnswersQuery = $dbconn->prepare("Select * from Answers where UID = :UID");
	$viewAnswersQuery->execute(array(":UID"=> $UID));
	$results = $viewAnswersQuery->fetchAll(PDO::FETCH_ASSOC);
	return $results
}
/*
//== View self profile: ==
function viewProfile($UID){
	global $db_user, $db_password, $db_host, $db_name;

    //variables needed- $UID
    'Select * from RegisteredUsers where UID = $UID'

//== View other profile: ==
    //variables needed - $otherUID
	global $db_user, $db_password, $db_host, $db_name;

    'Select * from RegisteredUsers where UID = $otherUID'
*/
//== Set/Change Username: ==
function changeUsername($new_username){ 
 //LITERALLY SO EASY
    //variables needed - $NEWusername
    global $db_user, $db_password, $db_host, $db_name;

	$updateStatement  = $dbconn->prepare('Update RegisteredUsers set username = :new_username');
	$updateStatement->execute((array(":new_username"=>$new_username));
	$affected_rows = $updateStatement->rowCount();
	if ($affected_rows == 0){
		return false
	}
	return $affected_rows
}
//== Add Question: == 
function addQuestion($title,$content,$category,$_timestamp){
    global $db_user, $db_password, $db_host, $db_name;
	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);	

	$allQIDsQuery  = $dbconn->query("Select UID from Users");
	$results = $allUIDsQuery->fetchAll(PDO::FETCH_ASSOC);
	$QID = mt_rand();
	while (in_array($QID,$results)){
		$QID = mt_rand();
		}	
		
	$insertQuestionStatement  = $dbconn->prepare("insert into questions (qid,title,content,category,_timestamp,numRating) values ($QID,$content,$category,$_timestamp,'0')");
	$insertQuestionStatement->execute(array(":QID"=>$QID,":title"=>$title,":content"=>$content,":category"=>$category,":_timestamp"=>$_timestamp));	
	$affected_rows = $insertQuestionStatement->rowCount();
	if ($affected_rows == 0){
		return false
	}
	return $affected_rows
}
//== Add Answer: ==
function addAnswer($QID,$title,$content,$category,$_timestamp){
    global $db_user, $db_password, $db_host, $db_name;
	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);	

	$answerCountQuery = $dbconn->prepare("Select MAX(AID) as Max from Answers where QID = :QID");
	$answerCountQuery->execute(array(":QID"=> $QID));
	$results = $answerCountQuery->fetch(PDO::FETCH_ASSOC);
	
	if ($results){
		$AID = $results['Max']+1;
	}
	else{
		AID = 0;
	}	
	
	if ($affected_rows == 0){
		return false
	}
	$insertAnswerStatement  = $dbconn->prepare("insert into answers (aid,qid,content,_timestamp,numRating) values ($QID,$content,$category,$_timestamp,'0')");
	$insertAnswerStatement->execute((array(":AID"=>$AID,":QID"=>$QID,":content"=>$content,":category"=>$category,":_timestamp"=>$_timestamp));
	$affected_rows = $insertAnswerStatement->rowCount();
	if ($affected_rows == 0){
		return false
	}
	return $affected_rows
}
/*//== Rate Answers: ==
function rateAnswer(){
    global $db_user, $db_password, $db_host, $db_name;
	$dbconn = connectToDB($db_user, $db_password, $db_host, $db_name);	

    //variables needed - $UID 
    Select AID from Rated where UID = $UID
}
*/
?>