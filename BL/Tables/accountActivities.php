<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class accountActivities extends TableItem {
	
	// fields
	public $ID;
	public $adminID;
	public $amount;
	public $currency;
	public $waccountID;
	public $daccountID;
	public $operation; 
	public $description;
	public $date_;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "accountActivities" );
		
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