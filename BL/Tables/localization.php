<?php
use data\TableItem;
require_once dirname(dirname(dirname(__FILE__)))."/DL/DAL.php";

class localization extends TableItem
{
	//fields
	Public $ID;
	Public $identifier;
	Public $label;
	Public $lang;
	Public $isDeleted;
	
	// Counctructor
	function __construct($lang=NULL,$ID=NULL)
	{
		parent::__construct();
		$this->ID=$ID;
		$this->lang= $lang;
		$this->settable("localization");
		$this->refresh($ID);
	}
	function __set($property, $value)
	{
		$this->$property = $value;
	}
	function __get($property)
	{
		if (isset($this->$property))
		{
			return $this->$property;
		}
	}
	public function label($name,$lang = null) {
		if($lang == NULL){
			$lang= $this->lang;
		}
		//echo "SELECT label FROM localization WHERE lang = '" . ($lang == null ? "en" : $lang) . "' AND identifier  = '" . $name . "' and isDeleted<>1 LIMIT 1;";
		$qry = "SELECT label FROM localization WHERE lang = '" . ($lang == null ? "en" : $lang) . "' AND identifier  = '" . $name . "' and isDeleted<>1 LIMIT 1;";
		$resource = $this->executenonquery($qry);
		if (mysqli_num_rows($resource) != 1) {
			$returner = '**'.$name.'**';
		}
		else {
			$returner = mysqli_fetch_object($resource)->label;
		}
		$returner = ($this->replaceLinebreaks ? nl2br($returner) : $returner);
		return $returner;
	}
	
	function getLoc($lang) {
		
		$sql = "SELECT * FROM localization WHERE lang = '$lang'";
		return $this->executenonquery ( $sql ); 
	
	}

}
?>
