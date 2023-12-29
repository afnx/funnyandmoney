<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "Tables/localization.php";
require_once "Tables/posts.php";
require_once "Tables/platforms.php";
require_once "Tables/positions.php";
require_once "Tables/definitions.php";
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


$loc = new localization($_SESSION['language']);

$usrid = $_SESSION["userID"];

$postr = new posts ();

$result = $postr->getPosts ($usrid,6,0,$_POST['page'],0);
$fn2 = new functions();
$fn3 = new functions();


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

 while ($row=mysqli_fetch_array($result)) {
	 
	 $completedPost = '';

					if($row['platformID'] == 4){
						$imgsrc= $row["imagePath"];
					}else{
						$imgsrc= project::uploadPath.$row["imagePath"];
					}

					$platform = new platforms($row["platformID"]);
					
					switch ($row["positionID"]) {
						
						case 4 :
							$positionName = $loc->label("V.I.P Field and Standart Field");
							break;
				
						case 3 :
							$positionName = $loc->label("Top Video Field and Standart Field");
							break;
				
						case 2 :
							$positionName = $loc->label("Top Standart Field");
							break;
				
						case 1 :
							$positionName = $loc->label("Standart Field");
							break;

					}
					
					
					$category = new definitions($row["categoryID"]);
					
					$postCountry = '';
					if(!empty($row["country"])){
						$row["country"]= explode(',',$row["country"]);
						foreach ( $row["country"] as $countries ) {
						
							$country = '';
							$country = new definitions($countries);
							
							if(is_array($row["country"])) {
								$postCountry .= evalLoc($country->definition) .'<br/>';
							} else {
								$postCountry .= evalLoc($country->definition) .'';
							}
		  
						}	 
					}
					
					$postGender = '';
					if(!empty($row["gender"])){
						$row["gender"]= explode(',',$row["gender"]);
						foreach ( $row["gender"] as $genders ) {
						
							$gender = '';
							$gender = new definitions($genders); 

							if(is_array($row["gender"])) {
								$postGender .= evalLoc($gender->definition) .'<br/>';
							} else {
								$postGender .= evalLoc($gender->definition);
							}
		  
						}	 
					}
					
					$postAge = '';
					if(!empty($row["age"])){
						$row["age"]= explode(',',$row["age"]);
						foreach ( $row["age"] as $ages ) {
						
							$age = '';
							$age = new definitions($ages);
							
							if(is_array($row["age"])) {
								$postAge .= evalLoc($age->definition) .'<br/>';
							} else {
								$postAge .= evalLoc($age->definition);
							}
		  
						}	 
					}
					
					$gender = new definitions($row["gender"]);
					$age = new definitions($row["age"]);
					
					$percLike = $fn2->calcPlatform($row["ID"],1);
					$percShare = $fn2->calcPlatform($row["ID"],2);
					$percFollower = $fn2->calcPlatform($row["ID"],3);
					$percView = $fn2->calcPlatform($row["ID"],4);
					
					$nowL = (is_null($row["nowLike"])) ? 0 : $row["nowLike"];
					$nowS = (is_null($row["nowShare"])) ? 0 : $row["nowShare"];
					$nowF = (is_null($row["nowFollow"])) ? 0 : $row["nowFollow"];
					$nowV = (is_null($row["nowView"])) ? 0 : $row["nowView"];
					
					if(is_null($row["likeCount"]) == false && $row["likeCount"] > $nowL) {

						$completedPost = 0;
						
					} else if(is_null($row["shareCount"]) == false && $row["shareCount"] > $nowS) {
						
						$completedPost = 0;
						
					} else if(is_null($row["followCount"]) == false && $row["followCount"] > $nowF) {
						
						$completedPost = 0;
						
					} else if(is_null($row["viewCount"]) == false && $row["viewCount"] > $nowV) {
						
						$completedPost = 0;
						
					}  else {
						
						$completedPost = 1;
						
					}
					
				?>
					
					

						<div id="<?php echo $row["ID"];?>" class="post post-md">
							<div class="row">
								<div class="col-md-4">
									<div class="post-thumbnail">
										<a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><img src="<?php echo ($row["imagePath"]!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" alt=""></a>
									</div>
								</div>
								<div class="col-md-8">
									<div class="post-header">
										<div style="float: right; font-size: 30px; padding-right:5px; color: #777;">
											<a href="#" data-toggle="modal" data-target=".modalStatics<?php echo $row["ID"];?>"><i class="fa fa-bar-chart"></i></a>
											<a href="#" data-toggle="modal" data-target=".modalView<?php echo $row["ID"];?>"><i class="fa fa-eye"></i></a>
											<a href="<?php echo $row["postUrl"];?>" target="_blank"><i class="fa fa-external-link"></i></a>
											<a href="#" data-toggle="modal" data-target=".modal<?php echo $row["ID"];?>"><i class="fa fa-remove"></i></a>
										</div>
										<div class="post-title">
											<h4><a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><?php echo custom_echo($row['title'], 45); ?></a></h4>
											<ul class="post-meta">
												<li><i class="fa fa-calendar-o" data-toggle="tooltip" title="<?php echo $loc->label("Start date");?>"></i>  <?php echo $row["createddate_"];?></li>
												<li id="<?php echo $row["ID"];?>icon"></li>
											</ul>
										</div>

									</div>
									<p><?php custom_echo($row['description'], 100);?> </p>

							
					
						<div class="panel panel-default" style="margin-bottom: 30px;">
							<div class="panel-body">

							<?php if ($row["status"] == 1) {?>
							
							<?php if ($completedPost != 1) { ?>

								<?php if ($row["likeCount"] > 0) {?>

								<div class="form-group">
								
								<p class="progress-label"><?php echo $loc->label("Likes");?> &nbsp; - &nbsp; <?php echo(($percLike == 100.00) ? '<b style="color: #0e9a49;">' . $loc->label("Completed") . '!</b>' : '<b style="color: #52baff;">' . $loc->label("Continuing") . '...</b>');?> <span><?php echo $percLike;?>%</span></p>
								<div class="progress progress-animation">
									<div class="progress-bar progress-bar-<?php echo(($percLike == 100.00) ? "success" : "info");?> progress-bar-striped" aria-valuenow="<?php echo $percLike;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percLike;?>%"></div>
								</div>
								
								</div>

								<?php }?>

								<?php if ($row["followCount"] > 0) {?>

								<div class="form-group">
								
								<p class="progress-label"><?php echo $loc->label("Followers");?> &nbsp; - &nbsp; <?php echo(($percFollower == 100.00) ? '<b style="color: #0e9a49;">' . $loc->label("Completed") . '!</b>' : '<b style="color: #52baff;">' . $loc->label("Continuing") . '...</b>');?> <span><?php echo $percFollower;?>%</span></p>
								<div class="progress progress-animation">
									<div class="progress-bar progress-bar-<?php echo(($percFollower == 100.00) ? "success" : "info");?> progress-bar-striped" aria-valuenow="<?php echo $percFollower;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percFollower;?>%"></div>
								</div>

								</div>
								
								<?php }?>
 
								<?php if ($row["shareCount"] > 0) {?>
 
								<div class="form-group">
								
								<p class="progress-label"><?php echo $loc->label("Shares");?> &nbsp; - &nbsp; <?php echo(($percShare == 100.00) ? '<b style="color: #0e9a49;">' . $loc->label("Completed") . '!</b>' : '<b style="color: #52baff;">' . $loc->label("Continuing") . '...</b>');?> <span><?php echo $percShare;?>%</span></p>
								<div class="progress progress-animation">
									<div class="progress-bar progress-bar-<?php echo(($percShare == 100.00) ? "success" : "info");?> progress-bar-striped" aria-valuenow="<?php echo $percShare;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percShare;?>%"></div>
								</div>
								
								</div>

								<?php }?>
								
								<?php if ($row["viewCount"] > 0) {?>

								<div class="form-group">
								
								<p class="progress-label"><?php echo $loc->label("Views");?> &nbsp; - &nbsp; <?php echo(($percView == 100.00) ? '<b style="color: #0e9a49;">' . $loc->label("Completed") . '!</b>' : '<b style="color: #52baff;">' . $loc->label("Continuing") . '...</b>');?> <span><?php echo $percView;?>%</span></p>
								<div class="progress progress-animation">
									<div class="progress-bar progress-bar-<?php echo(($percView == 100.00) ? "success" : "info");?> progress-bar-striped" aria-valuenow="<?php echo $percView;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percView;?>%"></div>
								</div>
								
								</div>

								<?php }?> 
								
							<?php } else { ?> 
								
								<h3 style="color: #0e9a49; text-align: center;"><i class="fa fa-check-circle" style="color: #0e9a49;"></i><?php echo $loc->label("COMPLETED");?></h3> 
								<p style="text-align: center;"><?php echo $loc->label("Your post closed due to reaching intended numbers. You can delete your post no longer.");?></p>
							
							<?php }?> 
								
							<?php } else if($row["status"] == 2) {?> 
								
								<h3 style="color: orange; text-align: center;"><?php echo $loc->label("SUSPENDED");?></h3> 
							
							<?php } else if($row["status"] == 0) {?> 
								
								<h3 style="color: #0e9a49; text-align: center;"><i class="fa fa-check-circle" style="color: #0e9a49;"></i><?php echo $loc->label("COMPLETED");?></h3> 
								<p style="text-align: center;"><?php echo $loc->label("Your post closed due to reaching intended numbers. You can delete your post no longer.");?></p>
							
							<?php } else if($row["status"] == 3) {?> 
								
								<h3 style="color: red; text-align: center;"><?php echo $loc->label("BANNED");?></h3> 
							
							<?php }?>
							
								

							</div>
							</div>

					


								</div>
							</div> 
							
						</div>


					<div class="modal fade modal<?php echo $row["ID"];?>" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title"><?php echo custom_echo($row["title"], 30);?> &nbsp; -  &nbsp; <?php echo $loc->label("Remove the post");?>?</h4>
							</div>
							<div class="modal-body">
								<?php echo $loc->label("If you remove it");?>
							</div>
							<div class="modal-footer">

								<form method="post" name="deletePost" id="deletePost" action="Controllers/formPosts.php?action=deletePost">

									<button type="submit" name="postID" value="<?php echo $row["ID"];?>" class="btn btn-primary"><?php echo $loc->label("Yes");?></button>

									<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("No");?></button>

								</form>

							</div>
						 </div><!-- /.modal-content -->
					</div>
					</div>

					<div class="modal fade modalView<?php echo $row["ID"];?>" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title"><?php echo custom_echo($row["title"], 30);?> &nbsp; -  &nbsp; <?php echo $loc->label("Overview");?></h4>
							</div>
							<div class="modal-body">
								<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<table class="table">
									<thead>
										<tr>
											<th><h3><?php echo $loc->label("Target Group");?><h3></th>
										</tr>
									</thead>

									<tbody>
										<tr>
											<th><?php echo $loc->label("Category");?></th>
											<td><?php echo evalLoc($category->definition);?></td>
										</tr>
										<tr>
											<th><?php echo $loc->label("Country");?></th>
											<td><?php echo $postCountry;?></td>
										</tr>
										<tr>
											<th><?php echo $loc->label("Gender");?></th>
											<td><?php echo $postGender;?></td>
										</tr>
										<tr> 
											<th><?php echo $loc->label("Age");?></th>
											<td><?php echo $postAge;?></td> 
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<br />

				<div class="row">
				<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-body">
				<h4><?php echo $loc->label("Position");?>:  <?php echo $positionName;?></h4>
				</div>
				</div>
				</div>
				</div>

							</div>
						 </div><!-- /.modal-content -->
					</div>
					</div>



					<div class="modal fade modalStatics<?php echo $row["ID"];?>" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title"><?php echo custom_echo($row["title"], 30);?> &nbsp; -  &nbsp; <?php echo $loc->label("Statistics");?></h4>
							</div>
							<div class="modal-body">

							<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<table class="table">
									<thead>
										<tr>
											<th><h3><?php echo $loc->label("Statistics ");?><h3></th>
										</tr>
									</thead>

									<tbody>
									<?php if ($row["likeCount"] > 0) {?>
										<tr>
											<th><?php echo $loc->label("Likes");?></th>
											<td><?php if(is_null($row["nowLike"])) { echo 0; } else { echo $row["nowLike"]; } ?>/<?php echo $row["likeCount"];?></td>
										</tr>
									<?php }?>
									<?php if ($row["followCount"] > 0) {?>
										<tr>
											<th><?php echo $loc->label("Followers");?></th>
											<td><?php if(is_null($row["nowFollow"])) { echo 0; } else { echo $row["nowFollow"]; }?>/<?php echo $row["followCount"];?></td>
										</tr>
									<?php }?>
									<?php if ($row["shareCount"] > 0) {?>
										<tr>
											<th><?php echo $loc->label("Shares");?></th>
											<td><?php if(is_null($row["nowShare"])) { echo 0; } else { echo $row["nowShare"]; }?>/<?php echo $row["shareCount"];?></td>
										</tr>
									<?php }?>
									<?php if ($row["viewCount"] > 0) {?>
										<tr>
											<th><?php echo $loc->label("Views");?></th>
											<td><?php if(is_null($row["nowView"])) { echo 0; } else { echo $row["nowView"]; }?>/<?php echo $row["viewCount"];?></td>
										</tr>
									<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

							</div>
						 </div><!-- /.modal-content -->
					</div>
					</div>



<script>

	
	$( document ).ready(function() {

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
		
	});
 
	
</script>

<?php } ?>

<?php if (mysqli_num_rows( $result ) == 0) {?> 

				<?php echo "NOPOST";?>

<?php }?>

<?php } ?> 
