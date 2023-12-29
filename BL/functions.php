<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
require_once (dirname ( dirname ( __FILE__ ) )) . "/DL/DAL.php";
use data\TableItem;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class functions {

	function redirect($url, $permanent = false) {

		if ($permanent) {
			header ( 'HTTP/1.1 301 Moved Permanently' );
		}
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . $url . '">';
		exit ();
	
	}

	function magicDate($date) {

		return date ( "d F Y", strtotime ( $date ) ) . " at " . date ( "H:i", strtotime ( $date ) );
	
	}

	function phoneFormat($phone) {

		return sprintf ( "(%s) %s-%s", substr ( $phone, 0, 3 ), substr ( $phone, 3, 3 ), substr ( $phone, 6, 4 ) );
	
	}

	function dately($date) {

		if (empty ( $date )) {
			return "No date provided";
		}
		$periods = array (
				"second",
				"minute",
				"hour",
				"day",
				"week",
				"month",
				"year",
				"decade" 
		);
		$lengths = array (
				"60",
				"60",
				"24",
				"7",
				"4.35",
				"12",
				"10" 
		);
		$now = time ();
		$unix_date = strtotime ( $date );
		// check validity of date
		if (empty ( $unix_date )) {
			return "Bad date";
		}
		// is it future date or past date
		if ($now > $unix_date) {
			$difference = $now - $unix_date;
			$tense = "ago";
		} else {
			$difference = $unix_date - $now;
			$tense = "from now";
		}
		for($j = 0; $difference >= $lengths [$j] && $j < count ( $lengths ) - 1; $j ++) {
			$difference /= $lengths [$j];
		}
		$difference = round ( $difference );
		if ($difference != 1) {
			$periods [$j] .= "s";
		}
		return "$difference $periods[$j] {$tense}";
	
	}
	
	function continput($data) {
			
			$data = trim($data);
			
			$data = stripslashes($data);
			
			$data = htmlspecialchars($data);
			
			return $data;
	}

	function calcNowCount($postID, $actionID) {

		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";
		$balance = new balance ();
		$resultB = $balance->getNowBalance ( $postID );
		$nowCount = 0;
		while ( $row = mysqli_fetch_array ( $resultB ) ) {
			if ($row ["actionID"] == $actionID) {
				$nowCount ++;
			}
		}
		return $nowCount;
	
	}

	function calcPlatform($postID, $actionID) {

		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
		$post = new posts ( $postID );
		switch ($actionID) {
			case 1 :
				$finishCount = $post->likeCount;
				$nowCount = $post->nowLike;
				break;
			case 2 :
				$finishCount = $post->shareCount;
				$nowCount = $post->nowShare;
				break;
			case 3 :
				$finishCount = $post->followCount;
				$nowCount = $post->nowFollow;
				break;
			case 4 :
				$finishCount = $post->viewCount;
				$nowCount = $post->nowView;
				break;
		}
		
		if ($nowCount != 0) {
			$result = number_format((($nowCount / $finishCount) * 100), 2, '.', '');  
		} else {
			$result = 0;
		}
		return $result;
	
	}

	function calcAddPost($position=65, $like = 0, $follow = 0, $share = 0, $shareFollower = 0, $view = 0, $refunc=0, $platform) {
		
		require_once "Tables/cost.php";
		require_once "Tables/definitions.php";
		require_once "Tables/positions.php";
		
		$cost = new cost ();
		
		$costLikeRow = mysqli_fetch_array ( $cost->getPointWithPlatform("Like",$platform) );
		$costLike = (($costLikeRow["point"] > 0) ? $costLikeRow["point"] : 0);
		
		$costFollowRow = mysqli_fetch_array ( $cost->getPointWithPlatform("Follow",$platform) );
		$costFollow = (($costFollowRow["point"] > 0) ? $costFollowRow["point"] : 0);
		
		$costShareRow = mysqli_fetch_array ( $cost->getPointWithShare($shareFollower) );  
		$costShare = (($costShareRow["point"] > 0) ? $costShareRow["point"] : 0);
		
		$costSubscribeRow = mysqli_fetch_array ( $cost->getPointWithPlatform("Subscribe",$platform) );
		$costSubscribe = (($costSubscribeRow["point"] > 0) ? $costSubscribeRow["point"] : 0);
		
		$costViewRow = mysqli_fetch_array ( $cost->getPointWithPlatform("View",$platform) );
		$costView = (($costViewRow["point"] > 0) ? $costViewRow["point"] : 0);
		
		$likePrice = $like * $costLike;
		$sharePrice = $share * $costShare;
		$followPrice = $follow * $costFollow;
		$subscribePrice = $follow * $costSubscribe;
		$viewPrice = $view * $costView;
		
		
		$total = $likePrice + $sharePrice + $followPrice + $subscribePrice + $viewPrice;
		
		switch ($position) {
			
			case 62 :
				$positionID = 4;
				break;
				
			case 63 :
				$positionID = 3;
				break;
				
			case 64 :
				$positionID = 2;
				break;
				
			case 65 :
				$positionID = 1;
				break;
				
			default:
				$positionID = 1;
		}
		
		$positionD = new positions ( $positionID );
		
		$result = $total + $positionD->point;
		
		if($refunc == 1) {
			
			return $result;
			
		} else {
			
			echo $result;
			
		}
		
		
	
	}
	
	function uploadDoc ($document, $folder, $rand, $addFormat=0,$prefix, $size=500000) {  
		
		$loc = new localization($_SESSION['language']);
		
		$target_dir = $folder;
		$imageFileType = pathinfo($document["name"],PATHINFO_EXTENSION);
		$target_file = $target_dir . $prefix . $rand . "." . $imageFileType;
		$uploadOk = 1;
		$showError = "";
		// Check if image file is a actual image or fake image
		if(isset($_POST["change"])) {
    		$check = getimagesize($document["tmp_name"]);
   		 if($check !== false) {
     		  $check = getimagesize ( $document ["tmp_name"] );
      		  $uploadOk = 1;
    		} else {
     		   $showError .= $loc->label("File is not an image.") . "<br/>";
     		   $uploadOk = 0;
   		 }
		}  

		// Check file size
		if ($document["size"] > $size) {
  		  $showError .= $loc->label("Sorry, your file is too large.") . "<br/>";
  		  $uploadOk = 0;
		}
		// Allow certain file formats
		if($addFormat == 0) {
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				$showError .= $loc->label("Sorry, only JPG, JPEG, PNG & GIF files are allowed.") . "<br/>";
				$uploadOk = 0;
			}
		} else if($addFormat == 1){
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx"  && $imageFileType != "pdf") {
				$showError .= $loc->label("Sorry, only JPG, JPEG, PNG, GIF, DOC, DOCX & PDF files are allowed.") . "<br/>";
				$uploadOk = 0;
			}
		} else if($addFormat == 2){
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx"  &&
			$imageFileType != "pdf" && $imageFileType != "mpeg" && $imageFileType != "mp3" && $imageFileType != "m4a" && $imageFileType != "avi" && $imageFileType != "wma" &&
			$imageFileType != "wmv" && $imageFileType != "swf" && $imageFileType != "flv" && $imageFileType != "ram" && $imageFileType != "mov" && $imageFileType != "asf") {
				$showError .= $loc->label("Sorry, the format of your file is not allowed.") . "<br/>";
				$uploadOk = 0;
			}
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		   return $showError;
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($document["tmp_name"], $target_file)) {
				return "XX//-_OK_-\\XX" . "appdoc" . $rand . "." . $imageFileType;
			} else {
				$showError .= $loc->label("Sorry, there was an error.") . "<br/>";
				return $showError;
			}		
		}
		
		
	}
  

}

