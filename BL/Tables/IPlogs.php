<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class IPlogs extends TableItem {
	
	// fields
	public $ID;
	public $userID;
	public $address; 
	public $date_;

	
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "IPlogs" );
		
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
	
	function getLastIPaddress($userID) {
		
		$userID = $this->checkInjection($userID);
		$sql = "select address from IPlogs where userID='" . $userID . "' order by date_ desc limit 1";
		return $this->executenonquery ( $sql ); 
		
	} 
	
}

?>