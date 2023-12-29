<?php
namespace data;
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
use \db;
use \history;
use \authorization;
use \mysqli;
require_once dirname(dirname(__FILE__))."/BL/Tables/history.php";
require_once dirname(dirname(__FILE__))."/BL/Enums/enums.php";
require_once dirname(dirname(__FILE__))."/BL/Consts/consts.php";

class DAL{
	var $con;
	var $dbName;
	
	function __construct() 
	{	
		$this->openCon();
		
	}
	
	function __destruct() {
		
		$this->closeCon();
	}
	
	function openCon()
	{
		if( !is_resource($this->con) || ($this->getdbName()!=""))
		{	
 			$conn=new mysqli(db::Server,db::Username,db::Password,$this->getdbName(),db::PortNumber,db::Socket);
 			$this->con = $conn;	
 			mysqli_query($conn, "SET NAMES 'utf8';");
 			
 			if ($this->getdbName()!="")
 			{
 				$databaseName= $this->getdbName();
 				
 			} else
 			{
 				$databaseName = db::Database;
 			}
 			mysqli_select_db ($this->con,$databaseName) or die(header( "refresh:1;" ));  
			
		} 
	}
	function closeCon()
	{
		if(is_resource($this->con))
		{
			mysqli_close($this->con);
		}
	}
	function setdbName($dbName)
	{
		$this->dbName = $dbName;
	}
	function getdbName()
	{
		return $this->dbName;
	}
	
}	


class DALProsess extends DAL

{	
	public $recordCount;
	public $toJson;
	
	function __construct()
	
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function checkInjection ($value)
	{
		return mysqli_real_escape_string($this->con,$value);
	}
	
	public function beginTransaction()
	{
		
		return mysqli_query($this->con,"BEGIN");
	}
	
	public function commit()
	{
		return mysqli_query($this->con,"COMMIT");
	}
	
	public function rollback()
	{
		return mysqli_query($this->con,"ROLLBACK");
	}
	
	function setRecordCount($result)
	{
			$this->recordCount = mysqli_num_rows($result);
	}
	
	
	public function executenonquery($sql,$toJson=NULL,$exec=false)
	{
		if (is_resource($this->con))
		{
			$this->closeCon();
		}
		
		$this->openCon();
		//echo $sql;
		$result = mysqli_query($this->con,$sql) or die (""); 
		$this->closeCon();
		
		if (!$result)
		{
			return mysqli_errno($this->con).":" . mysqli_error($this->con);
			
		}
		else
		{
			if ($exec==false) {
				$this->setRecordCount($result);
			} else {
				$this->recordCount=0;
			}
			
			return $result;
		}
		
	}
	
	public function fieldCount () {
		return mysqli_field_count($this->con);	
	}
	
	public function num_rows($result) {
		return (is_resource($result) ? mysqli_num_rows($result) : 0);
	}
	
	public function redirectUrl($url,$permanent = false){
		if($permanent)
		{
			header('HTTP/1.1 301 Moved Permanently');
		}
		session_write_close();
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$url.'">';
		exit();
	}
	public function redirectBack(){
		
		session_write_close();
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.getenv("HTTP_REFERER").'">';
		exit();
	}
	
	public function json_turkish($dizi)
	{
		foreach($dizi as $record){
			foreach($record as $key=>$og){
				$colm[]='"'.$key.'":"'.$og.'"';
			}
			$rec[]='{'.implode(',', $colm).'}';
			unset($colm);
		}
		$sonuc='['.implode(',', $rec).']';
		
		return $sonuc;
	}
	
}	

class TableItem extends DALProsess
{
	
	//properties 
	public $table;
	
	
	function __construct()
	
