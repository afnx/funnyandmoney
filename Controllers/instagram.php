<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start();


header('Content-Type:text/html; charset=utf8');
require_once dirname(dirname(__FILE__))."/Library/Instagram/src/Instagram.php";
require_once dirname(dirname(__FILE__))."/BL/functions.php";
use MetzWeb\Instagram\Instagram;
use MetzWeb\Instagram\InstagramException;

$func = new functions();
$instagram = new Instagram(array(
		'apiKey' => instagramApp::clientId,
		'apiSecret' => instagramApp::clientSecret,
		'apiCallback' => instagramApp::redirectURI // must point to success.php
));
$loginUrl = $instagram->getLoginUrl();
$func->redirect($loginUrl);
?>
