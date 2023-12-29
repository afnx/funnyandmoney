<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class admins extends TableItem {
	
	// fields
	public $ID;
	public $userID;
	public $rank;
	public $pin;
	public $adminNotes;
	
	// Counctructor
	function __construct($ID = NULL) {
		
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "admins" );
		
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
	
	public static function checkAdminWithPin($userID, $pin) {  
		
		$intc = new self ();
		$intc->refreshProcedure ( "select * from admins where userID='" . $userID . "' and pin='" . $pin . "' ");
		return $intc;
		
	}
	
	public static function checkAdmin($userID, $rank=0) {
		
		$intc = new self ();
		$intc->refreshProcedure ( "select * from admins where userID='" . $userID . "' and " . (($rank!=0) ? "rank='" . $rank . "'" : "0=0") . "");
		return $intc;
		
	}
	
	public static function checkRank($userID, $rank) {  
		
		$intc = new self ();
		$intc->refreshProcedure ( "select * from admins where userID='" . $userID . "' and rank='" . $rank . "'");
		return $intc;
		
	}

	
}

?>