class alerts {

	function error($message, $header) {

		?>
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert" type="button">×</button>
	<h4 class="alert-heading"><?php echo $header;?></h4>
	<p><?php echo $message;?></p>
</div>
<?php
	
	}

}

class objects extends TableItem {

	function radioFill($sql, $name, $selected) {

		$loc = new localization($_SESSION['language']);
		$result = $this->executenonquery ( $sql );
		$html = '<div class="radio radio-control radio-primary">';
		while ( list ( $ID, $item ) = mysqli_fetch_array ( $result ) ) {
			$html = $html . "<input type='radio' name='" . $name . "' id='" . $name . "_" . $ID . "' " . (($ID == $selected) ? "checked" : "") . " value='" . $ID . "'><label for='" . $name . "_" . $ID . "'>" . evalLoc($item) . "</label>";
		}
		$html = $html . "</div>";
		return $html;
	
	}
	
	function radioPositionFill($sql, $name, $selected) {
		require_once "Tables/positions.php";
		require_once "Tables/localization.php";
		
		$loc = new localization($_SESSION['language']);
		$position1 = new positions(1);
		$position2 = new positions(2);
		$position3 = new positions(3);
		$position4 = new positions(4);

		$arrayP = array(65 => $position1->point, 64 => $position2->point, 63 => $position3->point, 62 => $position4->point);  
		$loc = new localization($_SESSION['language']);  
		$result = $this->executenonquery ( $sql );
		$html = '<div class="radio radio-control radio-primary">';
		while ( list ( $ID, $item ) = mysqli_fetch_array ( $result ) ) {
			$html = $html . "<input type='radio' name='" . $name . "' id='" . $name . "_" . $ID . "' " . (($ID == $selected) ? "checked" : "") . " value='" . $ID . "'><label for='" . $name . "_" . $ID . "'>" . evalLoc($item) . (($arrayP[$ID] == 0) ? '<span class="label label-success">'.$loc->label("FREE").'</span>' : '') . "</label>";
		}
		$html = $html . "</div>";
		return $html;
	
	}

	function dropDownFill($sql, $name, $selected, $multiple = false, $placeholder="") {

		$loc = new localization($_SESSION['language']);
		if($placeholder == "") {	
			$placeholder = $loc->label ( "Select" );
		}
		$result = $this->executenonquery ( $sql );
		$html = '<select data-placeholder="' . $placeholder . '" class="chosen-select" name="' . $name . '" id="' . $name . '" ' . (($multiple == true) ? 'multiple' : '') . '>';
		$html = $html . "<option value='0' " . (($multiple != true) ? 'selected' : '') . " disabled>" . $loc->label ( "Select" ) . "</option>";
		while ( list ( $ID, $item ) = mysqli_fetch_array ( $result ) ) {
			$html = $html . "<option " . (($ID == $selected) ? "selected" : "") . " value='" . $ID . "'>" . evalLoc($item) . "</option>";
		}
		$html = $html . "</select>";
		return $html;
	
	}
	
	function dropDownFillCategory($sql, $name, $selected, $multiple = false) {

		$loc = new localization($_SESSION['language']);
		$result = $this->executenonquery ( $sql );
		$html = '<div class="dropdown display-block" style="margin-bottom: 15px;" name="' . $name . '" id="' . $name . '" ' . (($multiple == true) ? 'multiple' : '') . '>';
		$html = $html . '<a class="btn btn-default btn-icon-left btn-icon-right dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i>' . $loc->label("Category") . ' <i class="ion-android-arrow-dropdown"></i></a><ul class="dropdown-menu">';
		while ( list ( $ID, $item ) = mysqli_fetch_array ( $result ) ) {
			$html = $html . "<li><a href='javascript: sortGifts(". $ID .",0,0,0,1)'>" . evalLoc($item) . "</a></li>";
		}
		$html = $html . "</ul></div>";
		return $html;
	
}  

}

function evalLoc($string) {
	
	require_once "Tables/localization.php";
	$loc = new localization($_SESSION['language']);
	
	$result = ((strstr($string, '$loc->label')) ? eval ( "return $string;" ) : $string);
	
	return $result;
	
}


function randomchars($num = 15) {

	$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$string = '';
	for($i = 0; $i < $num; $i ++) {
		$string .= $characters [rand ( 0, strlen ( $characters ) - 1 )];
	}
	return $string;

}

