<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start ();
require_once "Tables/shopComments.php";
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
	
} else if(isset($_SESSION['userID'])) {

if(!isset($_POST['giftID']) or empty($_POST['giftID'])){
	echo "NOCOMMENT";
}else{
	
$giftID= $_POST['giftID'];



$loc = new localization($_SESSION['language']);
$userC = new users($_SESSION["userID"]);

?>
	

<?php
$comments = new shopComments();
$result = $comments->getComments ($giftID,$_POST['page']);  
while ($row=mysqli_fetch_array($result)) { 
	
$user = new users($row["userID"]);

?>  

	<div class="media">
								<a class="media-left" href="profile?id=<?php echo $row["userID"]; ?>">
									<img style="max-width: 65px !important;" src="<?php echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg";?>" alt="" />
								</a>
								<div class="media-body">
									<div class="media-content">
										<a href="profile?id=<?php echo $row["userID"]; ?>" class="media-heading"><?php echo $user->fullName; ?></a>
										<span class="date"><?php echo date((($userC->language=="tr") ? "d/m/Y H:i" : "m/d/Y H:i"), strtotime( $row["date_"] )); ?></span>
										<p><?php echo $row["comment"]; ?></p>
									</div>
								</div>
							</div>

<?php } ?>


	
<?php if (mysqli_num_rows( $result ) == 0) {?>	

	<?php echo "NOCOMMENT"; ?>
  

<?php } ?>

<?php } ?>
<?php } ?>