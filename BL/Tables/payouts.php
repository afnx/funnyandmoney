<?php

require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";



use data\TableItem;

class payouts extends TableItem {

	

	// fields

	public $ID;

	public $userID;
	
	public $cashNo;
	
	public $method;
	
	public $point;
	
	public $amount;
	
	public $currency;
	
	public $date_;
	
	public $result;
	
	public $token;

	public $isDeleted;

	

	// Counctructor

	function __construct($ID = NULL) {

		parent::__construct ();

		$this->ID = $ID;

		$this->settable ( "payouts" );

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
	
	public static function setUserPayout($userID,$result) {
		$intc = new self ();
		$intc->refreshProcedure ( "SELECT * FROM payouts WHERE isDeleted<>1 and userID=$userID  and ifnull(result,2)=$result order by ID desc LIMIT 1" );
		return $intc;
	}
	
	function getPayouts($userID) {
		$sql = "SELECT * FROM payouts WHERE userID=$userID and isDeleted<>1 order by ID desc";
		return $this->executenonquery ( $sql );
	}

}



?>