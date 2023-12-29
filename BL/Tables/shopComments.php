<?php

require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";



use data\TableItem;

class shopComments extends TableItem {

	

	// fields

	public $ID;

	public $userID;
	
	public $giftID;
	
	public $comment;
	
	public $status;
	
	public $date_;
	
	public $isDeleted;

	

	// Counctructor

	function __construct($ID = NULL) {

		parent::__construct ();

		$this->ID = $ID;

		$this->settable ( "shopComments" );

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
	
	function getComments($giftID, $pagenum=1, $limit=10) {
		
		$giftID = $this->checkInjection($giftID);
		$pagenum = $this->checkInjection($pagenum);
		$limit = $this->checkInjection($limit);
		$sql = "call pgetShopComments($giftID,$pagenum,$limit)";

		return $this->executenonquery ( $sql );

	}
	
	function getCommentCount($giftID) {
		$giftID = $this->checkInjection($giftID);
		$sql = "SELECT COUNT(*) FROM shopComments WHERE giftID=$giftID AND status=1 AND isDeleted<>1";
		return $this->executenonquery ( $sql );
	}
	
	function get2OldC($userID) {
		$userID = $this->checkInjection($userID);
		$sql = "SELECT date_ FROM shopComments WHERE userID=$userID AND status=1 AND isDeleted<>1 ORDER BY date_ DESC LIMIT 1, 1";
		return $this->executenonquery ( $sql );
	}

}



?>