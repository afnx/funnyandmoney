<?php

require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";



use data\TableItem;

class shopLikes extends TableItem {

	

	// fields

	public $ID;

	public $userID;
	
	public $giftID;
	
	public $userLike;
	
	public $date_;
	
	public $isDeleted;

	

	// Counctructor

	function __construct($ID = NULL) {

		parent::__construct ();

		$this->ID = $ID;

		$this->settable ( "shopLikes" );

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
	
	function getLikeCount($giftID) {
		$giftID = $this->checkInjection($giftID);
		$sql = "SELECT COUNT(*) FROM shopLikes WHERE giftID=".$giftID." AND userLike=1 AND isDeleted<>1";
		return $this->executenonquery ( $sql );
	}

	function getunLikeCount($giftID) {
		$giftID = $this->checkInjection($giftID);
		$sql = "SELECT COUNT(*) FROM shopLikes WHERE giftID=".$giftID." AND userLike=-1 AND isDeleted<>1";
		return $this->executenonquery ( $sql );
	}
	
	function getUserLike($userID,$giftID) {
		$giftID = $this->checkInjection($giftID);
		$userID = $this->checkInjection($userID);
		$sql = "SELECT * FROM shopLikes WHERE giftID=".$giftID." AND userID=". $userID ." AND isDeleted<>1 LIMIT 1";
		return $this->executenonquery ( $sql );
	}

}



?>