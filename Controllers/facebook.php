<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start ();


header ( 'Content-Type:text/html; charset=utf8' );
require_once dirname ( dirname ( __FILE__ ) ) . "/Library/facebook/facebook.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/userSocials.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
$func = new functions ();

$fb = new Facebook ( array (
		'appId' => facebookApp::appId,
		'secret' => facebookApp::appSecret 
) );

$fbuser = $fb->getUser ();
if ($fbuser) {
	try {
		$platformID = 1;
		$person = $fb->api ( "/me?fields=name,id,gender,location,birthday,email,locale,education", 'GET' );
		$friends = $fb->api ( "/me/friends", 'GET' );
		/*
		$user = users::getUserFromFacebook ( $fbuser );
		if ($user->ID == 0) {
			// create User
			$userCreated = 1;
			$user->email = $person ["email"];
			$user->fullName = $person ["name"];
			$user->fbUserID = $fbuser;
			$user->gender = $person ["gender"];
			$user->location = $person ["location"] ["name"];
			$user->country = $person ["locale"];
			$user->birthDate = $person ["birthday"];
			$user->password = microtime ();
			$userID = $user->save ();
		} else {
			$userID=$user->ID;
			
		}
		$_SESSION ["userID"] = $userID;
		$_SESSION ["fullName"] = $user->fullName;
		*/
		// create user social
		$us = userSocials::getUserSocialFromID ( $_SESSION ["userID"], $platformID );
		$us->userID = $_SESSION ["userID"];
		$us->platformID = $platformID;
		if(!empty($fb->getAccessToken ())){
		$ch = curl_init("https://graph.facebook.com/oauth/access_token?client_id=".facebookApp::appId."&client_secret=".facebookApp::appSecret."&grant_type=fb_exchange_token&fb_exchange_token=".$fb->getAccessToken ());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$array= explode ("&", $response);
		if(count($array)==1){
			$array1= explode ("=", $array[0]);
			$us->token = $array1[1];
		}else{
			$array1= explode ("=", $array[0]);
			$array2= explode ("=", $array[1]);
			$us->token = $array1[1];
			$us->willExpireAt= time()+ $array2[1];
		}
		}else{
			$us->token =$fb->getAccessToken ();
		}

		$us->platformUserID = $fbuser;
		$us->screenName = $person ["name"];
		$us->followerCount = 0;
		$us->friendsCount = $friends ["summary"] ["total_count"];
		$us->location = $person ["locale"];
		$us->lang = "";
		
		$runsql = new \data\DALProsess ();
		$sql = "SELECT 1 FROM userSocials WHERE userID!='".$_SESSION ["userID"]."' AND platformID='".$platformID."' AND platformUserID='".$us->platformUserID ."'";
		$runsql->executenonquery ( $sql, NULL, false );
		if ($runsql->recordCount > 0) {
			$func->redirect ( "/settings?tab=social&error=true" );
		}else{
			$us->save ();
			$func->redirect ( "/settings?tab=social" );
		}
		
		
	} catch ( FacebookApiException $error ) {
		echo $error->getMessage ();
	}
} else {
	$loginUrl = $fb->getLoginUrl ( array (
			'scope' => 'email,user_likes,user_posts,user_friends,user_birthday,publish_actions,manage_pages' 
	) );
	$func->redirect ( $loginUrl );
}
?>
