<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class definitions extends TableItem {
	
	// fields
	public $ID;
	public $definition;
	public $definitionID;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct (); 
		
		$this->ID = $ID;
		
		$this->settable ( "definitions" );
		
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
	public static function getDef($defID) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from definitions where definitionID='$defID'");
		return $intc;
	}
	
	function getAllDef($defID) {  

		$sql = "select * from definitions where definitionID='$defID'";

		return $this->executenonquery ( $sql );     

	}
	
	function getAllDefCho($defID) {  

		$sql = "select * from definitions where definitionID='$defID' and isDeleted<>1";

		return $this->executenonquery ( $sql );     

	}
	
}

?>