<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class currency extends TableItem {
	
	// fields
	public $ID;
	public $fromName;
	public $toName;
	public $monetaryValue;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "currency" );
		
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
	public static function getCurrency($fromName, $toName) {
		$intc = new self ();
		$intc->refreshProcedure ( "SELECT * FROM currency WHERE fromName = '$fromName' AND toName='$toName' AND isDeleted=0" );
		return $intc;
	}
}

?>