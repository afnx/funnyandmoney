<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start ();
require_once "Tables/publishers.php";
require_once "Tables/balance.php";
require_once "Tables/users.php";
require_once "Tables/definitions.php";
require_once "Tables/localization.php";
require_once "Tables/userSocials.php";  
require_once "functions.php";

if(isset($_SERVER['HTTP_REFERER'])) {
$pos = strpos($_SERVER['HTTP_REFERER'],getenv('HTTP_HOST'));
} else {
	$pos=false;
}
if($pos===false) {
	
  die('Restricted access');
  exit;
	
} else if(isset($_SESSION['userID'])) {
	
if($_POST['nextpage'] == 1){
	$_SESSION['pagenumP']++; 
}else{
	$_SESSION['pagenumP']=1;
}  

if(!isset($_POST['search']) or empty($_POST['search'])){
	$search="";
}else{
	$search= $_POST['search'];
}

if(!isset($_POST['category']) or empty($_POST['category'])){
	$categoryP=0;
}else{
	$categoryP= $_POST['category'];
}

if(!isset($_POST['language']) or empty($_POST['language'])){
	$languageP=0;
}else{
	$languageP= $_POST['language'];
}

if(!isset($_POST['platform']) or empty($_POST['platform'])){
	$platform=0;
}else{
	$platform= $_POST['platform'];
}

if(!isset($_POST['country']) or empty($_POST['country'])){
	$countryP=55;
}else{
	$countryP= $_POST['country'];
}

if(!isset($_POST['age']) or empty($_POST['age'])){
	$ageP=0;
}else{
	$ageP= $_POST['age'];
}

if(!isset($_POST['gender']) or empty($_POST['gender'])){
	$genderP=0;
}else{
	$genderP= $_POST['gender'];
}

if(!isset($_POST['sort']) or empty($_POST['sort'])){
	$sort=0;
}else{
	$sort= $_POST['sort'];
}

$loc = new localization($_SESSION['language']);


function custom_echo($x, $length)
{
  if(strlen($x)<=$length)
  {
    echo $x;
  }
  else
  {
    $y=substr($x,0,$length) . '&nbsp;...';
    echo $y;
  }
}

function kFormatter($num) {
    if(1000000 > $num && $num > 999) {
		return (number_format(($num/1000), 1, '.', '') + 0) . 'K';
	} else if(1000000000 > $num && $num > 999999) {
		return (number_format(($num/1000000), 1, '.', '') + 0) . 'M';
	} else if(1000000000000> $num && $num > 999999999) {
		return (number_format(($num/1000000000), 1, '.', '') + 0) . 'B'; 
	} else if($num > 999999999999) {
		return "-";
	} else {
		return $num;
	}
}  

?>
	

<?php
$publisher = new publishers();
$publisher->randomP=$_SESSION['randomP'];
$publisher->perpageP= 6; 
$publisher->pagenumP=$_SESSION['pagenumP'];
$userMe = new users($_SESSION['userID']);

$result = $publisher->getPublishers (0,$categoryP,$countryP,$genderP,$ageP,$platform,$languageP,$sort,$search);  
while ($row=mysqli_fetch_array($result)) { 
$user = new users($row["userID"]);
$balance = new balance(); 
$resultC = $balance->getPublisherPost($row["ID"]);
$postC = mysqli_fetch_array($resultC);

$arrayA = array();
array_push($arrayA, $row["a1318PM"]+$row["a1318PF"]);
array_push($arrayA, $row["a1924PM"]+$row["a1924PF"]);
array_push($arrayA, $row["a2534PM"]+$row["a2534PF"]);
array_push($arrayA, $row["a3544PM"]+$row["a3544PF"]);
array_push($arrayA, $row["a4554PM"]+$row["a4554PF"]);
array_push($arrayA, $row["a5564PM"]+$row["a5564PF"]);
array_push($arrayA, $row["a65PM"]+$row["a65PF"]);

$maxAge = max($arrayA);  

switch($maxAge) {
	case $row["a1318PM"]+$row["a1318PF"]:
		$agePer = "13-18(" . ($row["a1318PM"]+$row["a1318PF"]+0) . "%)";
		break;
	case $row["a1924PM"]+$row["a1924PF"]:
		$agePer = "19-24(" . ($row["a1924PM"]+$row["a1924PF"]+0) . "%)";
		break;
	case $row["a2534PM"]+$row["a2534PF"]:
		$agePer = "25-34(" . ($row["a2534PM"]+$row["a2534PF"]+0) . "%)";
		break;
	case $row["a3544PM"]+$row["a3544PF"]:
		$agePer = "35-44(" . ($row["a3544PM"]+$row["a3544PF"]+0) . "%)";
		break;
	case $row["a4554PM"]+$row["a4554PF"]:
		$agePer = "45-54(" . ($row["a4554PM"]+$row["a4554PF"]+0) . "%)";
		break;
	case $row["a5564PM"]+$row["a5564PF"]:
		$agePer = "55-64(" . ($row["a5564PM"]+$row["a5564PF"]+0) . "%)";
		break;
	case $row["a65PM"]+$row["a65PF"]:
		$agePer = "65+(" . ($row["a65PM"]+$row["a65PF"]+0) . "%)";
		break;
	case 0:
		$agePer = "-";
		break;
}

$arrayG = array();  
array_push($arrayG, $row["a1318PM"]+$row["a1924PM"]+$row["a2534PM"]+$row["a3544PM"]+$row["a4554PM"]+$row["a5564PM"]+$row["a65PM"]);
array_push($arrayG, $row["a1318PF"]+$row["a1924PF"]+$row["a2534PF"]+$row["a3544PF"]+$row["a4554PF"]+$row["a5564PF"]+$row["a65PF"]);

$maxGender = max($arrayG);  

switch($maxGender) {
	case $row["a1318PM"]+$row["a1924PM"]+$row["a2534PM"]+$row["a3544PM"]+$row["a4554PM"]+$row["a5564PM"]+$row["a65PM"]:
		$genderPer = $loc->label("Male") . "(" . ($row["a1318PM"]+$row["a1924PM"]+$row["a2534PM"]+$row["a3544PM"]+$row["a4554PM"]+$row["a5564PM"]+$row["a65PM"]+0) . "%)";
		break;
	case $row["a1318PF"]+$row["a1924PF"]+$row["a2534PF"]+$row["a3544PF"]+$row["a4554PF"]+$row["a5564PF"]+$row["a65PF"]:
		$genderPer = $loc->label("Female") . "(" . ($row["a1318PF"]+$row["a1924PF"]+$row["a2534PF"]+$row["a3544PF"]+$row["a4554PF"]+$row["a5564PF"]+$row["a65PF"]+0) . "%)";
		break;
	case 0:
		$genderPer = "-";
		break;
}

$arrayC = array();
array_push($arrayC, $row["c1P"]);
array_push($arrayC, $row["c2P"]);
array_push($arrayC, $row["c3P"]);
array_push($arrayC, $row["c4P"]);
array_push($arrayC, $row["c5P"]);

$maxCountry = max($arrayC);  

switch($maxCountry) {
	case $row["c1P"]:
		$country1 = new definitions($row["cN1"]);
		$countryPer = $country1->definition . "(" . ($row["c1P"]+0) . "%)";
		break;
	case $row["c2P"]:
		$country2 = new definitions($row["cN2"]);
		$countryPer = $country2->definition . "(" . ($row["c2P"]+0) . "%)";
		break;
	case $row["c3P"]:
		$country3 = new definitions($row["cN3"]);
		$countryPer = $country3->definition . "(" . ($row["c3P"]+0) . "%)";
		break;
	case $row["c4P"]:
		$country4 = new definitions($row["cN4"]);
		$countryPer = $country4->definition . "(" . ($row["c4P"]+0) . "%)";
		break;
	case $row["c5P"]:
		$country5 = new definitions($row["cN5"]);
		$countryPer = $country5->definition . "(" . ($row["c5P"]+0) . "%)";
		break;
	case NULL:
		$countryPer = "-";
		break;
}



$socialT = new userSocials($row["twitter"]);  
$socialY = new userSocials($row["youtube"]);
	
$category = new definitions($row["category"]);
$language = new definitions($row["language"]);
 



?>  
	
	<div class="panel panel-default panel-post" style="margin-bottom: 20px !important;">

					<div class="panel-body">

						<div class="post">	

	<div class="post post-review" style="padding-bottom: 30px;">  
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<div class="post-thumbnail">
										<a href="profile?id=<?php echo $user->ID; ?>"><img src="<?php echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg";?>" alt="" class="img-circle"></a>
									</div>
								</div>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
									<div class="post-header">
										<span class="label label-<?php if(10 >= $row["score"] && $row["score"] >= 7) { echo "success"; } else if(7 > $row["score"] && $row["score"] >= 4) { echo "warning"; } else if(4 > $row["score"] && $row["score"] >= 0) { echo "danger"; } ?>"><?php echo $row["score"]; ?></span>
									
										<div style="float: right;">
											<?php if($row["facebook"]!="" && $row["facebook"]!=0) { ?>
											<a class="btn btn-circle btn-social-icon btn-facebook" style="margin: 0px;"><i class="fa fa-facebook"></i></a>
											<?php } ?>
											<?php if($row["twitter"]!="" && $row["twitter"]!=0) { ?>	
											<a class="btn btn-circle btn-social-icon btn-twitter" style="margin: 0px;"><i class="fa fa-twitter"></i></a>
											<?php } ?>
											<?php if($row["youtube"]!="" && $row["youtube"]!=0) { ?>	
											<a class="btn btn-circle btn-social-icon btn-twitter" style="background-color: #cd201f !important; margin: 0px;"><i class="fa fa-youtube"></i></a>
											<? } ?>
										</div>
										<div class="post-title">
											<h4><?php echo $user->fullName; ?></h4>
											<ul class="post-meta">
											<?php if($row["facebook"]!="" && $row["facebook"]!=0) { ?>
												<li><?php echo $row["facebookPageName"];?></li>
											<?php } ?>
											<?php if($row["twitter"]!="" && $row["twitter"]!=0) { ?>	
												<li><?php echo $socialT->screenName;?></li>
											<?php } ?>
											<?php if($row["youtube"]!="" && $row["youtube"]!=0) { ?>	
												<li><?php echo $socialY->screenName;?></li>
											<? } ?>
											</ul>
										</div>
										
									
									</div>
									
									<div class="row" style="margin-bottom: 15px;">
									<div class="col-lg-4 col-md-4 col-sm-4 co-xs-6" style="margin-bottom: 10px;">
						<div class="panel panel-inverse" style="border-color: rgba(0, 0, 0, 0.1);">
							<div class="panel-body text-center" style="padding: 5px;">
								<div class="row">
								<div class="col-lg-12" style="font-size: 15px;">
								REACH
								</div>
								<div class="col-lg-12" style="font-size: 18px; font-weight: 500;">
								<?php echo kFormatter($row["facebookPageLikes"] + $socialT->followerCount + $row["youtubeSubscriber"]); ?>
								</div>
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="col-lg-4 col-md-4 col-sm-4 co-xs-6" style="margin-bottom: 10px;">
						<div class="panel panel-inverse" style="border-color: rgba(0, 0, 0, 0.1);">
							<div class="panel-body text-center" style="padding: 5px;">
								<div class="row">
								<div class="col-lg-12" style="font-size: 15px;">
								CATEGORY
								</div>
								<div class="col-lg-12" style="font-size: 18px; font-weight: 500;">
								<?php echo evalLoc($category->definition); ?>
								</div>
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="col-lg-4 col-md-4 col-sm-4 co-xs-6" style="margin-bottom: 10px;">
						<div class="panel panel-inverse" style="border-color: rgba(0, 0, 0, 0.1);">
							<div class="panel-body text-center" style="padding: 5px;">
								<div class="row">
								<div class="col-lg-12" style="font-size: 15px;">
								<i class="fa fa-check" style="margin-right: 5px; color: green;"></i>POSTS
								</div>
								<div class="col-lg-12" style="font-size: 18px; font-weight: 500;">
								<?php echo $postC[0]; ?>
								</div>
								</div>
							</div>
						</div>
					</div>


								
									<div class="col-lg-4 col-md-4 col-sm-4 co-xs-6" style="margin-bottom: 10px;">
						<div class="panel panel-inverse" style="border-color: rgba(0, 0, 0, 0.1);">
							<div class="panel-body text-center" style="padding: 5px;">
								<div class="row">
								<div class="col-lg-12" style="font-size: 15px;">
								COUNTRY
								</div>
								<div class="col-lg-12" style="font-size: 18px; font-weight: 500;">
								<?php echo $countryPer; ?>
								</div>
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="col-lg-4 col-md-4 col-sm-4 co-xs-6" style="margin-bottom: 10px;">
						<div class="panel panel-inverse" style="border-color: rgba(0, 0, 0, 0.1);">
							<div class="panel-body text-center" style="padding: 5px;">
								<div class="row">
								<div class="col-lg-12" style="font-size: 15px;">
								GENDER
								</div>
								<div class="col-lg-12" style="font-size: 18px; font-weight: 500;">
								<?php echo $genderPer; ?>  
								</div>
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="col-lg-4 col-md-4 col-sm-4 co-xs-6" style="margin-bottom: 10px;">
						<div class="panel panel-inverse" style="border-color: rgba(0, 0, 0, 0.1);">
							<div class="panel-body text-center" style="padding: 5px;">
								<div class="row">
								<div class="col-lg-12" style="font-size: 15px;">
								AGE
								</div>
								<div class="col-lg-12" style="font-size: 18px; font-weight: 500;">
								<?php echo $agePer; ?>  
								</div>
								</div>
							</div>
						</div>
					</div>


									</div>
									
									<div class="row">  
									
									<div class="col-lg-12 col-md-12 col-sm-12 co-xs-12">  
									
									<form action="" method="post" style="margin-top: 0px; float: right;">
										<a target="_blank" href="publisher/<?php echo $user->username; ?>" class="btn btn-default btn-shadow btn-icon-left"><i class="fa fa-star-o"></i>Profilini İncele</a>
										<button type="submit" id="button_<?php echo $row["ID"]; ?>" name="publisher" value="<?php echo $row["ID"]; ?>" class="btn btn-default btn-shadow btn-icon-left"><i class="fa fa-plus"></i>Gönderi Ekle</button>
									</form>	
									
									</div> 
									
									</div>
									
									
									
								</div>
							</div>
						</div>
						
							</div>

					</div>
				</div>
<script>
	
	$(document).ready(function(){
			
			$('[data-toggle="tooltip"]').tooltip();   
			
		});
		


</script>


<?php } ?>



	
<?php if (mysqli_num_rows( $result ) == 0) {?>	

	<?php echo "NOPUBLISHERS"; ?> 
  
<?php }?>	



<?php } ?>