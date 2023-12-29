<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/DL/DAL.php";

use data\TableItem;
class posts extends TableItem {

	// fields
	public $ID;
	public $userID;
	public $platformID;
	public $createddate_;
	public $duedate;
	public $postType;
	public $title;
	public $description;
	public $categoryID;
	public $subCategoryID;
	public $country;
	public $city;
	public $gender;
	public $age;
	public $languages;
	public $education;
	public $work;
	public $likeCount;
	public $nowLike;
	public $shareCount;
	public $nowShare;
	public $oneSharerFollowerCount;
	public $sharePlatforms;
	public $followCount;
	public $nowFollow;
	public $viewCount;
	public $nowView;
	public $positionID; 
	public $relationship;
	public $postUrl;
	public $socialID;
	public $imagePath;
	public $status;
	public $videoDuration;
	public $lastEdited;
	public $adminNote; 
	public $isTurkish; 
	public $isDeleted;
	
	//pagination
	public $perpage;
	public $pagenum;
	public $random;
	private $exceptIDs;

	// Counctructor
	function __construct($ID = NULL) {

		parent::__construct ();

		$this->ID = $ID;

		$this->settable ( "posts" );

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

	function getPosts($userID,$limit=0,$rnd=0,$pagenum=1,$platform=0) {

		$sql = "call newpgetPosts($userID,$limit,$rnd,$pagenum,$platform)";

		return $this->executenonquery ( $sql );

	}

	function getmatchCategory($userID,$positionID=0,$postType,$limit=0,$platformID=0, $paginate=0) {


		if($paginate==0){
			
			$sql = "call pgetMatch($userID,$positionID,$postType,$limit,$platformID)";

		}else{
			$sql = "call test_procedureAFN($userID,$positionID,$postType,$limit,$platformID,$this->random,$this->perpage,$this->pagenum,'$this->exceptIDs')";
		}
		
		return $this->executenonquery ( $sql );

		

	}
	
	function getAlPosts($userID=0,$limit=0,$search="",$platformID=0,$status=1) {

		$sql = "SELECT * FROM posts WHERE (title LIKE '%" . $search . "%' OR description LIKE '%" . $search . "%' ) AND (" . $userID . "=0 or userID='" . $userID . "') AND status='$status' AND isDeleted<>1 AND
		(" . $platformID . "=0 or platformID='" . $platformID . "') ORDER BY createddate_ desc ". (($limit!=0) ? "LIMIT $limit" : "") . "";  

		return $this->executenonquery ( $sql );  

	} 

}
?>
