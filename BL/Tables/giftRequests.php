<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class giftRequests extends TableItem {
	
	// fields
	public $ID;
	public $giftID;
	public $userID;
	public $orderNo;
	public $date_;
	public $price;
	public $addressID;
	public $deliveryStatus;
	public $cargoFirm;
	public $cargoNo;
	public $providerNo;
	public $adminNote;
	public $preturn;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "giftRequests" );
		
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
	
		function getGiftRequests($userID) {
		$sql = "SELECT * FROM giftRequests WHERE userID=$userID and isDeleted<>1 order by ID desc";
		return $this->executenonquery ( $sql );
	}
		
	function contDelivery($userID, $addressID) {

		$sql = "SELECT ID FROM giftRequests WHERE userID ='$userID' AND addressID='$addressID' AND (deliveryStatus=1 OR deliveryStatus=2)";  

		return $this->executenonquery ( $sql );

	}
	
	public static function checkOrderWithID($ID) {	
		$intc = new self ();
		$intc->refreshProcedure ( "SELECT * FROM giftRequests WHERE ID = '" . $ID . "' and isDeleted<>1" );		
		return $intc;
	}
	
	function getAdminGiftsReq($user=0,$limit=0,$search="",$searchCargoNo,$product=0,$delivery=4) {

		$sql = "SELECT * FROM giftRequests WHERE ". (($search!="") ? "(orderNo LIKE '".$search."') AND " : "") ." ". (($searchCargoNo!="") ? "(cargoNo LIKE '".$searchCargoNo."' or providerNo LIKE '".$searchCargoNo."') AND " : "") ." (".$user."=0 or userID='".$user."') AND
		(".$product."=0 or giftID='".$product."') AND (".$delivery."=4 or deliveryStatus='".$delivery."') AND isDeleted<>1 ORDER BY deliveryStatus=2 DESC, deliveryStatus=1 DESC, date_ DESC 
		" . (($limit!=0) ? "LIMIT ".$limit."" : "") . "";  

		return $this->executenonquery ( $sql );       

	} 
	
}

?>