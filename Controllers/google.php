<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/Library/vendor/autoload.php";
require_once dirname ( dirname(__FILE__))."/BL/Tables/userSocials.php";

session_start();
$OAUTH2_CLIENT_ID = google::clientID;
$OAUTH2_CLIENT_SECRET = google::clientSecret;
$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
		FILTER_SANITIZE_URL);
$client->setRedirectUri($redirect);

$platformID=4;

$youtube = new Google_Service_YouTube($client);
$g = new Google_Service_People($client);
if (isset($_GET['code'])) {
	if (strval($_SESSION['state']) !== strval($_GET['state'])) {
		die('The session state did not match.');
	}
	$client->authenticate($_GET['code']);
	$_SESSION['token'] = $client->getAccessToken();
	header('Location: ' . $redirect);
}

if (isset($_SESSION['token'])) {
	$client->setAccessToken($_SESSION['token']);
}

// Check to ensure that the access token was successfully acquired.
$htmlBody="";
if ($client->getAccessToken()) {
	try {
		$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : 0;
		$people = $g->people->get("people/me");
        $us = userSocials::getUserSocialFromID($userID, $platformID);
        
        $us->userID=$userID;
        $us->platformID=$platformID;
        $us->platformUserID=$people["modelData"]["names"][0]["metadata"]["source"]["id"];
        $us->screenName=$people["modelData"]["names"][0]["displayName"];
        $us->location="";
        $us->lang=$people["modelData"]["locales"][0]["value"];
        $us->save();
        
        $func = new functions();
        $func->redirect("youtube.php");
		//$listResponse = $youtube->channels->listChannels('brandingSettings', array('mine' => true));
		//echo var_dump($listResponse);
	} catch (Google_Service_Exception $e) {
		$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
		echo $htmlBody;
	} catch (Google_Exception $e) {
		$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
		echo $htmlBody;
	}
	$_SESSION['token'] = $client->getAccessToken();
} else {
	$state = mt_rand();
	$client->setState($state);
	$_SESSION['state'] = $state;
	$authUrl = $client->createAuthUrl("profile");
	header('Location: ' . $authUrl);
}

?>
