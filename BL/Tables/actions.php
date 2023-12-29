<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;

class actions extends TableItem {
	
	// fields
	public $ID;
	
	public $action;
	
	public $point;	
	
	public $isDeleted;
	
	function __construct($ID = NULL) {
		
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "actions" );
		
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

}

?>