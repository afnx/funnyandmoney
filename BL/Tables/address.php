<?php

require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;

class address extends TableItem {

	// fields

	public $ID;
	public $userID;
	public $recipientName;
	public $phone;
	public $addressLine1;
	public $addressLine2;
	public $region;
	public $postalCode;
	public $city;
	public $country;
	public $isDeleted;



	

	// Counctructor

	function __construct($ID = NULL) {

		parent::__construct ();

		$this->ID = $ID;

		$this->settable ( "address" );

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
	
	function getAddresses($userID) {

		$userID = $this->checkInjection($userID);
		$sql = "SELECT * FROM address WHERE isDeleted<>1 AND userID=$userID order by ID desc";

		return $this->executenonquery ( $sql );

	}
	
	function getAddressCount($userID) {

		$userID = $this->checkInjection($userID);
		$sql = "SELECT ID FROM address WHERE userID ='$userID' AND isDeleted<>1";

		return $this->executenonquery ( $sql );

	}
	 
}

?>