function image_resize($src, $dst, $width, $height, $crop = 0) {

	if (! list ( $w, $h ) = getimagesize ( $src ))
		return "Unsupported picture type!";
	
	$type = strtolower ( substr ( strrchr ( $src, "." ), 1 ) );
	
	switch($type) {
		case 'BMP' :
			$type = "bmp";
			break;
		case 'GIF' :
			$type = "gif";
			break;
		case 'JPG' :
			$type = "jpg";
			break;
		case 'PNG' :
			$type = "png";
		case 'JPEG' :
			$type = "jpeg";
			break;
	}
	
	if ($type == 'jpeg')
		$type = 'jpg';
	switch ($type) {
		case 'bmp' :
			$img = imagecreatefromwbmp ( $src );
			break;
		case 'gif' :
			$img = imagecreatefromgif ( $src );
			break;
		case 'jpg' :
			$img = imagecreatefromjpeg ( $src );
			break;
		case 'png' :
			$img = imagecreatefrompng ( $src );
			break;
		default :
			return "Unsupported picture type!";
	}
	
	// resize
	if ($crop) {
		if ($w < $width or $h < $height)
			return "Picture is too small!";
		$ratio = max ( $width / $w, $height / $h );
		$h = $height / $ratio;
		$x = ($w - $width / $ratio) / 2;
		$w = $width / $ratio;
	} else {
		if ($w < $width and $h < $height)
			return "Picture is too small!";
		$ratio = min ( $width / $w, $height / $h );
		$width = $w * $ratio;
		$height = $h * $ratio;
		$x = 0;
	}
	
	$new = imagecreatetruecolor ( $width, $height );
	
	// preserve transparency
	if ($type == "gif" or $type == "png") {
		imagecolortransparent ( $new, imagecolorallocatealpha ( $new, 0, 0, 0, 127 ) );
		imagealphablending ( $new, false );
		imagesavealpha ( $new, true );
	}
	
	imagecopyresampled ( $new, $img, 0, 0, $x, 0, $width, $height, $w, $h );
	
	switch ($type) {
		case 'bmp' :
			imagewbmp ( $new, $dst );
			break;
		case 'gif' :
			imagegif ( $new, $dst );
			break;
		case 'jpg' :
			imagejpeg ( $new, $dst );
			break;
		case 'png' :
			imagepng ( $new, $dst );
			break;
	}
	return true;

}
if (! empty ( $_POST ['functionname'] )) {
	if ($_POST ['functionname'] == 'calcAddPost') {
		$object = new functions ();
		$object->calcAddPost ( $_POST ['position'], $_POST ['like'], $_POST ['follow'], $_POST ['share'], $_POST ['shareFollower'], $_POST ['view'], $_POST ['refunc'], $_POST ['platform'] );
	}
}function detectLang(){
	if(!isset($_COOKIE['language'])){
		$nocookie = 1;
	} else {
		$nocookie = 0;
	}
	if($nocookie != 1){
		if($_COOKIE['language'] == 'en' or $_COOKIE['language'] == 'tr')
		return $_COOKIE['language'];
	} else {
		$known_langs = array('en','tr');
		$user_pref_langs = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) : '';
		$language='';
		foreach($user_pref_langs as $idx => $lang) {
			$lang = substr($lang, 0, 2);
			if (in_array($lang, $known_langs)) {
				$language= $lang;
				break;
			}
		}if(empty($language)){
			$language= 'en';
		}return $language;
	}
}function ip() {
  if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && filter_var( $_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP,
   FILTER_FLAG_NO_PRIV_RANGE ) ) {
   $_SERVER['REMOTE_ADDR'] = filter_var( $_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE ) ;
  } else
   if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) && filter_var( $_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP,
    FILTER_FLAG_NO_PRIV_RANGE ) ) {
    $_SERVER['REMOTE_ADDR'] = filter_var( $_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE ) ;
   } else {
    if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
     $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'] ;
    }
    if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
     $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'] ;
    }
   }
   return $_SERVER['REMOTE_ADDR'] ;
 }
