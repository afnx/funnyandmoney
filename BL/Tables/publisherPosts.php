<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class publisherPosts extends TableItem {
	
	// fields
	public $ID;
	public $userID;
	public $publisherID;
	public $platformID;
	public $title;
	public $details;
	public $link;
	public $document;
	public $bid;
	public $pConfirm;
	public $userOk;
	public $publisherOk;  
	public $createddate_;
	public $confirmdate;
	public $duedate_;
	public $status;
	public $isDeleted;
	
	// Counctructor
	function __construct($ID = NULL) {
		parent::__construct ();
		
		$this->ID = $ID;
		
		$this->settable ( "publisherPosts" );
		
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
	
	public static function cehckPostDocument($userID, $publisherID, $document) { 
		$intc = new self ();
		$intc->refreshProcedure ( "select * from publisherPosts where  (userID=$userID or publisherID=$publisherID) and document='$document' and isDeleted<>1" );
		return $intc;
	}

}

?>