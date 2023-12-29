<?php

require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;

class gifts extends TableItem {

	// fields

	public $ID;
	
	public $name;
	
	public $description;
	
	public $picture;
	
	public $price;
	
	public $category;
	
	public $quantity;
	
	public $numberOfSales;
	
	public $provider;
	
	public $deliverySpeed;
	
	public $isFeatured;
	
	public $isDigital;
	
	public $availableZone;  
	
	public $date_;
	
	public $isDeleted;

	//pagination
	private $perpageG;
	private $pagenumG;
	private $randomG;

	

	// Counctructor

	function __construct($ID = NULL) {

		parent::__construct ();  

		$this->ID = $ID;  

		$this->settable ( "gifts" );

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
	
	public static function checkGiftWithID($ID) {	
		$intc = new self ();
		$intc->refreshProcedure ( "SELECT * FROM gifts WHERE ID = '" . $ID . "' and isDeleted<>1" );		
		return $intc;
	}
	
	function getGifts($limit=0, $categoryID=0, $availableZone=0, $sort=0, $searchG="", $count=0) { 
		
		$limit = $this->checkInjection($limit);
		$categoryID = $this->checkInjection($categoryID);
		$availableZone = $this->checkInjection($availableZone);
		$sort = $this->checkInjection($sort);
		$searchG = $this->checkInjection($searchG);
		$count = $this->checkInjection($count); 

		if($count==1) {
			
			$sql = "call procedure_giftsCount($categoryID,$availableZone,$sort,'$searchG',$this->randomG)";
			return $this->executenonquery ( $sql );  
		
		} else {
			
			$sql = "call procedure_gifts($limit,$categoryID,$availableZone,$sort,'$searchG',$this->randomG,$this->perpageG,$this->pagenumG)";
			return $this->executenonquery ( $sql );  
			
		}
		
		//$sql = "SELECT * FROM gifts WHERE (name LIKE '%" . $search . "%' OR description LIKE '%" . $search . "%' ) AND isDeleted<>1"; 
		

		   

	}
	
	function getAdminGifts($provider=0,$limit=0,$search="",$category=0,$digital=3,$zone=3,$sort=0) {
		
		$provider = $this->checkInjection($provider);
		$limit = $this->checkInjection($limit);
		$search = $this->checkInjection($search);
		$category = $this->checkInjection($category);
		$digital = $this->checkInjection($digital);
		$zone = $this->checkInjection($zone); 
		$sort = $this->checkInjection($sort); 
		
		switch($sort) {
			
			case 0 :
				$str= "date_";
				break;
				
			case 1 :
				$str= "numberOfSales";
				break;
				
			case 2 :
				$str= "price";
				break;
			default:
				$str= "date_";
				break;
			
		}   

		$sql = "SELECT * FROM gifts WHERE (name LIKE '%".$search."%' or description LIKE '%".$search."%') AND (" . ((is_numeric($provider)) ? "".$provider."=0 or" : "") ." provider='".$provider."') AND
		(".$category."=0 or category='".$category."') AND (".$digital."=3 or isDigital='".$digital."') AND (".$zone."=3 or availableZone='".$zone."') AND isDeleted<>1
		ORDER BY ".$str." DESC LIMIT ".$limit."";  

		return $this->executenonquery ( $sql );    

	} 
}

?>