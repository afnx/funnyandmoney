<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class userSocials extends TableItem {
	
	// fields
	public $ID;
	public $platformID;
	public $userID;
	public $platformUserID;
	public $followerCount;
	public $friendsCount;
	public $pageCount;
	public $postCount;
	public $sharedPageCount;
	public $token;
	public $refreshToken;
	public $willExpireAt;
	public $secret;
	public $screenName;
	public $location;
	public $lang;
	public $updatedate_;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "userSocials" );
		
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
	public static function getUserSocialFromID($userID, $platformID) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from userSocials where  userID=$userID and platformID=$platformID and isDeleted<>1" );
		return $intc;
	}
	function getUserSocial($userID, $platformID) {
		$userID = $this->checkInjection($userID);
		$platformID = $this->checkInjection($platformID);
		$sql = "SELECT * FROM userSocials WHERE userID=$userID and platformID=$platformID and isDeleted<>1";
		return $this->executenonquery ( $sql );
	}
}

?>