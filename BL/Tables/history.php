<?php

require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;

class history extends TableItem {

	// fields

	public $ID;
	
	public $tableName;
	
	Public $tableID;
	
	Public $userID;
	
	Public $operation;
	
	Public $updated;
	
	// share/view/click/like/follow/subscribe or adding post or taking gift



	

	// Counctructor

	function __construct($ID = NULL) {

		parent::__construct ();

		$this->ID = $ID;

		$this->settable ( "history" );

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
	
		function getLastUpdated ($tableName)
	{
		return $this->executenonquery("select userID,updated from history where operation in (1,2) and tableName='$tableName' order by updated desc limit 1");
	}
}



?>