function iptocountry($ip) {   
    $numbers = preg_split( "/\./", $ip);   
    require_once dirname ( dirname ( __FILE__ ) ) . "/Library/ip_files/".$numbers[0].".php";
    $code=($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);   
    foreach($ranges as $key => $value){
        if($key<=$code){
            if($ranges[$key][0]>=$code){$country=$ranges[$key][1];break;}
            }
    }
    if ($country==""){$country="unkown";}
    return $country;
}
function youtubeParser($url, $type){
	if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
	try {
	error_reporting(0); 
	
	if($type == 4){
		$parts= parse_url($url);
		$query = array();
		if($parts['host'] == 'www.youtube.com' OR $parts['host'] == 'youtube.com'){
			parse_str($parts['query'], $query);
			return array($query['v'],0);
		}elseif($parts['host'] == 'www.youtu.be' OR $parts['host'] == 'youtu.be'){
			$query= explode('/',$parts['path']);
			return array($query[1],0);
		}else{
			return false;
		}
	}elseif($type == 3){
		$parts= parse_url($url);
		if($parts['host'] == 'www.youtube.com' OR $parts['host'] == 'youtube.com' OR $parts['host'] == 'www.youtu.be' OR $parts['host'] == 'youtu.be'){
		$query= explode('/',$parts['path']);
		foreach($query as $key=>$value){
			if($value == 'user'){
				return array($query[$key+1],1);
			}elseif($value == 'channel' OR $value == 'c'){
				return array($query[$key+1],0);
			}
		}return array($query[1],0);
		}else{
			return false;
		}
	} 
	
	} catch  ( Exception $e ) {
		echo  $loc->label("You entered valid youtube address") . ' ERRORNO:22<br/>';
		break;
	}
}function socialButtons($userID, $postID, $showview=true, $buttonStyle='large'){
	$output='';
	require_once "Tables/users.php";
	require_once "Tables/posts.php";
	require_once "Tables/localization.php";
	require_once "Tables/userSocials.php";
	$user= new users($userID);
	$post= new posts($postID);
	$row= (array)$post;
	$runsql = new \data\DALProsess ();
	$loc = new localization($_SESSION['language']);  

	$buttonCnt=0;
	if($userID != $post->userID){
	switch($row["postType"]){  
		case 1:
			//"page" can be 'follow'ed or can be 'share'd
			if($row["platformID"] == 1){
				//show facebook icons
				$us = userSocials::getUserSocialFromID ( $userID, 1 );
				$sql3 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=3";
				$runsql->executenonquery ( $sql3, NULL, false );
				if($runsql->recordCount != 1 && $row["followCount"] > 0 && $row["nowFollow"] < $row["followCount"]){
					//show follow icon
					$buttonCnt++;
					$output.='<div class="fb-like" data-layout="button" data-size="'.$buttonStyle.'" data-href="'.$row['postUrl'].'?id='.$postID.'" data-send="false" data-width="150" data-show-faces="false">-</div>';
				}$sql2 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=2";
				$runsql->executenonquery ( $sql2, NULL, false );
				if($runsql->recordCount != 1 && $row["shareCount"] > 0 && $row["nowShare"] < $row["shareCount"]){
					//show share icon
					if(!(
						($post->oneSharerFollowerCount == 30 AND (($us->friendsCount < 100 AND $us->followerCount < 100) OR ($us->friendsCount > 300 OR $us->followerCount > 300))) OR 
						($post->oneSharerFollowerCount == 31 AND (($us->friendsCount < 300 AND $us->followerCount < 300) OR ($us->friendsCount > 300 OR $us->followerCount > 500))) OR
						($post->oneSharerFollowerCount == 32 AND (($us->friendsCount < 500 AND $us->followerCount < 500) OR ($us->friendsCount > 300 OR $us->followerCount > 1000))) OR
						($post->oneSharerFollowerCount == 33 AND (($us->friendsCount < 1000 AND $us->followerCount < 1000) OR ($us->friendsCount > 300 OR $us->followerCount > 3000))) OR
						($post->oneSharerFollowerCount == 34 AND (($us->friendsCount < 3000 AND $us->followerCount < 3000) OR ($us->friendsCount > 300 OR $us->followerCount > 5000))) OR
						($post->oneSharerFollowerCount == 35 AND (($us->friendsCount < 5000 AND $us->followerCount < 5000) OR ($us->friendsCount > 300 OR $us->followerCount > 10000))) OR 
						($post->oneSharerFollowerCount == 36 AND (($us->friendsCount < 10000 AND $us->followerCount < 10000) OR ($us->friendsCount > 300 OR $us->followerCount > 30000))) OR
						($post->oneSharerFollowerCount == 37 AND (($us->friendsCount < 30000 AND $us->followerCount < 30000) OR ($us->friendsCount > 300 OR $us->followerCount > 50000))) OR
						($post->oneSharerFollowerCount == 38 AND (($us->friendsCount < 50000 AND $us->followerCount < 50000) OR ($us->friendsCount > 300 OR $us->followerCount > 100000))) OR
						($post->oneSharerFollowerCount == 39 AND (($us->friendsCount < 100000 AND $us->followerCount < 100000) OR ($us->friendsCount > 300 OR $us->followerCount > 300000))) OR
						($post->oneSharerFollowerCount == 40 AND (($us->friendsCount < 300000 AND $us->followerCount < 300000) OR ($us->friendsCount > 300 OR $us->followerCount > 500000))) OR
						($post->oneSharerFollowerCount == 333 AND (($us->friendsCount < 500000 AND $us->followerCount < 500000) OR ($us->friendsCount > 300 OR $us->followerCount > 1000000))) OR
						($post->oneSharerFollowerCount == 334 AND (($us->friendsCount < 1000000 AND $us->followerCount < 1000000) OR ($us->friendsCount > 300 OR $us->followerCount > 3000000))) OR
						($post->oneSharerFollowerCount == 335 AND (($us->friendsCount < 3000000 AND $us->followerCount < 3000000) OR ($us->friendsCount > 300 OR $us->followerCount > 5000000))) OR
						($post->oneSharerFollowerCount == 336 AND (($us->friendsCount < 5000000 AND $us->followerCount < 5000000) OR ($us->friendsCount > 300 OR $us->followerCount > 7000000))) OR 
						($post->oneSharerFollowerCount == 337 AND (($us->friendsCount < 7000000 AND $us->followerCount < 7000000) OR ($us->friendsCount > 300 OR $us->followerCount > 10000000))) OR
						($post->oneSharerFollowerCount == 338 AND ($us->friendsCount < 10000000 AND $us->followerCount < 10000000))
					)){
					$buttonCnt++;
					//button style
					if($buttonStyle == 'large'){   
						$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-share btn-rounded btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
					}else{
						$buttonScheme= '<button type="button" class="btn btn-share btn-sm btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
					}
					$output.='<li><a href="javascript: action(2,'.$row["platformID"].','.$row["ID"].');">'.str_replace('<@text@>',$loc->label("shareB"),$buttonScheme).'</a></li>';
					}
				}
			}elseif($row["platformID"] == 2){
				//show twitter icons
				$us = userSocials::getUserSocialFromID ( $userID, 2 );
				$sql3 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=3";
				$runsql->executenonquery ( $sql3, NULL, false );
				if($runsql->recordCount != 1 && $row["followCount"] > 0 && $row["nowFollow"] < $row["followCount"]){
					//show follow icon
					$buttonCnt++;
					//button style
					if($buttonStyle == 'large'){   
						$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-info btn-rounded btn-icon-left"><i class="fa fa-feed"></i><@text@></button>';
					}else{
						$buttonScheme= '<button type="button" class="btn btn-info btn-sm btn-icon-left"><i class="fa fa-feed"></i><@text@></button>';
					}
					$output.='<li><a href="javascript: action(3,'.$row["platformID"].','.$row["ID"].');">'.str_replace('<@text@>',$loc->label("followB"),$buttonScheme).'</a></li>';
				}$sql2 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=2";
				$runsql->executenonquery ( $sql2, NULL, false );
				if($runsql->recordCount != 1 && $row["shareCount"] > 0 && $row["nowShare"] < $row["shareCount"]){
					//show share icon
					if(!(
						($post->oneSharerFollowerCount == 30 AND (($us->friendsCount < 100 AND $us->followerCount < 100) OR ($us->friendsCount > 300 OR $us->followerCount > 300))) OR 
						($post->oneSharerFollowerCount == 31 AND (($us->friendsCount < 300 AND $us->followerCount < 300) OR ($us->friendsCount > 300 OR $us->followerCount > 500))) OR
						($post->oneSharerFollowerCount == 32 AND (($us->friendsCount < 500 AND $us->followerCount < 500) OR ($us->friendsCount > 300 OR $us->followerCount > 1000))) OR
						($post->oneSharerFollowerCount == 33 AND (($us->friendsCount < 1000 AND $us->followerCount < 1000) OR ($us->friendsCount > 300 OR $us->followerCount > 3000))) OR
						($post->oneSharerFollowerCount == 34 AND (($us->friendsCount < 3000 AND $us->followerCount < 3000) OR ($us->friendsCount > 300 OR $us->followerCount > 5000))) OR
						($post->oneSharerFollowerCount == 35 AND (($us->friendsCount < 5000 AND $us->followerCount < 5000) OR ($us->friendsCount > 300 OR $us->followerCount > 10000))) OR 
						($post->oneSharerFollowerCount == 36 AND (($us->friendsCount < 10000 AND $us->followerCount < 10000) OR ($us->friendsCount > 300 OR $us->followerCount > 30000))) OR
						($post->oneSharerFollowerCount == 37 AND (($us->friendsCount < 30000 AND $us->followerCount < 30000) OR ($us->friendsCount > 300 OR $us->followerCount > 50000))) OR
						($post->oneSharerFollowerCount == 38 AND (($us->friendsCount < 50000 AND $us->followerCount < 50000) OR ($us->friendsCount > 300 OR $us->followerCount > 100000))) OR
						($post->oneSharerFollowerCount == 39 AND (($us->friendsCount < 100000 AND $us->followerCount < 100000) OR ($us->friendsCount > 300 OR $us->followerCount > 300000))) OR
						($post->oneSharerFollowerCount == 40 AND (($us->friendsCount < 300000 AND $us->followerCount < 300000) OR ($us->friendsCount > 300 OR $us->followerCount > 500000))) OR
						($post->oneSharerFollowerCount == 333 AND (($us->friendsCount < 500000 AND $us->followerCount < 500000) OR ($us->friendsCount > 300 OR $us->followerCount > 1000000))) OR
						($post->oneSharerFollowerCount == 334 AND (($us->friendsCount < 1000000 AND $us->followerCount < 1000000) OR ($us->friendsCount > 300 OR $us->followerCount > 3000000))) OR
						($post->oneSharerFollowerCount == 335 AND (($us->friendsCount < 3000000 AND $us->followerCount < 3000000) OR ($us->friendsCount > 300 OR $us->followerCount > 5000000))) OR
						($post->oneSharerFollowerCount == 336 AND (($us->friendsCount < 5000000 AND $us->followerCount < 5000000) OR ($us->friendsCount > 300 OR $us->followerCount > 7000000))) OR 
						($post->oneSharerFollowerCount == 337 AND (($us->friendsCount < 7000000 AND $us->followerCount < 7000000) OR ($us->friendsCount > 300 OR $us->followerCount > 10000000))) OR
						($post->oneSharerFollowerCount == 338 AND ($us->friendsCount < 10000000 AND $us->followerCount < 10000000))
					)){
					$buttonCnt++;
					//button style
					if($buttonStyle == 'large'){   
						$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-share btn-rounded btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
					}else{
						$buttonScheme= '<button type="button" class="btn btn-share btn-sm btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
					}
					$output.='<li><a href="javascript: action(2,'.$row["platformID"].','.$row["ID"].');">'.str_replace('<@text@>',$loc->label("shareB"),$buttonScheme).'</a></li>';
					}
				}
			}
			break;
		case 2:
			//"post" can be 'like'd or can be 'share'd
			if($row["platformID"] == 1){
				//show facebook icons
				$us = userSocials::getUserSocialFromID ( $userID, 1 );
				$sql1 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=1";
				$runsql->executenonquery ( $sql1, NULL, false );
				if($runsql->recordCount != 1 && $row["likeCount"] > 0 && $row["nowLike"] < $row["likeCount"]){
					//show like icon
					$buttonCnt++;
					// button style
					if($buttonStyle == 'large'){   
						$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-like btn-rounded btn-icon-left"><i class="fa fa-thumbs-o-up"></i><@text@></button>';
					}else{
						$buttonScheme= '<button type="button" class="btn btn-like btn-sm btn-icon-left"><i class="fa fa-thumbs-o-up"></i><@text@></button>';
					}
					$output.='<li><a href="javascript: action(1,'.$row["platformID"].','.$row["ID"].');">'.str_replace('<@text@>',$loc->label("likeB"),$buttonScheme).'</a></li>';
				}$sql2 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=2";
				$runsql->executenonquery ( $sql2, NULL, false );
				if($runsql->recordCount != 1 && $row["shareCount"] > 0 && $row["nowShare"] < $row["shareCount"]){
					//show share icon
					if(!(
						($post->oneSharerFollowerCount == 30 AND (($us->friendsCount < 100 AND $us->followerCount < 100) OR ($us->friendsCount > 300 OR $us->followerCount > 300))) OR 
						($post->oneSharerFollowerCount == 31 AND (($us->friendsCount < 300 AND $us->followerCount < 300) OR ($us->friendsCount > 300 OR $us->followerCount > 500))) OR
						($post->oneSharerFollowerCount == 32 AND (($us->friendsCount < 500 AND $us->followerCount < 500) OR ($us->friendsCount > 300 OR $us->followerCount > 1000))) OR
						($post->oneSharerFollowerCount == 33 AND (($us->friendsCount < 1000 AND $us->followerCount < 1000) OR ($us->friendsCount > 300 OR $us->followerCount > 3000))) OR
						($post->oneSharerFollowerCount == 34 AND (($us->friendsCount < 3000 AND $us->followerCount < 3000) OR ($us->friendsCount > 300 OR $us->followerCount > 5000))) OR
						($post->oneSharerFollowerCount == 35 AND (($us->friendsCount < 5000 AND $us->followerCount < 5000) OR ($us->friendsCount > 300 OR $us->followerCount > 10000))) OR 
						($post->oneSharerFollowerCount == 36 AND (($us->friendsCount < 10000 AND $us->followerCount < 10000) OR ($us->friendsCount > 300 OR $us->followerCount > 30000))) OR
						($post->oneSharerFollowerCount == 37 AND (($us->friendsCount < 30000 AND $us->followerCount < 30000) OR ($us->friendsCount > 300 OR $us->followerCount > 50000))) OR
						($post->oneSharerFollowerCount == 38 AND (($us->friendsCount < 50000 AND $us->followerCount < 50000) OR ($us->friendsCount > 300 OR $us->followerCount > 100000))) OR
						($post->oneSharerFollowerCount == 39 AND (($us->friendsCount < 100000 AND $us->followerCount < 100000) OR ($us->friendsCount > 300 OR $us->followerCount > 300000))) OR
						($post->oneSharerFollowerCount == 40 AND (($us->friendsCount < 300000 AND $us->followerCount < 300000) OR ($us->friendsCount > 300 OR $us->followerCount > 500000))) OR
						($post->oneSharerFollowerCount == 333 AND (($us->friendsCount < 500000 AND $us->followerCount < 500000) OR ($us->friendsCount > 300 OR $us->followerCount > 1000000))) OR
						($post->oneSharerFollowerCount == 334 AND (($us->friendsCount < 1000000 AND $us->followerCount < 1000000) OR ($us->friendsCount > 300 OR $us->followerCount > 3000000))) OR
						($post->oneSharerFollowerCount == 335 AND (($us->friendsCount < 3000000 AND $us->followerCount < 3000000) OR ($us->friendsCount > 300 OR $us->followerCount > 5000000))) OR
						($post->oneSharerFollowerCount == 336 AND (($us->friendsCount < 5000000 AND $us->followerCount < 5000000) OR ($us->friendsCount > 300 OR $us->followerCount > 7000000))) OR 
						($post->oneSharerFollowerCount == 337 AND (($us->friendsCount < 7000000 AND $us->followerCount < 7000000) OR ($us->friendsCount > 300 OR $us->followerCount > 10000000))) OR
						($post->oneSharerFollowerCount == 338 AND ($us->friendsCount < 10000000 AND $us->followerCount < 10000000))
					)){
					$buttonCnt++;
					//button style
					if($buttonStyle == 'large'){   
						$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-share btn-rounded btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
					}else{
						$buttonScheme= '<button type="button" class="btn btn-share btn-sm btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
					}
					$output.='<li><a href="javascript: action(2,'.$row["platformID"].','.$row["ID"].');">'.str_replace('<@text@>',$loc->label("shareB"),$buttonScheme).'</a></li>';
					}
				}
			}elseif($row["platformID"] == 2){
				//show twitter icons
				$us = userSocials::getUserSocialFromID ( $userID, 2 );
				$sql1 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=1";
				$runsql->executenonquery ( $sql1, NULL, false );
				if($runsql->recordCount != 1 && $row["likeCount"] > 0 && $row["nowLike"] < $row["likeCount"]){
					//show like icon
					$buttonCnt++;
					//button style
					if($buttonStyle == 'large'){   
						$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-like btn-rounded btn-icon-left"><i class="fa fa-thumbs-o-up"></i><@text@></button>';
					}else{
						$buttonScheme= '<button type="button" class="btn btn-like btn-sm btn-icon-left"><i class="fa fa-thumbs-o-up"></i><@text@></button>';
					}
					$output.='<li><a href="javascript: action(1,'.$row["platformID"].','.$row["ID"].');">'.str_replace('<@text@>',$loc->label("likeB"),$buttonScheme).'</a></li>';
				}$sql2 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=2";
				$runsql->executenonquery ( $sql2, NULL, false );
				if($runsql->recordCount != 1 && $row["shareCount"] > 0 && $row["nowShare"] < $row["shareCount"]){
					//show retweet icon
					if(!(
						($post->oneSharerFollowerCount == 30 AND (($us->friendsCount < 100 AND $us->followerCount < 100) OR ($us->friendsCount > 300 OR $us->followerCount > 300))) OR 
						($post->oneSharerFollowerCount == 31 AND (($us->friendsCount < 300 AND $us->followerCount < 300) OR ($us->friendsCount > 300 OR $us->followerCount > 500))) OR
						($post->oneSharerFollowerCount == 32 AND (($us->friendsCount < 500 AND $us->followerCount < 500) OR ($us->friendsCount > 300 OR $us->followerCount > 1000))) OR
						($post->oneSharerFollowerCount == 33 AND (($us->friendsCount < 1000 AND $us->followerCount < 1000) OR ($us->friendsCount > 300 OR $us->followerCount > 3000))) OR
						($post->oneSharerFollowerCount == 34 AND (($us->friendsCount < 3000 AND $us->followerCount < 3000) OR ($us->friendsCount > 300 OR $us->followerCount > 5000))) OR
						($post->oneSharerFollowerCount == 35 AND (($us->friendsCount < 5000 AND $us->followerCount < 5000) OR ($us->friendsCount > 300 OR $us->followerCount > 10000))) OR 
						($post->oneSharerFollowerCount == 36 AND (($us->friendsCount < 10000 AND $us->followerCount < 10000) OR ($us->friendsCount > 300 OR $us->followerCount > 30000))) OR
						($post->oneSharerFollowerCount == 37 AND (($us->friendsCount < 30000 AND $us->followerCount < 30000) OR ($us->friendsCount > 300 OR $us->followerCount > 50000))) OR
						($post->oneSharerFollowerCount == 38 AND (($us->friendsCount < 50000 AND $us->followerCount < 50000) OR ($us->friendsCount > 300 OR $us->followerCount > 100000))) OR
						($post->oneSharerFollowerCount == 39 AND (($us->friendsCount < 100000 AND $us->followerCount < 100000) OR ($us->friendsCount > 300 OR $us->followerCount > 300000))) OR
						($post->oneSharerFollowerCount == 40 AND (($us->friendsCount < 300000 AND $us->followerCount < 300000) OR ($us->friendsCount > 300 OR $us->followerCount > 500000))) OR
						($post->oneSharerFollowerCount == 333 AND (($us->friendsCount < 500000 AND $us->followerCount < 500000) OR ($us->friendsCount > 300 OR $us->followerCount > 1000000))) OR
						($post->oneSharerFollowerCount == 334 AND (($us->friendsCount < 1000000 AND $us->followerCount < 1000000) OR ($us->friendsCount > 300 OR $us->followerCount > 3000000))) OR
						($post->oneSharerFollowerCount == 335 AND (($us->friendsCount < 3000000 AND $us->followerCount < 3000000) OR ($us->friendsCount > 300 OR $us->followerCount > 5000000))) OR
						($post->oneSharerFollowerCount == 336 AND (($us->friendsCount < 5000000 AND $us->followerCount < 5000000) OR ($us->friendsCount > 300 OR $us->followerCount > 7000000))) OR 
						($post->oneSharerFollowerCount == 337 AND (($us->friendsCount < 7000000 AND $us->followerCount < 7000000) OR ($us->friendsCount > 300 OR $us->followerCount > 10000000))) OR
						($post->oneSharerFollowerCount == 338 AND ($us->friendsCount < 10000000 AND $us->followerCount < 10000000))
					)){
					$buttonCnt++;
					//button style
					if($buttonStyle == 'large'){   
						$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-share btn-rounded btn-icon-left"><i class="fa fa-retweet"></i><@text@></button>';
					}else{
						$buttonScheme= '<button type="button" class="btn btn-share btn-sm btn-icon-left"><i class="fa fa-retweet"></i><@text@></button>';
					}
					$output.='<li><a href="javascript: action(2,'.$row["platformID"].','.$row["ID"].');">'.str_replace('<@text@>',$loc->label("retweetB"),$buttonScheme).'</a></li>';
					}
				}
			}
			break;
		case 3:
			//youtube "channel"  can be 'subscribe'd or 'share'd on other platforms
			$usFacebook = userSocials::getUserSocialFromID ( $userID, 1 );
			$usTwitter = userSocials::getUserSocialFromID ( $userID, 2 );
			$sql3 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=3";
			$runsql->executenonquery ( $sql3, NULL, false );
			if($runsql->recordCount != 1 && $row["followCount"] > 0 && $row["nowFollow"] < $row["followCount"]){
				//show subscribe icon
				$buttonCnt++;
				//button style
				if($buttonStyle == 'large'){   
					$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-danger btn-rounded btn-icon-left"><i class="fa fa-tv"></i><@text@></button>';
				}else{
					$buttonScheme= '<button type="button" class="btn btn-danger btn-sm btn-icon-left"><i class="fa fa-tv"></i><@text@></button>';
				}
				$output.='<li><a href="javascript: action(3,'.$row["platformID"].','.$row["ID"].');">'.str_replace('<@text@>',$loc->label("subscribeB"),$buttonScheme).'</a></li>';
			}$sql2 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=2";
			$runsql->executenonquery ( $sql2, NULL, false );
			if($runsql->recordCount != 1 && $row["shareCount"] > 0 && $row["nowShare"] < $row["shareCount"]){
				//show share icons
				if(!(
						($post->oneSharerFollowerCount == 30 AND (($usFacebook->friendsCount < 100 AND $usFacebook->followerCount < 100) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 300))) OR 
						($post->oneSharerFollowerCount == 31 AND (($usFacebook->friendsCount < 300 AND $usFacebook->followerCount < 300) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 500))) OR
						($post->oneSharerFollowerCount == 32 AND (($usFacebook->friendsCount < 500 AND $usFacebook->followerCount < 500) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 1000))) OR
						($post->oneSharerFollowerCount == 33 AND (($usFacebook->friendsCount < 1000 AND $usFacebook->followerCount < 1000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 3000))) OR
						($post->oneSharerFollowerCount == 34 AND (($usFacebook->friendsCount < 3000 AND $usFacebook->followerCount < 3000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 5000))) OR
						($post->oneSharerFollowerCount == 35 AND (($usFacebook->friendsCount < 5000 AND $usFacebook->followerCount < 5000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 10000))) OR 
						($post->oneSharerFollowerCount == 36 AND (($usFacebook->friendsCount < 10000 AND $usFacebook->followerCount < 10000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 30000))) OR
						($post->oneSharerFollowerCount == 37 AND (($usFacebook->friendsCount < 30000 AND $usFacebook->followerCount < 30000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 50000))) OR
						($post->oneSharerFollowerCount == 38 AND (($usFacebook->friendsCount < 50000 AND $usFacebook->followerCount < 50000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 100000))) OR
						($post->oneSharerFollowerCount == 39 AND (($usFacebook->friendsCount < 100000 AND $usFacebook->followerCount < 100000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 300000))) OR
						($post->oneSharerFollowerCount == 40 AND (($usFacebook->friendsCount < 300000 AND $usFacebook->followerCount < 300000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 500000))) OR
						($post->oneSharerFollowerCount == 333 AND (($usFacebook->friendsCount < 500000 AND $usFacebook->followerCount < 500000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 1000000))) OR
						($post->oneSharerFollowerCount == 334 AND (($usFacebook->friendsCount < 1000000 AND $usFacebook->followerCount < 1000000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 3000000))) OR
						($post->oneSharerFollowerCount == 335 AND (($usFacebook->friendsCount < 3000000 AND $usFacebook->followerCount < 3000000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 5000000))) OR
						($post->oneSharerFollowerCount == 336 AND (($usFacebook->friendsCount < 5000000 AND $usFacebook->followerCount < 5000000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 7000000))) OR 
						($post->oneSharerFollowerCount == 337 AND (($usFacebook->friendsCount < 7000000 AND $usFacebook->followerCount < 7000000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 10000000))) OR
						($post->oneSharerFollowerCount == 338 AND ($usFacebook->friendsCount < 10000000 AND $usFacebook->followerCount < 10000000))
					)){
				$buttonCnt++;
				//button style
				if($buttonStyle == 'large'){   
					$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-share btn-rounded btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
				}else{
					$buttonScheme= '<button type="button" class="btn btn-share btn-sm btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
				}
				$output.='<li><a href="javascript: action(2,'.$row["platformID"].','.$row["ID"].',1);">'.str_replace('<@text@>',$loc->label("share on facebookB"),$buttonScheme).'</a></li>';
					}
				if(!(
						($post->oneSharerFollowerCount == 30 AND (($usTwitter->friendsCount < 100 AND $usTwitter->followerCount < 100) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 300))) OR 
						($post->oneSharerFollowerCount == 31 AND (($usTwitter->friendsCount < 300 AND $usTwitter->followerCount < 300) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 500))) OR
						($post->oneSharerFollowerCount == 32 AND (($usTwitter->friendsCount < 500 AND $usTwitter->followerCount < 500) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 1000))) OR
						($post->oneSharerFollowerCount == 33 AND (($usTwitter->friendsCount < 1000 AND $usTwitter->followerCount < 1000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 3000))) OR
						($post->oneSharerFollowerCount == 34 AND (($usTwitter->friendsCount < 3000 AND $usTwitter->followerCount < 3000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 5000))) OR
						($post->oneSharerFollowerCount == 35 AND (($usTwitter->friendsCount < 5000 AND $usTwitter->followerCount < 5000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 10000))) OR 
						($post->oneSharerFollowerCount == 36 AND (($usTwitter->friendsCount < 10000 AND $usTwitter->followerCount < 10000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 30000))) OR
						($post->oneSharerFollowerCount == 37 AND (($usTwitter->friendsCount < 30000 AND $usTwitter->followerCount < 30000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 50000))) OR
						($post->oneSharerFollowerCount == 38 AND (($usTwitter->friendsCount < 50000 AND $usTwitter->followerCount < 50000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 100000))) OR
						($post->oneSharerFollowerCount == 39 AND (($usTwitter->friendsCount < 100000 AND $usTwitter->followerCount < 100000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 300000))) OR
						($post->oneSharerFollowerCount == 40 AND (($usTwitter->friendsCount < 300000 AND $usTwitter->followerCount < 300000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 500000))) OR
						($post->oneSharerFollowerCount == 333 AND (($usTwitter->friendsCount < 500000 AND $usTwitter->followerCount < 500000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 1000000))) OR
						($post->oneSharerFollowerCount == 334 AND (($usTwitter->friendsCount < 1000000 AND $usTwitter->followerCount < 1000000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 3000000))) OR
						($post->oneSharerFollowerCount == 335 AND (($usTwitter->friendsCount < 3000000 AND $usTwitter->followerCount < 3000000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 5000000))) OR
						($post->oneSharerFollowerCount == 336 AND (($usTwitter->friendsCount < 5000000 AND $usTwitter->followerCount < 5000000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 7000000))) OR 
						($post->oneSharerFollowerCount == 337 AND (($usTwitter->friendsCount < 7000000 AND $usTwitter->followerCount < 7000000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 10000000))) OR
						($post->oneSharerFollowerCount == 338 AND ($usTwitter->friendsCount < 10000000 AND $usTwitter->followerCount < 10000000))
					)){
				$buttonCnt++;
				//button style
				if($buttonStyle == 'large'){   
					$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-share btn-rounded btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
				}else{
					$buttonScheme= '<button type="button" class="btn btn-share btn-sm btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
				}
				$output.='<li><a href="javascript: action(2,'.$row["platformID"].','.$row["ID"].',2);">'.str_replace('<@text@>',$loc->label("share on twitterB"),$buttonScheme).'</a></li>';
					}
			}
			break;
		case 4:
			//youtube "video" can be 'like'd or 'share'd or 'view'ed
			$usFacebook = userSocials::getUserSocialFromID ( $userID, 1 );
			$usTwitter = userSocials::getUserSocialFromID ( $userID, 2 );
			$sql1 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=1";
			$runsql->executenonquery ( $sql1, NULL, false );
			if($runsql->recordCount != 1 && $row["likeCount"] > 0 && $row["nowLike"] < $row["likeCount"]){
				//show like icon
				$buttonCnt++;
				//button style
				if($buttonStyle == 'large'){   
					$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-like btn-rounded btn-icon-left"><i class="fa fa-thumbs-o-up"></i><@text@></button>';
				}else{
					$buttonScheme= '<button type="button" class="btn btn-like btn-sm btn-icon-left"><i class="fa fa-thumbs-o-up"></i><@text@></button>';
				}
				$output.='<li><a href="javascript: action(1,'.$row["platformID"].','.$row["ID"].');">'.str_replace('<@text@>',$loc->label("likeB"),$buttonScheme).'</a></li>';
			}$sql2 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=2";
			$runsql->executenonquery ( $sql2, NULL, false );
			if($runsql->recordCount != 1 && $row["shareCount"] > 0 && $row["nowShare"] < $row["shareCount"]){
				//show share icons
				if(!(
						($post->oneSharerFollowerCount == 30 AND (($usFacebook->friendsCount < 100 AND $usFacebook->followerCount < 100) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 300))) OR 
						($post->oneSharerFollowerCount == 31 AND (($usFacebook->friendsCount < 300 AND $usFacebook->followerCount < 300) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 500))) OR
						($post->oneSharerFollowerCount == 32 AND (($usFacebook->friendsCount < 500 AND $usFacebook->followerCount < 500) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 1000))) OR
						($post->oneSharerFollowerCount == 33 AND (($usFacebook->friendsCount < 1000 AND $usFacebook->followerCount < 1000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 3000))) OR
						($post->oneSharerFollowerCount == 34 AND (($usFacebook->friendsCount < 3000 AND $usFacebook->followerCount < 3000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 5000))) OR
						($post->oneSharerFollowerCount == 35 AND (($usFacebook->friendsCount < 5000 AND $usFacebook->followerCount < 5000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 10000))) OR 
						($post->oneSharerFollowerCount == 36 AND (($usFacebook->friendsCount < 10000 AND $usFacebook->followerCount < 10000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 30000))) OR
						($post->oneSharerFollowerCount == 37 AND (($usFacebook->friendsCount < 30000 AND $usFacebook->followerCount < 30000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 50000))) OR
						($post->oneSharerFollowerCount == 38 AND (($usFacebook->friendsCount < 50000 AND $usFacebook->followerCount < 50000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 100000))) OR
						($post->oneSharerFollowerCount == 39 AND (($usFacebook->friendsCount < 100000 AND $usFacebook->followerCount < 100000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 300000))) OR
						($post->oneSharerFollowerCount == 40 AND (($usFacebook->friendsCount < 300000 AND $usFacebook->followerCount < 300000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 500000))) OR
						($post->oneSharerFollowerCount == 333 AND (($usFacebook->friendsCount < 500000 AND $usFacebook->followerCount < 500000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 1000000))) OR
						($post->oneSharerFollowerCount == 334 AND (($usFacebook->friendsCount < 1000000 AND $usFacebook->followerCount < 1000000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 3000000))) OR
						($post->oneSharerFollowerCount == 335 AND (($usFacebook->friendsCount < 3000000 AND $usFacebook->followerCount < 3000000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 5000000))) OR
						($post->oneSharerFollowerCount == 336 AND (($usFacebook->friendsCount < 5000000 AND $usFacebook->followerCount < 5000000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 7000000))) OR 
						($post->oneSharerFollowerCount == 337 AND (($usFacebook->friendsCount < 7000000 AND $usFacebook->followerCount < 7000000) OR ($usFacebook->friendsCount > 300 OR $usFacebook->followerCount > 10000000))) OR
						($post->oneSharerFollowerCount == 338 AND ($usFacebook->friendsCount < 10000000 AND $usFacebook->followerCount < 10000000))
					)){
				$buttonCnt++;
				//button style
				if($buttonStyle == 'large'){   
					$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-share btn-rounded btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
				}else{
					$buttonScheme= '<button type="button" class="btn btn-share btn-sm btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
				}
				$output.='<li><a href="javascript: action(2,'.$row["platformID"].','.$row["ID"].',1);">'.str_replace('<@text@>',$loc->label("share on facebookB"),$buttonScheme).'</a></li>';
					}
				if(!(
						($post->oneSharerFollowerCount == 30 AND (($usTwitter->friendsCount < 100 AND $usTwitter->followerCount < 100) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 300))) OR 
						($post->oneSharerFollowerCount == 31 AND (($usTwitter->friendsCount < 300 AND $usTwitter->followerCount < 300) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 500))) OR
						($post->oneSharerFollowerCount == 32 AND (($usTwitter->friendsCount < 500 AND $usTwitter->followerCount < 500) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 1000))) OR
						($post->oneSharerFollowerCount == 33 AND (($usTwitter->friendsCount < 1000 AND $usTwitter->followerCount < 1000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 3000))) OR
						($post->oneSharerFollowerCount == 34 AND (($usTwitter->friendsCount < 3000 AND $usTwitter->followerCount < 3000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 5000))) OR
						($post->oneSharerFollowerCount == 35 AND (($usTwitter->friendsCount < 5000 AND $usTwitter->followerCount < 5000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 10000))) OR 
						($post->oneSharerFollowerCount == 36 AND (($usTwitter->friendsCount < 10000 AND $usTwitter->followerCount < 10000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 30000))) OR
						($post->oneSharerFollowerCount == 37 AND (($usTwitter->friendsCount < 30000 AND $usTwitter->followerCount < 30000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 50000))) OR
						($post->oneSharerFollowerCount == 38 AND (($usTwitter->friendsCount < 50000 AND $usTwitter->followerCount < 50000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 100000))) OR
						($post->oneSharerFollowerCount == 39 AND (($usTwitter->friendsCount < 100000 AND $usTwitter->followerCount < 100000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 300000))) OR
						($post->oneSharerFollowerCount == 40 AND (($usTwitter->friendsCount < 300000 AND $usTwitter->followerCount < 300000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 500000))) OR
						($post->oneSharerFollowerCount == 333 AND (($usTwitter->friendsCount < 500000 AND $usTwitter->followerCount < 500000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 1000000))) OR
						($post->oneSharerFollowerCount == 334 AND (($usTwitter->friendsCount < 1000000 AND $usTwitter->followerCount < 1000000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 3000000))) OR
						($post->oneSharerFollowerCount == 335 AND (($usTwitter->friendsCount < 3000000 AND $usTwitter->followerCount < 3000000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 5000000))) OR
						($post->oneSharerFollowerCount == 336 AND (($usTwitter->friendsCount < 5000000 AND $usTwitter->followerCount < 5000000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 7000000))) OR 
						($post->oneSharerFollowerCount == 337 AND (($usTwitter->friendsCount < 7000000 AND $usTwitter->followerCount < 7000000) OR ($usTwitter->friendsCount > 300 OR $usTwitter->followerCount > 10000000))) OR
						($post->oneSharerFollowerCount == 338 AND ($usTwitter->friendsCount < 10000000 AND $usTwitter->followerCount < 10000000))
					)){
				$buttonCnt++;
				//button style
				if($buttonStyle == 'large'){   
					$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-share btn-rounded btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
				}else{
					$buttonScheme= '<button type="button" class="btn btn-share btn-sm btn-icon-left"><i class="fa fa-share"></i><@text@></button>';
				}
				$output.='<li><a href="javascript: action(2,'.$row["platformID"].','.$row["ID"].',2);">'.str_replace('<@text@>',$loc->label("share on twitterB"),$buttonScheme).'</a></li>';
					}
			}$sql4 = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=4";
			$runsql->executenonquery ( $sql4, NULL, false );
			if($showview && $runsql->recordCount != 1 && $row["viewCount"] > 0 && $row["nowView"] < $row["viewCount"]){  
				//show view icon
				$buttonCnt++;
				//button style
				if($buttonStyle == 'large'){   
					$buttonScheme= '<button type="button" style="font-size: 20px;" class="btn btn-danger btn-rounded btn-icon-left"><i class="glyphicon glyphicon-play"></i><@text@></button>';
				}else{
					$buttonScheme= '<button type="button" class="btn btn-danger btn-sm btn-icon-left"><i class="glyphicon glyphicon-play"></i><@text@></button>';
				}
				$output.='<li><a href="/videopost?id='.$row["ID"].'">'.str_replace('<@text@>',$loc->label("viewB"),$buttonScheme).'</a></li>';
			}
			break;
	}
	if($buttonCnt == 0 AND isset($_POST['postID'])){//to hide all posts that doesn't hava any button, delete the second condition
		$output.= '{"hidePost": true}';
	}
	}
	return $output;
	
}
if(isset($_GET['function'])){
	if($_GET['function'] == 'showImage'){
		$contentType = mime_content_type(($img = file_get_contents($_GET['url'])));
		header("Content-Type: $contentType");
		echo $img;
	}elseif($_GET['function'] == 'updateButtons'){
		echo socialButtons($_SESSION['userID'],$_POST['postID'],true,$_POST['buttonStyle']);
	}
}
?>
