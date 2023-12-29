<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class publisherApplications extends TableItem {  
	
	// fields
	public $ID;
	public $userID;  
	public $firstName;
	public $lastName;
	public $birthDate;
	public $genderGroups;
	public $nationality;
	public $identityID;
	public $identityDocument;  
	public $proofAddress;
	public $email;
	public $phone;
	public $address;
	public $region;
	public $postalCode;
	public $city;
	public $country;
	public $description;
	public $facebook;
	public $twitter;
	public $youtube;
	public $category;
	public $language;
	public $ageGroups;
	public $countryGroups;
	public $status;
	public $date_;
	public $isDeleted;  
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "publisherApplications" );
		
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
	
	public static function checkUserPublishForm($userID) { 
		$intc = new self ();
		$intc->refreshProcedure ( "select * from publisherApplications where isDeleted<>1 and userID='$userID'");
		return $intc;
	}
	
	function getPublishForm($userID) {  
		$sql = "SELECT * FROM publisherApplications WHERE userID = '" . $userID . "'";
		return $this->executenonquery ( $sql ); 
	}

}

?>