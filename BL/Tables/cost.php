<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class cost extends TableItem {
	
	// fields
	public $ID;
	public $actionID;
	public $serviceName;
	public $platformID; 
	public $count;
	public $point;		
	public $followerOrFirendCount;
	public $isDeleted;
	
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "cost" );
		
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
	
	function getCost($serviceName,$followerOrFirendCount=0) {
		if($followerOrFirendCount < 100){
			return 0;
		}else{
			if($followerOrFirendCount < 300){
				$definition= 30;
			}elseif($followerOrFirendCount < 500){
				$definition= 31;
			}elseif($followerOrFirendCount < 1000){
				$definition= 32;
			}elseif($followerOrFirendCount < 3000){
				$definition= 33;
			}elseif($followerOrFirendCount < 5000){
				$definition= 34;
			}elseif($followerOrFirendCount < 10000){
				$definition= 35;
			}elseif($followerOrFirendCount < 30000){
				$definition= 36;
			}elseif($followerOrFirendCount < 50000){
				$definition= 37;
			}elseif($followerOrFirendCount < 100000){
				$definition= 38;
			}elseif($followerOrFirendCount < 300000){
				$definition= 39;
			}elseif($followerOrFirendCount < 500000){
				$definition= 40;
			}elseif($followerOrFirendCount < 1000000){
				$definition= 333;
			}elseif($followerOrFirendCount < 3000000){
				$definition= 334;
			}elseif($followerOrFirendCount < 5000000){
				$definition= 335;
			}elseif($followerOrFirendCount < 7000000){
				$definition= 336;
			}elseif($followerOrFirendCount < 10000000){
				$definition= 337;
			}else{
				$definition= 338;
			}
			$sql = "SELECT point FROM cost WHERE serviceName='Share' and followerOrFirendCount=$definition";

			$result= mysqli_fetch_array ($this->executenonquery ( $sql ));
			return $result['point'];
		}
	}
	
	function getPointWithPlatform($service,$platformID) {
		
		$service = $this->checkInjection($service);
		$platformID = $this->checkInjection($platformID);
		
		$sql = "SELECT point FROM cost WHERE platformID = '" . $platformID . "' AND serviceName = '" . $service . "' AND isDeleted<>1";		
	
		return $this->executenonquery ( $sql );	 
		
	}
	
	function getPointWithShare($ffcount) {
		
		$ffcount = $this->checkInjection($ffcount);
		
		$sql = "SELECT point FROM cost WHERE serviceName='Share' AND followerOrFirendCount = '" . $ffcount . "' AND isDeleted<>1";		
	
		return $this->executenonquery ( $sql );	 
		
	}  
	
	public static function getCostAll($actionID,$platformID) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from cost where isDeleted<>1 and actionID='". $actionID ."' and platformID='". $platformID ."'");
		return $intc;
	}

}

?>