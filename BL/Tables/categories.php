<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class categories extends TableItem {
	
	// fields
	public $ID;
	public $category;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "categories" );
		
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