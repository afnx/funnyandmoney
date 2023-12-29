<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class banks extends TableItem {
	
	// fields
	public $ID;
	
	public $name;
	
	public $image;
	
	public $accountOwner;
	
	public $branchName;
	
	public $branchCode;
	
	public $accountNo;
	
	public $iban;
	
	public $country;
	
	public $currency;
	
	public $status;
	
	public $isDeleted;  
	
	// Counctructor
	function __construct($ID = NULL) {
		
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "banks" );
		
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
	
	function getBanks() {		
	
		$sql = "SELECT * FROM banks WHERE status=1 AND isDeleted<>1";		 
	
		return $this->executenonquery ( $sql );	
	}
}

?>