<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
date_default_timezone_set ( 'Europe/Istanbul' );
setlocale ( LC_ALL, "tr_TR" );

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/socials.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/mailTemplates.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/currency.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/cost.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/IPlogs.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/localization.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/communication.php";
$func = new functions ();
$loc = new localization ($_SESSION['language']);

if(isset($_SERVER['HTTP_REFERER'])) {
$pos = strpos($_SERVER['HTTP_REFERER'],getenv('HTTP_HOST'));
} else {
	$pos=false;
}
if($pos===false AND !(isset($_GET ["action"]) AND $_GET ["action"]=='pingback')) {
	
  die('Restricted access');
  exit;
	
} else {

	

$action = (isset ( $_GET ["action"] ) ? $_GET ["action"] : "");
$userID = (isset ( $_SESSION ["userID"] ) ? $_SESSION ["userID"] : "");
switch ($action) {
	case "earnPoints" :
		require_once  dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once  dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/actions.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/userSocials.php";
		require_once  "rifat.php";
		break;    
	case "login" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/adminSettings.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/admins.php";
		$runsql = new \data\DALProsess ();
		$email = isset ( $_POST ["usernameOrEmail"] ) ? $_POST ["usernameOrEmail"] : "";
		$password = isset ( $_POST ["password"] ) ? $_POST ["password"] : "";
		$userEmail = users::checkUserEmailForLogin ( $email );
		$time_difference = time() - $userEmail->loginAttemptTime;
		$userEmailID = $userEmail->ID;
		$loginSetting = new adminSettings(1);
		$adminC = admins::checkAdmin ( $userEmailID );
		if($loginSetting->status == 1) { 
			
			$siteOn = 1;
			
		} else {
			
			if($adminC->ID > 0) {
				
				$siteOn = 1;
			
			} else {
				
				$siteOn = 0;
				
			}
			
		}
		
		if($siteOn == 1) {

		if($time_difference >= 86400) {
			
			$sql = "UPDATE users SET loginAttempt=0, loginAttemptTime=0 WHERE ID ='$userEmailID'";
			$runsql->executenonquery ( $sql, NULL, true );
			
		}
		
		if($userEmail->loginAttempt >= 3 && $time_difference < 600) {
			
	
			if(empty($_POST['getCaptcha'])) {

				echo "loginAttemptCaptchaError";
				echo $loc->label("Please verify that you are not a robot.");

			} else {
				
				$recaptcha = $_POST['getCaptcha'];
				$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfSiyYTAAAAAHVLeu8TqMy11HDP6BXb5AEhpcmp&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
				
				if($response['success'] == false) {

					echo "loginAttemptCaptchaError";
					echo $loc->label("Sorry. We do not verify that you are not a robot. You cannot sign up.");

				} else {
				
					$user = users::checkUserWithoutPass($email);
			
					if (password_verify($password, $user->password)) {
						$_SESSION ["userID"] = $user->ID;
						$_SESSION ["fullName"] = $user->fullName;
						
						$sql = "SELECT * FROM IPlogs WHERE userID='".$user->ID."' ORDER BY ID DESC LIMIT 1";
						$ipresult= mysqli_fetch_assoc($runsql->executenonquery ( $sql, NULL, true ));
						
						if($ipresult==NULL OR ip2long(ip()) != $ipresult['address']){
						$iplogs = new IPlogs();
						$iplogs->userID = $user->ID;
						$iplogs->address= ip2long(ip());
						$iplogs->save();
						}
						if(!empty($user->username) && !is_null($user->username)) {
							$_SESSION ["username"] = $user->username; 
						}
						if($user->language == 'tr' OR $user->language == 'en'){
							$_SESSION['language']= $user->language;
						}else{
							$_SESSION['language']= 'en';
						}
			
						$sql = "UPDATE users SET loginAttempt=0, loginAttemptTime=0, loginDate=NOW() WHERE ID ='$userEmail->ID'";
						$runsql->executenonquery ( $sql, NULL, true );
						
						setcookie("username", $email, time()+84600,"/",'funnyandmoney.com',true,true);
						echo "ok"; 
						
					} else {
					
						echo "loginAttemptCaptchaError";
						echo $loc->label("You entered username or password is incorrect.");
						
					} 

				
				}
				
			}
			

	
		} else {
			
			$user = users::checkUserWithoutPass($email);
			
			if (password_verify($password, $user->password)) {
				$_SESSION ["userID"] = $user->ID;
				$_SESSION ["fullName"] = $user->fullName;
				
				$sql = "SELECT * FROM IPlogs WHERE userID='".$user->ID."' ORDER BY ID DESC LIMIT 1";
				$ipresult= mysqli_fetch_assoc($runsql->executenonquery ( $sql, NULL, true ));  
				
				if($ipresult==NULL OR ip2long(ip()) != $ipresult['address']){
				$iplogs = new IPlogs();
				$iplogs->userID = $user->ID;
				$iplogs->address= ip2long(ip());
				$iplogs->save();
				}
				if(!empty($user->username) && !is_null($user->username)) {
					$_SESSION ["username"] = $user->username; 
				}
				if($user->language == 'tr' OR $user->language == 'en'){
					$_SESSION['language']= $user->language;
				}else{
					$_SESSION['language']= 'en';
				}
			
				if($user->loginAttempt != 0 OR $user->loginAttemptTime != 0){
				
					$sql = "UPDATE users SET loginAttempt=0, loginAttemptTime=0 WHERE ID ='$userEmail->ID'";
					$runsql->executenonquery ( $sql, NULL, true );
		
				} 
			
				$sql2 = "UPDATE users SET loginDate=NOW() WHERE ID ='$userEmail->ID'";
				$runsql->executenonquery ( $sql2, NULL, true );
				
	
				setcookie("username", $email, time()+84600,"/",'funnyandmoney.com',true,true); 
				echo "ok";
			
			} else {
				
				$nowloginAttempt = $userEmail->loginAttempt + 1;
				
				if($nowloginAttempt >= 3) {
					
					$sql = "UPDATE users SET loginAttempt=3, loginAttemptTime=UNIX_TIMESTAMP() WHERE ID ='$userEmail->ID'";
					$runsql->executenonquery ( $sql, NULL, true );
					
				} else {
					
					$sql = "UPDATE users SET loginAttempt='$nowloginAttempt', loginAttemptTime=0 WHERE ID ='$userEmail->ID'";
					$runsql->executenonquery ( $sql, NULL, true );
					
				}
					
				echo $loc->label("You entered username or password is incorrect.");
						
			} 
	
		}

		} else {
			
			echo $loc->label("Site offline now. Please try again later.");
		
		}
		
		break;
		
	case "logout" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
		if(isset($_COOKIE['username'])) {
			setcookie("username", "", time()-84600,"/",'funnyandmoney.com',true,true);
		}
		session_destroy ();
		break;
		
	case "adminlogin" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/admins.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/adminHistory.php";
		$email = isset ( $_POST ["usernameOrEmail"] ) ? $_POST ["usernameOrEmail"] : "";
		$password = isset ( $_POST ["password"] ) ? $_POST ["password"] : "";
		$pin = isset ( $_POST ["pin"] ) ? $_POST ["pin"] : "";
		$user = users::checkUserWithoutPass($email);
		if (password_verify($password, $user->password)) {
			
			$admin = admins::checkAdminWithPin ( $user->ID, $pin );
			if($admin->ID > 0) {
				$_SESSION ["adminID"] = $admin->ID;
				
				$history = new adminHistory();
				$history->tableName = "admins";
				$history->tableID = $admin->ID;
				$history->adminID = $admin->ID;
				$history->operation = "admin logged in"; 
				$history->updated = new DateTime(date('Y/m/d H:i:s'));
				$history->save();
				
				echo "ok"; 
			}
			
		}
		
		break;
		
	case "callModal" :
		$page = (isset ( $_POST ["page"] ) ? $_POST ["page"] : "");
		require_once dirname ( dirname ( __FILE__ ) ) . '/Views/' . $page . ".php";
		break;
	case "saveForm" :
		$tableName = (isset ( $_POST ["tableName"] ) ? $_POST ["tableName"] : "");
		$ID = (isset ( $_POST ["ID"] ) ? $_POST ["ID"] : 0);
		 
		if(!isset($tableName)) {
			break; 
		}
		 
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/" . $tableName . ".php";
		
		switch ($tableName) {
			case "users" :
			require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/adminSettings.php";
			
			$loginSetting = new adminSettings(1);
			if($loginSetting->status == 1) { 
				
			$pattern = '/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/';
			
			$user = users::checkUserEmailAct ( $_POST ["email"] );
			
			$error = 0;
			$errOutput ="";
				
			if(empty($_POST ["fullName"])) {
				
				$error = 1;
				$errOutput .= $loc->label("Enter your name please.");
				
			} else if (strcspn($_POST ['fullName'], '0123456789') != strlen($_POST ['fullName']) || preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_POST ['fullName'])) {
				
				$error = 1;
				$errOutput .= $loc->label("Your name cannot contain special characters.");
				
			} else if(strlen($_POST ["fullName"]) > 100) {
				
				$error = 1;
				$errOutput .= $loc->label("Your name is too long.");
				
			} else if(strlen($_POST ["fullName"]) < 3) {
				
				$error = 1;
				$errOutput .= $loc->label("Your name is too short.");
				
			} else if(strlen(trim($_POST ['fullName'])) == 0) {
			
				$error = 1;
				$errOutput .= $loc->label ("Your name cannot be blank.");
			
			} 
			
			if(empty($_POST ["email"])) {
				
				$error = 1;
				$errOutput .= $loc->label("Enter your email address please.");
				
				
			} else if(strlen($_POST ["email"]) > 100) {
				
				$error = 1;
				$errOutput .= $loc->label("Your email address is too long.");
		
				
			} else if(!filter_var($_POST ["email"], FILTER_VALIDATE_EMAIL)) {
				
				$error = 1;
				$errOutput .= $loc->label("Your email address is invalid.");
		
				
			} else if($user->ID > 0) {
				
				$error = 1;
				$errOutput .= $loc->label("Your email address is used by another account.");
				
				
			} 
			if(empty($_POST ["password"])) {
				
				$error = 1;
				$errOutput .= $loc->label("Enter your password please.");
			
				
			} else if(strlen($_POST ["password"]) > 35) {
				
				$error = 1;
				$errOutput .= $loc->label("Your password is too long.");
		
				
			} else if(strlen($_POST ["password"]) < 8) {
				
				$error = 1;
				$errOutput .= $loc->label("Your password is too short.");
			
				
			} else if( !(preg_match($pattern,$_POST ["password"]))) {
				
				$error = 1;
				$errOutput .= $loc->label("Your password must contain at least one uppercase letter one lower case letter and one number.");
			
				
			} else if(empty($_POST ["retype"])) {
				
				$error = 1;
				$errOutput .= $loc->label("Please retype your password.");
				
			} else if($_POST ["password"] != $_POST ["retype"]) {
				
				$error = 1;
				$errOutput .= $loc->label("Your passwords does not match.");
				
			} 
			
			if(empty($_POST['g-recaptcha-response'])) {
				
				$error = 1;
				$errOutput .= $loc->label("Please verify that you are not a robot.");
				
			} else {
				
				$recaptcha = $_POST['g-recaptcha-response'];
				$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfQdyYTAAAAAAmGpG-gGzdxeHj6HBlalVe4Y8p9&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
				
				if($response['success'] == false) {
					
					$error = 1;
					$errOutput .= $loc->label("Sorry. We do not verify that you are not a robot. You cannot sign up.");
					
				}
				
			}
			
			if($errOutput != "") {
				
				echo $errOutput;  
				
			}
			
			if($error == 0) {
				
				
					$tableClass = new ReflectionClass ( $tableName );
					$record = $tableClass->newInstanceArgs ( array (
						$ID 
					) );
					$reflectionClass = new ReflectionClass ( $tableName );
					$reflectionClass->getProperty ( 'ID' )->setValue ( $record, ((( int ) $ID > 0) ? $ID : 0) );
					if($tableName == "users"){
						$countryArray=array(
							'AF' => 79,
							'AL' => 81,
							'DZ' => 82,
							'AS' => 83,
							'AD' => 84,
							'AO' => 85,
							'AI' => 86,
							'AQ' => 87,
							'AG' => 88,
							'AR' => 89,
							'AM' => 90,
							'AW' => 91,
							'AU' => 92,
							'AT' => 93,
							'AZ' => 94,
							'BS' => 95,
							'BH' => 96,
							'BD' => 97,
							'BB' => 98,
							'BY' => 99,
							'BE' => 100,
							'BZ' => 101,
							'BJ' => 102,
							'BM' => 103,
							'BT' => 104,
							'BO' => 105,
							'BA' => 107,
							'BW' => 108,
							'BR' => 110,
							'IO' => 111,
							'BN' => 112,
							'BG' => 113,
							'BF' => 114,
							'BI' => 115,
							'KH' => 116,
							'CM' => 117,
							'CA' => 118,
							'CV' => 119,
							'KY' => 120,
							'CF' => 121,
							'TD' => 122,
							'CL' => 123,
							'CN' => 124,
							'CO' => 127,
							'KM' => 128,
							'CG' => 129,
							'CK' => 130,
							'CR' => 131,
							'CI' => 132,
							'HR' => 133,
							'CU' => 134,
							'CY' => 136,
							'CZ' => 137,
							'DK' => 139,
							'DJ' => 140,
							'DM' => 141,
							'DO' => 142,
							'EC' => 143,
							'EG' => 144,
							'SV' => 145,
							'GQ' => 146,
							'ER' => 147,
							'EE' => 148,
							'ET' => 149,
							'FK' => 150,
							'FO' => 151,
							'FJ' => 152,
							'FI' => 153,
							'FR' => 154,
							'GF' => 155,
							'PF' => 156,
							'TF' => 157,
							'GA' => 158,
							'GM' => 159,
							'GE' => 160,
							'DE' => 161,
							'GH' => 162,
							'GI' => 163,
							'GR' => 164,
							'GL' => 165,
							'GD' => 166,
							'GP' => 167,
							'GU' => 168,
							'GT' => 169,
							'GN' => 171,
							'GW' => 172,
							'GY' => 173,
							'HT' => 174,
							'HN' => 176,
							'HK' => 177,
							'HU' => 178,
							'IS' => 179,
							'IN' => 180,
							'ID' => 181,
							'IR' => 182,
							'IQ' => 183,
							'IE' => 184,
							'IL' => 186,
							'IT' => 187,
							'JM' => 188,
							'JP' => 189,
							'JO' => 191,
							'KZ' => 192,
							'KE' => 193,
							'KI' => 194,
							'KW' => 196,
							'KG' => 197,
							'LV' => 199,
							'LB' => 200,
							'LS' => 201,
							'LR' => 202,
							'LY' => 203,
							'LI' => 204,
							'LT' => 205,
							'LU' => 206,
							'MO' => 207,
							'MK' => 208,
							'MG' => 209,
							'MW' => 210,
							'MY' => 211,
							'MV' => 212,
							'ML' => 213,
							'MT' => 214,
							'MH' => 215,
							'MQ' => 216,
							'MR' => 217,
							'MU' => 218,
							'YT' => 219,
							'MX' => 220,
							'FM' => 221,
							'MD' => 222,
							'MC' => 223,
							'MN' => 224,
							'ME' => 225,
							'MA' => 227,
							'MZ' => 228,
							'MM' => 229,
							'NA' => 230,
							'NR' => 231,
							'NP' => 232,
							'NL' => 233,
							'NC' => 234,
							'NZ' => 235,
							'NI' => 236,
							'NE' => 237,
							'NG' => 238,
							'NU' => 239,
							'NF' => 240,
							'MP' => 242,
							'NO' => 243,
							'OM' => 244,
							'PK' => 245,
							'PW' => 246,
							'PS' => 247,
							'PA' => 248,
							'PG' => 249,
							'PY' => 250,
							'PE' => 251,
							'PH' => 252,
							'PL' => 254,
							'PT' => 255,
							'PR' => 256,
							'QA' => 257,
							'RE' => 258,
							'RO' => 259,
							'RU' => 260,
							'RW' => 261,
							'KN' => 264,
							'LC' => 265,
							'VC' => 268,
							'WS' => 269,
							'SM' => 270,
							'ST' => 271,
							'SA' => 272,
							'SN' => 273,
							'RS' => 274,
							'SC' => 275,
							'SL' => 276,
							'SG' => 277,
							'SK' => 279,
							'SI' => 280,
							'SB' => 281,
							'SO' => 282,
							'ZA' => 283,
							'GS' => 284,
							'ES' => 287,
							'LK' => 288,
							'SD' => 289,
							'SR' => 290,
							'SZ' => 292,
							'SE' => 293,
							'CH' => 294,
							'SY' => 295,
							'TJ' => 297,
							'TZ' => 298,
							'TH' => 299,
							'TL' => 300,
							'TG' => 301,
							'TK' => 302,
							'TO' => 303,
							'TT' => 304,
							'TN' => 305,
							'TR' => 306,
							'TM' => 307,
							'TV' => 309,
							'UG' => 310,
							'UA' => 311,
							'AE' => 312,
							'GB' => 313,
							'US' => 314,
							'UY' => 316,
							'UZ' => 317,
							'VU' => 318,
							'VA' => 319,
							'VE' => 320,
							'VN' => 321,
							'VG' => 322,
							'VI' => 323,
							'YE' => 326,
							'ZM' => 327,
							'ZW' => 328
						);
						$randomchars= randomchars();
						$_POST['email_code']= $randomchars;
						$_POST['balance']=0;
						$_POST['signupStep']=0;
						$_POST['language']=$_SESSION['language'];
						$_POST['country']= $countryArray[iptocountry(ip())];
						if(isset($_POST['referrerID'])) {
							$_POST['referrerID']=$_SESSION['referrerID'];
						}
						
					
						
					}
					foreach ( $_POST as $key => $value ) { 
						if ($key != "ID" && $key != "tableName" && substr ( $key, 0, 1 ) != "_" && $key != "password") {
							try {
								if (is_array ( $value )) {
								
									foreach ( $value as $thing ) {
										if ($thing != "") {
										
											$value = $thing;
										
										}
									}
								}
								$reflectionClass->getProperty ( $key )->setValue ( $record, $value );
							
							} catch ( Exception $err ) {
								
								// err
								
							}
						}
						if ($key == "password") {
							
							$reflectionClass->getProperty ( $key )->setValue ( $record, password_hash($value, PASSWORD_DEFAULT) );
							
						}
					}  
					$result = $record->save ();
				
				
					$_SESSION ["userID"] = $result;
					$_SESSION ["fullName"] = $_POST ["fullName"];
					$iplogs = new IPlogs();
					$iplogs->userID = $result;
					$iplogs->address= ip2long(ip());
					$iplogs->save();
					// send mail
					//E_MAIL AKTIVASYON KODU: $randomchars
					
					$mail = new Mail ( $_POST ["email"], $_POST ["fullName"], mailServer::fromEmail, mailServer::fromName, $loc->label ( "signupSubject" ), "", NULL, NULL, 1,  $_SESSION ["userID"] );
					$mail->sendMail();
					  
			
				}
			
			} else {
				
				echo $loc->label("Site offline now. Please try again later.");  
				
			}
			
				break;
			
			case "posts" :
				
				require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
				require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/campaigns.php";
				require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/campaignsHistory.php";
				$runsql = new \data\DALProsess ();
				
				$platformIDPost = $_POST ["platformID"];
				//Country Control
				$userCCont = new users($_SESSION["userID"]);
				
				$campaign = new campaigns(1);
				$campaignh = new campaignsHistory();
				$iplog = new IPlogs();
				$ipresult = $iplog->getLastIPaddress($_SESSION["userID"]);
				$rowip = mysqli_fetch_array($ipresult);
				$address = $rowip[0];
				$campaignCont = new campaignsHistory();
				$campaignCont = campaignsHistory::checkUserCampaignWithIPaddress($campaign->ID,$campaign->startdate_,$campaign->duedate_,$address,$_SESSION["userID"]);
				
				if($campaign->status == 1) {
					if($campaign->limit != NULL) {
						
						$result = $campaignh->countCampaign($campaign->ID,$campaign->startdate_,$campaign->duedate_);
						$rowcc = mysqli_fetch_array($result);
						$campCount = $rowcc[0];
						
						if($campCount == $campaign->limit or $campCount > $campaign->limit) {
							$campON = 0;
						} else {
							$campON = 1;
						}
					
					} else {
						
						if ((time() > $campaign->startdate_) && (time() < $campaign->duedate_)) {
							$campON = 1;
						} else {
							$campON = 0;  
							if($campaign->status == 1) {

								$sql = "UPDATE campaigns SET status=0 WHERE ID=1";
								$runsql->executenonquery ( $sql, NULL, true );

							}
						}
						
					}
					
				} else {
					$campON = 0;
				}
				
				if($campaignCont->ID > 0) {
					
					$campON = 0;

				}  
				
				if($campON == 1) {
				
					if($campaign->lower != NULL) {
						$lower = $campaign->lower;
					} else {
						$lower = 0;
					}
					
				}
				
					
				if ($platformIDPost == 1 || $platformIDPost == 2 || $platformIDPost == 4) {
					
					switch ($platformIDPost) {
						
						case 1 :  
							
							$error = 0;
							$errOutput ="";
							
							
							if(empty($_POST['postType'])) { 
								
								$error = 1;
								$errOutput .= $loc->label("Select a post type please.") . "<br/>";
								
							}
							
		
							
							if(empty($_POST ["socialID"])) {
								
								$error = 1;
								$errOutput .= $loc->label("Select your post or page.") . "<br/>";
								
							}
				
							
							if(empty($_POST ["categoryID"])) {
								
								$error = 1;
								$errOutput .= $loc->label("Select a category please.") . "<br/>";
								
							}
							
							if(empty($_POST ["numberFollowFacebook"]) && empty($_POST ["numberShare"]) && empty($_POST ["numberLike"])) {
								
								$error = 1;
								$errOutput .= $loc->label("Fill one at least field."). "<br/>";
								
							} else {
								
								if(!empty($_POST ["numberFollowFacebook"])) {
							
									if(strlen($_POST ["numberFollowFacebook"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("Page like field are too long."). "<br/>";
								
									} else if(!filter_var($_POST ["numberFollowFacebook"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("Page like field must be a number."). "<br/>";
								
									} else if($_POST ["numberFollowFacebook"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("Page like cannot be zero."). "<br/>";
								
									}
								}
							
								if(!empty($_POST ["numberShare"])) {
									
									if(strlen($_POST ["numberShare"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("Share field are too long."). "<br/>";
								
									} else if(!filter_var($_POST ["numberShare"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("Share field must be a number."). "<br/>";
								
									} else if($_POST ["numberShare"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("Share field cannot be zero."). "<br/>";
								
									}
								}
							
								if(!empty($_POST ["numberLike"])) {
									
									if(strlen($_POST ["numberLike"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("Like field are too long."). "<br/>";
								
									} else if(!filter_var($_POST ["numberLike"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("Like field must be a number."). "<br/>";
								
									} else if($_POST ["numberLike"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("Like field cannot be zero."). "<br/>";
								
									}
								}
							
								if($_POST ["numberShare"] > 0 && empty($_POST ["shareSelectFollowers"])) {
								
									$error = 1;
									$errOutput .= $loc->label("Select number of followers owned by one person please."). "<br/>";
								
								}
								
								if(!isset($_POST['radioPositionInline'])) {   
								
									$error = 1;
									$errOutput .= $loc->label("Select a position please."). "<br/>";
								
								}
								
							}
							
							$user = new users($_SESSION ["userID"]);
							$fn = new functions();
							 
							if ($_POST ["postType"] == 1) {
								
								$totalPoint = $fn->calcAddPost(isset($_POST['radioPositionInline']) ? $_POST['radioPositionInline'] : NULL, 0, 
								isset($_POST ["numberFollowFacebook"]) ? $_POST ["numberFollowFacebook"] : NULL, 
								isset($_POST ["numberShare"]) ? $_POST ["numberShare"] : NULL, 
								isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL,0,1,
								isset($platformIDPost) ? $platformIDPost : NULL);
								
							} else if ($_POST ["postType"] == 2) {
								
								$totalPoint = $fn->calcAddPost(isset($_POST['radioPositionInline']) ? $_POST['radioPositionInline'] : NULL, 
								isset($_POST ["numberLike"]) ? $_POST ["numberLike"] : NULL, 0, 
								isset($_POST ["numberShare"]) ? $_POST ["numberShare"] : NULL, 
								isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL,0,1,
								isset($platformIDPost) ? $platformIDPost : NULL);
								
							}
							
							
							
							if($totalPoint > $user->balance) {
								
								if($campON == 1) {
									
									if($lower > $totalPoint) {
									
										$error = 1;
										$errOutput .= $loc->label("Your don not have enough & to add this post."). "<br/>";
									
									} else {
										
										if(($totalPoint - $campaign->quantity) > $user->balance) {
											
											$error = 1;
											$errOutput .= $loc->label("Your don not have enough & to add this post."). "<br/>";
										
										}
									
									}

								} else {
									
									$error = 1;
									$errOutput .= $loc->label("Your don not have enough & to add this post."). "<br/>";
									
								}
								
								
							}

							if($errOutput != "") {
								
								echo $errOutput;
						
							}
								
							if($error == 0) {
						 
					
								$post = new posts ();
								$post->userID= $_SESSION ["userID"];
								if(!empty($_POST["socialID"])){
									$facebook= new zuckerberg($_SESSION ["userID"], array($_POST["socialID"]));
									if($_POST ["postType"] == 2){
									$fp= $facebook->getPost();
									}elseif($_POST ["postType"] == 1){
										$fp= $facebook->getPage();
									}
									if(isset($fp['picture'])){
										$arr = explode ( ".", explode ( "?", $fp['picture'] )[0] );
										$data = file_get_contents ($fp['picture']);
										$newFile = md5 ( date ( "Y-m-d H:i:s" ) ) . "." . end ( $arr );
										$new = dirname ( dirname ( __FILE__ ) ) . project::uploadPath . $newFile;
										file_put_contents ( $new, $data );
										
										if (! file_exists ( $new )) {
											
											$newFile = "";
										
										}
									}else{
										$newFile = "";
									}
									$post->title = $fp['message'];
									$post->description = $fp['message'];
									
									$phptest= explode(".",$newFile);
									if(end($phptest) == "php"){
										$newFile="";
									}

									$post->imagePath = $newFile;
									$post->postUrl = $fp['permalink'];
									
								}
								
								$post->postType = $_POST ["postType"];
								
								switch ($_POST ["radioPositionInline"]) {
			
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
								
								$post->positionID = $positionID;
								$post->platformID = $_POST["platformID"];
								$post->socialID = $_POST["socialID"];
								$post->categoryID = $_POST["categoryID"];
								
								//Country Control
								if($userCCont->country != 306) {
									$post->isTurkish = 0;
								} else {
									$post->isTurkish = 1;
								}

								
								//FOR ALL CODE - START
								
								if(!empty($_POST ['country'])) {
									if(is_array($_POST ['country'])){
										$ctr = 0;
										$postCountry = '';
										foreach ( $_POST ['country'] as $countries ) {
											$ctr ++;
											$postCountry .= $countries;
											if (count ( $_POST ['country'] ) != $ctr) {
												$postCountry .= ',';
											}			
										}
									}else{
										$postCountry= $_POST ['country'];
									}
									//Country Control
									if($userCCont->country != 306) {
										$post->country = $postCountry;
									} else {
										$post->country = 306;
									}
								
								} else {
									//Country Control
									if($userCCont->country != 306) {
										$post->country = 55;
									} else {
										$post->country = 306;
									}
								}
								
								if(!empty($_POST ['gender'])) {
									if(is_array($_POST ['gender'])){
										$ctr = 0;
										$postGender = '';
										foreach ( $_POST ['gender'] as $genders ) {
											$ctr ++;
											$postGender .= $genders;
											if (count ( $_POST ['gender'] ) != $ctr) {
												$postGender .= ',';
											}			
										}
									}else{
										$postGender= $_POST ['gender'];
									}
									$post->gender = $postGender;
								
								} else {
									$post->gender = 1;
								}
								
								if(!empty($_POST ['age'])) {
									if(is_array($_POST ['age'])){
										$ctr = 0;
										$postAge = '';
										foreach ( $_POST ['age'] as $ages ) {
											$ctr ++;
											$postAge .= $ages;
											if (count ( $_POST ['age'] ) != $ctr) {
												$postAge .= ',';
											}			
										}
									}else{
										$postAge=$_POST ['age'];
									}
									$post->age = $postAge;
								
								} else {
									$post->age = 17;	
								}
								
								//FOR All CODE - FINISH
									
								if ($_POST ["postType"] == 1) {
								
									$post->followCount = $_POST ["numberFollowFacebook"];
									$post->nowFollow=0;
									
									$post->shareCount = $_POST ["numberShare"];
									$post->nowShare=0;
									
									$post->oneSharerFollowerCount = isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL;
								
								} else if ($_POST ["postType"] == 2) {
								
									$post->likeCount = $_POST ["numberLike"];
									$post->nowLike=0;
									
									$post->shareCount = $_POST ["numberShare"];
									$post->nowShare=0;
									
									$post->oneSharerFollowerCount = isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL;
								
								}
							
								$post->status=1;
								$result = $post->save ();
								
								if($campON == 1) {
									
									$campaignHis = new campaignsHistory();
									$campaignHis->campaignID = $campaign->ID;
									$campaignHis->userID = $_SESSION["userID"];
									$campaignHis->IPaddress = $address;
									$campaignHis->date_ = new DateTime(date('Y/m/d H:i:s'));
									$campaignHis->save();
									
									if($totalPoint > $campaign->quantity) {
									
										$user->balance = str_replace(",",".",($user->balance - ($totalPoint - $campaign->quantity)));
										$user->save();
										
									}
									
									
									if(($campaign->limit - ($campCount+1)) <= 0) { 
										$sql = "UPDATE campaigns SET status=0 WHERE ID=1";
										$runsql->executenonquery ( $sql, NULL, true );
									}
									
									
									
								} else {
									
									$user->balance = str_replace(",",".",($user->balance - $totalPoint));
									$user->save();
									
								}
								
								
								
								
								if($result > 0){echo "ok";}   
								
							}
							
							break;
						
						case 2 :
							require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/userSocials.php";
							$us = userSocials::getUserSocialFromID ( $_SESSION ["userID"], 2 );
							$error = 0;
							$errOutput ="";
							
							
							if(empty($_POST['postType'])) { 
								
								$error = 1;
								$errOutput .= $loc->label("Select a post type please.") . "<br/>";
								
							}
							
							
							
							if ($_POST ["postType"] == 2) {
							
								if(empty($_POST ["socialID"])) {
								
									$error = 1;
									$errOutput .= $loc->label("Select your tweet.") . "<br/>";
								
								}
							
							}
							
							if(empty($_POST ["categoryID"])) {
								
								$error = 1;
								$errOutput .= $loc->label("Select a category please.") . "<br/>";
								
							}
							
							if(empty($_POST ["numberFollowTwitter"]) && empty($_POST ["numberShare"]) && empty($_POST ["numberLike"])) {
								
								$error = 1;
								$errOutput .= $loc->label("Fill one at least field."). "<br/>";
								
							} else {
								
								if(!empty($_POST ["numberFollowTwitter"])) {
							
									if(strlen($_POST ["numberFollowTwitter"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("Follow field are too long."). "<br/>";
								
									} else if(!filter_var($_POST ["numberFollowTwitter"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("Follow field must be a number."). "<br/>";
								
									} else if($_POST ["numberFollowTwitter"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("Follow field cannot be zero."). "<br/>";
								
									}
								}
							
								if(!empty($_POST ["numberShare"])) {
									
									if(strlen($_POST ["numberShare"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("Share field are too long."). "<br/>";
								
									} else if(!filter_var($_POST ["numberShare"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("Share field must be a number."). "<br/>";
								
									} else if($_POST ["numberShare"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("Share field cannot be zero."). "<br/>";
								
									}
								}
							
								if(!empty($_POST ["numberLike"])) {
									
									if(strlen($_POST ["numberLike"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("Like field are too long."). "<br/>";
								
									} else if(!filter_var($_POST ["numberLike"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("Like field must be a number."). "<br/>";
								
									} else if($_POST ["numberLike"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("Like field cannot be zero."). "<br/>";
								
									}
								}
							
								if($_POST ["numberShare"] > 0 && empty($_POST ["shareSelectFollowers"])) {
								
									$error = 1;
									$errOutput .= $loc->label("Select number of followers owned by one person please."). "<br/>";
								
								}
								
								if(!isset($_POST['radioPositionInline'])) { 
								
									
									$error = 1;
									$errOutput .= $loc->label("Select a position please."). "<br/>";
								
								} else if($_POST['radioPositionInline'] != 65 && $_POST['radioPositionInline'] != 64 && $_POST['radioPositionInline'] != 63 && $_POST['radioPositionInline'] != 62) {
									
									$error = 1;
									$errOutput .= "ERROR POSITION NOT DEFINED". "<br/>";
									
								}
								
							}
							
							$user = new users($_SESSION ["userID"]);
							$fn = new functions();
							 
							if ($_POST ["postType"] == 1) {
								
								$totalPoint = $fn->calcAddPost(isset($_POST['radioPositionInline']) ? $_POST['radioPositionInline'] : NULL, 0, 
								isset($_POST ["numberFollowTwitter"]) ? $_POST ["numberFollowTwitter"] : NULL, 
								isset($_POST ["numberShare"]) ? $_POST ["numberShare"] : NULL, 
								isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL,0,1,
								isset($platformIDPost) ? $platformIDPost : NULL);
								
							} elseif ($_POST ["postType"] == 2) {
								
								$totalPoint = $fn->calcAddPost(isset($_POST['radioPositionInline']) ? $_POST['radioPositionInline'] : NULL, 
								isset($_POST ["numberLike"]) ? $_POST ["numberLike"] : NULL, 0, 
								isset($_POST ["numberShare"]) ? $_POST ["numberShare"] : NULL, 
								isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL,0,1,
								isset($platformIDPost) ? $platformIDPost : NULL);
								
							}	   
							
							if($totalPoint > $user->balance) {
								
								if($campON == 1) {
									
									if($lower > $totalPoint) {
									
										$error = 1;
										$errOutput .= $loc->label("Your don not have enough & to add this post."). "<br/>";
									
									} else {
										
										if(($totalPoint - $campaign->quantity) > $user->balance) {
											
											$error = 1;
											$errOutput .= $loc->label("Your don not have enough & to add this post."). "<br/>";
										
										}
									
									}

								} else {
									
									$error = 1;
									$errOutput .= $loc->label("Your don not have enough & to add this post."). "<br/>";
									
								}
								
								
							}

							if($errOutput != "") {
								
								echo $errOutput;
						
							}
								
							if($error == 0) {
								
								$post = new posts ();
								
								if ($_POST ["postType"] == 1) {
									$img_exists=1;
									$twitter = new twitter ($_SESSION ["userID"], array($us->platformUserID) );
									$tp = $twitter->getUser ();
									$media = str_replace("_normal","",$tp->profile_image_url_https);
									$arr = explode ( ".", $media );
									$file = $media;
									
									$post->title = $tp->screen_name;
									$post->description = $tp->name;
									$post->postUrl = "https://www.twitter.com/".$tp->screen_name;
								} elseif ($_POST ["postType"] == 2) {
									$twitter = new twitter ($_SESSION ["userID"], array($_POST["socialID"]) );
									if(!empty($_POST["socialID"])){
									$tp = $twitter->getPost ();
									$img_exists=0;
									if(isset($tp->entities->media [0]->media_url)){
									$img_exists=1;
									$media = $tp->entities->media [0]->media_url;
									$arr = explode ( ".", $media );
									$file = $tp->entities->media [0]->media_url;
									}
									$post->title = $tp->text;
									$post->description = $tp->text;
									$post->postUrl = "https://www.twitter.com/".$us->screenName."/status/".$_POST["socialID"];
									}
								}
								if($img_exists == 1){
								$data = file_get_contents ( $file );
								$newFile = md5 ( date ( "Y-m-d H:i:s" ) ) . "." . end ( $arr );
								$new = dirname ( dirname ( __FILE__ ) ) . project::uploadPath . $newFile;
								file_put_contents ( $new, $data );
								
								if (! file_exists ( $new )) {
									
									$newFile = "";
								
								}
								$post->imagePath = $newFile;
								}else{
									$post->imagePath = "";
								}
								
								
								$post->userID= $_SESSION ["userID"];
								
								
								
								$post->postType = $_POST ["postType"];
								
								switch ($_POST ["radioPositionInline"]) {
			
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
								
								$post->positionID = $positionID;
								$post->platformID = $_POST["platformID"];
								$post->categoryID = $_POST["categoryID"];
								
								if ($_POST ["postType"] == 2) {
								
									$post->socialID = $_POST["socialID"];
								
								}elseif($_POST ["postType"] == 1) {
									$post->socialID = $us->platformUserID;
								}
								
								//Country Control
								if($userCCont->country != 306) {
									$post->isTurkish = 0;
								} else {
									$post->isTurkish = 1;
								}
								
								//FOR ALL CODE - START
								
								if(!empty($_POST ['country'])) {
									if(is_array($_POST ['country'])){
										$ctr = 0;
										$postCountry = '';
										foreach ( $_POST ['country'] as $countries ) {
											$ctr ++;
											$postCountry .= $countries;
											if (count ( $_POST ['country'] ) != $ctr) {
												$postCountry .= ',';
											}			
										}
									}else{
										$postCountry= $_POST ['country'];
									}
									//Country Control
									if($userCCont->country != 306) {
										$post->country = $postCountry;
									} else {
										$post->country = 306;
									}
								
								} else {
									//Country Control
									if($userCCont->country != 306) {
										$post->country = 55;
									} else {
										$post->country = 306;
									}
								}
								
								if(!empty($_POST ['gender'])) {
									if(is_array($_POST ['gender'])){
										$ctr = 0;
										$postGender = '';
										foreach ( $_POST ['gender'] as $genders ) {
											$ctr ++;
											$postGender .= $genders;
											if (count ( $_POST ['gender'] ) != $ctr) {
												$postGender .= ',';
											}			
										}
									}else{
										$postGender= $_POST ['gender'];
									}
									$post->gender = $postGender;
								
								} else {
									$post->gender = 1;
								}
								
								if(!empty($_POST ['age'])) {
									if(is_array($_POST ['age'])){
										$ctr = 0;
										$postAge = '';
										foreach ( $_POST ['age'] as $ages ) {
											$ctr ++;
											$postAge .= $ages;
											if (count ( $_POST ['age'] ) != $ctr) {
												$postAge .= ',';
											}			
										}
									}else{
										$postAge=$_POST ['age'];
									}
									$post->age = $postAge;
								
								} else {
									$post->age = 17;	
								}
								
								//FOR All CODE - FINISH
									
								if ($_POST ["postType"] == 1) {
								
									$post->followCount = $_POST ["numberFollowTwitter"];
									$post->nowFollow=0;
									
									$post->shareCount = $_POST ["numberShare"];
									$post->nowShare=0;
									
									$post->oneSharerFollowerCount = isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL;
								
								} else if ($_POST ["postType"] == 2) {
								
									$post->likeCount = $_POST ["numberLike"];
									$post->nowLike=0;
									
									$post->shareCount = $_POST ["numberShare"];
									$post->nowShare=0;
									
									$post->oneSharerFollowerCount = isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL;
								
								}
							
								$post->status=1;
								$result = $post->save ();
								
								if($campON == 1) {
									
									$campaignHis = new campaignsHistory();
									$campaignHis->campaignID = $campaign->ID;
									$campaignHis->userID = $_SESSION["userID"];
									$campaignHis->IPaddress = $address;
									$campaignHis->date_ = new DateTime(date('Y/m/d H:i:s'));
									$campaignHis->save();
									
									if($totalPoint > $campaign->quantity) {
									
										$user->balance = str_replace(",",".",($user->balance - ($totalPoint - $campaign->quantity)));
										$user->save();
										
									}
									
									if(($campaign->limit - ($campCount+1)) <= 0) { 
										$sql = "UPDATE campaigns SET status=0 WHERE ID=1";
										$runsql->executenonquery ( $sql, NULL, true );
									}
									
									
									
								} else {
									
									$user->balance = str_replace(",",".",($user->balance - $totalPoint));
									$user->save();
									
								}
								
								if($result > 0){echo "ok";}
								
							}
							
							break;
							
						case 4 :
							/*$yt = new youTube ( $_SESSION ["userID"], $result );
							$ytp = $yt->getPost ();
							echo var_dump ( $ytp );*/
								
								
							$error = 0;
							$errOutput ="";
							
							
							if(empty($_POST['postType'])) { 
								
								$error = 1;
								$errOutput .= $loc->label("Select a post type please.") . "<br/>";
								
							}
							
							
							if(empty($_POST ["postUrlYoutube"])) {
								
									$error = 1;
									$errOutput .= $loc->label("Enter your video/channel url please.") . "<br/>";

							} else if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST ["postUrlYoutube"])) {
								
									$error = 1;
									$errOutput .= $loc->label("The url is invalid.") . "<br/>";
								
							}
							if(youtubeParser($_POST ["postUrlYoutube"],$_POST ["postType"]) == false){
								$error = 1;
								$errOutput .= $loc->label("Url have to start with www.youtube.com or www.youtu.be") . "<br/>";
							}
							
							if(empty($_POST ["categoryID"])) {
								
								$error = 1;
								$errOutput .= $loc->label("Select a category please.") . "<br/>";
								
							}
							
							if(empty($_POST ["numberFollowYoutube"]) && empty($_POST ["numberShare"]) && empty($_POST ["numberLike"]) && empty($_POST ["numberView"])) {
								
								$error = 1;
								$errOutput .= $loc->label("Fill one at least field.") . "<br/>";
								
							} else {
								
								if(!empty($_POST ["numberFollowYoutube"])) {
							
									if(strlen($_POST ["numberFollowYoutube"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("Subscribe field are too long.") . "<br/>";
								
									} else if(!filter_var($_POST ["numberFollowYoutube"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("Subscribe field must be a number.") . "<br/>";
								
									} else if($_POST ["numberFollowYoutube"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("Subscribe field cannot be zero.") . "<br/>";
								
									}
								}
							
								if(!empty($_POST ["numberShare"])) {
									
									if(strlen($_POST ["numberShare"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("Share field are too long."). "<br/>";
								
									} else if(!filter_var($_POST ["numberShare"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("Share field must be a number."). "<br/>";
								
									} else if($_POST ["numberShare"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("Share field cannot be zero."). "<br/>";
								
									}
								}
							
								if(!empty($_POST ["numberLike"])) {
									
									if(strlen($_POST ["numberLike"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("Like field are too long.") . "<br/>";
								
									} else if(!filter_var($_POST ["numberLike"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("Like field must be a number.") . "<br/>";
								
									} else if($_POST ["numberLike"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("Like field cannot be zero.") . "<br/>";
								
									}
								}
								
								if(!empty($_POST ["numberView"])) {
									
									if(strlen($_POST ["numberView"]) > 11) {
								
										$error = 1;
										$errOutput .= $loc->label("View field are too long.") . "<br/>";
								
									} else if(!filter_var($_POST ["numberView"], FILTER_VALIDATE_INT)) {
								
										$error = 1;
										$errOutput .= $loc->label("View field must be a number.") . "<br/>";
								
									} else if($_POST ["numberView"] == 0) {
								
										$error = 1;
										$errOutput .= $loc->label("View field cannot be zero.") . "<br/>";
								
									}
								}
							
								if($_POST ["numberShare"] > 0 && empty($_POST ["shareSelectFollowers"])) {
								
									$error = 1;
									$errOutput .= $loc->label("Select number of followers owned by one person please."). "<br/>";
								
								}
								
								if(!isset($_POST['radioPositionInline'])) { 
								
									$error = 1;
									$errOutput .= $loc->label("Select a position please."). "<br/>";
								
								}
								
							}
							
							$user = new users($_SESSION ["userID"]);
							$fn = new functions();
							 
							if ($_POST ["postType"] == 3) {
								
								$totalPoint = $fn->calcAddPost(isset($_POST['radioPositionInline']) ? $_POST['radioPositionInline'] : NULL, 0, 
								isset($_POST ["numberFollowYoutube"]) ? $_POST ["numberFollowYoutube"] : NULL, 
								isset($_POST ["numberShare"]) ? $_POST ["numberShare"] : NULL, 
								isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL,0,1,
								isset($platformIDPost) ? $platformIDPost : NULL);
								
							} elseif ($_POST ["postType"] == 4) {
								
								$totalPoint = $fn->calcAddPost(isset($_POST['radioPositionInline']) ? $_POST['radioPositionInline'] : NULL, 
								isset($_POST ["numberLike"]) ? $_POST ["numberLike"] : NULL, 0, 
								isset($_POST ["numberShare"]) ? $_POST ["numberShare"] : NULL, 
								isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL,
								isset($_POST ["numberView"]) ? $_POST ["numberView"] : NULL,1,
								isset($platformIDPost) ? $platformIDPost : NULL);
								
							}	   
							
							if($totalPoint > $user->balance) {
								
								if($campON == 1) {
									
									if($lower > $totalPoint) {
									
										$error = 1;
										$errOutput .= $loc->label("Your don not have enough & to add this post."). "<br/>";
									
									} else {
										
										if(($totalPoint - $campaign->quantity) > $user->balance) {
											
											$error = 1;
											$errOutput .= $loc->label("Your don not have enough & to add this post."). "<br/>";
										
										}
									
									}

								} else {
									
									$error = 1;
									$errOutput .= $loc->label("Your don not have enough & to add this post."). "<br/>";
									
								}
								
								
							}  
							
							if($errOutput != "") {
								
								echo $errOutput;
						
							}
								
							if($error == 0) {
						
								$post = new posts ();
								
							try{
								error_reporting(0); 
								
								$post->socialID = youtubeParser($_POST ["postUrlYoutube"],$_POST ["postType"])[0];
								$youtube= new youtube($_SESSION ["userID"],array($post->socialID));

								if ($_POST ["postType"] == 4) {
									$yt= $youtube->getPost();
									$post->title = $yt['modelData']['items'][0]['snippet']['title'];
									$post->description = $yt['modelData']['items'][0]['snippet']['description'];
									$post->imagePath = "https://i.ytimg.com/vi/$post->socialID/mqdefault.jpg";
									$duration = new DateInterval($yt['modelData']['items'][0]['contentDetails']['duration']);
									$post->videoDuration=  $duration->format('%H:%I:%S');
								}elseif ($_POST ["postType"] == 3) {
									$yt= $youtube->getPage(youtubeParser($_POST ["postUrlYoutube"],$_POST ["postType"])[1]);
									$post->title = $yt['items'][0]['snippet']['title'];
									$post->description = $yt['items'][0]['snippet']['description'];
									$post->imagePath = $yt['items'][0]['snippet']['thumbnails']['medium']['url'];
								}
								
							} catch ( Exception $e ) {
								
								echo  $loc->label("You entered valid youtube address") . '<br/>';
								break;
								
							}
								
								$post->userID= $_SESSION ["userID"];
								$post->postUrl = $_POST ["postUrlYoutube"];
								$post->postType = $_POST ["postType"];
								
								switch ($_POST ["radioPositionInline"]) {
			
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
								
								$post->positionID = $positionID;
								$post->platformID = $_POST["platformID"];
								$post->categoryID = $_POST["categoryID"];
								
								//Country Control
								if($userCCont->country != 306) {
									$post->isTurkish = 0;
								} else {
									$post->isTurkish = 1;  
								}
								
								//FOR ALL CODE - START
								
								if(!empty($_POST ['country'])) {
									if(is_array($_POST ['country'])){
										$ctr = 0;
										$postCountry = '';
										foreach ( $_POST ['country'] as $countries ) {
											$ctr ++;
											$postCountry .= $countries;
											if (count ( $_POST ['country'] ) != $ctr) {
												$postCountry .= ',';
											}			
										}
									}else{
										$postCountry= $_POST ['country'];
									}
									//Country Control
									if($userCCont->country != 306) {
										$post->country = $postCountry;
									} else {
										$post->country = 306;
									}
								
								} else {
									//Country Control
									if($userCCont->country != 306) {
										$post->country = 55;
									} else {
										$post->country = 306;
									}
								}
								
								if(!empty($_POST ['gender'])) {
									if(is_array($_POST ['gender'])){
										$ctr = 0;
										$postGender = '';
										foreach ( $_POST ['gender'] as $genders ) {
											$ctr ++;
											$postGender .= $genders;
											if (count ( $_POST ['gender'] ) != $ctr) {
												$postGender .= ',';
											}			
										}
									}else{
										$postGender= $_POST ['gender'];
									}
									$post->gender = $postGender;
								
								} else {
									$post->gender = 1;
								}
								
								if(!empty($_POST ['age'])) {
									if(is_array($_POST ['age'])){
										$ctr = 0;
										$postAge = '';
										foreach ( $_POST ['age'] as $ages ) {
											$ctr ++;
											$postAge .= $ages;
											if (count ( $_POST ['age'] ) != $ctr) {
												$postAge .= ',';
											}			
										}
									}else{
										$postAge=$_POST ['age'];
									}
									$post->age = $postAge;
								
								} else {
									$post->age = 17;	
								}
								
								//FOR All CODE - FINISH
									
								if ($_POST ["postType"] == 3) {
								
									$post->followCount = $_POST ["numberFollowYoutube"];
									$post->nowFollow=0;
									
									$post->shareCount = $_POST ["numberShare"];
									$post->nowShare=0;
									
									$post->oneSharerFollowerCount = isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL;
								
								} else if ($_POST ["postType"] == 4) {
								
									$post->viewCount = $_POST ["numberView"];
									$post->nowView=0;
									
									$post->likeCount = $_POST ["numberLike"];
									$post->nowLike=0;
									
									$post->shareCount = $_POST ["numberShare"];
									$post->nowShare=0;
									
									$post->oneSharerFollowerCount = isset($_POST ["shareSelectFollowers"]) ? $_POST ["shareSelectFollowers"] : NULL;
								
								}
							
								$post->status=1;
								$result = $post->save ();
								
								if($campON == 1) {
									
									$campaignHis = new campaignsHistory();
									$campaignHis->campaignID = $campaign->ID;
									$campaignHis->userID = $_SESSION["userID"];
									$campaignHis->IPaddress = $address;
									$campaignHis->date_ = new DateTime(date('Y/m/d H:i:s'));
									$campaignHis->save();
									
									if($totalPoint > $campaign->quantity) {
									
										$user->balance = str_replace(",",".",($user->balance - ($totalPoint - $campaign->quantity)));
										$user->save();
										
									}
									
									if(($campaign->limit - ($campCount+1)) <= 0) { 
										$sql = "UPDATE campaigns SET status=0 WHERE ID=1";
										$runsql->executenonquery ( $sql, NULL, true );
									}
									
									
									
								} else {
									
									$user->balance = str_replace(",",".",($user->balance - $totalPoint));
									$user->save();
									
								}
								
								if($result > 0){echo "ok";}
								
							}
							
							break;

					}
					
				} else {
					
					echo $loc->label("Select a platform");
					
				}
				
				break;
		}
		break;
	
	case "cash" :
		
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payouts.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/currency.php";
		
		$userID = $_SESSION ["userID"];
		$point = (isset ( $_POST ["cashPoint"] ) ? $_POST ["cashPoint"] : "");
		
		$userd = new users ( $userID );
		$balance = $userd->balance;
		$limitMin = 300;
		$limitMax = 100000;
		
		$error = 0;
		
		if ($point == "") {
			
			echo $loc->label("Please, enter a number");
			$error = 1;
		} else if ($point < $limitMin) {
			
			echo $loc->label("You can change at least 50&");
			$error = 1;
		} else if ($point > $balance) {
			
			echo $loc->label("Insufficient balance");
			$error = 1;
		} else if ($point > $limitMax) {
			
			echo $loc->label("You can change maximum 200&");
			$error = 1;
		} else if($userd->IBAN == "" || $userd->IBAN == NULL) {
			
			echo $loc->label("You have no saved IBAN number");
			$error = 1;
			
		} else { 
			
			$payoutC = payouts::setUserPayout ( $userID, 1 );
		
			if($payoutC->ID > 0) {
			
				$date1 = new DateTime($payoutC->date_);
				$date2 = new DateTime(date('Y/m/d H:i:s'));

				$diff = $date2->diff($date1);

				$hours = $diff->h;
				$hours = $hours + ($diff->days*24);
			
			} else {
			
				$hours = 999;
			
			}
			
			if($hours < 168) {
				
				echo $loc->label("You can withdraw cash only once a week.");
				$error = 1;
			}
			
		}
		
		if ($error == 0) {
			
			$currency = new currency ( 2 );
			$amount = $currency->monetaryValue *  $point;
			
			$payout = new payouts ();
			$payout->date_ = date ( "Y-m-d H:i:s" );
			$payout->userID = $userID;
			$payout->method = "bankTransfer";
			$payout->point = $point;
			$payout->amount = str_replace(",",".",$amount);
			$payout->result = 2;  
			$payout->currency = "TRY";
			$payout->IBAN = $userd->IBAN;
			$payout->bankFirstName = $userd->bankFirstName;
			$payout->bankLastName = $userd->bankLastName;
			$payout->save ();
			
			$user = new users ( $userID );
			$user->balance = str_replace(",",".",($user->balance - $point));
			$user->save ();
			
			echo $loc->label("The transaction executed. Check payment list often.");
		}
		
		break;
		
	case "buyTransfer" :
		$runsql = new \data\DALProsess ();
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/banks.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/products.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/definitions.php";
		
		$userID = $_SESSION ["userID"];
		$productID = (isset ( $_POST ["product"] ) ? $_POST ["product"] : "");
		$bankID = (isset ( $_POST ["bankID"] ) ? $_POST ["bankID"] : "");
		
		$product = new products ( $productID );
		
		$bank = new banks($bankID);
		$country = new definitions($bank->country);
		
		do {
	
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$random_string_length = 8;
			$string = '';
			$max = strlen($characters) - 1;
			for ($i = 0; $i < $random_string_length; $i++) {
				$string .= $characters[mt_rand(0, $max)];
			}
			
			$sql = "SELECT 1 FROM payments WHERE salesNo='$string'";
			$runsql->executenonquery ( $sql, NULL, false );
		
		} while ( $runsql->recordCount != 0 );
		
		
		$payment = new payments();
		$payment->date_ = date ( "Y-m-d H:i:s" );
		$payment->userID = $userID;
		$payment->productID = $productID;
		$payment->salesNo = $string;
		$payment->currency = "TRY";
		$payment->method = "bankTransfer";
		$payment->result = 0;
		$payment->bankID = $bankID;
		$payment->amount = $product->price;
		$result = $payment->save ();
		
		if($result > 0) {
			
			echo '
				<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-sm-30">
					<div class="panel panel-inverse" style="border-color: rgba(0, 0, 0, 0.1);">  
						<div class="panel-body text-center" style="padding: 5px;">
								
						<table class="table">
									<thead>
										<tr>
											<center style="margin-top: 20px; margin-bottom: 20px;"><h3 style="color: green;">İşlemi tamamlamak için aşağıdaki yönergeleri uygulayın.</h3></center>
										</tr>
									</thead>

									<tbody>
										<tr>
											<th><p style="float: right; font-size: 18px; font-weight: 400;">İşlem Numarası</p></th> 
											<td><p style="float: left; font-weight: 500; font-size: 32px; color: #f4bb00">'. $string .'</p></td>
										</tr>
										<tr>
											<th><p style="float: right; font-size: 18px; font-weight: 400;">Banka İsmi</p></th> 
											<td><p style="float: left; font-size: 18px;">'. $bank->name . ' (' . evalLoc($country->definition) .')</p></td>
										</tr>
										<tr>
											<th><p style="float: right; font-size: 18px; font-weight: 400;">IBAN</p></th> 
											<td><p style="float: left; font-size: 18px;">'. $bank->iban .'</p></td>
										</tr>
										<tr>
											<th><p style="float: right; font-size: 18px; font-weight: 400;">Hesap Numarası</p></th> 
											<td><p style="float: left; font-size: 18px;">'. $bank->accountNo .'</p></td>
										</tr>
										<tr>
											<th><p style="float: right; font-size: 18px; font-weight: 400;">Alıcı İsim ve Soyisim</p></th> 
											<td><p style="float: left; font-size: 18px;">'. $bank->accountOwner .'</p></td>
										</tr>
										<tr>
											<th><p style="float: right; font-size: 18px; font-weight: 400;">Tutar</p></th> 
											<td><p style="float: left; font-size: 18px;">'. $product->price .' TRY</p></td>
										</tr>
										<tr>
											<th><p style="float: right; font-size: 18px; font-weight: 400;">Ürün</p></th> 
											<td><p style="float: left; font-size: 18px;">'. $product->productName .'</p></td>
										</tr>
									</tbody>    
								</table>
								
								<strong style="color: red;">Havale/EFT açıklama kısmına işlem numarasını yazmayı unutmayın!</strong><br/>
								Beklemek istemiyorsanız işlem numarasını ve dekontu <a href="mailto: payment@funnyandmoney.com;">payment@funnyandmoney.com</a> adresine gönderebilirsiniz.
						
								<p style="margin-top: 20px ; margin-bottom: 5px; font-weight: bold;">YÖNERGELER</p>
								<div style="height: 150px; overflow: auto; border: 2px; margin-top: 0px; text-align: justify; padding: 0px 8px 8px 8px;color: #999;">
								
									<div class="form-group" style="font-weight: bold;">1. İnternet bankacılığınız var ise bankanızın web sitesine giriş yaparak İnternet Bankacılığı yazısına tıklayın ve hesabınıza giriş yapın.(İnternet bankacılığı kullanmıyorsanız ödemeyi ATM yoluyla da gerçekleştirebilirsiniz.)</div>  
									<div class="form-group" style="font-weight: bold;">2. Havale/EFT yazan bölüme girin. Yukarıdaki IBAN numarasını veya hesap numarasını ilgili alanlara girin.</div>
									<div class="form-group" style="font-weight: bold;">3. Gönderilecek miktara yukardaki tutarı girin.</div>
									<div class="form-group" style="font-weight: bold;">4. Açıklama kısmına yukarıdaki işlem numarasını girin. Eğer işlem numarasını girmeyi unutursanız ürünün size ulaşması normal süreden daha uzun olacaktır. Lütfen dikkat edin.</div>
									<div class="form-group" style="font-weight: bold;">5. Havale/EFT sorunsuz şekilde gerçekleştikten kısa süre sonra ürününüz hesabınıza aktarılacaktır. Beklemek istemiyorsanız işlem numarasını ve mümkünse ödemenin dekontunu payment@funnyandmoney.com adresine gönderebilirsiniz.</div>
								
								</div>  
								
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
				</div>
			</div>
			';
			
		} else {
			
			echo "Error";
			
		}
		
		break;
		
	
	case "buyCard" :
		
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/currency.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/products.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/Library/paymentwall.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Consts/consts.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";
		
		$userID = $_SESSION ["userID"];
		$productID = (isset ( $_SESSION ["productID"] ) ? $_SESSION ["productID"] : "");
		unset($_SESSION["productID"]);
		$product = new products ( $productID );
		$productCategory = $product->category;
		
		$user = new users ( $userID );
		$balance = $user->balance;
		
		switch ($productCategory) {
			
			case "point" :
				
				if ($product->price == "") {
					
					$currency = new currency ( 1 );
					$amount = $product->quantity * $currency->monetaryValue;
				} else {
					
					$amount = $product->price;
				}
				
				break;
			
			case "campaign" :
				
				$amount = $product->price;
				// Campaign codes here
				break;
			
			case "premium" :
				
				$amount = $product->price;
				// Premium accounts codes here
				break;
		}
		//paymentwall
		$nameArray= explode(' ', $user->fullName, 2);
		Paymentwall_Config::getInstance()->set(array(
			'private_key' => paymentwall::testsecretKey
		));
		$cardInfo = array(
			'email' => $user->email,
			'amount' => $amount,
			'currency' => 'TRY',
			'token' => $_POST['brick_token'],
			'fingerprint' => $_POST['brick_fingerprint'],
			'description' => $product->productName,
			'lang' => $user->language,
			'uid' => $user->ID,
			'history[registration_date]' => DateTime::createFromFormat('Y-m-d H:i:s', $user->registerdate_)->getTimestamp(),
			'customer[birthday]' => DateTime::createFromFormat('Y-m-d', $user->birthDate)->getTimestamp(),
			'customer[sex]' => ($user->gender == 2) ? "female" : "male",
			'history[registration_name]'=> $nameArray[0],
			'history[registration_lastname]' => $nameArray[1]
			
		);
		
		if (isset($_POST['brick_charge_id']) AND isset($_POST['brick_secure_token'])) {
			$cardInfo['charge_id'] = $_POST['brick_charge_id'];
			$cardInfo['secure_token'] = $_POST['brick_secure_token'];
		}
		$charge = new Paymentwall_Charge();
		$charge->create($cardInfo);
		$responseData = json_decode($charge->getRawResponseData(),true);
		$response = $charge->getPublicData();
		if ($charge->isSuccessful() AND empty($responseData['secure'])) {
			$payments = new payments ();
			if ($charge->isCaptured()) {
				$payments->result=1;
				$user->balance= str_replace(",",".",($user->balance+$product->quantity));
				$user->save();
				
				$creBalance = new balance();
				$creBalance->actionID = 6;
				$creBalance->actiondate_ = new DateTime(date('Y/m/d H:i:s'));
				$creBalance->userID = $userID;
				$creBalance->point = $product->quantity;
				$creBalance->save();
				
			} elseif ($charge->isUnderReview()) {
			   echo 'on review';
			   $payments->result=0;
			}
			$payments->date_ = date ( "Y-m-d H:i:s" );
			$payments->userID = $userID;
			$payments->productID = $productID;
			$payments->currency = "TRY";
			$payments->method = "card";
			$payments->amount = $amount;
			$payments->referenceID= $charge->id;
			$payments->save ();
			
			if($payments->result == 1){
			//payment success page
			$fn = new functions ();
			$fn->redirect ( "/completepayment" );
			}
		} elseif (!empty($responseData['secure'])) {
			echo '<div id="3ds_form_container">' . $responseData['secure']['formHTML'] . '</div><script>document.getElementById("3ds_form_container").getElementsByTagName("form")[0].submit();</script>';
			//$response = json_encode(array('secure' => $responseData['secure']));
		} else {
			$errors = json_decode($response, true);
			echo $errors['error']['message'];
		}
		
		//paymentwall
		
		
		break;
	//pingback for the brick API
	/*case "pingback":
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/products.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";
		$runsql = new \data\DALProsess ();
		$userID = $_GET['uid'];
		$user = new users ( $userID );
	
		if(ip() == '174.36.92.186' OR ip() == '174.36.92.187' OR ip() == '174.36.92.192' OR ip() == '174.36.96.66' OR ip() == '174.37.14.28'){
			echo 'OK';
			if($_GET['type'] == 201){
				//payment accepted
				$payment= new payments();
				$paymentDetails= $payment->paymentQuery("SELECT productID,result FROM payments WHERE userID='".$_GET['uid']."' AND (referenceID ='".$_GET['ref']."' OR referenceID ='".substr($_GET['ref'], 1)."' )");
				$product = new products ($paymentDetails->productID);
				if($paymentDetails->result == 0){
					$user->balance= str_replace(",",".",($user->balance+$product->quantity));
					$user->save();
				}
				$sql = "UPDATE payments SET result='1' WHERE userID='".$_GET['uid']."' AND (referenceID ='".$_GET['ref']."' OR referenceID ='".substr($_GET['ref'], 1)."' )";
				$runsql->executenonquery ( $sql, NULL, true );
			}elseif($_GET['type'] == 202){
				//payment declined
				$sql = "UPDATE payments SET result='-1' WHERE userID='".$_GET['uid']."' AND (referenceID ='".$_GET['ref']."' OR referenceID ='".substr($_GET['ref'], 1)."' )";
				$runsql->executenonquery ( $sql, NULL, true );
			}
		}
		break;
	*/
	case "pingback":
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/products.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/Library/paymentwall.php";
		
		Paymentwall_Config::getInstance()->set(array(
			'api_type' => Paymentwall_Config::API_VC,
			'public_key' => paymentwall::projectKey,
			'private_key' => paymentwall::secretKey
		));
		$runsql = new \data\DALProsess ();
		$userID = $_GET['uid'];
		$user = new users ( $userID );
	
		if($_GET['type']==0 OR $_GET['type']==1){
			$typeOfPingback=1;
		}else{
			$typeOfPingback=-1;
		}
		$pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
		if ($pingback->validate()) {
			//$_GET['ref'] = $runsql->checkInjection($_GET['ref']);
			$virtualCurrency = $pingback->getVirtualCurrencyAmount();
			$sql = "SELECT 1 FROM payments WHERE referenceID='".$_GET['ref']."' AND result='".$typeOfPingback."'";
			$runsql->executenonquery ( $sql, NULL, false );
			if($runsql->recordCount == 0 ){
				$payments = new payments ();
				$payments->date_ = date ( "Y-m-d H:i:s" );
				$payments->userID = $userID;
				$payments->currency = "&";
				$payments->method = "widget";
				$payments->amount = $_GET['currency'];
				$payments->referenceID= $_GET['ref'];

				if ($pingback->isDeliverable()) {
				// deliver the virtual currency
				$payments->result=1;
				} else if ($pingback->isCancelable()) {
				// withdraw the virual currency
				$payments->result=-1;
				$payments->reason=$_GET['reason'];
				}
				$payments->save ();

				$user->balance= str_replace(",",".",($user->balance+$_GET['currency']));
				$user->save();		
				
				$creBalance = new balance();
				$creBalance->actionID = 6;
				$creBalance->actiondate_ = new DateTime(date('Y/m/d H:i:s'));
				$creBalance->userID = $userID;
				$creBalance->point = $_GET['currency'];
				$creBalance->save();
				
				echo 'OK'; 
		  }else{
			  echo 'DUPLICATE REFERENCE ID';
		  }
		} else {
		  echo $pingback->getErrorSummary();
		}


		break;
	case "deletePost" :
		
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
		
		$postDeleteID = (isset ( $_POST ["postID"] ) ? $_POST ["postID"] : "");
		$userID = $_SESSION ["userID"];
		
		$postD = new posts ( $postDeleteID );
		
		if ($postD->userID == $userID && $postD->isDeleted != 1) {
			
			$postD->delete ();
			
			$fn = new functions ();
			$fn->redirect ( "../myposts?delete=" . $postDeleteID );
		}
		
		$fn = new functions ();
		$fn->redirect ( "../myposts" );
		
		break;
	
	case "forgot" :
		
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		$email = isset ( $_POST ["usernameOrEmail"] ) ? $_POST ["usernameOrEmail"] : "";
		
		
		if(empty($_POST['getCaptcha'])) {

			echo $loc->label("Please verify that you are not a robot.");

		} else {
				
			$recaptcha = $_POST['getCaptcha'];
			$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lfj9iYTAAAAAOhK4Pqe0IzEb5fZONaiRe08VtcS&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
				
			if($response['success'] == false) {

				echo $loc->label("Sorry. We do not verify that you are not a robot. You cannot sign up.");

			} else {
				
				if(empty($email)) {
		
					echo $loc->label ( "Forgot Error" );
			
				} else {
		
					$user = users::checkUserWithoutPass ( $email );
					if ($user->ID > 0) {
						$emailCode = randomchars ();
			
						$runsql = new \data\DALProsess ();
						$email = $runsql->checkInjection($email);
						$sql = "UPDATE users SET email_code='$emailCode' WHERE email ='$email' or username ='$email'";
						$runsql->executenonquery ( $sql, NULL, true );
						// send mail
						$mail = new Mail ( $user->email, $user->fullName, mailServer::fromEmail, mailServer::fromName, $loc->label("Account Recovery"), "", NULL, NULL, 3, $user->ID  );
						$mail->sendMail ();
						echo "XxforgotokxX";
						echo $loc->label ( "Forgot Success" );
					} else {
						echo $loc->label ( "Forgot Error" );
					}
		
				}
				
			}
		
		}
		

		break;
	
	case "reset" :
		
		$pattern = '/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/';

		if(empty($_POST ["newpassword"])) {
				
			echo $loc->label("Enter your password please.");
			
				
		} else if(strlen($_POST ["newpassword"]) > 35) {

			echo $loc->label("Your password is too long.");

		} else if(strlen($_POST ["newpassword"]) < 8) {
				
			echo $loc->label("Your password is too short.");
			
				
		} else if( !(preg_match($pattern,$_POST ["newpassword"]))) {
				

			echo $loc->label("Your password must contain at least one uppercase letter one lower case letter and one number.");
			
				
		} else if(empty($_POST ["newpassword2"])) {
				
			echo $loc->label("Please retype your password.");
				
		} else if($_POST ["newpassword"] != $_POST ["newpassword2"]) {
	
			echo $loc->label("Your passwords does not match.");
		
		} else {
		
			$newPassword = password_hash($_POST ["newpassword"], PASSWORD_DEFAULT);
			require_once dirname ( dirname ( __FILE__ ) ) . "/DL/DAL.php";
			$runsql = new \data\DALProsess ();
			$sql = "UPDATE users SET password='$newPassword' WHERE email ='" . $_POST ["email"] . "' AND email_code= '" . $_POST ["code"] . "'";
			$sql2 = "UPDATE users SET email_code=NULL WHERE email ='" . $_POST ["email"] . "'";
			$runsql->executenonquery ( $sql, NULL, true );
			$runsql->executenonquery ( $sql2, NULL, true );
			echo "XxresetokZzxX";
			echo $loc->label ( "Reset Complete" );
		
		}
		
		break;
	
	case "settings" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		$userid = $_SESSION ['userID'];
		$user = new users ( $userid );
		$runsql = new \data\DALProsess ();
		switch ($_POST ['tab']) {
			case "account" :
				$email = $runsql->checkInjection($_POST ['email']);
				$userC = users::checkUserEmail ( $email );
				
			
				//Country Control
				$userCCont = new users($_SESSION["userID"]);
				if($userCCont->country == 306) {
					
				$language = $runsql->checkInjection($_POST ['language']);
				if (empty ( $_POST ['email'] ) && $_POST ['language'] == $user->language) {
					echo $loc->label ( "Please enter your email or change your language");
				} else if(empty ( $_POST ['email'] ) && $_POST ['language'] != $user->language) {
				
					if (($_POST ['language'] == 'tr' or $_POST ['language'] == 'en') and $_POST ['language'] != $user->language) {
						$sql2 = "UPDATE users SET language='$language' WHERE ID ='$userid'";
						$runsql->executenonquery ( $sql2, NULL, true );
						$_SESSION ["language"]= $_POST ['language'];
						echo $loc->label ("Your account language has been successfully changed!");
					}
					
				} else if(!empty ( $_POST ['email'] )) {
				if ($_POST ['email'] == $user->email) {
					echo $loc->label ( "You have entered your current email address");
				} else if($userC->ID > 0) {
					echo $loc->label("Your email address is used by another account.");
				} else if (!empty ( $_POST ['email'] ) && ! filter_var ( $_POST ['email'], FILTER_VALIDATE_EMAIL )) {
					echo $loc->label ("Invalid email format!");
				} else if(!empty ( $_POST ['email'] )) {
					
					$randomchars= randomchars();//e-mail onay kodu
					$sql = "UPDATE users SET pendingEmail='$email', email_code='$randomchars' WHERE ID ='$userid'";
					$runsql->executenonquery ( $sql, NULL, true );

					$mail = new Mail ( $_POST ["email"], $user->fullName, mailServer::fromEmail, mailServer::fromName, $loc->label ( "changeEmailSubject" ), "", NULL, NULL, 2, $_SESSION ["userID"] );
					$mail->sendMail();
					echo $loc->label ("Confirmation code is sent your new e-mail address. After you verify it,");
				}
				}
				
				} else {
					
				if (empty ( $_POST ['email'] )) {
					echo $loc->label ( "Please enter your new email");
				} else if(!empty ( $_POST ['email'] )) {
				if ($_POST ['email'] == $user->email) {
					echo $loc->label ( "You have entered your current email address");
				} else if($userC->ID > 0) {
					echo $loc->label("Your email address is used by another account.");
				} else if (!empty ( $_POST ['email'] ) && ! filter_var ( $_POST ['email'], FILTER_VALIDATE_EMAIL )) {
					echo $loc->label ("Invalid email format!");
				} else if(!empty ( $_POST ['email'] )) {
					
					$randomchars= randomchars();//e-mail onay kodu
					$sql = "UPDATE users SET pendingEmail='$email', email_code='$randomchars' WHERE ID ='$userid'";
					$runsql->executenonquery ( $sql, NULL, true );

					$mail = new Mail ( $_POST ["email"], $user->fullName, mailServer::fromEmail, mailServer::fromName, $loc->label ( "changeEmailSubject" ), "", NULL, NULL, 2, $_SESSION ["userID"] );
					$mail->sendMail();
					echo $loc->label ("Confirmation code is sent your new e-mail address. After you verify it,");
				}
				}
					
					
				}  
				
				break;   
			
			case "personal" :
				
			if (strcspn($_POST ['fullName'], '0123456789') != strlen($_POST ['fullName']) || preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_POST ['fullName'])) {
		
				echo $loc->label ("Only letters and white space allowed");
	
			} else if(strlen($_POST ['fullName']) > 30) {
			
				echo $loc->label ("Your name is too long.");
				
			} else if(strlen($_POST ["fullName"]) < 3) {
				
				echo $loc->label ("Your name is too short.");
				
			} else if(strlen(trim($_POST ['fullName'])) == 0) {
			
				echo $loc->label ("Your name cannot be blank.");
			
			} else {
				
				$fullName = $runsql->checkInjection($_POST ['fullName']);
				$gender = $runsql->checkInjection($_POST ['gender']);
				$bio = $runsql->checkInjection($_POST ['bio']); 
				$year = $runsql->checkInjection($_POST ['year']);
				$month = $runsql->checkInjection($_POST ['month']);
				$day = $runsql->checkInjection($_POST ['day']);
				$birthdate = date('Y-m-d', strtotime($year .'-' . $month . '-' . $day));
					
				$sql = "UPDATE users SET fullname='$fullName', gender='$gender', birthdate='$birthdate', about='$bio' WHERE ID ='$userid'";
				$runsql->executenonquery ( $sql, NULL, true );
				$_SESSION["fullName"] = $fullName;
				echo $loc->label ("Your personal informations has been successfully changed!");
				
			}
				
				break;
			
			case "password" :
				$pattern = '/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/';
				if ($_POST ['password'] == "" or $_POST ['newPassword'] == "" or $_POST ['retype'] == "") {
					echo $loc->label("Fill All Fields");
				} elseif ($_POST ['newPassword'] != $_POST ['retype']) {
					echo $loc->label("Values of New Password and Retype fields do not match!");
				} else if(strlen($_POST ['newPassword']) > 35) {

					echo $loc->label("Your password is too long.");

				} else if(strlen($_POST ['newPassword']) < 8) {
				
					echo $loc->label("Your password is too short.");
			
				
				} else if( !(preg_match($pattern,$_POST ['newPassword']))) {
				

					echo $loc->label("Your password must contain at least one uppercase letter one lower case letter and one number.");
			
				
				} elseif (!password_verify($_POST ['password'], $user->password)) {
					echo $loc->label("Your current password does not match with our records");
				} else {
					$_POST ['newPassword'] = $runsql->checkInjection($_POST ['newPassword']);
					$newpassword = password_hash($_POST ['newPassword'], PASSWORD_DEFAULT);
					$sql = "UPDATE users SET password='$newpassword' WHERE ID ='$userid'";
					$runsql->executenonquery ( $sql, NULL, true );
					echo $loc->label("Your password has been successfully changed!");;
				}
				break;
			case "image" :
				$_POST ['picture'] = $runsql->checkInjection($_POST ['picture']);
				//$_FILES ['image'] = $runsql->checkInjection($_FILES ['image']);
				
				if($_POST["picture"] == "profile") {
					
					$name = "picture";  
					$targetFile = "userImg";
					$prefix = "xxxProfile";
					$size = 500000;
					$width = 160;
					$height = 160;
					
				} else if($_POST["picture"] == "cover") {
					
					$name = "coverPicture";
					$targetFile = "userCoverImg";
					$prefix = "xxxCover";
					$size = 1000000;
					$width = 1920;
					$height = 500;
					
				} else {
					
					echo $loc->label("Sorry, there was an error.");
					break;
					
				}
				
				do {
					$randImg = rand ( 1000000, 9999999 );
					$sql = "SELECT 1 FROM users WHERE ".$name."='$randImg.*'";
					$runsql->executenonquery ( $sql, NULL, false );
				} while ( $runsql->recordCount != 0 );
				$showError = "";
				$target_dir = "../Uploads/" . $targetFile . "/";
				$imageFileType = pathinfo ( $_FILES ["image"] ["name"], PATHINFO_EXTENSION );
				$target_file = $target_dir . $prefix . $randImg . "." . $imageFileType;
				$uploadOk = 1;
				
				// Check if image file is a actual image or fake image
				if (isset ( $_POST ["submit"] )) {
					$check = getimagesize ( $_FILES ["image"] ["tmp_name"] );
					if ($check !== false) {
						$uploadOk = 1;
					} else {
						$showError .= $loc->label("File is not an image.");
						$uploadOk = 0;
					}
				}
				// Check file size
				if ($_FILES ["image"] ["size"] > $size) {
					$showError .= $loc->label("Sorry, your file is too large.");
					$uploadOk = 0;
				}
				// Allow certain file formats
				if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF") {
					$showError .= $loc->label("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
					$uploadOk = 0;
				}
				// Check GIF for Cover
				if ($imageFileType == "gif" && $imageFileType == "GIF" && $name == "coverPicture") {
					$showError .= $loc->label("Sorry, there was an error.");
					$uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					// if everything is ok, try to upload file
				} else {
					if (move_uploaded_file ( $_FILES ["image"] ["tmp_name"], $target_file )) {
						if (true !== ($pic_error = @image_resize ( $target_file, $target_dir . $prefix . $randImg . "." . $imageFileType, $width, $height))) {
							unlink ( $target_file );
							$showError .= $loc->label("Sorry, there was an error.");
						} else {  
							
							if($name == "picture") {
								if ($user->picture != NULL or !empty ( $user->picture )) {
									unlink ( $target_dir . $user->picture );
								}
							} else if($name == "coverPicture") {
								if ($user->coverPicture != NULL or !empty ( $user->coverPicture )) {
									unlink ( $target_dir . $user->coverPicture );
								} 
							}
								
								
							$sql2 = "UPDATE users SET ".$name."='" . $prefix . $randImg . "." . $imageFileType. "' WHERE ID ='$userid'";
							$runsql->executenonquery ( $sql2, NULL, true );
						}
						// upload OK
					} else {
						$showError .= $loc->label("Sorry, there was an error.");
					}
				}  
				if (! empty ( $showError )) {
					echo $showError;
				} else {
					echo 'ok';
				}
				break;
			
			case "social" :
				
				require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/userSocials.php";
		
				$deleteFacebook = (isset ( $_POST ["deleteFacebook"] ) ? $_POST ["deleteFacebook"] : "");
				$deleteTwitter = (isset ( $_POST ["deleteTwitter"] ) ? $_POST ["deleteTwitter"] : "");
				$deleteYoutube = (isset ( $_POST ["deleteYoutube"] ) ? $_POST ["deleteYoutube"] : "");
				
				if($_POST['whichone'] == 1){
					$deleteID = $deleteFacebook;
				}elseif($_POST['whichone'] == 2){
					$deleteID = $deleteTwitter;
				}elseif($_POST['whichone'] == 4){
					$deleteID = $deleteYoutube;
				} else {
					
					echo $loc->label("There is a problem, your social account could not be deleted.");
					break;
					
				}

				
				
				$userID = $_SESSION ["userID"];
		
				$social = new userSocials ( $deleteID );
		
				if ($social->userID == $userID && $social->isDeleted != 1) {
			
					$social->delete ();
			
					echo $loc->label("Your social account has been deleted.");
					
				} else {
					 
					echo $loc->label("There is a problem, your social account could not be deleted.");
					
				}
		
				break;
				
			case "newAddress" :
				
				require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/address.php"; 
				
				$addressCont = new address();
				$result = $addressCont->getAddressCount($userid);
				
				$x = 0;
				while ($row=mysqli_fetch_array($result)) { $x++; }
				
				if($x >= 4) {   
				
					echo $loc->label("You can add maximum 5 addresses.");
					
				} else if(empty($_POST ["recipientName"])) {
					
					echo $loc->label("Please write recipient name");
					
				} else if (strcspn($_POST ['recipientName'], '0123456789') != strlen($_POST ['recipientName']) || preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_POST ['recipientName'])) {
		
					echo $loc->label("Only letters and white space allowed"); 
	
				} else if(strlen($_POST ['recipientName']) > 250) {
			
					echo $loc->label("Your name is too long."); 
				
				} else if(strlen(trim($_POST ['recipientName'])) == 0) {
			
					echo $loc->label("Your name cannot be blank.");
		
				} else if(empty($_POST ["countryCode"])) {
					
					echo $loc->label("Please select your phone country code");
					
				} else if(empty($_POST ["phone"])) {
					
					echo $loc->label("Please enter your phone number");
					
				} else if(strlen($_POST ['phone']) > 20) {
			
					echo $loc->label("Your phone number is too long");
				
				} else if(preg_match('/^\+?\d+$/', $_POST ["phone"])) {
					
					echo $loc->label("Phone number invalid");
					
				} else if(empty($_POST ["addressline1"])) {
					
					echo $loc->label("Please write your addres");
					
				} else if(strlen($_POST ['addressline1']) > 250) {
			
					echo $loc->label("Your Address Line 1 is too long"); 
				
				} else if(strlen($_POST ['addressline1']) < 30) {
			
					echo $loc->label("Your Address Line 1 is too short"); 
				
				} else if(strlen($_POST ['addressline2']) > 250) {
			
					echo $loc->label("Address Line 2 is too long");
				
				}  else if(empty($_POST ["region"])) {
					
					echo $loc->label("Please write region");
					
				} else if(strlen($_POST ['region']) > 250) {
			
					echo $loc->label("Region is too long");
				
				} else if (preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_POST ['region'])) {
		
					echo $loc->label("Only letters, white space and numbers allowed for region"); 
	
				}  else if(empty($_POST ["city"])) {
					
					echo $loc->label("Please write city");
					
				} else if(strlen($_POST ['city']) > 250) {
			
					echo $loc->label("City is too long"); 
				
				} else if (preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_POST ['city'])) {
		
					echo $loc->label("Only letters, white space and numbers allowed for city");
	
				} else if(strlen($_POST ['postalCode']) > 50) {
			
					echo $loc->label("Postal code is too long"); 
				
				} else if(empty($_POST ["countryAddress"])) {
					
					echo $loc->label("Please select your country");
					
				} else {
					
					$recipient = $_POST ["recipientName"];
					$phone = $_POST ["phone"];
					$addressline1 = $_POST ["addressline1"];
					$addressline2 = $_POST ["addressline2"];
					$city = $_POST ["city"];
					$region = $_POST ["region"];
					$country = $_POST ["countryAddress"];
					
					$phone = str_replace(array( '(', ')' ), '', $phone);
					$phone = preg_replace('/\s+/', '', $phone);
					$phone = "+" . $_POST ["countryCode"] . $phone;  
					
					$address = new address();
					
					$address->userID = $userid;
					$address->recipientName = $recipient;
					$address->phone = $phone;
					$address->addressLine1 = $addressline1;
					$address->addressLine2 = $addressline2;
					$address->city = $city;
					$address->region = $region;
					$address->country = $country;
					$address->save();
					
					echo $loc->label("Your address has been successfully changed");
					
				}
				
			
				
				break;
				
			
			case "interest" :

				$ctr = 0;
				$categories = '';
				foreach ( $_POST ['interests'] as $interest ) {
					$ctr ++;
					$categories .= $interest;
					if (count ( $_POST ['interests'] ) != $ctr) {
						$categories .= ',';
					}
				}
				$categories = $runsql->checkInjection($categories);
				$sql = "UPDATE users SET categoryID='$categories' WHERE ID ='$userid'";
				$runsql->executenonquery ( $sql, NULL, true );
				echo "ok";
	

				break;
		
		
		}
		break;
		
				

	case "signupStep" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		$userid = $_SESSION ['userID'];
		$user = new users ( $userid );
		$runsql = new \data\DALProsess ();
		switch ($_POST ['step']) {
			case 0 :
				$_POST ['code'] = preg_replace('/\s+/', '', $_POST ['code']); 
				if ($_POST ['code'] == $user->email_code) {
					
					$userIn = new users();
					$result = $userIn->getUserIneffEmailAct( $user->email, $userid );
					
					while($row=mysqli_fetch_array($result)) { 
					
						$userDelete = new users($row["ID"]);
						$userDelete->delete();
				
					}
					
					$sql = "UPDATE users SET email_code= NULL, signupStep=1 WHERE ID ='$userid'";
					$runsql->executenonquery ( $sql, NULL, true );
					echo 'activitionXXxzzxoksssX';
				} else if($_POST ['code'] == "") {
					echo $loc->label("Please enter your code.");
				} else if($_POST ['code'] != $user->email_code) {
					echo $loc->label("The code youve entered isnt correct.");
					
				}
				break;
			
			case 1 :
				if (empty ( $_POST ['gender'] )) {
					
					echo $loc->label("Select your gender please.");
					
				} else if(empty ( $_POST ['year'] )) {
					
					echo $loc->label("Select your year for birthday please.");
					
				} else if(empty ( $_POST ['month'] )) {
					
					echo $loc->label("Select your month for birthday please.");
					
				} else if(empty ( $_POST ['day'] )) {
					
					echo $loc->label("Select your day for birthday please.");
					
				} else {
					
					$gender = ($_POST ['gender'] == 'female') ? 2 : 77;
					$year = $runsql->checkInjection($_POST ['year']);
					$month = $runsql->checkInjection($_POST ['month']);
					$day = $runsql->checkInjection($_POST ['day']);
					$birthDay = date('Y-m-d', strtotime($year .'-' . $month . '-' . $day));
					$sql = "UPDATE users SET gender= '$gender', birthDate='$birthDay', signupStep=2 WHERE ID ='$userid'";
					$runsql->executenonquery ( $sql, NULL, true );
					echo 'ok';
				}
				break;
			
			case 2 :
				$username = mb_strtolower($_POST ['username'],'UTF-8');
				if (! empty ( $username )) {
					
					if(preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $username)) { 
					
						echo $loc->label("Username cannot contain special characters.");
						
					} else if(strlen(trim($username)) == 0) {
			
						echo $loc->label("Username cannot be blank.");  
			
					}  else if(preg_match('/\s/', $username)) {   

						echo $loc->label("Username cannot contain special characters.");
	
					} else if(strlen($username) > 30) {
				
						echo $loc->label("Your username too long.");
				
					} else {  
						
						$username = $runsql->checkInjection($username);
						$sql = "SELECT 1 FROM users WHERE username='$username'";
						$runsql->executenonquery ( $sql, NULL, false );
						if ($runsql->recordCount != 0) {
							echo $loc->label("Username youve have given is in use. Please try another one.");
						} else {
							echo 'ok';
							$sql3 = "UPDATE users SET username= '$username', signupStep= 3 WHERE ID ='$userid'";
							$runsql->executenonquery ( $sql3, NULL, true );
						}
					
					}
				} else {
					echo 'ok';
					$sql2 = "UPDATE users SET signupStep=3 WHERE ID ='$userid'";
					$runsql->executenonquery ( $sql2, NULL, true );
				}
				break;
			
			case 3 :
				$ctr = 0;
				$categories = '';
				foreach ( $_POST ['interests'] as $interest ) {
					$ctr ++;
					$categories .= $interest;
					if (count ( $_POST ['interests'] ) != $ctr) {
						$categories .= ',';
					}
				}
				$categories = $runsql->checkInjection($categories);
				$sql = "UPDATE users SET categoryID='$categories', signupStep=4 WHERE ID ='$userid'";
				$runsql->executenonquery ( $sql, NULL, true );
				break;
			
			case 4 :
				$sql = "UPDATE users SET signupStep=5 WHERE ID ='$userid'";
				$runsql->executenonquery ( $sql, NULL, true );
				break;
				
			case 5 :
				$sql = "UPDATE users SET signupStep=-1 WHERE ID ='$userid'";
				$runsql->executenonquery ( $sql, NULL, true );
				break;
		}
		break;
		
	case "sendConfirmCode" :
		
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		
		if(empty($_POST['getCaptcha'])) {

			echo $loc->label("Please verify that you are not a robot.");

		} else {
				
			$recaptcha = $_POST['getCaptcha'];
			$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lc-hSYTAAAAAOLfCY7hImvEGzdUDe1TsD8vW0nn&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
				
			if($response['success'] == false) {

				echo $loc->label("Sorry. We do not verify that you are not a robot. You cannot sign up.");

			} else {
				
				$userid = (isset ( $_POST ["userID"] ) ? $_POST ["userID"] : "");
				
				$user = new users ( $userid );
		
				$mail = new Mail ( $user->email, $user->fullName, mailServer::fromEmail, mailServer::fromName, $loc->label ( "signupSubject" ), "", NULL, NULL, 1, $user->ID );
				$mail->sendMail();
		
				echo $loc->label("SentActivationCode");
				
			}
				
		}
		
		
		
		break;
		
	case "changeLang" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		if(isset($_SESSION ['userID'])){
			$userid = $_SESSION ['userID'];
			$user = new users ( $userid );
			$runsql = new \data\DALProsess ();
			$_GET['lang'] = $runsql->checkInjection($_GET['lang']);
			if(($_GET['lang'] == 'tr' OR $_GET['lang'] == 'en') AND $_GET['lang'] != $user->language){
				$sql = "UPDATE users SET language='".$_GET['lang']."' WHERE ID ='$userid'";
				$runsql->executenonquery ( $sql, NULL, true );
				$_SESSION['language']= $_GET['lang'];
			}
		}else{
			if($_GET['lang'] == 'tr' OR $_GET['lang'] == 'en'){
				$_SESSION['language']= $_GET['lang'];
				setcookie("language", $_GET['lang'], time() + (10 * 365 * 24 * 60 * 60),"/",'funnyandmoney.com',true,true); 
			}
		}
		
		if(!isset($_GET['go'])) {
			$_GET['go'] = "/";
		}
		
		$fn = new functions ();
		$fn->redirect ($_GET['go']);
		break;
		
		
	case "cashinfo" :
			
				require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
				$first = (isset ( $_POST ["firstname"] ) ? $_POST ["firstname"] : "");
				$last = (isset ( $_POST ["lastname"] ) ? $_POST ["lastname"] : "");
				$iban = (isset ( $_POST ["iban"] ) ? $_POST ["iban"] : "");
				
				if(empty($first)) {
					
					echo $loc->label("Account First Name field cannot be empty!");
		
					
				} elseif(strlen($first) > 100) {
					
					echo $loc->label("Account Your first name is too long");
			
					
				} elseif (strcspn($first, '0123456789') != strlen($first) || preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $first)) {
					
					echo $loc->label("Account Invalid First Name");
			
					
				} elseif(empty($last)) {
					
					echo $loc->label("Account Last Name field cannot be empty!");
			
					
				} elseif(strlen($last) > 100) {
					
					echo $loc->label("Account Your last name is too long");

						
				} elseif (strcspn($last, '0123456789') != strlen($last) || preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $last)) {
				
					echo $loc->label("Account Invalid Last Name");
	
					
				} elseif(empty($iban)) {
					
					echo $loc->label("Account IBAN cannot be empty!");
		
					
				} elseif(strlen($iban) > 34 || strlen($iban) < 12) {
				
					echo $loc->label("Account Invalid IBAN");
			
				
				} elseif(!ctype_alpha(substr($iban, 0, 2))) {
					
					echo $loc->label("Account Invalid IBAN");

					
				} elseif(!is_numeric(substr($iban, 2, 34)) || preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $first)) {
					
					echo $loc->label("Account Invalid IBAN");

					
				} else {
					
					$userID = $_SESSION ["userID"];
					
					$iban = substr_replace(substr($iban, 0, 2),strtoupper(substr($iban, 0, 2)),0) . substr($iban, 2, 34);
					
					$user = new users($userID);
					$user->bankFirstName = $first;
					$user->bankLastName = $last;
					$user->IBAN = $iban;
					$user->save();
					
					echo $loc->label("Successfully, you added a Bank Information");
					
				}

		break;
		
		
	case "changeEmail" :
			
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		$runsql = new \data\DALProsess ();
		$userid = $_SESSION ['userID'];
		$user = new users ( $userid );
		
		if($user->pendingEmail != NULL && $user->pendingEmail !="") {
			
			$userIn = new users();
			$result = $userIn->getUserIneffEmail( $user->pendingEmail );  
			$_POST ['emailcode'] = preg_replace('/\s+/', '', $_POST ['emailcode']);
				
			if ($_POST ['emailcode'] == $user->email_code) {
				
				while($row=mysqli_fetch_array($result)) { 
					
					$userDelete = new users($row["ID"]);
					$userDelete->delete();
				
				}
				
				$userNewEmail = $user->pendingEmail;
				$sql = "UPDATE users SET email_code= NULL, email= '$userNewEmail', pendingEmail= NULL WHERE ID ='$userid'";
				$runsql->executenonquery ( $sql, NULL, true );
				echo $loc->label("Your e-mail successfully changed!");
			} else if($_POST ['emailcode'] == "") {
				echo $loc->label("Please enter your code.");
			} else if($_POST ['emailcode'] != $user->email_code) {
				echo $loc->label("The code youve entered isnt correct.");
					
			}
				
		}
				
				
		break;
		
	case "cancelPendingEmail" :
			
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		$runsql = new \data\DALProsess ();
		$userid = $_SESSION ['userID'];
		$user = new users ( $userid );
			
		$sql = "UPDATE users SET pendingEmail=NULL, email_code=NULL WHERE ID ='$userid'";
		$runsql->executenonquery ( $sql, NULL, true );
			
		echo $loc->label("Cancelled your e-mail change!");
		
		break;
		
	case "sendCodePendingEmail" :
			
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		
		if(empty($_POST['getCaptcha'])) {

			echo $loc->label("Please verify that you are not a robot.");

		} else {
				
			$recaptcha = $_POST['getCaptcha'];
			$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeliiYTAAAAAK7tLlBgSKDvKWYK4V8sRksTLRS5&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
				
			if($response['success'] == false) {

				echo $loc->label("Sorry. We do not verify that you are not a robot. You cannot sign up.");

			} else {
				
				$userid = $_SESSION ['userID'];
				$user = new users ( $userid );
			
				$mail = new Mail ( $user->pendingEmail, $user->fullName, mailServer::fromEmail, mailServer::fromName, $loc->label ( "changeEmailSubject" ), "", NULL, NULL, 2, $_SESSION ["userID"] );
				$mail->sendMail();
		
				echo $loc->label("Your e-mail code sent again!"); 
				
			}
				
		}
			
		break;		
		
	case "getGift" :
		
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/gifts.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/giftRequests.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/digitalGiftCodes.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/definitions.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/address.php";
		
		$userid = $_SESSION ['userID'];
		$user = new users ( $userid );
		$giftID = (isset ( $_POST ["giftID"] ) ? $_POST ["giftID"] : "");
		$addressID = (isset ( $_POST ["addressID"] ) ? $_POST ["addressID"] : "");
		
		$gift = new gifts($giftID);
	
		if($gift->quantity == 0) {
			
			echo $loc->label("This product is out of stock.");
			
		} else if($user->balance < $gift->price) {
			
			echo $loc->label("Not enough &s");
			
		} else if($gift->isDigital != 1 && $gift->deliverySpeed != 2 && $gift->deliverySpeed != 0 && $addressID == "") {
			
			$address = new address();
			$resultAddress = $address->getAddresses($userid);
			$x = 0;
			
			echo "XXsqwq-_!343**3434sdXxADs";
			
			echo '<div class="modal fade bs-modalAddress" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">' . $loc->label("Close") . '</span></button>
								<h4 class="modal-title" style="text-align: center;">' . $gift->name . '</h4>
							</div>
							<div class="modal-body">    
							
							
							<center><p>'. $loc->label("Select a address or add new one.") .'</p></center> 
							
							<div class="panel-group" id="accordion">  
							
							';

							
					while ($row=mysqli_fetch_array($resultAddress)) { 
						
						$x++;
			
						$definitionF = new definitions($row['country']);
				
							
							echo '<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
									<div class="row">
									
										<div class="col-lg-10 col-md-8 col-sm-6 col-xs-6"> 
										<a style="height: 40px;" href="#collapse_' . $row['ID'] . '" data-toggle="collapse" data-parent="#accordion">

										' . $row['recipientName'] . ' 

										</a>  
										</div>
										
										<div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">  
										<a href="javascript: getTheGift('. $gift->ID .',' . $row['ID'] . ',0);">
										<button style="padding-left: 10px; float: right;" type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i></button>
										</a> 
										</div>
	
									</div>
									</h4>
								</div>
								<div id="collapse_' . $row['ID'] . '" class="panel-collapse collapse">
									<div class="panel-body">
									
									
									' . $row['addressLine1'] . '  
															'; if($row['addressLine2'] != "") {
															echo '<br/>';
															} echo'
															' . $row['addressLine2'] . '
															<br/>
															' . $row['region'] . '
															<br/>
															' . $row['city'] . '
															<br/>
															' . evalLoc( $definitionF->definition ). '
															<br/>
															+' . $row['phone']. '
									
									
									</div>
								</div>
							</div>'; 
					
					} 
					
					if($x <= 4) {
					
					echo '
				    
							<div class="panel panel-default">
							<div class="panel-heading">
									<h4 class="panel-title">
										<a href="settings?tab=address">

										<i class="fa fa-plus"></i>' . $loc->label("Add new address") . '  

										</a>
									</h4>
								</div>
								
							</div>';
						
					}
							
					echo '
							
							
						</div>
							
					

							</div>
							<div class="modal-footer">

									<button id="closeA" type="button" class="btn btn-warning" data-dismiss="modal">' . $loc->label("Close") . '</button>
								
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>';

		} else {
			
			$user->balance = str_replace(",",".",($user->balance - $gift->price));
			$user->save();
			
			$giftRequest = new giftRequests();
			$giftRequest->giftID = $giftID;
			$giftRequest->userID = $userid;
			$giftRequest->date_ = date ( "Y-m-d H:i:s" );
			$giftRequest->price = $gift->price;
			
			if($gift->isDigital == 1) {
			
				if($gift->deliverySpeed == 0) {
					
					$digitalGiftCode = new digitalGiftCodes ();
					$getGiftCode = mysqli_fetch_array ( $digitalGiftCode->getGiftsCode($giftID) );
				
					if (mysqli_num_rows( $digitalGiftCode->getGiftsCode($giftID) ) == 0) {
					
						echo $loc->label("This product is out of stock. Please wait");
						
					} else {
					
						$giftRequest->addressID = "";
						$giftRequest->deliveryStatus = 0;
						$result = $giftRequest->save();
					
						if(is_null($gift->numberOfSales)) {$gift->numberOfSales = 0;}
						$gift->numberOfSales = $gift->numberOfSales + 1;
						$gift->save();

						$giftCode = $getGiftCode ["giftCode"];
						$description = $getGiftCode ["descriptionText"];
				
						$usedGift = new digitalGiftCodes ($getGiftCode ["ID"]);
						$usedGift->userID = $userid;
						$usedGift->giftRequestID = $result;
						$usedGift->isUsed = 1;
						$resultD = $usedGift->save();
				
						$giftRequestT = new giftRequests($result);
						$text = "
							<div class='row'>
							<div class='col-lg-9'>
							<div class='panel panel-default'>
							<div class='panel-body' style='padding: 15px;'>

							<table class='table'> 
									<thead>
										<tr>
											<th><h3>".$loc->label("Order Details")."</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>" . $loc->label("Order Number") . "</th>
											<td><b>" . $giftRequestT->orderNo . "</b></td>
										</tr>
										<tr>
											<th>" . $loc->label("Product Name") . "</th>
											<td>" . $gift->name . "</td>
										</tr>
										<tr>
											<th>" . $loc->label("Product Code") . "</th>
											<td><h3>" . $giftCode . "</h3></td>
										</tr>
										<tr>
											<th>" . $loc->label("Description") . "</th>
											<td>" . $description . "</td>
										</tr>
									</tbody>    
							</table>
							</div>
							</div>
							</div>
							</div>
						
						";
				
					
						if($result > 0 && $resultD > 0){
						
							$mail = new Mail ( $user->email,$user->fullName, 'shop@funnyandmoney.com', 'Funny&Money Shop', $loc->label("newGiftMailTitle")  . " " . $gift->name, '', NULL, NULL, 4,  $user->ID, $loc->label("newGiftMailTextDigital"), $result);
							$mail->sendMail();
					
							echo "okGiftxX232";
					
						}
					
						echo $text;
						
					}
				
				} else if($gift->deliverySpeed == 2) {
					
					$giftRequest->addressID = "";
					$giftRequest->deliveryStatus = 2;
					$result = $giftRequest->save();
					
					if(is_null($gift->numberOfSales)) {$gift->numberOfSales = 0;}
					$gift->numberOfSales = $gift->numberOfSales + 1;
					$gift->save();

				
					$giftRequestT = new giftRequests($result);				
					$text = "
							<div class='row'>
							<div class='col-lg-9'>
							<div class='panel panel-default'>
							<div class='panel-body' style='padding: 15px;'>

							<table class='table'> 
									<thead>
										<tr>
											<th><h3>".$loc->label("Order Details")."</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>" . $loc->label("Order Number") . "</th>
											<td><b>" . $giftRequestT->orderNo . "</b></td>
										</tr>
										<tr>
											<th>" . $loc->label("Product Name") . "</th>
											<td>" . $gift->name . "</td>
										</tr>	
										<tr>
											<th>" . $loc->label("Description") . "</th>
											<td>" . $loc->label("emailDeliveyProductDescription") . "</td>
										</tr>
									</tbody>    
							</table>
							</div>
							</div>
							</div>
							</div>
						
						";
				
					if($result > 0){
						
						$mail = new Mail ( $user->email,$user->fullName, 'shop@funnyandmoney.com', 'Funny&Money Shop', $loc->label("newGiftMailTitle")  . " " . $gift->name, '', NULL, NULL, 4,  $user->ID, $loc->label("newGiftMailTextDigitalMailD"), $result);
						$mail->sendMail();
					
						echo "okGiftxX232";
					
					}
					
					echo $text;

				}
			
			
			} else {
				
				$address = new address($addressID);
				
			if($address->userID == $userid) {

				$definition = new definitions($address->country);
				
				$giftRequest->addressID = $addressID;
				$giftRequest->deliveryStatus = 2;
				$result = $giftRequest->save();
				
				if(is_null($gift->numberOfSales)) {$gift->numberOfSales = 0;}
				$gift->numberOfSales = $gift->numberOfSales + 1;
				$gift->save();
				
				$giftRequestT = new giftRequests($result);
				$text = "
							<div class='row'>
							<div class='col-lg-9'>
							<div class='panel panel-default'>
							<div class='panel-body' style='padding: 15px;'>

							<table class='table'> 
									<thead>
										<tr>
											<th><h3>".$loc->label("Order Details")."</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>" . $loc->label("Order Number") . "</th>
											<td><b>" . $giftRequestT->orderNo . "</b></td>
										</tr>
										<tr>
											<th>" . $loc->label("Product Name") . "</th>
											<td>" . $gift->name . "</td>
										</tr>
										<tr>
											<th>" . $loc->label("Description") . "</th>
											<td>" . $loc->label("cargoProductDescription") . "</td>
										</tr>
										<tr>
											<th>" . $loc->label("Your Address Deatails") . "</th>
											<td>" . $address->addressLine1 . "<br/>" . $address->addressLine2 . "<br/>" . $address->region . "<br/>" . $address->city . "<br/>" . evalLoc( $definition->definition ) . "</td>
										</tr>
										
									</tbody>     
							</table>
							</div>
							</div>
							</div>
							</div>
							
							
							<div class='row' style='margin-top: 15px;'><div class='col-lg-4' style='margin-bottom: 30px;'>
						<div class='panel panel-danger'>
							<div class='panel-heading'>
								<h3 class='panel-title'><i style='font-size: 15px; margin-right: 5px;' class='glyphicon glyphicon-warning-sign'></i>". $loc->label("Warning") ."</h3>
							</div>
							<div class='panel-body' style='padding: 10px 20px;'> 
								". $loc->label("We refuse responsibility in case of incorret address details") ."
							</div>
						</div>
					</div> </div>
						
						";
				
				
				if($result > 0){
					
					$mail = new Mail ( $user->email,$user->fullName, 'shop@funnyandmoney.com', 'Funny&Money Shop', $loc->label("newGiftMailTitle")  . " " . $gift->name, '', NULL, NULL, 4,  $user->ID, $loc->label("newGiftMailText"), $result);
					$mail->sendMail();  
					
					echo "okGiftxX232";
					
				}
				
					
				
				echo $text;
				 
			} else {
				
				echo "Fatal Error: Addresses don't match!";
				
			}
	
	
			}
			
				
				
		}
		
		
		break;
		
	case "deleteAddress" :
		
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/address.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/giftRequests.php";
		
		
		$deleteAddress = (isset ( $_POST ["deleteID"] ) ? $_POST ["deleteID"] : "");
		$userID = $_SESSION ["userID"];
		


		$address = new address ( $deleteAddress );
		
		$contDeliveryA = new giftRequests();
		$result = $contDeliveryA->contDelivery($userID,$deleteAddress);

		$x = 0;
		while ($row=mysqli_fetch_array($result)) { $x++; }
		
		if ($address->userID == $userID && $address->isDeleted != 1 && $x <= 0) {
			
			
			$address->delete ();  

			echo $loc->label("Your address have been successfully deleted");
			
		} else {
			
			echo $loc->label("There is a problem. Your address have not been deleted.");  
			
		}
		
		break;
		
	case "onReference" :
		
			require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
			$userID = $_SESSION ["userID"];
			
			$user = new users($userID);
			$user->referrerON = 1;
			$user->save();
			
			$fn = new functions ();
			$fn->redirect ( "/reference" );  
			
		break;
		
	case "offReference" :
		
			require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
			$userID = $_SESSION ["userID"];
			
			$user = new users($userID);
			$user->referrerON = 0;
			$user->save();
			
			$runsql = new \data\DALProsess (); 
			$sql = "UPDATE users SET referrerID=NULL WHERE referrerID ='$userID'";
			$runsql->executenonquery ( $sql, NULL, true );
			
			$fn = new functions ();
			$fn->redirect ( "/reference" );  
			
		break;
		
	case "refEarn" :
		
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";
		
		$userID = $_SESSION ["userID"];
		
		$balance = new balance();
		$result = $balance->getRefEarn($userID);

		$row=mysqli_fetch_row($result);
		
		$total = $row[0];
		
		echo (intval(($total*100))/100);  
		
		break;
		
	case "refCount" :
		
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		
		$userID = $_SESSION ["userID"];
		
		$user = new users();
		$result = $user->getRefCount($userID);

		$row=mysqli_fetch_row($result);
		
		$total = $row[0];
		
		echo $total;
		
		break;
		
		
	case "admin" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/admins.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/adminHistory.php";
		$userid = $_SESSION ['userID'];
		$adminid = $_SESSION ['adminID'];
		$user = new users ( $userid );
		$admin = new admins ( $_SESSION['adminID'] );
		$runsql = new \data\DALProsess ();  
		
		$adminC = admins::checkAdmin ( $userid );
		$adminA = admins::checkRank ( $userid, "A");
		$adminSM = admins::checkRank ( $userid, "SM");
		$adminM = admins::checkRank ( $userid, "M");
		if ($adminC->ID > 0 && isset($_SESSION['adminID'])) {
			
			switch ((isset ( $_POST ['tab'] ) ? $_POST ['tab'] : "")) {
				
				case "dashboard" :  
					
					$admin->adminNotes = htmlspecialchars($_POST ['adminNotes']);  
					$result = $admin->save();
					
					if($result > 0) {  

						header("Location: https://www.funnyandmoney.com/admin?message=success");
						exit();
		
		
					} else {

						header("Location: https://www.funnyandmoney.com/admin?message=fail");
						exit();
			
					}
					
					
					break;  
			
				case "editPost" :
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
					$postID = $_POST ['postID'];
					
					$post = new posts($postID);
					$post->userID = $_POST ['puserID'];
					$post->platformID = $_POST ['platformID'];
					$post->lastEdited = date('Y-m-d H:i:s'); 
				
					if($adminA->ID > 0 or $adminSM->ID > 0) {
					
						$post->positionID = $_POST ["position"];  
						
					}
					
					$post->categoryID = $_POST["category"]; 
					$post->videoDuration = $_POST["video"];    
								
								
					if(!empty($_POST ['country'])) {
						if(is_array($_POST ['country'])){
							$ctr = 0;
							$postCountry = '';
							foreach ( $_POST ['country'] as $countries ) {
								$ctr ++;
								$postCountry .= $countries;
								if (count ( $_POST ['country'] ) != $ctr) {
									$postCountry .= ',';
								}			
							}
						}else{
							$postCountry= $_POST ['country'];
						}
						$post->country = $postCountry;
								
					} else {
						$post->country = 55;
					}
								
					if(!empty($_POST ['gender'])) {
						if(is_array($_POST ['gender'])){
							$ctr = 0;
							$postGender = '';
							foreach ( $_POST ['gender'] as $genders ) {
								$ctr ++;
								$postGender .= $genders;
								if (count ( $_POST ['gender'] ) != $ctr) {
									$postGender .= ',';
								}			
							}
						}else{
							$postGender= $_POST ['gender'];
						}
						$post->gender = $postGender;
								
					} else {
						$post->gender = 1;
					}
								
					if(!empty($_POST ['age'])) {
						if(is_array($_POST ['age'])){
							$ctr = 0;
							$postAge = '';
							foreach ( $_POST ['age'] as $ages ) {
								$ctr ++;
								$postAge .= $ages;
								if (count ( $_POST ['age'] ) != $ctr) {
									$postAge .= ',';
								}			
							}
						}else{
							$postAge=$_POST ['age'];
						}
						$post->age = $postAge;
						
					} else {
						$post->age = 17;	
					}
		
	
					if($adminA->ID > 0 or $adminSM->ID > 0) {
						
						$post->followCount = (isset ( $_POST ['follow'] ) ? $_POST ['follow'] : 0);
						$post->viewCount = (isset ( $_POST ['view'] ) ? $_POST ['view'] : 0);
						$post->likeCount = (isset ( $_POST ['like'] ) ? $_POST ['like'] : 0);
						$post->shareCount = (isset ( $_POST ['share'] ) ? $_POST ['share'] : 0);
						$post->oneSharerFollowerCount = (isset ( $_POST ['shareSelectFollowers'] ) ? $_POST ['shareSelectFollowers'] : NULL);  
						
					}
					
					$post->adminNote = $_POST ['adminNote'];
					$post->status= $_POST ["status"];
					$result = $post->save (); 
			
		
					if($result > 0) {
		
						$history = new adminHistory();
						$history->tableName = "posts";
						$history->tableID = $postID;
						$history->adminID = $adminid;
						$history->operation = "post updated"; 
						$history->updated = new DateTime(date('Y/m/d H:i:s'));
						$history->save();
					

						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=success" );  
		
		
					} else {
		
						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
			
					}
					
					break;
					
				case "editUser" :
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";
					
					$userIDa = $_POST ['euserID']; 
					
					if($adminA->ID > 0 or $adminSM->ID > 0) {  
						
						$balance = $_POST ['balance'];
					
					}
					

					$cash = $_POST ['cash'];
					
					$userB = new users($userIDa);
					$oldBalance = $userB->balance;
					$oldCash = $userB->cash;
					
					$result = "";
					
					
					if($adminA->ID > 0 or $adminSM->ID > 0) {  
						
						$userB->cash = $cash;  
					
					}
					
					if($adminA->ID > 0 or $adminSM->ID > 0) {
						
						if($userB->balance < $balance) {
							
							if($_POST ['addPointC'] != "") {
								
								$creBalance = new balance();
								if($_POST ['addPointC'] == 1) {
									$creBalance->actionID = 6;
								} else if($_POST ['addPointC'] == 0) {
									$creBalance->actionID = 7;
								} 
								$creBalance->actiondate_ = new DateTime(date('Y/m/d H:i:s'));
								$creBalance->userID = $userIDa;
								$creBalance->point = $balance-$userB->balance;
								$creBalance->save();  	
								
							}
							
							
							$userB->balance = $balance;
						
						} else {
							
							$userB->balance = $balance;
							
						}
						

					}
					
					if($adminA->ID > 0 or $adminSM->ID > 0) {

						$result = $userB->save();
						
					}
				
					if($result > 0) {
		
						$history = new adminHistory();
						$history->tableName = "users";
						$history->tableID = $userIDa;
						$history->adminID = $adminid;
						
						
						if($oldBalance > $balance) {
						
							$history->operation = "updated user balance: -" . ($oldBalance-$balance); 
							
						} else if($oldBalance < $balance) {
							
							$history->operation = "updated user balance: +" . ($balance-$oldBalance); 
							
						} else {
							
							$history->operation = "updated user"; 
							
						}	
						
						if($oldCash != $cash) {
							
							$history->operation .= " and updated user cash status"; 
							
						}
						
						$history->updated = new DateTime(date('Y/m/d H:i:s'));
						$history->save();
					

						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=success" );  
		
		
					} else {
		
						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
			
					}
					
					break;
					
				case "editGift" :
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/gifts.php";
					$giftID = $_POST ['giftID'];
					
					
					
					$error = "";
					
					if(empty($_POST ['giftID'])){
						$error .= "Ürün id boş. Teknik departmana başvurun.<br/>";
					}
					
					if(empty($_POST ['giftName'])){
						$error .= "Ürün ismini boş bırakmayın.<br/>";
					}
					
					if(empty($_POST ['description'])){
						$error .= "Ürün açıklamasını boş bırakmayın.<br/>";
					}
					
					if(empty($_POST ['price'])){
						$error .= "Ürün ücretini boş bırakmayın.<br/>"; 
					}
					
					if(!is_numeric($_POST ['price'])){
						$error .= "Ürün ücreti geçersiz.<br/>";
					}
					
					if($_POST ['availableZone'] != 0) {
				
						if(empty($_POST ['availableZone'])){
							$error .= "Ürün dağıtım alanını seçin.<br/>";
						}
					
					}
		

					
					if($error == "") {
					
						$gift = new gifts($giftID);
						$gift->name = $_POST ['giftName'];
						$gift->description = $_POST ['description'];
						$gift->price = $_POST ['price'];
						$gift->category = $_POST ['category'];
						$gift->quantity = $_POST ['quantity'];
						$gift->numberOfSales = $_POST ['numberOfSales']; 
						$gift->provider = $_POST ['provider'];
						$gift->deliverySpeed = $_POST ['deliverySpeed'];

						$gift->isFeatured = $_POST ['isFeatured'];
						$gift->isDigital = $_POST ['digital'];
						$gift->availableZone = $_POST ['availableZone'];
						$gift->status = $_POST ['status'];
						
						$result = "";
						if($adminA->ID > 0 or $adminSM->ID > 0) {  
							
							$result = $gift->save();
							
						}
						
						if($result > 0) {
		
							$history = new adminHistory();
							$history->tableName = "gifts";
							$history->tableID = $giftID;
							$history->adminID = $adminid;
							$history->operation = "updated gift"; 
							$history->updated = new DateTime(date('Y/m/d H:i:s'));
							$history->save();
					

							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=success" );  
		
		
						} else {
		
							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
			
						}
					
					} else {
						
						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail&error=" . $error );
						
					}
					
					break;  
					
				case "deletePost" :
					
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
					$postID = $_POST ['postID'];

					if($adminA->ID > 0 or $adminSM->ID > 0) {
						
						$post = new posts($postID);
						$post->delete();
						
						$history = new adminHistory();
						$history->tableName = "posts";
						$history->tableID = $postID;
						$history->adminID = $adminid;
						$history->operation = "deleted post"; 
						$history->updated = new DateTime(date('Y/m/d H:i:s'));
						$history->save();

						header("Location: https://www.funnyandmoney.com/admin?tab=editpost&message=success");  
						
					} else {
						
						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
						
					}
					
					break;
					
					
			case "deleteUser" :
					
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
					$userIDa = $_POST ['euserID'];

					if($adminA->ID > 0 or $adminSM->ID > 0) {
						
						$userv = new users($userIDa);
						$userv->delete();
						
						$history = new adminHistory();
						$history->tableName = "users";
						$history->tableID = $userIDa;
						$history->adminID = $adminid;
						$history->operation = "deleted user"; 
						$history->updated = new DateTime(date('Y/m/d H:i:s'));
						$history->save();

						header("Location: https://www.funnyandmoney.com/admin?tab=edituser&message=success");
						
					} else {
						
						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
						
					}  
					
					break; 
					
				case "deleteGift" :
					
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/gifts.php";
					$giftIDa = $_POST ['giftID'];

					if($adminA->ID > 0 or $adminSM->ID > 0) {  
						
						$gift = new gifts($giftIDa);
						$gift->delete();
						
						$history = new adminHistory();
						$history->tableName = "gifts";
						$history->tableID = $giftIDa;
						$history->adminID = $adminid;
						$history->operation = "deleted gift"; 
						$history->updated = new DateTime(date('Y/m/d H:i:s'));
						$history->save();

						header("Location: https://www.funnyandmoney.com/admin?tab=editgift&message=success");
						
					} else {
						
						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
						
					}  
					
					break; 
					
				
				case "imageGift" :
					
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/gifts.php";
					$giftID = $_POST ['giftID'];
					$gift = new gifts($giftID);
					
					if($adminA->ID > 0 or $adminSM->ID > 0) {  
						
					do {
						$randImg = rand ( 1000000, 9999999 );
						$sql = "SELECT 1 FROM gifts WHERE picture='$randImg.*'";
						$runsql->executenonquery ( $sql, NULL, false );
				
					} while ( $runsql->recordCount != 0 );
			
					$showError = "";
					$imageFileType = pathinfo ( $_FILES ["image"] ["name"], PATHINFO_EXTENSION );
					$target_dir = "../Uploads/giftImg/";
					$target_file = $target_dir . "x" . $randImg . "." . $imageFileType;
					$uploadOk = 1;
				
					// Check if image file is a actual image or fake image
					if (isset ( $_POST ["change"] )) {
						$check = getimagesize ( $_FILES ["image"] ["tmp_name"] );
						if ($check !== false) {
							$uploadOk = 1;
						} else {
							$showError .= "Dosya bir resim değil.";
							$uploadOk = 0;
						}
					}
					// Check file size
					if ($_FILES ["image"] ["size"] > 90000000) {  
						$showError .= "Üzgünüz, resmin boyutu çok büyük.";
						$uploadOk = 0;
					}
					// Allow certain file formats
					if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
						$showError .= "Üzgünüz, sadece JPG, JPEG, PNG & GIF dosyalarına izin veriliyor.";
						$uploadOk = 0;
					}
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						// if everything is ok, try to upload file
					} else {
						if (move_uploaded_file ( $_FILES ["image"] ["tmp_name"], $target_file )) {
						
								if ($gift->picture != NULL or !empty ( $gift->picture )) {
									unlink ( $target_dir . $gift->picture );
								}
								$sql2 = "UPDATE gifts SET picture='" . "x" . $randImg . "." . $imageFileType . "' WHERE ID ='$giftID'"; 
								$runsql->executenonquery ( $sql2, NULL, true );  
							
							// upload OK 
						} else {
							$showError .= "Üzgünüz, bir hata oluştu.";
						}
					}  
					if (! empty ( $showError )) {
						echo $showError;
					} else {
						echo 'ok';
					}
				
					}
					
					break;
					
				case "newGift" :
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/gifts.php";
					

					$error = "";
					
					if(empty($_POST ['giftName'])){
						$error .= "Ürün ismini boş bırakmayın.<br/>";
					}
					
					if(empty($_POST ['description'])){
						$error .= "Ürün açıklamasını boş bırakmayın.<br/>";
					} else if($_POST ['description'] == "Ürün ile ilgili açıklama girin") {
						$error .= "Ürün açıklamasını boş bırakmayın.<br/>";
					}
					
					if(empty($_POST ['price'])){
						$error .= "Ürün ücretini boş bırakmayın.<br/>"; 
					}
					
					if($_POST ['status'] == ""){
						$error .= "Ürün durumunu seçin.<br/>"; 
					}
					
					if(!is_numeric($_POST ['price'])){
						$error .= "Ürün ücreti geçersiz.<br/>";
					}
					
					if($_POST ['availableZone'] != 0) {
				
						if(empty($_POST ['availableZone'])){
							$error .= "Ürün dağıtım alanını seçin.<br/>";
						}
					
					}

					
					if($error == "") {
					
						$gift = new gifts();
						$gift->name = $_POST ['giftName'];
						$gift->description = $_POST ['description'];
						$gift->price = $_POST ['price'];
						$gift->category = $_POST ['category'];
						$gift->quantity = $_POST ['quantity'];
						$gift->provider = $_POST ['provider'];
						$gift->deliverySpeed = $_POST ['deliverySpeed'];
						$gift->isFeatured = $_POST ['isFeatured'];
						$gift->isDigital = $_POST ['digital'];
						$gift->availableZone = $_POST ['availableZone'];
						$gift->status = $_POST ['status']; 
						
						$result = "";
						if($adminA->ID > 0 or $adminSM->ID > 0) {  
							$result = $gift->save();
						}
						
						if($result > 0) {
		
							$history = new adminHistory();
							$history->tableName = "gifts";
							$history->tableID = $result;
							$history->adminID = $adminid;
							$history->operation = "new gift added"; 
							$history->updated = new DateTime(date('Y/m/d H:i:s'));
							$history->save();
					

							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=success" );    
		
		
						} else {
		
							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
			
						}
					
					} else {
						
						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail&error=" . $error );
						
					}
					
					break;  
					
				case "editOrder" :
				
					require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/giftRequests.php';
					require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/gifts.php';
					require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/digitalGiftCodes.php';
					
					$orderID = $_POST ['orderID'];
					
					$error = "";
					
					if(empty($_POST ['orderID'])){
						$error .= "Sipariş id boş. Teknik departmana başvurun.<br/>";
					} else {
						
						$contOr = new giftRequests ($orderID);
						$contGift = new gifts ($contOr->giftID);
						$contdeliverySpeed = $contGift->deliverySpeed;
					}
					
					if($_POST ['deliveryStatus']==""){
						$error .= "Sipariş durumunu boş bırakmayın.<br/>" . $_POST ['deliveryStatus'];  
					} else {
						
						if($_POST ['deliveryStatus'] == 0) {
						
							if(isset($contdeliverySpeed) && isset($contOr->giftID)) {
								
								if($contdeliverySpeed == 2) {
								
									if($_POST ['digitalGCode'] == "") {
									
										$error .= "E-posta ile kullanıcıya teslim etmek için bir dijital kod seçin.<br/>";
										
									} else {
										
										$contDigi = new digitalGiftCodes($_POST ['digitalGCode']);
										if($contDigi->ID > 0) {
											
											if($contDigi->giftID != $contOr->giftID) {
											
												$error .= "Kod, hediye ile uyuşmuyor.<br/>";
												
											}
											
											if($contDigi->isUsed != 0) {
											
												$error .= "Seçtiğiniz kod kullanılmış.<br/>";
												
											}
											
										} else {
											
											$error .= "Geçersiz dijital kod.<br/>";
											
										}
									}	
								
								}
								
							
							} else {
								
								$error .= "Sipariş geçersiz. Lütfen teknik departmana başvurun.<br/>";
								
							}
						
						}
						
					}
					
					if(!empty($_POST ["cargoFirm"])) {
						if(strlen($_POST ["cargoFirm"]) > 120) {
						
							$error .= "Kargo firma ismi çok uzun.<br/>";
						
						}
						
					}
					
					if(!empty($_POST ['sendMail'])) {
					if($_POST ['sendMail']==1){

						if($_POST ['mailTemp']=="") {
							
							$error .= "Mail göndermek için mail türünü seçin.<br/>";
							
						} else {
							if($_POST ['mailTemp']=="c"){
								if($_POST ['sendMailTitle']=="" || $_POST ['sendMailText']==""){
									$error .= "Kullanıcıya mail gönderecekseniz bir metin girmelisiniz.<br/>";
								} 
							}
						}
					}
					}
					
					
					$result = "";
			
					if($error == "") {
						
						$order = new giftRequests($orderID);
						$order->deliveryStatus = $_POST ['deliveryStatus'];
						
						$gift = new gifts($order->giftID);
						
						if($gift->isDigital != 1) {
							$order->providerNo = $_POST ['providerNo'];
							$order->cargoFirm= $_POST ['cargoFirm'];
							$order->cargoNo = $_POST ['cargoNo'];
						}
						
						$order->adminNote = $_POST ['adminNote'];
						
						
						
						if(!empty($_POST ['sendMail']) && $_POST ['mailTemp']!=""){
						if($_POST ['sendMail']==1){
							
							$user = new users($order->userID);
							
							if($_POST ['mailTemp']=="c"){

								$mail = new Mail ( $user->email,$user->fullName, 'support@funnyandmoney.com', 'Funny&Money Support', $_POST ['sendMailTitle'], '', NULL, NULL, 4,  $user->ID, $_POST ['sendMailText'], $orderID);
								$mail->sendMail();
							
							} else {
								
								switch ($_POST ['mailTemp']) {
									
									case 0 :
										$orderMailTitle = $loc->label("orderMailTitleDelivered");
										$orderMailText = $loc->label("orderMailTextDelivered");
										break;
									case 1 :
										$orderMailTitle = $loc->label("orderMailTitleShipped"); 
										$orderMailText = $loc->label("orderMailTextShipped");
										break;
									case 2 :
										$orderMailTitle = $loc->label("orderMailTitlePending");
										$orderMailText = $loc->label("orderMailTextPending");
										break;
									case 3 :
										$orderMailTitle = $loc->label("orderMailTitleCancelled");
										$orderMailText = $loc->label("orderMailTextCancelled");
										break;  
								
								}
								
								$mail = new Mail ( $user->email,$user->fullName, 'shop@funnyandmoney.com', 'Funny&Money Shop', $orderMailTitle . " " . $gift->name, '', NULL, NULL, 4,  $user->ID, $orderMailText, $orderID);
								$mail->sendMail();
								
							}
							
						}
						}
						
						if($_POST ['deliveryStatus'] == 0) {
							
							if($gift->deliverySpeed == 2) {
							
								$digital = new digitalGiftCodes($_POST ['digitalGCode']);
								$digital->giftRequestID = $order->ID;
								$digital->isUsed = 1;
								$digital->save();
								
								$mail = new Mail ( $user->email,$user->fullName, 'shop@funnyandmoney.com', 'Funny&Money Shop', $loc->label("emailProductTitle") . " " . $gift->name, '', NULL, NULL, 5,  $user->ID, $loc->label("emailProductText"), $orderID);
								$mail->sendMail();
								
							}
							
						}
						
						if($adminA->ID > 0 or $adminSM->ID > 0) {  
							
							if($order->preturn!=1){
							
							if(!empty($_POST ['pointBack'])){
								if($_POST ['pointBack']==1){
									$user1 = new users($order->userID);
									$user1->balance= str_replace(",",".",($user1->balance+$order->price));
									$sresult = $user1->save();
									
									$creBalance = new balance();
									$creBalance->actionID = 6;
									$creBalance->actiondate_ = new DateTime(date('Y/m/d H:i:s'));
									$creBalance->userID = $userID;
									$creBalance->point = $_GET['currency'];
									$creBalance->save();  
									
									if($sresult >0) {
										$order->preturn = 1;
									}
								}
							}
							
							$result = $order->save();	
							
							}
							
						}
		
						if($result > 0) {
		
							$history = new adminHistory();
							$history->tableName = "giftRequests"; 
							$history->tableID = $result;
							$history->adminID = $adminid;
							$history->operation = "order updated"; 
							$history->updated = new DateTime(date('Y/m/d H:i:s'));
							$history->save();
					

							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=success" );    
		
		
						} else {
		
							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
			
						}
						
					} else {
						
						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail&error=" . $error );  
						
					}
					
					break;  
					
				case "newCode" :
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/digitalGiftCodes.php";
					$giftID = $_POST ['giftID'];
					
					
					
					$error = "";
					
					if(empty($_POST ['giftID'])){
						$error .= "Ürün id boş. Teknik departmana başvurun.<br/>"; 
					}
					
					if(empty($_POST ['code'])){
						$error .= "Kodu boş bırakmayın.<br/>";
					}
					
					if(empty($_POST ['description'])){
						$error .= "Kod açıklamasını boş bırakmayın.<br/>";
					} else if($_POST ['description'] == "Kod ile ilgili açıklama girin") {
						$error .= "Kod açıklamasını boş bırakmayın.<br/>";
					}
					
					if(empty($_POST ['day'])){
						$error .= "Son kullanma tarihinin gün alanını boş bırakmayın.<br/>"; 
					}
					
					if(empty($_POST ['month'])){
						$error .= "Son kullanma tarihinin ay alanını boş bırakmayın.<br/>"; 
					}
					
					if(empty($_POST ['year'])){
						$error .= "Son kullanma tarihinin yış alanını boş bırakmayın.<br/>"; 
					}

					
					if($error == "") {
					
						$giftCode = new digitalGiftCodes();
						$giftCode->giftID = $_POST ['giftID'];
						$giftCode->giftCode = $_POST ['code'];
						$giftCode->descriptionText = $_POST ['description'];
						$giftCode->expirationDate_ = date('Y-m-d', strtotime($_POST ['year'] .'-' . $_POST ['month'] . '-' . $_POST ['day']));
						$giftCode->isUsed = 0;

						$result = "";
						if($adminA->ID > 0 or $adminSM->ID > 0) {  

							$result = $giftCode->save();  
						
						}
						
						if($result > 0) {
		
							$history = new adminHistory();
							$history->tableName = "digitalGiftCodes";
							$history->tableID = $result;
							$history->adminID = $adminid;
							$history->operation = "new code created"; 
							$history->updated = new DateTime(date('Y/m/d H:i:s'));
							$history->save();
					

							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=success" );  
		
		
						} else {
		
							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
			
						}
					
					} else {
						
						$fn = new functions ();
						$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail&error=" . $error );
						
					}
					
					break;
					
				case "deleteCode" :
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/digitalGiftCodes.php";
					$codeID = $_POST ['deleteID'];
					
					$code = new digitalGiftCodes($codeID);
					
					$result = "";
					if($adminA->ID > 0 or $adminSM->ID > 0) {  
						$result = $code->delete(1);  
					}
					
					if($result > 0) {
		
							$history = new adminHistory();
							$history->tableName = "digitalGiftCodes";
							$history->tableID = $codeID;
							$history->adminID = $adminid;
							$history->operation = "code deleted"; 
							$history->updated = new DateTime(date('Y/m/d H:i:s'));
							$history->save();
					

							echo "ok"; 
		
		
					} else {
		
						echo "error";
			
					}
					
					break;
					
				case "getAccounts" :
					
					if($adminA->ID > 0 or $adminSM->ID > 0) {  
						
						require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/account.php';

						$sql = "
	
						SELECT *
						FROM balance
						WHERE (actionID=1 OR actionID=2 OR actionID=3 OR actionID=4 OR actionID=5) AND (IFNULL(calculated,0)<>1) AND isDeleted<>1

						";  
						$result = $runsql->executenonquery ( $sql, NULL, true );

						$array = array();
						$totalNonRef=0;
						$totalRef=0;
						while($row=mysqli_fetch_array($result)) { 

							if($row["actionID"] == 1 or $row["actionID"] == 2 or $row["actionID"] == 3 or $row["actionID"] == 4) {
								$totalNonRef = $totalNonRef + $row["point"];
							} else if($row["actionID"] == 5) {	
								$totalRef = $totalRef + $row["point"];
							} 
							array_push($array, $row["ID"]);
	
						}

						foreach ($array as $value) {
							$value = $runsql->checkInjection($value);
							$sql3 = "UPDATE balance SET calculated = 1 WHERE ID ='".$value."'";   
							$runsql->executenonquery ( $sql3, NULL, true );  
						}

						
						$totalProductDec = ($totalNonRef + $totalRef) * 0.329;
						// Kullanıcıların kazandığı referanslı toplam puan değeri ile 0.329* çarpılarak ürünlere harcanacak puanların lira cinsi hesaplanır.
						// * -> 0.329 = ürün satın alırken & nin birim lira değeri(0.47** nin %70 alınarak türetilmiştir)
						// ** -> 0.47 = en ucuz puan paketinin yani 1000& nin fiyatı göze alınarak ulaşılmış satın alırkenki & nin birim lira değeri
						$totalProfitDec = $totalProductDec / 4;  
						// Kullanıcıların kazandığı referansız toplam para değerinin üzerinden bizim karımızın puan karşılığı kar oranımız kadarı alınarak bulunur. 
						// Kar oranımız %20'dir. Geriye kalan %10 referansa dahildir.
						// Bu kar ürün bütçesinden bağımsız olarak istediğimiz kadarını çekebileceğimiz miktarı temsil eder.
						// Bu alandan ödeme sisteminin komisyonu düşülecek.

						if($totalProfitDec != 0) {
							$account1 = new account(1);
							$account1->total = $account1->total+ (intval(($totalProfitDec*100))/100);
							$account1->updated = new DateTime(date('Y/m/d H:i:s'));
							$account1->save();
						}						

						if($totalProductDec != 0) {
							$account2 = new account(2);
							$account2->total = $account2->total + (intval(($totalProductDec*100))/100);
							$account2->updated = new DateTime(date('Y/m/d H:i:s'));
							$account2->save();
						}

						$account1P = new account(1);
						$account2P = new account(2);
						
						$which = $_POST ['whichAc'];

						if($which == 1) {
							
							echo "PROFIT" . number_format((float)$account1P->total, 2, ',', '.');
							
						} else if($which == 2) {
							
							echo "PRODUCT" . number_format((float)$account2P->total, 2, ',', '.');
							
						} else if($which == "total") {
							
							echo "TOTAL" . ( number_format((float)$account1P->total + $account2P->total, 2, ',', '.'));
							 
						}

						
					}
					
					break;
					
				case "newTransaction" :
					

					
					if($adminA->ID > 0 or $adminSM->ID > 0) {  
						
						require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/account.php';
						require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/accountActivities.php';
						
						$error = "";
						
						$account1 = new account(1);
						$account2 = new account(2);
						
						if(empty($_POST ['amount'])){
							$error .= "İşlem görecek para miktarını girin.<br/>"; 
						} else {
							
							if(is_numeric($_POST ['amount'])) { 
								
								$amount = $_POST ['amount'];
								
								if(empty($_POST ['transactionID'])){ 
									
									$error .= "Bir işlem seçin.<br/>"; 
									
								} else if($_POST ['transactionID'] == 3) {
							
									if(empty($_POST ['withdrawAcID'])){
										
										$error .= "Paranın çekileceği hesabı seçin.<br/>";  
										
									} else if($_POST ['withdrawAcID'] == 1){
								
										if($amount > $account1->total){
											
											$error .= "Hesapta yeterli para yok.<br/>"; 
											
										}
								
									} else if($_POST ['withdrawAcID'] == 2){
	
										if($amount > $account2->total){
											
											$error .= "Hesapta yeterli para yok.<br/>"; 
											
										}
								
									}
							
									$waccount = $_POST ['withdrawAcID'];
							
									if(empty($_POST ['depositAcID'])){
										
										$error .= "Paranın aktarılacağı hesabı seçin.<br/>"; 
										
									} else {
								
										$daccount = $_POST ['depositAcID'];
								
									}
							
									if($_POST ['withdrawAcID'] == $_POST ['depositAcID']) {
								
										$error .= "Para transferi yapılacak hesaplar aynı.<br/>"; 
								
									}
							
							
							
								} else if($_POST ['transactionID'] != 3) {
							
									if(empty($_POST ['accountID'])){
										
										$error .= "İşlem yapılacak hesabı seçin.<br/>"; 
										
									} else if($_POST ['transactionID'] == 1) {

										$waccount = $_POST ['accountID'];
									
										if($_POST ['accountID'] == 1) {
								
											if($amount > $account1->total){
												
												$error .= "Hesapta yeterli para yok.<br/>"; 
												
											}
								
										}
								
										if($_POST ['accountID'] == 2) {
								
											if($amount > $account2->total){
												
												$error .= "Hesapta yeterli para yok.<br/>"; 
												
											}  
										
										}
								
									} else if($_POST ['transactionID'] == 2) {
										
										$daccount = $_POST ['accountID'];

									}
							
								}
								
							} else {
								
								$error .= "Lütfen geçerli bir sayı girin. Örnek: 5.24<br/>";
								
							}							
						
						}
						
						if(empty($_POST ['description'])){
							$error .= "İşlem ile ilgili bir açıklama girin.<br/>"; 
						}
						
	
						
						
						if($error == "") {
							
							$result1 = "";
							$result2 = "";  
						
							$amount = $runsql->checkInjection($amount);
						
							if($_POST ['transactionID'] == 1) {  
								
								if($_POST ['accountID'] == 1) {
									
									$sql = "UPDATE account SET total = total - ". $amount .", updated = now() WHERE ID = 1"; 
									$result1 = $runsql->executenonquery ( $sql, NULL, true );  
									
								} else if($_POST ['accountID'] == 2) {
									
									$sql = "UPDATE account SET total = total - ". $amount .", updated = now() WHERE ID = 2"; 
									$result2 = $runsql->executenonquery ( $sql, NULL, true );  
									
								}
								
								
							} else if($_POST ['transactionID'] == 2) {
								
								
								if($_POST ['accountID'] == 1) {
									
									$sql = "UPDATE account SET total = total + ". $amount .", updated = now() WHERE ID = 1"; 
									$result1 = $runsql->executenonquery ( $sql, NULL, true );  
									
								} else if($_POST ['accountID'] == 2) {
									
									$sql = "UPDATE account SET total = total + ". $amount .", updated = now() WHERE ID = 2"; 
									$result2 = $runsql->executenonquery ( $sql, NULL, true );  
									
								}
								
								
							} else if($_POST ['transactionID'] == 3) {  
								
								if($_POST ['withdrawAcID'] == 1){
								
									$sql = "UPDATE account SET total = total - ". $amount .", updated = now() WHERE ID = 1"; 
									$result1 = $runsql->executenonquery ( $sql, NULL, true );  
								
								} else if($_POST ['withdrawAcID'] == 2){
								
									$sql = "UPDATE account SET total = total - ". $amount .", updated = now() WHERE ID = 2"; 
									$result2 = $runsql->executenonquery ( $sql, NULL, true );  
								
								}
								
								if($_POST ['depositAcID'] == 1){
								
									$sql2 = "UPDATE account SET total = total + ". $amount .", updated = now() WHERE ID = 1"; 
									$result1 = $runsql->executenonquery ( $sql2, NULL, true );  
								
								} else if($_POST ['depositAcID'] == 2){
								
									$sql2 = "UPDATE account SET total = total + ". $amount .", updated = now() WHERE ID = 2"; 
									$result2 = $runsql->executenonquery ( $sql2, NULL, true );  
								
								}
								
								
							}
							
							
							$activity = new accountActivities();
							$activity->adminID = $adminid;
							$activity->amount = $amount;
							$activity->currency = $_POST ['currency'];
							
							if(isset($waccount)) {
						
								$activity->waccountID = $waccount;
							
							} 
							
							if(isset($daccount)) {
						
								$activity->daccountID = $daccount;  
							
							}
							
							if($_POST ['transactionID'] == 1) {
								
								$activity->operation = 1;
								
							} else if($_POST ['transactionID'] == 2) {
								
								$activity->operation = 2;
								
							} else if($_POST ['transactionID'] == 3) {
								
								$activity->operation = 3;
								
							}
							
							$activity->description = $_POST ['description'];
							$activity->date_ = new DateTime(date('Y/m/d H:i:s'));
							$result = $activity->save();

							
							if($result1 > 0 or $result2 > 0) {
		
								$history = new adminHistory();
								$history->tableName = "account";  
								$history->tableID = $result;
								$history->adminID = $adminid;
								$history->operation = "new transaction executed"; 
								$history->updated = new DateTime(date('Y/m/d H:i:s'));  
								$history->save();
			
		
								$fn = new functions ();
								$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=success" ); 
		
		
							} else {
		
								$fn = new functions ();
								$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail" );
			
							}
			
						} else {
		
							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail&error=" . $error );
			
						}
				
					
					}
				
					
					break;
					
				case "download" :
					if($adminA->ID > 0 or $adminSM->ID > 0) {  
						
	require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/accountActivities.php';  

	$sql = $_POST["sql"];
	$result =  $runsql->executenonquery ( $sql, NULL, true );  

header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=\"export_table.csv\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

$output = fopen('php://output', 'w');

    fputcsv($output, array('ID','adminID','Amount','Currency','Withdraw Account','Deposit Account','Operation','Description','Date'));  

while ($row = mysqli_fetch_assoc($result))
    {
    fputcsv($output, $row);  
    }

fclose($output);

				}
 

					break;
					
					
				case "changeDigitalCode" : 
					
					if($adminA->ID > 0 or $adminSM->ID > 0) { 
						
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/digitalGiftCodes.php";
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/giftRequests.php";
					
					$error = "";
					
					if(empty($_POST["cdigitalCode"])) {
		
						$error .= "Lütfen yeni bir dijital kod seçin.<br/>";
		
					}
					
					if(empty($_POST["oldcdigitalCode"])) {
		
						$error .= "Eski dijital kod bulunamadı.<br/>";
		
					}
					
					if(empty($_POST["agiftRequestID"])) {
						
						$error .= "Sipariş numarası belirtilmemiş.<br/>";
						
					}
					
					if(!empty($_POST["cdigitalCode"]) && !empty($_POST["agiftRequestID"])) {
						
						$contDigital = new digitalGiftCodes($_POST["cdigitalCode"]);
						$contReq = new giftRequests($_POST["agiftRequestID"]);
						
						if($contDigital->ID > 0 && $contReq->ID > 0) {

							if($contReq->giftID != $contReq->giftID) {
							
								$error .= "Kod ile sipariş edilen eşleşmiyor.<br/>";
							
							}
							
							if($contReq->isUsed != 0) {
							
								$error .= "Seçtiğiniz kod kullanılmış.<br/>";
							
							}
							
						} else {
							
							$error .= "Bir sebepten dolayı hata oluştu. Lütfen teknik departmana başvurun.<br/>";
							
						}
						
					}
					
					if($error == "") {
						
						$digitalNew = new digitalGiftCodes($_POST["cdigitalCode"]);
						$digitalNew->giftRequestID = $_POST["agiftRequestID"];
						$digitalNew->isUsed = 1;
						$result1= $digitalNew->save();
						
						$digitalOld = new digitalGiftCodes($_POST["oldcdigitalCode"]);
						$digitalOld->giftRequestID = "";
						$digitalNew->isUsed = 1;
						$result2= $digitalOld->save();
					
						if($result1 > 0 or $result2 > 0) {
							
							$mail = new Mail ( $user->email,$user->fullName, 'shop@funnyandmoney.com', 'Funny&Money Shop', $loc->label("changeCodeNewMailTitle"), '', NULL, NULL, 4,  $user->ID, $loc->label("changeCodeNewMailText"), $_POST ['agiftRequestID']);
							$mail->sendMail();
		
							$history = new adminHistory();
							$history->tableName = "digitalGiftCodes";  
							$history->tableID = $result1; 
							$history->adminID = $adminid;
							$history->operation = "the gift digital code replaced with another"; 
							$history->updated = new DateTime(date('Y/m/d H:i:s'));  
							$history->save();
			
	
							echo "XXsdsaok322XXX";
	
	
						} else {
		
							$fn = new functions ();
							echo "";
		
						}
						
					} else {
		
						$fn = new functions ();
						echo $error;
		
					}
			
					}
			
					break;
					
				case "settings" :
					
					if($adminA->ID > 0) { 
					
						require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
						require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/adminSettings.php";
						
						$error = "";
						
						if(empty($_POST["password"])) {
							
							$error .= "Şifrenizi girin.<br/>";
						
						} else {
							

							$password = isset ( $_POST ["password"] ) ? $_POST ["password"] : "";
							$user = new users($userid);
							
							if (password_verify($password, $user->password)) {
								
								if($_POST["siteStatus"] == "") {
							
									$error .= "Site durumunu seçin.<br/>";
						
								}
								
								
							} else {
								
								$error .= "Şifreniz hatalı. Lütfen tekrar deneyin.<br/>";
								
							}
							
						}
					
						
						if($error == "") {
							
							$siteStatus = new adminSettings(1);
							if($_POST["siteStatus"] == 1) {
								$siteStatus->status = $_POST["siteStatus"];
								$siteStatus->save();
							} else if($_POST["siteStatus"] == 0) {
								$siteStatus->status = $_POST["siteStatus"];
								$siteStatus->save();
								shell_exec('rm -rf '.session_save_path() );
							}

							
							$history = new adminHistory();
							$history->tableName = "adminSettings";  
							$history->tableID = 0; 
							$history->adminID = $adminid;
							$history->operation = "the site settings updated"; 
							$history->updated = new DateTime(date('Y/m/d H:i:s'));  
							$history->save();
	 
	
							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=success" ); 
		
			
						} else {
		
							$fn = new functions ();
							$fn->redirect ( $_SERVER['HTTP_REFERER'] . "&message=fail&error=" . $error );
			
						}
					}
						
					break;
					
					
				case "payment" :  
					
					if($adminA->ID > 0 or $adminSM->ID > 0) {
					
						require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";

						if(empty($_POST["paymentID"])) {
							
							echo "Hata";
						
						} else {
							
							$payment = new payments($_POST["paymentID"]);
							
							if($payment->method =="bankTransfer") {
							
								$payment->reason = 1;
								$result = $payment->save();
								
								if($result > 0) {
								
									$history = new adminHistory();
									$history->tableName = "payments";  
									$history->tableID = $payment->ID; 
									$history->adminID = $adminid;
									$history->operation = "the payment confirmed"; 
									$history->updated = new DateTime(date('Y/m/d H:i:s'));  
									$history->save();
									
									echo "ok";
									
								}
							
							}
							
						}
	 
	
					}
			
					break;
					
				case "deletePayment" :  
					
					if($adminA->ID > 0 or $adminSM->ID > 0) {
					
						require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";

						if(empty($_POST["deleteID"])) {
							
							echo "Hata";
						
						} else {
							
							$payment = new payments($_POST["deleteID"]);
							$payment->delete();

							$history = new adminHistory();  
							$history->tableName = "payments";  
							$history->tableID = $_POST["deleteID"]; 
							$history->adminID = $adminid;
							$history->operation = "the payment deleted"; 
							$history->updated = new DateTime(date('Y/m/d H:i:s'));  
							$history->save();
							
							echo "ok";
			
							
		
						}
	 
	
					}
			
					break;
					
				case "payout":
					require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payouts.php";
					
					if($_POST["payoutID"] == "") {
						
						echo "ERROR1";
						
					} else if($_POST["status"] == "") {
						
						echo "ERROR2";
						
					} else {
						
						switch($_POST["status"]) {
							
							case 1:
								$status=1;
								break;
							case 2:
								$status=2;
								break;
							case 0:
								$status=0;
								break;
							default:
								$status=-1;
								break;
						}
						
						if($status==-1) {
							
							echo "ERROR3";
							
						} else {
							
							$payout = new payouts($_POST["payoutID"]);
							$payout->result = $status;
							$result = $payout->save();
							
							if($result > 0) {
								
								$history = new adminHistory();
								$history->tableName = "payouts";  
								$history->tableID = $payout->ID; 
								$history->adminID = $adminid;
								$history->operation = "the payout status updated"; 
								$history->updated = new DateTime(date('Y/m/d H:i:s'));  
								$history->save();
						
								echo "ok";
							
							}
							
						}
						
						
						
					}
					
					
					break;
				
					
			}
			
		} else {
			
			echo "YETKİSİZ GİRİŞ";   
		
		
		}
		
		break;
		
	case "adminSignOut" :
	
		unset($_SESSION['adminID']);
		$fn = new functions ();
		$fn->redirect ( "/adminlogin" );
	
		break;
		
	case "newShopComment" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/shopComments.php";
		
		$userid = $_SESSION["userID"];
		$user = new users($userid);
		
		$oldC = new shopComments();
		$row = mysqli_fetch_array( $oldC->get2OldC($userid) );
		
		$to_time = strtotime(time());
		
		if(!empty($row["date_"]) && $row["date_"] != "") {	
			$to_time = new DateTime();
			$from_time = new DateTime($row["date_"]);
			$minutea = $from_time->diff($to_time);
			$minute = $minutea->format('%i');
		} else {
			$minute = 999;
		}
		
		if($minute < 5) {
			
			echo $loc->label("5 five minute comments");      
			
		} else if(empty($_POST["giftID"])) {
			
			echo $loc->label("There is technical problem. Please contact F&M");
			
		} else if(empty($_POST["comment"])) {
			
			echo $loc->label("Comment box is empty");
			
		} else {
			
			$giftID = $_POST["giftID"];
			$commentText = $_POST["comment"];
			
			if(strlen($_POST ["comment"]) > 250) { 
				
				echo $loc->label("Your comment too long");
				
			} else if(strlen($_POST ["comment"]) < 5) {
				
				echo $loc->label("Your comment too short");
					
			} else {
			
				$newC ='';
			
				$comment = new shopComments();
				$comment->userID = $userid;
				$comment->giftID = $giftID;
				$comment->comment = $commentText;
				$comment->status = 1;
				$comment->date_ = new DateTime(date('Y/m/d H:i:s'));  
				$result = $comment->save();
			
				$commentNew = new shopComments($result);
			
				$newC = '
			
				<div class="media">
								<a class="media-left" href="profile?id='. $userid .'">
									<img style="max-width: 65px !important;" src="'. (($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg") .'" alt="" />
								</a>
								<div class="media-body">
									<div class="media-content">
										<a href="profile?id='. $userid .'" class="media-heading">'. $user->fullName .'</a>
										<span class="date">'. date((($user->language=="tr") ? "d/m/Y H:i" : "m/d/Y H:i"), strtotime( $commentNew->date_ )) .'</span>
										<p>'. $commentNew->comment .'</p>
									</div>
								</div>
							</div>

				
				' ;
			
				echo "xxASSokS322_!!Xsaxx" . $newC;    
				
			}

			
		} 
		
		
		break;
		
	case "likeShop" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/shopLikes.php";
		
		$userid = $_SESSION["userID"];
		$giftID = (isset ( $_POST ["giftID"] ) ? $_POST ["giftID"] : 0);
		$likeNow = (isset ( $_POST ["like"] ) ? $_POST ["like"] : 0);
		
		if($giftID != 0) {
			
			if($likeNow == 1 or $likeNow == -1) {
				$like = new shopLikes();
				$userL = mysqli_fetch_array( $like->getUserLike($userid,$giftID) );
		
				if($userL["ID"] > 0) {
					
					$newLike = new shopLikes($userL["ID"]);
					
					if($likeNow != $newLike->userLike) {

						$newLike->userLike = $likeNow;
						$newLike->date_ = new DateTime(date('Y/m/d H:i:s'));  
						$newLike->save();
						
						echo "tk";
						
					} else {
						
						echo "already";
						
					}
			
				} else {
					
					$newLike = new shopLikes();
					$newLike->userID = $userid;
					$newLike->giftID = $giftID;
					$newLike->userLike = $likeNow;
					$newLike->date_ = new DateTime(date('Y/m/d H:i:s'));  
					$newLike->save();
				
					echo "ok";
						
					
				
				}
				
			} else {
			
				echo "120error";

			}
			
		} else {
			
			echo "150error";

		}
		
		break;
		
	case "bePublisher" :
		$runsql = new \data\DALProsess ();
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/publisherApplications.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		
		$error = "";
		
		if($_POST["newID"] == "") {

			$error = $loc->label("There is technical problem. Please contact F&M") . "<br/>";   
		
		} else {
			$rowID = $_POST["newID"];
		
						
		if(empty($_POST["firstName"])) {

			$error .= $loc->label("Enter your first name") . "<br/>";
		
		} else if(strlen($_POST ["firstName"]) > 100) {
			
			$error .= $loc->label("Your first name too long") . "<br/>";
			
		} else if (strcspn($_POST ['firstName'], '0123456789') != strlen($_POST ['firstName']) || preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_POST ['firstName'])) {
		
			$error .= $loc->label ("Only letters and white space allowed") . "<br/>";
	
		} 
		
		if(empty($_POST["lastName"])) {

			$error .= $loc->label("Enter your last name") . "<br/>";
		
		} else if(strlen($_POST ["lastName"]) > 100) {
			
			$error .= $loc->label("Your last name too long") . "<br/>";
			
		} else if (strcspn($_POST ['lastName'], '0123456789') != strlen($_POST ['lastName']) || preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_POST ['lastName'])) {
		
			$error .= $loc->label ("Only letters and white space allowed") . "<br/>";
	
		} 
		
		if(empty($_POST["year"]) or empty($_POST["month"]) or empty($_POST["day"])) {

			$error .= $loc->label("Select your birthdate") . "<br/>";
		
		} 
		
		if(empty($_POST["nationality"])) {

			$error .= $loc->label("Select your nationality") . "<br/>";
		
		} 
		
		if(empty($_POST["identityID"])) {

			$error .= $loc->label("Enter your Identity ID") . "<br/>";
		
		} else if(strlen($_POST ["identityID"]) > 35) {
			
			$error .= $loc->label("Your identity ID too long") . "<br/>";
			
		} else if (preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_POST ['identityID'])) {
		
			$error .= $loc->label ("Only letters and numbers allowed for identity ID") . "<br/>";
	
		} 
		
		if(empty($_POST["phone"])) {

			$error .= $loc->label("Enter your mobile or business phone number") . "<br/>";
		
		} else if(strlen($_POST ["phone"]) > 25) {
			
			$error .= $loc->label("Your phone number too long") . "<br/>";
			
		}else if(preg_match('/^\+?\d+$/', $_POST ["phone"])) {
		
			$error .= $loc->label("Phone number invalid") . "<br/>";
		
		}  if(empty($_POST ["countryCode"])) {
		
			$error .= $loc->label("Please select your phone country code") . "<br/>";
	
		}
		
		if(empty($_POST["address"])) {

			$error .= $loc->label("Enter your mailing address") . "<br/>";
		
		} else if(strlen($_POST ["address"]) > 250) {
			
			$error .= $loc->label("Address too long") . "<br/>";
			
		}
		
		if(empty($_POST["region"])) {

			$error .= $loc->label("Enter your region") . "<br/>";
		
		} else if(strlen($_POST ["region"]) > 50) {
			
			$error .= $loc->label("Region too long") . "<br/>";
			
		}
		
		if(empty($_POST["postalCode"])) {

			$error .= $loc->label("Enter your postal code") . "<br/>";
		
		} else if(strlen($_POST ["postalCode"]) > 50) {
			
			$error .= $loc->label("Postal code too long") . "<br/>";
			
		}
		
		if(empty($_POST["city"])) {

			$error .= $loc->label("Enter your city") . "<br/>";
		
		} else if(strlen($_POST ["region"]) > 50) {
			
			$error .= $loc->label("City too long") . "<br/>";
			
		}
		
		if(empty($_POST["countryAddress"])) {

			$error .= $loc->label("Select your country") . "<br/>";
		
		}
		
		$userC2 = new users($_SESSION["userID"]);
		
		if($userC2->username == "" or is_null($userC2->username)) {
			
			if(empty($_POST["username"])) {

				$error .= $loc->label("Enter a username for your social media accounts") . "<br/>";
		
			} else if(preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $_POST ["username"])) {   

				$error .= $loc->label("Username cannot contain special characters.");
	
			} else if(strlen(trim($_POST ['username'])) == 0) {
			
				$error .= $loc->label("Username cannot be blank.");
			
			} else if(preg_match('/\s/', $_POST ["username"])) {   

				$error .= $loc->label("Username cannot contain special characters.");
	
			} else if(strlen($_POST ["username"]) > 30) {
		
				$error .= $loc->label("Your username too long.");
		
			}
			
		}
		
		if(empty($_POST["description"])) {

			$error .= $loc->label("Enter a description for your social media accounts") . "<br/>";  
		
		}
		
		if(empty($_POST["fbsocialID"]) && empty($_POST["twsocialID"]) && empty($_POST["gosocialID"])) {

			$error .= $loc->label("Select your one social media account at least") . "<br/>";
		
		} else if($_POST["fbsocialID"] == 0 && $_POST["twsocialID"] == 0 && $_POST["gosocialID"] == 0) {
			
			$error .= $loc->label("Select your one social media account at least") . "<br/>";
			
		}
		
		if(empty($_POST["category"])) {

			$error .= $loc->label("Select a category for your social media accounts") . "<br/>";
		
		}
		
		if(empty($_POST["language"])) {

			$error .= $loc->label("Select a language for your social media shares") . "<br/>";
		
		}
		
		
		
		//Hata kotnrol sonu
		
		if($rowID != 0) {
			$test = new publisherApplications($rowID);
			if($test->userID != $_SESSION["userID"]) {
				break;
			} else if($test->status == 1 or $test->status == 0) {
				break;
			}
		} else {
		
		if(empty($_FILES['identityDocument']) or empty($_FILES['proofAddress'])) {

			$error .= $loc->label("Upload all required documents") . "<br/>";
		
		} else {
			
			$randImg = "";
			$randImg2 = "";  
			
			do {
				$randImg = rand ( 1000000, 9999999 );
				$sql = "SELECT 1 FROM publisherApplications WHERE identityDocument='$randImg.*' or identityDocument='$randImg.*'";
				$runsql->executenonquery ( $sql, NULL, false );
			} while ( $runsql->recordCount != 0 );
			
			do {
				$randImg2 = rand ( 1000000, 9999999 );
				$sql2 = "SELECT 1 FROM publisherApplications WHERE identityDocument='$randImg.*' or identityDocument='$randImg.*'";
				$runsql->executenonquery ( $sql2, NULL, false );
			} while ( $runsql->recordCount != 0 );
			
			if($randImg == "" or $randImg2 == "") {
				
				$error .= $loc->label("There is a problem. Try again.") . "<br/>";
				
			} else {
				
				if($error == "") {
				
					$fn1 = new functions ();
					$fn2 = new functions ();
					$identityDocument = $fn1->uploadDoc ($_FILES["identityDocument"],"../Uploads/appImg/",$randImg,1,"appDoc");
					$proofAddress = $fn2->uploadDoc ($_FILES["proofAddress"],"../Uploads/appImg/",$randImg2,1,"appDoc");
					
				
				
					if (strpos($identityDocument, 'XX//-_OK_-\\XX') === false) {
						$error .= $loc->label("ID of publisher") . ": <br/>" . $identityDocument . "<br/>";
					} else {
						$identityDocument = str_replace('XX//-_OK_-\\XX', '', $identityDocument);
					}
				
					if (strpos($proofAddress, 'XX//-_OK_-\\XX') === false) {
						$error .= $loc->label("Proof of address") . ": <br/>" . $proofAddress . "<br/>";
					} else {
						$proofAddress = str_replace('XX//-_OK_-\\XX', '', $proofAddress);
					}
				
				}

			}
			
		}
		
		}
		
		}
		if($error == "") {
			
			if($rowID != 0) {
				$app = new publisherApplications($rowID);
				if($app->userID != $_SESSION["userID"]) {
					break;
				}
			} else {
				$app = new publisherApplications();
			}
			
			$birthdate = date('Y-m-d', strtotime($_POST ['year'] .'-' . $_POST ['month'] . '-' . $_POST ['day']));
			
			$user = new users($_SESSION["userID"]);
			
			$app->userID = $_SESSION["userID"];
			$app->firstName = $_POST["firstName"];
			$app->lastName = $_POST["lastName"];
			$app->birthDate = $birthdate;
			$app->nationality = $_POST["nationality"];
			$app->identityID = $_POST["identityID"];
			
			$app->identityDocument = $identityDocument;
			$app->proofAddress = $proofAddress;
			
			$app->email = $user->email;
			$app->phone =  "+" . $_POST ["countryCode"] . $_POST["phone"];
			$app->address = $_POST["address"];
			$app->region = $_POST["region"];
			$app->postalCode = $_POST["postalCode"];
			$app->city = $_POST["city"];
			$app->country = $_POST["countryAddress"];
			
			$user->username = mb_strtolower($_POST ['username'],'UTF-8');
			$user->save();
			
			$app->description = $_POST["description"];
			$app->facebook = $_POST["fbsocialID"];
			$app->twitter = $_POST["twsocialID"];
			$app->youtube = $_POST["gosocialID"];
			$app->category = $_POST["category"];
			$app->language = $_POST["language"];
			
			$app->date_ = new DateTime(date('Y/m/d H:i:s'));
			
			$app->status = 2;
			$result = $app->save();
				
			if($result > 0) {
				echo "XXxpublisher!_%+%+XXxzzxoksssX";
			}
			
		} else {
			
			echo $error;
			
		}
		
		
		break;
		
	case "cancelPublisherApp" : 
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/publisherApplications.php";
		
		if(empty($_POST["appID"])) {
			
			echo "Error";
			
		} else if(empty($_POST["userID"])) {
			
			echo "Error";
			
		} else {
			
			$publisher = new publisherApplications($_POST["appID"]);
			
			if($publisher->userID == $_SESSION["userID"]) {
				
				if($publisher->status == 2 or $publisher->status == 0) {
					
					$publisher->delete();
				
					echo "ok";
					
				} else {
					
					echo "Error";
					
				}
				
			
			} else {
				
				echo "Request denied!";
				
			}
			
		}

		
		break;
		
	case "publisherPost" :
		$runsql = new \data\DALProsess ();
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/publisherPosts.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/publishers.php";
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		
		$user = new users($_SESSION["userID"]);
		
		$error = "";
		
		if(empty($_POST["publisherID"])) {

			$error .= $loc->label("Select a publisher.") . "<br/>";   
		
		} else {
			
			$publisher = new publishers($_POST["publisherID"]);
			
			if($publisher->ID > 0) {
				
			if($publisher->userID == $_SESSION["userID"]) {
				
				$error .= $loc->label("You cannot add a post for you.") . "<br/>"; 
				
			} else {
			
			if(empty($_POST["which_package"])) {
				
				$error .= $loc->label("Select a package.") . "<br/>";   
				
			} else {
				
				if(empty($_POST["title"])) {
					
					$error .= $loc->label("The title cannot be empty.") . "<br/>";   
				
				} else if(strlen($_POST ["title"]) > 100) {
				
					$error .= $loc->label("The title cannot longer than 100 characters.") . "<br/>";
				
				} else if(strlen($_POST ["title"]) < 10) {
				
					$error .= $loc->label("The title must be min 10 characters.") . "<br/>";
				
				} else if(strlen(trim($_POST ['title'])) == 0) {
			
					$error .= $loc->label ("The title cannot be blank.") . "<br/>";
			
				} 
				
				if(empty($_POST["details"])) {
					
					$error .= $loc->label("The details cannot be empty.") . "<br/>";   
				
				} else if(strlen($_POST ["details"]) > 500) {
				
					$error .= $loc->label("The details cannot longer than 500 characters.") . "<br/>";
				
				} else if(strlen($_POST ["details"]) < 50) {
				
					$error .= $loc->label("The details must be min 50 characters.") . "<br/>";
				
				} else if(strlen(trim($_POST ['details'])) == 0) {
			
					$error .= $loc->label ("The details cannot be blank.") . "<br/>";
			
				} 
				
				if(!empty($_POST["link"])) {
					
					if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST ["link"])) {
					
						$error .= $loc->label("The link you entered is invalid. Please, check it.") . "<br/>";   
		
					}
					
				}
			
				if(empty($_POST["bid"])) {
				
					$error .= $loc->label("Enter a bid.") . "<br/>";
				
				} else {
					
					$bidError = 0;
					switch($_POST["which_package"]) {
						
						case 1 :
				
							if($_POST["bid"] < ($publisher->priceF)) {
					
								$error .= $loc->label("The bid must be higher than lower bound.") . "<br/>";
								$bidError = 1;
					
							}
							
							break;
							
						case 2 :
				
							if($_POST["bid"] < ($publisher->priceT)) {
					
								$error .= $loc->label("The bid must be higher than lower bound.") . "<br/>";
								$bidError = 1;
					
							}
							
							break;
							
						case 4 :
				
							if($_POST["bid"] < ($publisher->priceY)) {
					
								$error .= $loc->label("The bid must be higher than lower bound.") . "<br/>";
								$bidError = 1;
					
							}
							
							break;
							
						case 99 :
				
							if($_POST["bid"] < ($publisher->priceF + $publisher->priceT + $publisher->priceY)) {
					
								$error .= $loc->label("The bid must be higher than lower bound.") . "<br/>";
								$bidError = 1;
					
							}
							
							break;
						
						case 999 :
				
							if($_POST["bid"] < ($publisher->priceF + $publisher->priceT + $publisher->priceY)) {
					
								$error .= $loc->label("The bid must be higher than lower bound.") . "<br/>";
								$bidError = 1;
					
							}
							
							break;
							
						default:
							
							$error .= $loc->label("The bid must be higher than lower bound.") . "<br/>";
							$bidError = 1;
						
							break;
					}
					
					if($bidError == 0) {
						
						if($_POST["bid"] > $user->balance) {
							
							$error .= $loc->label("You do not have &s you entered for the bid.") . "<br/>";
						
						}
						
					}
				
				}
				
			}
			
			}
			
			} else {
				$error .= $loc->label("No such publisher.") . "<br/>";
			}
			
		}
		
		if(!empty($_FILES['document'])) {

			$randDocument = "";
			
			do {
				$randDocument = rand ( 1000000, 9999999 );
				$sql = "SELECT 1 FROM publisherPosts WHERE document='$randDocument.*'";
				$runsql->executenonquery ( $sql, NULL, false );
			} while ( $runsql->recordCount != 0 );
			
			if($randDocument == "") {
				
				$error .= $loc->label("There is a problem. Try again.") . "<br/>";
				
			} else {
				
				if($error == "") {
				
					$fn1 = new functions ();
					$document = $fn1->uploadDoc ($_FILES["document"],"../Uploads/publisherPosts/",$randDocument,2,"postDoc",1000000);    
					
				
					if (strpos($document, 'XX//-_OK_-\\XX') === false) {
						$error .= $document . "<br/>";
					} else {
						$document = str_replace('XX//-_OK_-\\XX', '', $document);
					}
				
				}

			}
			
		} else {
			$document = NULL;
		}
		
		
		if($error == "") {
			
			$post = new publisherPosts();
			$post->userID = $_SESSION["userID"];
			$post->publisherID = $_POST["publisherID"];
			$post->platformID = $_POST["which_package"];
			$post->title = $_POST["title"];
			$post->details = $_POST["details"];
			$post->link = $_POST["link"];
			$post->document = $document;
			$post->bid = $_POST["bid"];
			$post->pConfirm = 0;
			$post->status = 2;
			$post->createddate_ = new DateTime(date('Y/m/d H:i:s'));
			$post->duedate_ = NULL;
			$result = $post->save();
			
			if($result > 0) {
				
				$user->balance= str_replace(",",".",($user->balance-$_POST['bid']));
				$user->save();
			
				echo "ok";
			}  

		} else {
			
			echo $error;
			
		}
		
		break;
		
	case "getNowBalance" : 
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		
		$user = new users($_SESSION["userID"]);
		if($user->balance == NULL) {
			$now = 0;
		} else {
			$now = $user->balance; 
		}
		echo bcdiv($now, 1, 2);
		
		break;
		
	case "removeUserImage" :
		require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
		$user = new users($_SESSION["userID"]);
		
		if($_POST["image"] == "profile") {
			
			if($user->picture != "") {
			
				unlink ("../Uploads/userImg/" .  $user->picture);
				
				$user->picture = "";
				$user->save();
				
				echo "ok";
				
			}
			
		} else if($_POST["image"] == "cover") {
			
			if($user->coverPicture != "") {
			
				unlink ("../Uploads/userCoverImg/" .  $user->coverPicture);
				
				$user->coverPicture = "";
				$user->save();
				
				echo "ok";
				
			}
			
		}
		

	}




	
}


?>

