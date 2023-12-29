<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start ();
require_once "Tables/gifts.php";
require_once "Tables/users.php";
require_once "Tables/definitions.php";
require_once "Tables/localization.php";
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

if(!isset($_POST['category']) or empty($_POST['category'])){
	$which_category=0;
}else{
	$which_category= $_POST['category'];
}

if(!isset($_POST['sortby']) or empty($_POST['sortby'])){
	$which_sort=0;
}else{
	$which_sort= $_POST['sortby'];
}

if(!isset($_POST['search']) or empty($_POST['search'])){
	$search="";
}else{
	$search= $_POST['search'];
}

if($_POST['nextpageG'] != ""){
	
	$_SESSION['pagenumG']=$_POST['nextpageG'];
} 

if(!isset($_POST['pegiON']) or empty($_POST['pegiON'])){
	$showPegi=0;
}else{
	$showPegi= $_POST['pegiON'];
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

?>
	

<?php
$gifts = new gifts();
$gifts->randomG=$_SESSION['randomG'];
$gifts->perpageG= 6; 
$gifts->pagenumG=$_SESSION['pagenumG'];
$user = new users($_SESSION['userID']);

if ($user->country == 306) {
	$availableZone = 1; 
} else {
	$availableZone = 2;   
}

$result = $gifts->getGifts (0,$which_category,$availableZone,$which_sort,$search);  
while ($row=mysqli_fetch_array($result)) { 

$category = new definitions($row["category"]);

if($showPegi != 1) {

?>  
				
				
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">    
						<div class="card">  
							<div class="card-img" style="height: 300px !important;display: block;overflow: hidden;position: relative;">
								<a href="javascript: getTheGiftDetails(<?php echo $row["ID"];?>);"><img src="<?php echo ($row["picture"]!="") ? project::uploadPath."/giftImg/".$row["picture"] : project::assetImages. "giftimage.jpg";?>" alt="<?php echo $row["name"];?>" 
								style="
									display: block;
									
									position: absolute;
									top: 50%;
									left: 50%;
									-o-transform: translate(-50%, -50%);
									-ms-transform: translate(-50%, -50%);
									-moz-transform: translate(-50%, -50%);
									-webkit-transform: translate(-50%, -50%);
									translate(-50%, -50%);
								" /></a> 
								<div class="category"><span class="label label-success" style="font-size: 14px;"><?php echo evalLoc($category->definition);?></span></div>  
							</div>
							<div class="caption" style="text-align: center;">    
								<h3 class="card-title" style="height: 57px !important;"><a href="javascript: getTheGiftDetails(<?php echo $row["ID"];?>);"><?php echo custom_echo(evalLoc($row["name"]), 45);?></a></h3>
								<ul><li><?php if($row["deliverySpeed"] == 0) { echo '<i class="glyphicon glyphicon-gift"></i>' . $loc->label("Spot delivery"); } else if($row["deliverySpeed"] == 1) { echo '<i class="fa fa-truck"></i>' . $loc->label("Sent by cargo in 24 hours at the latest"); } else if($row["deliverySpeed"] == 2) { echo '<i class="fa fa-envelope"></i>' . $loc->label("E-mail delivery"); } ?></li></ul>
								<p style="height: 37px !important;"><?php echo custom_echo(evalLoc($row["description"]), 80);?></p>
								<div>
								<span style="font-size: 32px; font-weight: bold; margin-right: 5px;"><?php echo $row["price"];?>& </span>  
								<br/>
								<a href="javascript: getTheGiftDetails(<?php echo $row["ID"];?>);"><button class="btn btn-primary btn-icon-left"><i class="fa fa-search"></i><?php echo $loc->label("Details");?></button></a>       
								<a href="javascript: getTheGift(<?php echo $row["ID"];?>,'',1,<?php echo $row["price"];?>);"><button type="submit" class="btn btn-success btn-icon-left"><i class="fa fa-check-circle-o"></i><?php echo $loc->label("Buy");?></button></a>
								
								</div>     
							</div>  
						</div>  
					</div>
					

<?php } ?>

<?php } ?>



<?php 
if($showPegi == 1) {
	
$resultT = $gifts->getGifts (0,$which_category,$availableZone,$which_sort,$search,1);  
	
if (mysqli_num_rows( $resultT ) != 0) {
$rowNum = mysqli_num_rows( $resultT );

$a = ceil($rowNum / 6);

$pegis = '<ul class="pagination">';
if($_SESSION['pagenumG'] != 1){
	$pegis .= '<li><a href="javascript: sortGifts(0,'.($_SESSION['pagenumG']-1).',0,0)"><span>&laquo;</span></a></li>';
} else {
	$pegis .= '<li class="disabled"><a><span>&laquo;</span></a></li>';
}
$pegi = '';
$pegie = '';
if($_SESSION['pagenumG'] != $a){
	$pegie = '<li><a href="javascript: sortGifts(0,'.($_SESSION['pagenumG']+1).',0,0)"><span>&raquo;</span></a></li></ul>';
} else {
	$pegie = '<li class="disabled"><a><span>&raquo;</span></a></li></ul>';
}
for($i=1; $i <= $a; $i++) {

	if($i <= $_SESSION['pagenumG']+3 && $i >= $_SESSION['pagenumG']-4){
		
		$pegi .= '<li '.(($_SESSION['pagenumG']==$i)?'class="active"':'').'><a href="javascript: sortGifts(0,'.$i.',0,0)">'.$i.'</a></li>';
	
	}
	
}
$resultpegi = $pegis . $pegi . $pegie;

echo $resultpegi;

} 


?>

<?php } else { ?>
	
<?php if (mysqli_num_rows( $result ) == 0) {?>	

	<?php echo "NOPRODUCT"; ?>
  
<?php }?>	

<?php } ?>

<?php } ?>