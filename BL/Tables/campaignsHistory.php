<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class campaignsHistory extends TableItem { 
	
	// fields
	public $ID;
	public $campaignID;
	public $userID;
	public $IPaddress;
	public $campaignCodeID;
	public $date_;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "campaignsHistory" );
		
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
	
	public static function checkUserCampaignWithIPaddress($campaignID,$startdate_="0000-00-00 00:00:00",$duedate_="0000-00-00 00:00:00",$address,$userID) {
		
		$intc = new self ();
		$intc->refreshProcedure ( "select * from campaignsHistory where isDeleted<>1 and campaignID='" . $campaignID . "' and (date_ between '" . $startdate_ . "' and '" . $duedate_ . "') and (IPaddress='" . $address . "' or userID='" . $userID . "') limit 1");
		return $intc;
		
	}
	
	function countCampaign($campaignID,$startdate_="0000-00-00 00:00:00",$duedate_="0000-00-00 00:00:00") {
		
		$sql = "select count(*) from campaignsHistory where isDeleted<>1 and campaignID='" . $campaignID . "' and (date_ between '" . $startdate_ . "' and '" . $duedate_ . "')";
		return $this->executenonquery ( $sql ); 
		
	}    

}

?>