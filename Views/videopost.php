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

    if ($res->platformID > 0 && $res->postType==4) {

		$usrid = $res->userID;

    $user = new users( $usrid );
		$post = new posts( $ID );

		$postVideos = new posts();
		$resultVideos = $postVideos->getPosts(0,4,1);

		$platform = new platforms($post->platformID);
		$actionLike = balance::getUserAction($_SESSION['userID'], $post->ID, 1);
		$actionShare = balance::getUserAction($_SESSION['userID'], $post->ID, 2);

		$postsVideo = new posts();
		$resultVideo = $postsVideo->getmatchCategory(1,3,4,4);

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
		$show=0;
		$userID=$_SESSION['userID'];
		$runsql = new \data\DALProsess ();
		$sql = "SELECT 1 FROM balance WHERE userID='$userID' AND socialID='$post->socialID' AND actionID=4";
		$runsql->executenonquery ( $sql, NULL, false );
		if($post->viewCount > 0 && $post->nowView < $post->viewCount){
			$show=1;
		}
?>
		<section class="background-image" style="background-image: url(<?php echo ($post->imagePath!="") ? $post->imagePath : project::assetImages.$platform->platformBlankPicture;?>);">
			<span class="background-overlay"></span>
			<div class="container">
				<div class="embed-responsive embed-responsive-16by9">
					<?php if($show==1)echo'<div id="player"></div>'; ?>
				</div>
				
			</div>
		</section>
		<class="padding-top-20 padding-bottom-0">
		<div class="container" style="text-align: center; font-size: 32px;">
		<div id="displayMsg">
		<?php if($post->userID == $userID){ echo'<div class="alert alert-danger" style="margin-top:15px;"><i class="glyphicon glyphicon-warning-sign"></i>'; }elseif($runsql->recordCount == 1){ echo'<div class="alert alert-success" style="margin-top:15px;"><i class="glyphicon glyphicon-ok-circle"></i>'; }elseif($show==1){ echo '<div class="alert alert-info" style="margin-top:15px;"><b style="font-size: 32px; margin-right: 10px;">'. $loc->label("You must watch over") .':</b><i class="glyphicon glyphicon-time"></i>'; }else{ echo'<div class="alert alert-danger" style="margin-top:15px;"><i class="glyphicon glyphicon-warning-sign"></i>'; }?>
		<b id="display">
		<?php if($post->userID == $userID){echo $loc->label("This is your post. Therefore, you cannot earn points");}elseif($runsql->recordCount == 1){echo $loc->label("You have already earned points");}elseif($show==1){echo'20'; $_SESSION['youtubePost']['ID']=$ID; $_SESSION['youtubePost']['time']=time();}else{echo $loc->label("You cannot earn points by watching this post");} ?>
		</b>
		<?php if($show==1 && $runsql->recordCount != 1 && $post->userID != $userID) { ?>
		<b style="font-size: 32px; margin-right: 10px;"><?php echo " " . $loc->label("secs"); ?></b>
		<?php } ?>
		</div>
		</div>
		</div>
		</section>
		<section class="padding-top-50 padding-bottom-50">
			<div class="container">
					<div class="col-md-8 leftside">
						<div class="post post-single">
							<div class="post-header post-author">
								<a href="profile?id=<?php echo $user->ID;?>" class="author" data-toggle="tooltip" title="<?php echo $user->fullName;?>"><img src="<?php echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg";?>" alt="" /></a>
								<div class="post-title">
									<h3><a href="videopost?id=<?php echo $post->ID;?>"><?php echo custom_echo($post->title, 80);?></a></h3>
									<ul class="post-meta">
										<li><a href="profile?id=<?php echo $user->ID;?>"><i class="fa fa-user"></i> <?php echo $user->fullName;?></a></li>
										<li><i class="fa fa-calendar-o" data-toggle="tooltip" title="<?php echo $loc->label("Start date");?>"></i>  <?php echo $post->createddate_;?></li>
										<li><i class="glyphicon glyphicon-ok-circle"></i><?=!isset($post->videoDuration)?'00:00': ltrim($post->videoDuration, '00:') ?></li>
										<li id="<?php echo $post->ID;?>icon"></li>
									</ul>
								</div>
							</div>

							<p><?php echo $post->description;?></p>



							<div class="row margin-top-40" style="margin-right: 10px;">


							<div class="widget widget-friends">
							<?php
							$buttons= socialButtons($_SESSION['userID'],$ID,false,'large');
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



					</div>
			
			</div>
		</section>


		<section id="topFieldS" class="bg-grey-50 border-bottom-1 border-grey-200 padding-top-25 padding-bottom-5">

			<div class="container">
				<div class="row">


					<?php while ($row=mysqli_fetch_array($resultVideo)) {
					$platform = new platforms($row["platformID"]);
					$actionLike = balance::getUserAction($_SESSION['userID'], $row["ID"], 1);
					$actionShare = balance::getUserAction($_SESSION['userID'], $row["ID"], 2);
				?>



					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="card card-video">
							<div class="card-img">
								<a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>"><img src="<?php echo ($row["imagePath"]!="") ? $row["imagePath"] : project::assetImages.$platform->platformBlankPicture;?>" alt="<?php echo custom_echo($row["title"], 20);?>"></a>
								<div class="time"><?=!isset($row["videoDuration"])?'00:00': ltrim($row["videoDuration"], '00:') ?></div>
							</div>
							<div class="caption">
								<h3 class="card-title"><a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>" style=" display:inline-block; width:260px; white-space: nowrap; overflow:hidden !important; text-overflow: ellipsis; font-size: 17px;"><?php echo $row["title"];?></a></h3>
								<ul>
									<?php echo socialButtons($_SESSION['userID'],$row["ID"],true,'small'); ?>
								</ul>
							</div>
						</div>
					</div>


					<?php }?>

					<?php if (mysqli_num_rows( $resultVideo ) == 0) {?>

						<script>
							$("#topFieldS").hide();
						</script>

					<?php }?>


				</div>
			</div>
		</section>
		
<script>
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '390',
          width: '640',
          videoId: '<?=$post->socialID?>',
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
       
      }
