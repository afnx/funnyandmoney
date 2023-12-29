<?php

require_once dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) . "/DL/DAL.php";

use data\TableItem;

class users extends TableItem {

	// fields

	public $ID;
	
	public $username;
	
	public $fullname;
	
	public $asWho;
	
	public $token;

	// Counctructor

	function __construct($ID = NULL) {

		parent::__construct ();
		$this->setdbName('subfun2f432ds');
		$this->ID = $ID;

		$this->settable ( "users" );

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
	
	public static function getUserFromUsername($username) {
		$intc = new self ();
		$intc->refreshProcedure ( "select * from users where username='" . $username . "' limit 1" );
		return $intc;
	}
}

?>