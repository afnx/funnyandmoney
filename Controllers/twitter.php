<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start ();
header ( 'Content-Type:text/html; charset=utf8' );
require_once dirname ( dirname ( __FILE__ ) ) . "/Library/twitteroauth/autoload.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname(__FILE__))."/BL/Tables/userSocials.php";

$func = new functions ();
use Abraham\TwitterOAuth\TwitterOAuth;
$connection = new TwitterOAuth(twitterApp::consumerKey, twitterApp::consumerSecret);
$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => twitterApp::redirectURI));
$oauth_token=$request_token['oauth_token'];
$token_secret=$request_token['oauth_token_secret'];
setcookie("token_secret", " ", time()-3600);
setcookie("token_secret", $token_secret, time()+60*10);
setcookie("oauth_token", " ", time()-3600);
setcookie("oauth_token", $oauth_token, time()+60*10);
setcookie("oauth_userID", " ", time()-3600);
setcookie("oauth_userID", $_SESSION["userID"], time()+60*10);

$url = $connection->url("oauth/authorize", array("oauth_token" => $oauth_token));
$func->redirect ( $url );
?>
