<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class users extends TableItem {
	
	// fields
	public $ID;
	public $fbUserID;
	public $email;
	public $fullName;		
	public $username;
	public $picture;
	public $coverPicture;
	public $age;
	public $gender;
	public $country;
	public $language;
	public $education;
	public $work;
	public $password;
	public $mobileNumber;
	public $birthDate;
	public $religion;
	public $location;
	public $relationship;
	public $homeTown;
	public $livesIn;
	public $income;
	public $about;
	public $registerdate_;
	public $status;
	public $balance;
	public $categoryID;
	public $email_code;
	public $signupStep;
	public $IBAN;
	public $bankFirstName;
	public $bankLastName;
	public $publisher;  
	public $phone;
	public $city;
	public $pendingEmail;
	public $loginDate;
	public $loginAttempt;
	public $loginAttemptTime;
	public $referrerON;
	public $referrerID; 
	public $isDeleted;  
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "users" );
		
		$this->refresh ( $ID );
	}
	function __set($property, $value) {
		$this->$property = $value;
	}
	function __get($property) {
		if (isset ( $this->$property )) {
			return $this->$property;
		}
	}
	public static function checkUser($email, $password) {
		$intc = new self ();
		// echo "select * from users where isDeleted=1 and email='".$email. "' and password='".md5($password)."'";
		$intc->refreshProcedure ( "select * from users where isDeleted<>1 and (email='" . $email . "' or username= '$email') and password='" . password_hash($password, PASSWORD_DEFAULT) . "'" );
		return $intc;
	}public static function checkUserEmail($email) {
		$intc = new self ();
		// echo "select * from users where isDeleted=1 and email='".$email. "' and password='".md5($password)."'";
		$intc->refreshProcedure ( "select * from users where isDeleted<>1 and signupStep=-1 and email='" . $email . "' limit 1" );
		return $intc;
	}public static function checkUserEmailAct($email) {
		$intc = new self ();
		// echo "select * from users where isDeleted=1 and email='".$email. "' and password='".md5($password)."'";
		$intc->refreshProcedure ( "select * from users where isDeleted<>1 and signupStep<>0 and email='" . $email . "' limit 1" );
		return $intc;
	}public static function checkUserWithoutPass($email) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from users where isDeleted<>1 and (email='" . $email . "' or username= '" . $email . "')");
		return $intc;
	}public static function checkUserWithUsername($username) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from users where isDeleted<>1 and username='$username'");
		return $intc;
	}
	public static function getUserFromEmail($email) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from users where isDeleted<>1 and email='" . $email . "' limit 1" );
		return $intc;
	}
	public static function getUserFromFacebook($fbUserID) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from users where isDeleted<>1 and fbUserID='" . $fbUserID . "' limit 1" );
		return $intc;
	}
	
	function getBalance($ID) {
		$sql = "SELECT balance FROM users WHERE ID = '" . $ID . "'";
		return $this->executenonquery ( $sql );
	}		
	function checkUserID($ID) {	
		$sql = "SELECT ID FROM users WHERE ID = '" . $ID . "' LIMIT 1";		
		return $this->executenonquery ( $sql );	
	}
	
	public static function checkUserWithID($ID) {	
		$intc = new self ();
		$intc->refreshProcedure ( "SELECT * FROM users WHERE ID = '" . $ID . "' and isDeleted<>1" );		
		return $intc;
	}
	
	function getUserID($username) {
		$username = $this->checkInjection($username);
		$sql = "SELECT ID FROM users WHERE username = '" . $username . "'";
		return $this->executenonquery ( $sql ); 
	}
	
	public static function checkUserEmailForLogin($email) {
		$intc = new self ();
		// echo "select * from users where isDeleted=1 and email='".$email. "' and password='".md5($password)."'";
		$intc->refreshProcedure ( "select * from users where isDeleted<>1 and (email='" . $email . "' or username= '$email')" );
		return $intc;
	}
	
	function getRefCount($userID) {  
		$userID = $this->checkInjection($userID);
		$sql = "SELECT COUNT(*) FROM users WHERE referrerID = '" . $userID . "' AND isDeleted<>1";
		return $this->executenonquery ( $sql ); 
	}
	
	function getAllUsers() {  
		$sql = "SELECT * FROM users WHERE isDeleted<>1";
		return $this->executenonquery ( $sql );   
	}
	
	function getUserIneffEmail($email) {
		$email = $this->checkInjection($email);
		$sql = "select * from users where isDeleted<>1 and signupStep<>-1 and email='" . $email . "'";
		return $this->executenonquery ( $sql ); 
	}
	
	function getUserIneffEmailAct($email,$userID) {
		$email = $this->checkInjection($email);
		$sql = "select * from users where ID<>'".$userID."' and isDeleted<>1 and signupStep=0 and email='" . $email . "'";
		return $this->executenonquery ( $sql ); 
	}
	
	function getAllUsersFilter($limit,$search,$country,$age,$gender,$status,$sort) {  
		
		$limit = $this->checkInjection($limit);
		$search = $this->checkInjection($search);
		$country = $this->checkInjection($country);
		$age = $this->checkInjection($age);
		$gender = $this->checkInjection($gender);
		$status = $this->checkInjection($status);
		$sort = $this->checkInjection($sort);
		
		$sit1= "0=0";
		$sit2 = "FIND_IN_SET(".$age.", age)>0";
		switch($sort) {
			
			case 1 :
				$str= "registerdate_";
				$which= "desc";
				break;
				
			case 2 :
				$str= "registerdate_";
				$which= "asc";
				break;
				
			case 3 :
				$str= "balance";
				$which= "desc";
				break;
			default:
				$str= "registerdate_";
				$which= "desc";
				break;
			
		} 
		
		$sql = "SELECT * FROM users WHERE (fullName LIKE '%" . $search . "%' or username LIKE '%" . $search . "%' or  email LIKE '%" . $search . "%') AND (" . $country . "=0 or country='" . $country . "') AND isDeleted='$status' AND
		(" . $gender . "=0 or gender='" . $gender . "') AND ". ( ( $age==0 ) ? $sit1 : $sit2 ) ." ORDER BY " . $str . " " . $which . " ". (($limit!=0) ? "LIMIT $limit" : "") . "";   

		return $this->executenonquery ( $sql );    
		
	}
	
}

?>