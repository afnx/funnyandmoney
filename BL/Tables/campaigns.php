<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class campaigns extends TableItem {
	
	// fields
	public $ID;
	public $name;
	public $locLabel;
	public $quantity;
	public $lower;
	public $status;
	public $limit;
	public $startdate_;
	public $duedate_;  
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "campaigns" );
		
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