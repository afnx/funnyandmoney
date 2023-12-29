<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class publishers extends TableItem {  
	
	// fields 
	public $ID;
	public $userID;  
	public $applicationID;  
	public $name;
	public $description;
	public $priceF;
	public $priceT;
	public $priceY;
	public $facebook;
	public $facebookPageName;
	public $facebookPageLink;
	public $facebookPageLikes;
	public $twitter;
	public $youtube;
	public $youtubeChannelLink;
	public $youtubeSubscriber;
	public $category;
	public $language;
	public $a1318PF;
	public $a1924PF;
	public $a2534PF;
	public $a3544PF;
	public $a4554PF;
	public $a5564PF;
	public $a65PF;
	public $a1318PM;
	public $a1924PM;
	public $a2534PM;
	public $a3544PM;
	public $a4554PM;
	public $a5564PM;
	public $a65PM;
	public $cN1;
	public $cN2;
	public $cN3;
	public $cN4;
	public $cN5;
	public $c1P;
	public $c2P;
	public $c3P;
	public $c4P;
	public $c5P;
	public $cOtherP;
	public $score;
	public $status;
	public $date_;
	public $isDeleted;  
	
	//pagination
	private $perpageP;
	private $pagenumP;
	private $randomP;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "publishers" );
		
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
	
	public static function checkPublisherWithUserID($userID) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from publishers where isDeleted<>1 and userID='$userID'");
		return $intc;
	}
	
	function getPublishers($limit=0, $categoryP=0, $country=55, $gender=1, $age=17, $platform=0, $languageP=0, $sort=0, $searchP="") {   

		$sql = "call procedure_publishers($limit,$categoryP,$country,$gender,$age,$platform,$languageP,$sort,'$searchP',$this->randomP,$this->perpageP,$this->pagenumP)";
		return $this->executenonquery ( $sql );  

	} 


}

?>