	{
		
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	function settable($table)
	{
		$this->table = $table;
	}
	function gettable()
	{
		return $this->table;
	}
	
	
	
	//methods
	public function refresh($ID)
	{
		if (is_numeric($ID))
		{
		$sql = "select * from " . $this->table . " where " . key($this) . "=" .$ID;
		$this->openCon();
		$query=mysqli_query($this->con,$sql) or die(""); 
		$this->closeCon();
		
		$result= mysqli_fetch_array($query);
		if ($result) 
		{
			foreach ($result as $name => $value) {
			$this->$name=$value;
			}
		}
		}
	
	}
	
	public function refreshProcedure($sql)
	{
		if ($sql!='')
		{
			
			$this->openCon();
			$query=mysqli_query($this->con,$sql) or die("");
			$this->closeCon();
	
			$result= mysqli_fetch_array($query);
			if ($result)
			{
				foreach ($result as $name => $value) {
					$this->$name=$value;
				}
			}
		}
	
	}
	public function toJson()
	{
		
		$classItems = get_object_vars($this);
		$json = "{";
		foreach ($classItems as $key => $val) {
			if ($key!=='table' && $key!=='con' && !is_numeric($key))
			{
				$json.= "\"".$key."\":\"".$val."\",";
						
			}
			
		}
		$json = substr($json, 0,strlen($json)-1);
		$json.="}";
		return $json;
	}
	
	public function save()
	{
				$userID = (isset($_SESSION['userID']) ? $_SESSION['userID'] : 0);
				/*
				if ($userID==0 && $this->table!='users') {
						//http_redirect("../Masters/mainLayout.php?menuID=4", array("name" => "value"), true, HTTP_REDIRECT_PERM);
						$this->redirectUrl("../Masters/mainLayout.php?menuID=4");
						exit;
						
				}
				*/
				$this->openCon();
				$classitems = get_object_vars($this);
				
				try{
				unset($classitems['perpage']);
				unset($classitems['pagenum']);
				unset($classitems['random']);
				}finally{}
				$lastInsert= 0;
				$vars = NULL;
				
				if (!is_numeric($this->ID) || $this->ID==0 ) {
				
					$sql="insert into " . $this->table . " (" ;
					foreach ($classitems as $key => $val) {
						if ($key !=='ID' && $key !=='id' && $key !=='table' && $key !=='dbName' && $key !=='con' &&  $key !=='recordCount' && $key !=='toJson' && !is_numeric($key))
							$sql .= $key.',';
					}
					$sql = substr($sql, 0, strlen($sql) - 1);
					$sql .= ') VALUES (';
					foreach ($classitems as $key => $val) {
						if ($key !=='ID' && $key !=='id' && $key !=='table' && $key !=='dbName' && $key !=='con'  && $key !=='recordCount' && $key !=='toJson' && !is_numeric($key)) {
							if ($key=='updated' || $key=='created')
							{
								$sql.="now(),";
							}
							elseif ($key=='isDeleted')
							{
								$sql.="0,";
							}
							elseif (strpos($key,'date_')!== FALSE)
							{
								$sql.="now(),";
							}
							elseif (strpos($key,'by_')!== FALSE)
							{
								$sql.=(isset($_SESSION['userID']) ? $_SESSION['userID'] : 0).",";
							}
							else
							{
									if (is_numeric($val))
									{
										$sql .= str_replace("'on'","True",str_replace("'NULL'","NULL" ,mysqli_real_escape_string($this->con,is_null($val) ? "NULL":$val))) . ",";
									}
									else
									{
										$sql .= str_replace("'on'","True",str_replace("'NULL'","NULL" ,"'" .mysqli_real_escape_string($this->con,is_null($val) ? "NULL":$val) ."'")) . ",";
									}
							}	
							}	
					}
					$sql = substr($sql, 0, strlen($sql) - 1);
					$sql .= ')';
				}
				else
				{
					$sql="update " . $this->table . " set " ;
					foreach ($classitems as $key => $val) {
						if ($key !=='ID' && $key !=='id' && $key !=='table' && $key !=='dbName' && $key !=='con' &&  $key !=='recordCount' && $key !=='toJson' && !is_numeric($key) ) {
							if (!is_null($val) || $key == 'updated' || $key == 'isDeleted' || strpos($key,'date_')==true || strpos($key,'by_')==true)
							{
								if($key != 'createddate_' && $key != 'registerdate_'){
										$sql .= $key.'=';
								}
								
								if ($key=='updated' || $key=='created')
								{
									$sql.="now(),";
								}
								elseif ($key=='isDeleted')  
								{
									$sql.="0,";
								}
								//Some date fields may change per update due to the following lines(name similarity)!
								elseif (strpos($key,'date_')!== FALSE)
								{
									if($key != 'createddate_' && $key != 'registerdate_'){
										$sql.="now(),";
									}
								}
								elseif (strpos($key,'by_')!== FALSE)
								{
									$sql.=(isset($_SESSION['userID']) ? $_SESSION['userID'] : 0).",";
								}
								else
								{
									if (is_numeric($val))
									{
										$sql .= str_replace("'on'","True",str_replace("'NULL'","NULL" ,mysqli_real_escape_string($this->con,is_null($val) ? "NULL":$val))) . ",";
									}
									else
									{
										$sql .= str_replace("'on'","True",str_replace("'NULL'","NULL" ,"'" .mysqli_real_escape_string($this->con,is_null($val) ? "NULL":$val) ."'")) . ",";
									}
								}
							}
						}
						else
						{
							if ($key =='ID' && !is_numeric($key)) {
								$sqlwhere = " where ";
								$sqlwhere .= $key.'=';
								$sqlwhere .= str_replace("'NULL'","NULL" , is_null($val)? "NULL":$val) ;
								} 
						}
					}
					$sql = substr($sql, 0, strlen($sql) - 1);
						
					$sql = $sql . $sqlwhere;
				}
				try
				{	
					//echo $sql;
					//History operasyonu
					
					if (!mysqli_query($this->con,$sql)) {
						echo mysqli_error($this->con);
					};
					
					if (substr($sql, 0, 6) == "insert")
					{
						$operation = 1;
						$lastInsert = mysqli_insert_id($this->con);
					}
					else
					{
						$operation = 2;
						$lastInsert=$this->ID;
					}
					$this->closeCon();
					$this->history($lastInsert, $operation);
					return $lastInsert;
				}
				catch (\Exception $error)
				{
					echo "Hata ".$error;
					return 0;
				}
				
		}

	
	public function createGUID() 
	{
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = ""// "{"
			.substr($charid, 0, 8).$hyphen
			.substr($charid, 8, 4).$hyphen
			.substr($charid,12, 4).$hyphen
			.substr($charid,16, 4).$hyphen
			.substr($charid,20,12)
			."";// "}"
			return $uuid;
		}
	}
	
