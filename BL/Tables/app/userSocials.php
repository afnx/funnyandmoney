<?php
require_once dirname( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) . "/DL/DAL.php";

use data\TableItem;
class userSocials extends TableItem {
	
	// fields
	public $ID;
	public $userID;
	public $platformUserID;
	public $friendsCount;
	public $token;
	public $willExpireAt;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		$this->setdbName('subfun2f432ds');
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
	public static function getUserSocialFromID($userID) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from userSocials where  userID=$userID" );
		return $intc;
	}
	function getUserSocial($userID) {
		$sql = "SELECT * FROM userSocials WHERE userID=$userID";
		return $this->executenonquery ( $sql );
	}
}

?>