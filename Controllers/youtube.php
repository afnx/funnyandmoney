<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
error_reporting(E_ALL);

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/Library/vendor/autoload.php";
require_once dirname ( dirname(__FILE__))."/BL/Tables/userSocials.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$OAUTH2_CLIENT_ID = google::clientID;
$OAUTH2_CLIENT_SECRET = google::clientSecret;
$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$client->setAccessType("offline");
$client->setScopes('https://www.googleapis.com/auth/youtube');
$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
		FILTER_SANITIZE_URL);
$client->setRedirectUri($redirect);

$platformID=4;
$youtube = new Google_Service_YouTube($client);

if (isset($_GET['code'])) {
	if (strval($_SESSION['state']) !== strval($_GET['state'])) {
		die('The session state did not match.');
	}
	$client->authenticate($_GET['code']);
	$_SESSION['tokenYoutube'] = $client->getAccessToken();
	header('Location: ' . $redirect);
}

if (isset($_SESSION['tokenYoutube'])) {
	$client->setAccessToken($_SESSION['tokenYoutube']);
}

// Check to ensure that the access token was successfully acquired.
$htmlBody="";
if ($client->getAccessToken()) {
	try {
		$runsql = new \data\DALProsess ();
		$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : 0;
		$yt = $youtube->channels->listChannels("id,snippet,statistics",array("mine"=>true));
        $us = userSocials::getUserSocialFromID($userID, $platformID);
        $us->platformID=$platformID;
		$us->platformUserID=$yt['items'][0]['id'];
		$us->screenName=$yt['items'][0]['snippet']['title'];
        $us->token=$client->getAccessToken()["access_token"];
		$sql = "SELECT refreshToken FROM userSocials WHERE userID=$userID AND platformID=$platformID AND refreshToken IS NOT NULL ORDER BY ID DESC LIMIT 1";
		$oldRefreshToken= mysqli_fetch_assoc($runsql->executenonquery ( $sql, NULL, false ));
		$us->refreshToken=($client->getRefreshToken() != NULL?$client->getRefreshToken(): $oldRefreshToken['refreshToken']);
		$us->willExpireAt=time()+3600-30;
        $us->userID=$userID;
        $us->followerCount=$yt['items'][0]['statistics']['subscriberCount'];
        $us->friendsCount=0;
		$func = new functions();
		
		$sql = "SELECT 1 FROM userSocials WHERE userID!='".$_SESSION ["userID"]."' AND platformID='".$platformID."' AND platformUserID='".$us->platformUserID ."'";
		$runsql->executenonquery ( $sql, NULL, false );
		if ($runsql->recordCount > 0) {
			$func->redirect ( "/settings?tab=social&error=true" );
		}else{
			$us->save ();
			$func->redirect ( "/settings?tab=social" );
		}
        
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
	$_SESSION['tokenYoutube'] = $client->getAccessToken();
} else {
	$state = mt_rand();
	$client->setState($state);
	$_SESSION['state'] = $state;
	$authUrl = $client->createAuthUrl("https://www.googleapis.com/auth/youtube");
	header('Location: ' . $authUrl);
}

?>
