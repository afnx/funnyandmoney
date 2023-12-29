<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "functions.php";
require_once "Tables/users.php";
require_once "Tables/posts.php";
require_once "Tables/platforms.php";
require_once "Tables/balance.php";
require_once "Tables/localization.php";
require_once "Tables/publishers.php";

if(isset($_SERVER['HTTP_REFERER'])) {
$pos = strpos($_SERVER['HTTP_REFERER'],getenv('HTTP_HOST'));
} else {
	$pos=false;
}
if($pos===false) {
	
  die('Restricted access');
  exit;
	
} else if($_SESSION['userID']) {

$loc = new localization ($_SESSION['language']);

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

if (isset($_POST['id'])) {
	
	$userCont = new users($_POST['id']);
	
	if($userCont->ID > 0) {
		
		
		$ID  = $_POST['id'];
		$res = new users($ID);
	
		if ($res->email != "") {
		
			$usrid = $_POST['id'];
		
			$user = new users( $usrid );
		
			$myP = 0;

			if($usrid == $_SESSION["userID"]) {
	
				$myP = 1;
			}
		
			$post = new posts();
			$result = $post->getPosts ( $usrid,6,0,$_POST['page'],$_POST['platform'] );

		} else {
        
			$fn = new functions();
			$fn->redirect("404");
		
		}
		
		
	}
	

}

 while ($row=mysqli_fetch_array($result)) {
					$platform = new platforms($row["platformID"]);
					$actionLike = balance::getUserAction($_SESSION['userID'], $row["ID"], 1);
					$actionShare = balance::getUserAction($_SESSION['userID'], $row["ID"], 2);
					if($row['platformID'] == 4){
						$imgsrc= $row["imagePath"];
					}else{
						$imgsrc= project::uploadPath.$row["imagePath"];
					}
					
					if($row['status'] == 1) {
					
				?>
					
						
						<div class="panel panel-default panel-post" id="post<?=$row["ID"] ?>">
							<div class="panel-body">
								<div class="post">
									<div class="post-header post-author">
										<a href="profile?id=<?php echo $user->ID; ?>" class="author" data-toggle="tooltip" title="<?php echo $user->fullName;?>"><img src="<?php echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg";?>" alt=""></a>
										<div class="post-title">
											<h3><a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><?php echo custom_echo($row['title'], 45);?></a></h3>
											<ul class="post-meta">
												<li><i class="fa fa-calendar-o" data-toggle="tooltip" title="<?php echo $loc->label("Start date");?>"></i>  <?php echo $row["createddate_"];?></li>
												<li id="<?php echo $row["ID"];?>icon"></li>
											</ul>
										</div>
									</div>
									
							<?php if($row["imagePath"] !="") {?>
							
								<div class="post-thumbnail">
								
									<a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><img src="<?php echo $imgsrc;?>" alt="<?php echo custom_echo($row['title'], 45);?>"></a>
									
								</div>
							
							<?php }?>
									
									<?php custom_echo($row['description'], 100);?>
								</div>
							</div>
							
							<?php if ($myP != 1) {?>
							
							<div class="panel-footer">
								<ul class="post-action">
									<?php
									
									echo socialButtons($_SESSION['userID'],$row['ID'],true,'large');
									
									?>
								</ul>
							</div>
							
							
							<?php }?>
							
							
							
						</div>
						
						
						
						
<script>
						
		var platformIcon;
		
		switch(<?php echo $row["platformID"] ;?>) {
			
			case 1 :
				platformIcon = '<i class="fa fa-facebook"></i>facebook';
				break;
				
			case 2 :
				platformIcon = '<i class="fa fa-twitter"></i>twitter';
				break;
			
			case 3 :
				platformIcon = '<i class="fa fa-instagram"></i>instagram';
				break;
				
			case 4 :
				platformIcon = '<i class="fa fa-youtube"></i>youtube';
				break;
				
			case 5 :
				platformIcon = '<i class="fa fa-google-plus"></i>googleplus';
				break;
				
			case 6 :
				platformIcon = '<i class="fa fa-film"></i>video';
				break;
				
			case 7 :
				platformIcon = '<i class="fa fa-music"></i>music';
				break;
				
			case 8 :
				platformIcon = '<i class="fa fa-mouse-pointer"></i>website';
				break;
		}
		
		document.getElementById("<?php echo $row["ID"];?>icon").innerHTML = platformIcon;

	

						
</script>
						
					
					<?php }?>
					
					<?php }?>
					
					<?php if (mysqli_num_rows( $result ) == 0) {?>	

						<?php echo "NOPOST";?>

					<?php }?>
					
					
<?php } ?>