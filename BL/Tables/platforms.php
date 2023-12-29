<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class platforms extends TableItem {
	
	// fields
	public $ID;
	public $platform;
	public $platformIcon;		public $platformLabel;
	public $platformBlankPicture;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "platforms" );
		
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