<?php
if($post->userID == $userID OR $runsql->recordCount == 1){?>
function onPlayerStateChange(event) {
}
<?php }else{ ?>

	   var timer, timeSpent = [], display = document.getElementById('display'), stop=0;
function onPlayerStateChange(event) {
  if (event.data === 1) { // Started playing
    if (!timeSpent.length) {
      for (var i = 0, l = parseInt(player.getDuration()); i < l; i++) timeSpent.push(false);
    }if(stop != 1){
    timer = setInterval(record, 100);
	}
  } else {
    clearInterval(timer);
  }
}


function record() {
  timeSpent[parseInt(player.getCurrentTime())] = true;
  seconds();
}

function showPercentage() {
  var percent = 0;
  for (var i = 0, l = timeSpent.length; i < l; i++) {
    if (timeSpent[i]) percent++;
  }
  percent = Math.round(percent / timeSpent.length * 100);
  display.innerHTML = timeSpent;
}
function seconds(){
var cnt=0;
for (var i = 0, l = timeSpent.length; i < l; i++) {
if(timeSpent[i] === true && i != 0) 
if(cnt >= 20){
	$.ajax({
			type: 'POST',
			url: "/Controllers/formPosts.php?action=earnPoints",
			data: {platformID:<?=$post->platformID?>,postID:<?=$ID?>,actionID:4},
			success: function cevap(e){
				var response = jQuery.parseJSON(e);
				if(!response.errorDetected){
					$("#newPoints").text('+'+(parseFloat(Math.floor(response.earnedPoints * 100) / 100).toFixed(2))).css("display", "inline");
					$("#newPoints").className = "label label-success";
					$("#currentPoints").fadeOut(500, function(){$("#currentPoints").text(response.newBalance)}).fadeIn(1500, function(){$("#newPoints").css("display", "none");});
					$("#displayMsg").html("<div class='alert alert-success' style='margin-top:15px;'><i class='fa fa-check' stlye='margin-left: 10px;'></i><?php echo $loc->label('You have just earned &s from this post!'); ?></div>"); 
					refreshButtons(post);
				}else{
					document.getElementById("modalText").innerHTML = response.errorMsg;
					document.getElementById("modalButton").click();
				}
			}
	});
	clearInterval(timer);
	stop=1;
	break;
}else{
	cnt++;
}
}
display.innerHTML = 20-cnt;
}
<?php } ?>
    </script>