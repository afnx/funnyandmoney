<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class balance extends TableItem {
	
	// fields
	public $ID;
	
	public $postID;
	
	public $actionID; // like = 1, share = 2, follow = 3, view = 4
	
	public $actiondate_;
	
	public $userID;
	
	public $point;
	
	public $socialID;
	
	public $calculated;
	
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "balance" );
		
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
	public static function getUserAction($userID,$postID,$actionID) {
		
		$intc = new self ();
		
		$intc->refreshProcedure ( "select * from balance where userID=$userID and postID=$postID and actionID=$actionID" );
		
		return $intc;
	}		
	
	function getBalanceHistory($userID) {		
	
		$sql = "SELECT * FROM balance WHERE userID = '" . $userID . "' order by actiondate_ DESC LIMIT 10";		 
	
		return $this->executenonquery ( $sql );	
	}
	
	function getNowBalance($postID) {
		
		$sql = "SELECT * FROM balance WHERE postID = '" . $postID . "'";		
	
		return $this->executenonquery ( $sql );	
		
	}
	
	function getRefEarn($userID) {
		
		$sql = "SELECT SUM(point) FROM balance WHERE userID = '" . $userID . "' AND actionID=5 AND isDeleted<>1";
		
		return $this->executenonquery ( $sql ); 
		
	}
	
	function getPublisherPost($userID) {
		
		$sql = "SELECT COUNT(*) FROM balance WHERE userID = '" . $userID . "' AND actionID=6 AND isDeleted<>1";
		
		return $this->executenonquery ( $sql ); 
		
	}
}

?>