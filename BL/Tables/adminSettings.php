<?php

require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";



use data\TableItem;

class adminSettings extends TableItem {

	

	// fields

	public $ID;

	public $execution;
	
	public $status;
	
	public $minRank;
	

	

	// Counctructor

	function __construct($ID = NULL) {

		parent::__construct ();

		$this->ID = $ID;

		$this->settable ( "adminSettings" );

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
	

}



?>