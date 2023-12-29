<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class countries extends TableItem {
	
	// fields
	public $ID;
	public $country;
	public $longName;
	public $countryCode;
	public $language;
	public $callingCode;
	public $currencyCode;
	public $currencyName;
	public $currencySymbol;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "countries" );
		
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