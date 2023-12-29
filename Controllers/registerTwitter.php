<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start();


require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
$func = new functions ();
require_once dirname( dirname (__FILE__))."/Library/twitteroauth/autoload.php";
require_once dirname ( dirname(__FILE__))."/BL/Tables/userSocials.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$oauth_verifier = $_GET['oauth_verifier'];
$token_secret = $_COOKIE['token_secret'];
$oauth_token = $_COOKIE['oauth_token'];
$userID = $_COOKIE['oauth_userID'];
$platformID = 2;

$connection = new TwitterOAuth(twitterApp::consumerKey, twitterApp::consumerSecret, $oauth_token, $token_secret);
$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $oauth_verifier));
//echo var_dump($access_token);
$twitter = new TwitterOAuth(twitterApp::consumerKey, twitterApp::consumerSecret, $access_token["oauth_token"], $access_token["oauth_token_secret"]);
$userInfo = $twitter->get("account/verify_credentials");
$ud=$userInfo;

$us = userSocials::getUserSocialFromID($userID, $platformID);
$us->userID=$userID;
$us->platformID=$platformID;
$us->token=$access_token["oauth_token"];
$us->secret=$access_token["oauth_token_secret"];
$us->platformUserID=$access_token["user_id"];
$us->screenName=$access_token["screen_name"];
$us->followerCount=$ud->followers_count;
$us->friendsCount=$ud->friends_count;
$us->location=$ud->location;
$us->lang=$ud->lang;


$runsql = new \data\DALProsess ();
$sql = "SELECT 1 FROM userSocials WHERE userID!='".$_SESSION ["userID"]."' AND platformID='".$platformID."' AND platformUserID='".$us->platformUserID ."'";
$runsql->executenonquery ( $sql, NULL, false );
if ($runsql->recordCount > 0) {
	$func->redirect ( "/settings?tab=social&error=true" );
}else{
	$us->save ();
	$func->redirect ( "/settings?tab=social" );
}


?>


