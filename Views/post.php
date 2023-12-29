<?php

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/platforms.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";

$loc = new localization ($_SESSION['language']);

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}

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

if (isset($_GET['id'])) {

	$ID  = $_GET['id'];
	$res = new posts($ID);

    if ($res->platformID > 0 && $res->postType!=4) {

		$usrid = $res->userID;

    $user = new users( $usrid );
		$post = new posts( $ID );

		$postUser = new posts();
		$resultUser = $postUser->getPosts($usrid,5,1);

		$platform = new platforms($post->platformID);
		$actionLike = balance::getUserAction($_SESSION['userID'], $post->ID, 1);
		$actionShare = balance::getUserAction($_SESSION['userID'], $post->ID, 2);

    }
    else {

		$fn = new functions();
		$fn->redirect("404");

    }

} else {

		$fn = new functions();
		$fn->redirect("404");

}

if($post->isDeleted == 1 OR $post->status != 1) {

		$fn = new functions();
		$fn->redirect("404");

}
if($post->platformID == 4){
	$imgsrc= $post->imagePath;
}else{
	$imgsrc= project::uploadPath.$post->imagePath;
}
?>




		<section class="padding-top-50 padding-bottom-50 padding-top-sm-30 border-bottom-1 border-grey-300">
			<div class="container">
				

					<div class="col-md-8 leftside">
						<div class="post post-single">
							<div class="post-header post-author">
								<a href="profile?id=<?php echo $user->ID;?>" class="author" data-toggle="tooltip" title="<?php echo $user->fullName;?>"><img src="<?php echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg";?>" alt="" /></a>
								<div class="post-title">
									<h3><a href="post?id=<?php echo $post->ID;?>"><?php echo custom_echo($post->title, 80);?></a></h3>
									<ul class="post-meta">
										<li><a href="profile?id=<?php echo $user->ID;?>"><i class="fa fa-user"></i> <?php echo $user->fullName;?></a></li>
										<li><i class="fa fa-calendar-o" data-toggle="tooltip" title="<?php echo $loc->label("Start date");?>"></i>  <?php echo $post->createddate_;?></li>
										<li id="<?php echo $post->ID;?>icon"></li>
									</ul>
								</div>
							</div>

							<?php if($post->imagePath !="") {?>

							<div class="post-thumbnail">
								<img src="<?php echo $imgsrc;?>" alt="<?php echo $post->title;?>">
							</div>

							<?php }?>



							<p><?php echo $post->description;?></p>




							<div class="row margin-top-40" style="margin-right: 10px;">


							<div class="widget widget-friends">
							<?php
							$buttons= socialButtons($_SESSION['userID'],$ID,true,'large');
							if(strlen(trim($buttons))>0){
							?>
							<div class="panel panel-default">
								<div class="panel-heading"><?php echo $loc->label("Like, Follow or do something else!");?></div>
								<div class="panel-body">
									<ul class="post-action">
									<?php
									
									echo $buttons;
									
									?>
									</ul>
								</div>
							</div>
							<?php } ?>
						</div>

							</div>
						</div>

					</div>


					<script>

		var platformIcon;

		switch(<?php echo $post->platformID ;?>) {

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

		document.getElementById("<?php echo $post->ID;?>icon").innerHTML = platformIcon;




</script>



			<div class="col-md-4 rightside">



						<div class="widget widget-game" style="<?php echo ($user->coverPicture!="") ? "background-image: url(" . project::uploadPath."/userCoverImg/".$user->coverPicture . ");" : "background-color: rgba(0,0,0,0.6);";?>">
							<div class="overlay" style="background: rgba(0,0,0,0.7);">
							
								<div class="title" style="text-align: center; z-index: 1;"><a href="profile?id=<?php echo $user->ID;?>" class="img-circle"><img class="img-thumbnail" src="<?php echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg";?>" alt="" /></a></div>




								<div class="bold text-uppercase margin-top-40" style="text-align: center; z-index: 1;"><a style="color: #f5f5f5;" href="profile?id=<?php echo $user->ID;?>"><?php echo $user->fullName;?></a></div>


								<div class="description">
									<p style="text-align: center; z-index: 1; color: #eee;"><?php echo $user->about;?></p>



								</div>
							</div>
						</div>




						<div class="widget widget-list">
							<div class="title"><?php echo $user->fullName;?><?php echo $loc->label("s Posts");?></div>
							<ul>

							<?php while ($row=mysqli_fetch_array($resultUser)) {
								
								
							if($ID != $row["ID"]) {
							$platform = new platforms($row["platformID"]);
							

							if($row['platformID'] == 4){
								$imgsrc= $row["imagePath"];
							}else{
								$imgsrc= project::uploadPath.$row["imagePath"];
							}

							?>
								<li>
									<a href="<?php echo (($row["postType"] == 4)? "videopost?id=" : "post?id=");?><?php echo $row["ID"];?>" class="thumb"><img src="<?php echo ($row["imagePath"]!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" alt="<?php echo custom_echo($row["title"], 30);?>"></a>
									<div class="widget-list-meta">
										<h4 class="widget-list-title"><a href="<?php echo (($row["postType"] == 4)? "videopost?id=" : "post?id=");?><?php echo $row["ID"];?>"><?php echo custom_echo($row["title"], 30);?></a></h4>
										<p style=" display:inline-block; width:230px; white-space: nowrap; overflow:hidden !important; text-overflow: ellipsis;"><?php echo $row["description"];?></p>
									</div>
								</li>



							<?php }?>
							<?php }?>

							</ul>
						</div>


					</div>
			
			</div>
		</section>
		