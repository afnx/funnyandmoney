<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class payments extends TableItem {
	
	// fields
	public $ID;
	public $userID;		
	public $productID;
	public $salesNo;
	public $method;
	public $amount;
	public $bankID;
	public $currency;
	public $date_;
	public $payerID;
	public $token;
	public $result;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "payments" );
		
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
	public static function setUserPayment($userID,$result) {
		$intc = new self ();
		$intc->refreshProcedure ( "SELECT * FROM payments WHERE isDeleted<>1 and userID=$userID  and ifnull(result,1)=$result order by ID desc LIMIT 1" );
		return $intc;
	}public static function paymentQuery($query) {
		$intc = new self ();
		$intc->refreshProcedure ($query);
		return $intc;
	}
}

?>