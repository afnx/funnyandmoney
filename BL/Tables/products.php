<?php

require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";



use data\TableItem;

class products extends TableItem {

	

	// fields

	public $ID;
	
	public $category;

	public $productName;

	public $description;
	
	public $quantity;
	
	public $price;
	
	public $noDiscount;
	
	public $imagePath;
	
	public $duedate_;
	
	public $status;

	

	// Counctructor

	function __construct($ID = NULL) {

		parent::__construct ();

		

		$this->ID = $ID;

		

		$this->settable ( "products" );

		

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
	
	function getProducts($category) {

		$category = $this->checkInjection($category);
		$sql = "SELECT * FROM products WHERE category = '" . $category . "' AND status=1";

		return $this->executenonquery ( $sql );

	}

}



?>