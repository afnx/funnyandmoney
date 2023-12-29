<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/userSocials.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/Library/facebook/facebook.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/localization.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/Library/twitteroauth/autoload.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/Library/vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
use Facebook\FacebookRequest;
if(!isset($_SESSION['language'])){
	$_SESSION['language']='tr';
}
$loc = new localization ($_SESSION['language']);

class twitter {
	public $title;
	public $description;
	public $image;
	public $userId;
	public $postId;
	function __construct($userId, $postId) {
		$this->userId = $userId;
		$this->postId = $postId;
	}
	function __set($property, $value) {
		$this->$property = $value;
	}
	function __get($property) {
		if (isset ( $this->$property )) {
			
			return $this->$property;
		}
	}
	function getPost() {
		if(!is_array($this->postId)){
		$post = new posts ( $this->postId );
		}$us = userSocials::getUserSocialFromID ( $this->userId, 2 );
		$twitter = new TwitterOAuth ( twitterApp::consumerKey, twitterApp::consumerSecret, $us->token, $us->secret );
		$pst = $twitter->get ( "statuses/show", array (
				"id" => !is_array($this->postId) ? $post->socialID : $this->postId[0]
		) );
		return $pst;
	}function getUser() {
		if(!is_array($this->postId)){
		$post = new posts ( $this->postId );
		}$us = userSocials::getUserSocialFromID ( $this->userId, 2 );
		$twitter = new TwitterOAuth ( twitterApp::consumerKey, twitterApp::consumerSecret, $us->token, $us->secret );
		$pst = $twitter->get ( "users/show", array (
				"id" => !is_array($this->postId) ? $post->socialID : $this->postId[0]
		) );
		return $pst;
	}
	function like() {
		$post = new posts ( $this->postId );
		$us = userSocials::getUserSocialFromID ( $this->userId, 2 );
		$twitter = new TwitterOAuth ( twitterApp::consumerKey, twitterApp::consumerSecret, $us->token, $us->secret );
		$pst = $twitter->post ( "favorites/create", array (
				'id' => $post->socialID 
		) );
		return $pst;
	}function retweet() {
		$post = new posts ( $this->postId );
		$us = userSocials::getUserSocialFromID ( $this->userId, 2 );
		$twitter = new TwitterOAuth ( twitterApp::consumerKey, twitterApp::consumerSecret, $us->token, $us->secret );
		$pst = $twitter->post ( "statuses/retweet", array (
				'id' => $post->socialID 
		) );
		return $pst;
	}function follow() {
	$post = new posts ( $this->postId );
		$us = userSocials::getUserSocialFromID ( $this->userId, 2 );
		$twitter = new TwitterOAuth ( twitterApp::consumerKey, twitterApp::consumerSecret, $us->token, $us->secret );
		$pst = $twitter->post ( "friendships/create", array (
				'id' => $post->socialID ,
				'follow' => 'true'
		) );
		return $pst;
	
	}
	function tweet($tweetMessage = NULL) {
		if (strlen ( $tweetMessage ) <= 140) {
			$post = new posts ( $this->postId );
			$us = userSocials::getUserSocialFromID ( $this->userId, 2 );
			$twitter = new TwitterOAuth ( twitterApp::consumerKey, twitterApp::consumerSecret, $us->token, $us->secret );
			$pst = $twitter->post ( "statuses/update", array (
					'status' => ($tweetMessage==NULL?$post->postUrl:$tweetMessage)
			) );
		} else {
			$pst = [ ];
		}
		return $pst;
	}function listOwnPosts() {
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/localization.php";
		$loc = new localization ($_SESSION['language']);
		$us = userSocials::getUserSocialFromID ( $this->userId, 2 );
		$twitter = new TwitterOAuth ( twitterApp::consumerKey, twitterApp::consumerSecret, $us->token, $us->secret );
		$content='';
		$pst = $twitter->get ( "statuses/user_timeline", array (
				'user_id ' => $us->platformUserID,
				'count' => 100,
				'include_rts' => false
		) );
		$cnt=0;
		foreach($pst as $item => $value){
			$cnt++;
			$content.= '<div class="media" style="height: 100px;">
						<a class="media-left">
							'.((isset($pst[$item]->entities->media[0]->media_url_https))?'<img alt="" style="max-height: 100px;" src="/BL/functions.php?function=showImage&url='.$pst[$item]->entities->media[0]->media_url_https.'"/>':'').'
						</a>
						<div class="media-body">
							<div class="media-content">
								<div class="pull-right">
									<div class="checkbox checkbox-control checkbox-inline checkbox-success" style="width: 45%;">
					
										<input class="sourceList" onclick="javascript: smartList(\''.$pst[$item]->id.'\',\'myPosts\');" type="checkbox" id="checkboxS'.$pst[$item]->id.'" name="listItem[]" value="'.$pst[$item]->id.'"> 
										<label for="checkboxS'.$pst[$item]->id.'"></label>
						
									</div>
								</div>
								<span class="date"></span>
									<p>'.preg_replace('/\s+/', ' ', trim($pst[$item]->text)).'</p>
								</div>
							</div>
					</div>';
		}if($cnt == 0){
			echo $loc->label("Post not found");
		}
			
		return $content;
	}
}
class zuckerberg {
	public $userId;
	public $fb;
	public $postId;
	public $socialId;
	public $pageName;
	function __construct($userId, $postId) {
		$this->userId = $userId;
		$this->postId = $postId;
		if(!is_null($this->postId)) {
			if(!is_array($this->postId)){
				$post = new posts ( $this->postId );
				$this->socialId = $post->socialID;
			/*
			$post = new posts ( $this->postId );
			$arr = explode ( "/", parse_url($post->postUrl, PHP_URL_PATH));
			$this->pageName= $arr[1];
			if($this->pageName == 'groups'){
				$this->pageName= $arr[2];
			}
			$this->socialId = end ( $arr );
		
			while(trim($this->socialId) == ''){
				$this->socialId = prev ( $arr );
			}*/}else{
				$this->socialId = $this->postId[0];
			}
		
		} else {
			$this->socialId = $socialId;
		}
		
		$this->fb = new Facebook ( array (
				'appId' => facebookApp::appId,
				'secret' => facebookApp::appSecret 
		) );
		$us = userSocials::getUserSocialFromID ( $this->userId, 1 );
		if($us->willExpireAt != NULL AND $us->willExpireAt < time()+30){
			$func = new functions ();
			$func->redirect ("/Controllers/facebook.php");
		}
	}
	function __set($property, $value) {
		$this->$property = $value;
	}
	function __get($property) {
		if (isset ( $this->$property )) {
			
			return $this->$property;
		}
	}
	function getPost() {
		$result = [ ];
		$us = userSocials::getUserSocialFromID ( $this->userId, 1 );
		$this->fb->setAccessToken ( $us->token );
		$fbuser = $this->fb->getUser ();
		if ($fbuser) {
			try {
				$url = $this->socialId.'?fields=message,attachments,permalink_url';
				$result = $this->fb->api ( $url, 'GET' );
				if ($result) {
					$output=array();
					$output['message']= $result['message'];
					$output['permalink']= $result['permalink_url'];
					if(isset($result['attachments']['data'][0]['media']['image']['src'])){
						$output['picture']=$result['attachments']['data'][0]['media']['image']['src'];
					}
						
				}
			} catch ( FacebookApiException $error ) {
				$output= array('error',$error->getMessage ());
			}
		}
		return $output;
	}function getPage() {
		$result = [ ];
		$us = userSocials::getUserSocialFromID ( $this->userId, 1 );
		$this->fb->setAccessToken ( $us->token );
		$fbuser = $this->fb->getUser ();
		if ($fbuser) {
			try {
				$url = "/" . $this->socialId . '?fields=access_token,name,link,picture.type(large)';  
				$result = $this->fb->api ( $url, 'GET' );
				if ($result) {
					$output=array();
					$output['message']= $result['name'];
					$output['permalink']= $result['link'];
					if(isset($result['picture']['data']['url'])){
						$output['picture']=$result['picture']['data']['url'];
					}
						
				}
			} catch ( FacebookApiException $error ) {
				$output= array('error',$error->getMessage ());
			}
		}
		return $output;
	}
	function getPageReq() {
		
		/* PHP SDK v5.0.0 */
		/* make the API call */
		$request = new FacebookRequest(
			$session,
			'GET',
			'/' . $this->socialId . '/picture'
		);
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
		return $graphObject;
		/* handle the result */
	
	}
	function listOwnPosts() {
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/localization.php";
		$loc = new localization ($_SESSION['language']);
		$result = [ ];
		$us = userSocials::getUserSocialFromID ( $this->userId, 1 );
		$this->fb->setAccessToken ( $us->token );
		$fbuser = $this->fb->getUser ();
		$content='';
		if ($fbuser) {
			try {
				$url = $fbuser . "/posts?fields=privacy,attachments,message&limit=100";
				$result = $this->fb->api ( $url, 'GET' );
				$others=0;
				for($i=0;$i<count($result['data'])-1;$i++){
					if($result['data'][$i]['privacy']['value'] == 'EVERYONE'){
					$content.= '<div class="media" style="height: 100px;">
						<a class="media-left">
							'.((isset($result['data'][$i]['attachments']['data'][0]['media']['image']['src']))?'<img alt="" src="'.$result['data'][$i]['attachments']['data'][0]['media']['image']['src'].'"/>':'').'
						</a>
						<div class="media-body">
							<div class="media-content">
								<div class="pull-right">
									<div class="checkbox checkbox-control checkbox-inline checkbox-success" style="width: 45%;">
					
										<input class="sourceList" onclick="javascript: smartList(\''.$result['data'][$i]['id'].'\',\'myPosts\');" type="checkbox" id="checkboxS '.$i.'" name="listItem[]" value="'.$result['data'][$i]['id'].'"> 
										<label for="checkboxS '.$i.'"></label>
						
									</div>
								</div>
								<span class="date"></span>
									<p>'.(isset($result['data'][$i]['message'])?preg_replace('/\s+/', ' ', trim($result['data'][$i]['message'])):'').'</p>
								</div>
							</div>
					</div>';
					}else{
						$others++;
					}
				}if($i == 0 OR $i == $others){
					echo $loc->label("Post not found");
				}
			} catch ( FacebookApiException $error ) {
				$content= array('error',$error->getMessage ());
			}
		}
		return $content;
	}function showMe(){
		$result = [ ];
		$us = userSocials::getUserSocialFromID ( $this->userId, 1 );
		$this->fb->setAccessToken ( $us->token );
		$fbuser = $this->fb->getUser ();
		if ($fbuser) {
			try {
				$profileResult = $this->fb->api($fbuser, array(
					'fields' => 'picture.type(normal)'
					));
				$content= '<div class="media" style="height: 100px;">
						<a class="media-left">
							<img style="max-height:100px;" alt="" src="'.$profileResult['picture']['data']['url'].'"/>
						</a>
						<div class="media-body">
							<div class="media-content">
								<div class="pull-right">
									<div class="checkbox checkbox-control checkbox-inline checkbox-success" style="width: 45%;">
					
										<input class="sourceList" onclick="javascript: smartList(\'me\',\'pagesAndMe\');" type="checkbox" id="color-checkbox-first" name="listItem[]" value="me"> 
										<label for="color-checkbox-first"></label>
						
									</div>
								</div>
								<span class="date"></span>
									<p>'.$us->screenName.'</p>
								</div>
							</div>
						</div>';
				} catch ( FacebookApiException $error ) {
				$content= array('error',$error->getMessage ());
			}
		}
		return $content;
	}function listOwnPages($progress='') {
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/localization.php";
		$loc = new localization ($_SESSION['language']);
		$result = [ ];
		$us = userSocials::getUserSocialFromID ( $this->userId, 1 );
		$this->fb->setAccessToken ( $us->token );
		$fbuser = $this->fb->getUser ();
		$content= '';
		$others= 0;
		if ($fbuser) {
			try {
				$url = $fbuser . "/accounts?limit=100&fields=is_published,name";
				$result = $this->fb->api ( $url, 'GET' );
				
				for($i=0;$i<count($result['data']);$i++){
					if($result['data'][$i]['is_published'] == true){
					$result2 = $this->fb->api($result['data'][$i]['id'], array(
					'fields' => 'picture.type(normal)'
					));
					$content.= '<div class="media" style="height: 100px;">
						<a class="media-left">
							<img style="max-height:100px;" alt="" src="'.$result2['picture']['data']['url'].'"/>
						</a>
						<div class="media-body">
							<div class="media-content">
								<div class="pull-right">
									<div class="checkbox checkbox-control checkbox-inline checkbox-success" style="width: 45%;">
					
										<input class="sourceList" onclick="javascript: smartList(\''.$result['data'][$i]['id'].'\',\''.$progress.'\');" type="checkbox" id="color-checkbox'.$i.'" name="listItem[]" value="'.$result['data'][$i]['id'].'"> 
										<label for="color-checkbox'.$i.'"></label>
						
									</div>
								</div>
								<span class="date"></span>
									<p>'.$result['data'][$i]['name'].'</p>
								</div>
							</div>
						</div>';
				}else{
					$others++;
				}
				}if(($i == 0 OR $i == $others) && $progress != 'pagesAndMe'){
					echo $loc->label("Page not found");  
				}
			} catch ( FacebookApiException $error ) {
				$content= array('error',$error->getMessage ());
			}
		}
		return $content;
	}function listPagePosts() {
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/localization.php";
		$loc = new localization ($_SESSION['language']);
		$result = [ ];
		$us = userSocials::getUserSocialFromID ( $this->userId, 1 );
		$this->fb->setAccessToken ( $us->token );
		$fbuser = $this->fb->getUser ();
		$content='';
		if ($fbuser) {
			try {
				$url = $this->postId[0]."/posts?limit=100&fields=picture,message";
				$result = $this->fb->api ( $url, 'GET' );
				
				for($i=0;$i<count($result['data'])-1;$i++){
					$content.= '<div class="media" style="height: 100px;">
						<a class="media-left">
							'.((isset($result['data'][$i]['picture']))?'<img style="max-height:100px;" alt="" src="'.$result['data'][$i]['picture'].'"/>':'').'
						</a>
						<div class="media-body">
							<div class="media-content">
								<div class="pull-right">
									<div class="checkbox checkbox-control checkbox-inline checkbox-success" style="width: 45%;">
					
										<input class="sourceList" onclick="javascript: smartList(\''.$result['data'][$i]['id'].'\',\'pagePosts\');" type="checkbox" id="checkboxS '.$i.'" name="listItem[]" value="'.$result['data'][$i]['id'].'"> 
										<label for="checkboxS '.$i.'"></label>
						
									</div>
								</div>
								<span class="date"></span>
									<p>'.(isset($result['data'][$i]['message'])?preg_replace('/\s+/', ' ', trim($result['data'][$i]['message'])):'').'</p>
								</div>
							</div>
					</div>';
				}if($i == 0){
					echo $loc->label("Post not found");
				}
			} catch ( FacebookApiException $error ) {
				$content= array('error',$error->getMessage ());
			}
		}
		return $content;
	}
	function like() {
		//POST -> pageid_postid/likes
		$result = [ ];
		$post = new posts ( $this->postId );
		$us = userSocials::getUserSocialFromID ( $this->userId, 1 );
		$this->fb->setAccessToken ( $us->token );
		$fbuser = $this->fb->getUser ();
		if ($fbuser) {
			try {
				/*$result = $this->fb->api ( $this->pageName, 'GET' );
				if ($result) {
					if ($result ["id"] != "") {*/
						$url = $this->socialId."/likes";
						$result = $this->fb->api ( $url, 'POST' );
					//}
				//}
			} catch ( FacebookApiException $error ) {
				$result= array($error->getMessage ());
			}
		}
		return $result;
	}
	function share() {
		$result = [ ];
		$post = new posts ( $this->postId );
		$us = userSocials::getUserSocialFromID ( $this->userId, 1 );
		$this->fb->setAccessToken ( $us->token );
		$fbuser = $this->fb->getUser ();
		if ($fbuser) {
			try {
				$url = $fbuser . "/feed";
				$result = $this->fb->api ( $url, 'POST', array (
						"link" => $post->postUrl 
				) );
				
				if ($result) {
					
				}
			} catch ( FacebookApiException $error ) {
				$result= array($error->getMessage ());
			}
		}
		return $result;
	}
}
class instagram {
	function __construct($postURL) {
	}
	function __set($property, $value) {
		$this->$property = $value;
	}
	function __get($property) {
		if (isset ( $this->$property )) {
			
			return $this->$property;
		}
	}
}
class youTube {
	public $userId;
	public $fb;
	public $postId;
	public $socialId;
	public $channel; 
	public $connected;
	function __construct($userId,$postId) {
		$this->postId = $postId;
		$this->userId=$userId;
		$us = userSocials::getUserSocialFromID ( $this->userId, 4 );
		if($us->ID < 1){
			$this->connected=false;
		}else{
			$this->connected=true;
		}
		if($this->connected AND $us->willExpireAt < time()+30 AND !empty($us->refreshToken)){
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

			$result= $client->refreshToken($us->refreshToken);
			$us->token= $result['access_token'];
			$us->willExpireAt= time()+3600-30;
			$us->save();
		}
		if(is_array($this->postId)){
			$this->socialId= $this->postId;
		}
	}
	function __set($property, $value) {
		$this->$property = $value;
	}
	function __get($property) {
		if (isset ( $this->$property )) {
			
			return $this->$property;
		}
	}
	function getPost() {
		$result = [];
		$us = userSocials::getUserSocialFromID ( $this->userId, 4 );
		$client = new Google_Client ();
		$client->setClientId ( google::clientID );
		$client->setClientSecret ( google::clientSecret );
		$client->setScopes ( 'https://www.googleapis.com/auth/youtube' );
		if($this->connected){
			$client->setAccessToken ( $us->token );
		}else{
			$client->setDeveloperKey(google::apikey);
		}
		$youtube = new Google_Service_YouTube ( $client );
		if ($client) {
			$result = $youtube->videos->listVideos ( "snippet,statistics,contentDetails", array (
					"id" => $this->socialId[0]
			) );
		}
		return $result;
	}function getPage($chanOrUser) {
		$result = [];
		$us = userSocials::getUserSocialFromID ( $this->userId, 4 );
		$client = new Google_Client ();
		$client->setClientId ( google::clientID );
		$client->setClientSecret ( google::clientSecret );
		$client->setScopes ( 'https://www.googleapis.com/auth/youtube' );
		if($this->connected){
			$client->setAccessToken ( $us->token );
		}else{
			$client->setDeveloperKey(google::apikey);
		}
		$youtube = new Google_Service_YouTube ( $client );
		if ($client) {
			$result = $youtube->channels->listChannels ( "snippet", array (
					$chanOrUser==0?"id":"forUsername" => $this->socialId[0]
			) );
		}
		return $result;
	}
	function like() {
		$result = [ ];
		$post = new posts ( $this->postId );
		$us = userSocials::getUserSocialFromID ( $this->userId, 4 );
		$client = new Google_Client ();
		$client->setClientId ( google::clientID );
		$client->setClientSecret ( google::clientSecret );
		$client->setScopes ( 'https://www.googleapis.com/auth/youtube' );
		try {
			$client->setAccessToken ( $us->token );
			$youtube = new Google_Service_YouTube ( $client );
			if ($client) {
				$youtube->videos->rate ( $post->socialID, "like" );
				$result = 1;
			}
		} catch (Exception $ex) {
			$result = 0;
		}
		return $result;
	}function subscribe() {
		$result = [ ];
		$post = new posts ( $this->postId );
		$parts = parse_url($post->postUrl);
		$arr = explode ( "/", $parts['path']);
		$this->channel= $arr[2];
		
		$us = userSocials::getUserSocialFromID ( $this->userId, 4 );
		$client = new Google_Client ();
		$client->setClientId ( google::clientID );
		$client->setClientSecret ( google::clientSecret );
		$client->setScopes ( 'https://www.googleapis.com/auth/youtube' );
		try {
			$client->setAccessToken ( $us->token );
			$youtube = new Google_Service_YouTube ( $client );
			if ($client) {
				$resourceId = new Google_Service_YouTube_ResourceId();
				$resourceId->setChannelId($this->channel);
				$resourceId->setKind('youtube#channel');
				$subscriptionSnippet = new Google_Service_YouTube_SubscriptionSnippet();
				$subscriptionSnippet->setResourceId($resourceId);
				$subscription = new Google_Service_YouTube_Subscription();
				$subscription->setSnippet($subscriptionSnippet);
				$youtube->subscriptions->insert('id,snippet',
				$subscription, array());
				
				$result = 1;
			}
		} catch (Google_Service_Exception $e) {
			//$result= sprintf('<p>A service error occurred: <code>%s</code></p>',
			//htmlspecialchars($e->getMessage()));
			$result=0;
		} catch (Google_Exception $e) {
			//$result= sprintf('<p>An client error occurred: <code>%s</code></p>',
			//htmlspecialchars($e->getMessage()));
			$result=0;
		}
		return $result; 
}
}if(isset($_GET['run'])){
	$userID=$_SESSION ["userID"];
	$fbError= '<a href="../Controllers/facebook.php" target="_blank"><button type="button" class="btn btn-primary btn-block" style="width: 70%; margin: 0 auto;">'.$loc->label("Add a Facebook account").'</button></a>';
	$twError= '<a href="../Controllers/twitter.php" target="_blank"><button type="button" class="btn btn-primary btn-block" style="width: 70%; margin: 0 auto;">'.$loc->label("Add a Twitter account").'</button></a>';
	if($_GET['run'] == 'pagesAndMe'){
		$us = userSocials::getUserSocialFromID ( $userID, 1 );
		if($us->ID <1){
			echo $fbError;
		}else{
		$facebook= new zuckerberg($userID,array(null));
		echo $facebook->showMe();
		echo $facebook->listOwnPages('pagesAndMe');
		}
	}elseif($_GET['run'] == 'posts'){
		$us = userSocials::getUserSocialFromID ( $userID, 1 );
		if($us->ID <1){
			echo $fbError;
		}else{
		if($_POST['sourceid'] == 'me'){
			$facebook= new zuckerberg($userID,array(null));
		echo $facebook->listOwnPosts(); 
		}else{
			$facebook= new zuckerberg($userID,array($_POST['sourceid']));
		echo $facebook->listPagePosts(); 
		}
		}
	}elseif($_GET['run'] == 'pages'){
		$us = userSocials::getUserSocialFromID ( $userID, 1 );
		if($us->ID <1){
			echo $fbError;
		}else{
		$facebook= new zuckerberg($userID,array(null));
		echo	$facebook->listOwnPages('pages');
		}
	}elseif($_GET['run'] == 'tweets'){
		$us = userSocials::getUserSocialFromID ( $userID, 2 );
		if($us->ID <1){
			echo $twError;
		}else{
		$twitter= new twitter($userID,array(null));
		echo $twitter->listOwnPosts();
		}		
	}
}
	?>