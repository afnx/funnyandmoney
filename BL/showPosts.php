<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start ();
require_once "Tables/posts.php";
require_once "Tables/platforms.php";
require_once "Tables/balance.php";
require_once "Tables/users.php";
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
	
} else if($_SESSION['userID']) {

if(!isset($_POST['platform']) or empty($_POST['platform'])){
	$which_platform=0;
}else{
	$which_platform= $_POST['platform'];
}
if($_POST['nextpage'] == 1){
	$_SESSION['pagenum']++;
}else{
	$_SESSION['pagenum']=1;
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
	
<?php if($_POST['allPositions'] == 1) {?>

<?php
$postsTOP = new posts(); 
$resultTOP = $postsTOP->getmatchCategory($_SESSION['userID'],2,0,8,$which_platform,0);
while ($row=mysqli_fetch_array($resultTOP)) {
					$platform = new platforms($row["platformID"]);
					$userPostS = new users($row["userID"]);
					//$actionLike = balance::getUserAction($_SESSION['userID'], $row["ID"], 1);
					//$actionShare = balance::getUserAction($_SESSION['userID'], $row["ID"], 2);
					if($row['platformID'] == 4){
						$imgsrc= $row["imagePath"];
					}else{
						$imgsrc= project::uploadPath.$row["imagePath"];
					} 
					
					if(!isset($_SESSION['exceptIDs'])) {
						
						$_SESSION['exceptIDs'] = $row["ID"];
						
					} else {
						
						$_SESSION['exceptIDs'] .= ',' . $row["ID"];
						
					}
					
				?>

					<div id="post<?=$row["ID"]?>" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 post-grid">
						<div class="card card-hover">
							<div class="card-img">
								<a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><img src="<?php echo ($row["imagePath"]!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" alt="<?php echo custom_echo($row['title'], 36);?>"></a>
								<div class="category"><span class="<?php echo $platform->platformLabel;?>"><i style="margin: auto;" class="<?php echo $platform->platformIcon;?>"></i></span></div>
								<?php if($row["postType"] == 4) {?><div class="time"><?=!isset($row["videoDuration"])?'00:00': ltrim($row["videoDuration"], '00:') ?></div><?php }?>
							</div>
							<div class="caption">
								<h3 class="card-title"><a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><?php echo custom_echo($row['title'], 36);?></a></h3>
								
								<ul>
									<li><a href="profile?id=<?php echo $userPostS->ID;?>"><i class="fa fa-user"></i> <?php echo $userPostS->fullName;?></a></li>
								</ul>
								
								<p><?php custom_echo($row['description'], 80);?></p>
								<ul class="socialButtons">
									<?php
									
									echo socialButtons($_SESSION['userID'],$row["ID"],true,'large');
									
									?>
								</ul>
								
								

								

							</div>
						</div>
					</div>


	<?php } ?>
	
	
	<?php 
$posts = new posts();
$posts->random=$_SESSION['random'];
$posts->perpage= 16; 
$posts->pagenum=$_SESSION['pagenum'];
$posts->exceptIDs=$_SESSION['exceptIDs'];
$result = $posts->getmatchCategory($_SESSION['userID'],0,0,0,$which_platform,1);
while ($row=mysqli_fetch_array($result)) {
						
					$platform = new platforms($row["platformID"]);
					$userPostS = new users($row["userID"]);
					//$actionLike = balance::getUserAction($_SESSION['userID'], $row["ID"], 1);
					//$actionShare = balance::getUserAction($_SESSION['userID'], $row["ID"], 2);
					if($row['platformID'] == 4){
						$imgsrc= $row["imagePath"];
					}else{
						$imgsrc= project::uploadPath.$row["imagePath"];
					}
				?>



					<div id="post<?=$row["ID"]?>" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 post-grid">
						<div class="card card-hover">
							<div class="card-img">
								<a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><img src="<?php echo ($row["imagePath"]!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" alt="<?php echo custom_echo($row['title'], 36);?>"></a>
								<div class="category"><span class="<?php echo $platform->platformLabel;?>"><i style="margin: auto;" class="<?php echo $platform->platformIcon;?>"></i></span></div>
								<?php if($row["postType"] == 4) {?><div class="time"><?=!isset($row["videoDuration"])?'00:00': ltrim($row["videoDuration"], '00:') ?></div><?php }?>
							</div>
							<div class="caption">
								<h3 class="card-title"><a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><?php echo custom_echo($row['title'], 36);?></a></h3>
								
								<ul>
									<li><a href="profile?id=<?php echo $userPostS->ID;?>"><i class="fa fa-user"></i> <?php echo $userPostS->fullName;?></a></li>
								</ul>
								
								<p><?php custom_echo($row['description'], 80);?></p>
								<ul class="socialButtons">
									<?php
									
									echo socialButtons($_SESSION['userID'],$row["ID"],true,'large');
									
									?>
								</ul>
								
								

								

							</div>
						</div>
					</div>
				



			<?php } ?>

	
	

<?php } else { ?>

<?php 
$posts = new posts();
$posts->random=$_SESSION['random'];
$posts->perpage= 16; 
$posts->pagenum=$_SESSION['pagenum'];
$posts->exceptIDs=$_SESSION['exceptIDs'];
$result = $posts->getmatchCategory($_SESSION['userID'],0,0,0,$which_platform,1);
while ($row=mysqli_fetch_array($result)) {
						
					$platform = new platforms($row["platformID"]);
					$userPostS = new users($row["userID"]);
					//$actionLike = balance::getUserAction($_SESSION['userID'], $row["ID"], 1);
					//$actionShare = balance::getUserAction($_SESSION['userID'], $row["ID"], 2);
					if($row['platformID'] == 4){
						$imgsrc= $row["imagePath"];
					}else{
						$imgsrc= project::uploadPath.$row["imagePath"];
					}
				?>



					<div id="post<?=$row["ID"]?>" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 post-grid">
						<div class="card card-hover">
							<div class="card-img">
								<a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><img src="<?php echo ($row["imagePath"]!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" alt="<?php echo custom_echo($row['title'], 36);?>"></a>
								<div class="category"><span class="<?php echo $platform->platformLabel;?>"><i style="margin: auto;" class="<?php echo $platform->platformIcon;?>"></i></span></div>
								<?php if($row["postType"] == 4) {?><div class="time"><?=!isset($row["videoDuration"])?'00:00': ltrim($row["videoDuration"], '00:') ?></div><?php }?>
							</div>
							<div class="caption">
								<h3 class="card-title"><a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><?php echo custom_echo($row['title'], 36);?></a></h3>
								
								<ul>
									<li><a href="profile?id=<?php echo $userPostS->ID;?>"><i class="fa fa-user"></i> <?php echo $userPostS->fullName;?></a></li>
								</ul>
								
								<p><?php custom_echo($row['description'], 80);?></p>
								
								<ul class="socialButtons">
									<?php
									
									echo socialButtons($_SESSION['userID'],$row["ID"],true,'large');
									
									?>
								</ul>
								
								
								

							</div>
						</div>
					</div>
				



			<?php } ?>


					<?php if (mysqli_num_rows( $result ) == 0) {

						echo 'NOPOST';

					 }?>

<?php } ?>


<?php } ?>