	public function delete($force=0)
	{
				if ($force==1)
				{
					$sql="delete from " . $this->table . " where " . key($this) . "=" .$this->ID;
						
				}
				else
				{
					$sql="update " . $this->table . " set isDeleted=1 where " . key($this) . "=" .$this->ID;
				}
				try {
					//echo $sql;
					$this->openCon();
					mysqli_query($this->con,$sql );
					$this->closeCon();
					
					$this->history( $this->ID, 3);
					return true;
				}
				catch (\Exception $error)
				{
					echo "Hata ".$error;
					return false;
				}
				
	}
	
	public function deleteAll()
	{
			//$con = new DAL();
			$sql="delete from " . $this->table ;
			try
			{
			$this->openCon();
			mysqli_query($this->con,$sql );
			$this->closeCon();
			
			return true;
			}
			catch (\Exception $error)
			{	
				echo "Hata ".$error;
				return false;
			}
	}
	
	public function history($ID,$operation)
	{
		//$con = new DAL();
		try
		{
			//operation 1-New 2-Update 3-Delete
			$sqlhist = "insert into history (tableName,tableID,userID,operation,updated) values ('".$this->table."',".$ID.",".$this->UID.",".$operation.",now())";
			//echo $sqlhist;
			$this->openCon();
			mysqli_query($this->con,$sqlhist);
			$this->closeCon();
			
			return true;
		}
		catch (\Exception $error)
		{
			echo "Hata " . $error;
			return false; 
		}
	}
	public function tableRecordCount()
	{
		$sql = "select count(*) from ".$this->table;
		$count = $this->executenonquery($sql);
		
		$result =mysqli_fetch_array($count);
		return $result;
		
		
	}
	
	
}

?>