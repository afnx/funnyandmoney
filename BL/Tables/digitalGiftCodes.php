<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class digitalGiftCodes extends TableItem {
	
	// fields
	public $ID;
	public $giftID;
	public $giftRequestID;
	public $userID;
	public $giftCode;
	public $descriptionText;
	public $expirationDate_;
	public $isUsed;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "digitalGiftCodes" );
		
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

	function getGiftsCode($giftID) {

		$giftID = $this->checkInjection($giftID);
		$sql = "SELECT * FROM digitalGiftCodes WHERE giftID='$giftID' and isUsed<>1 and expirationDate_ > NOW() ORDER BY RAND() LIMIT 1";

		return $this->executenonquery ( $sql );

	}
	
	function findGiftCode($giftRequestID) {

		$giftRequestID = $this->checkInjection($giftRequestID);
		$sql = "SELECT * FROM digitalGiftCodes WHERE giftRequestID='$giftRequestID' LIMIT 1";

		return $this->executenonquery ( $sql );

	}

	
}

?>