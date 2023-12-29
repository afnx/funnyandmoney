<?php
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/platforms.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}

$loc = new localization ($_SESSION['language']); 

		$postsVideo = new posts();
		$resultVideo = $postsVideo->getmatchCategory($_SESSION['userID'],3,4,4);

		$_SESSION['pagenum']=1;
		$_SESSION['random']=rand(1,50);

?>
		<div id="carouselS1" class="owl-carousel">
		
		<?php
		$postsVIP = new posts();
		$resultVIP = $postsVIP->getmatchCategory($_SESSION['userID'],4,0,10);
		$_SESSION['exceptIDs']='';
		while ($row=mysqli_fetch_array($resultVIP)) {
					$platform = new platforms($row["platformID"]);
					$actionLike = balance::getUserAction($_SESSION['userID'], $row["ID"], 1);
					$actionShare = balance::getUserAction($_SESSION['userID'], $row["ID"], 2);
		if($row['platformID'] == 4){
			$imgsrc= $row["imagePath"];
		}else{
			$imgsrc= project::uploadPath.$row["imagePath"];
		}
		
		if(empty($_SESSION['exceptIDs'])) {
						
			$_SESSION['exceptIDs'] = $row["ID"];
						
		} else {
						
			$_SESSION['exceptIDs'] .= ',' . $row["ID"];
						
		}
		
		$array["postsVIP"][] = '
			<div id="post'.$row["ID"].'" class="post-carousel">
					<img style="background-color: #FAFAFA;" src="'.(($row["imagePath"]!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture).'" alt="'.$row["title"].'" height="500" width="475">
					<div class="overlay">
						<div class="caption">
							<span class="'.$platform->platformLabel.'"><i class="'.$platform->platformIcon.'"></i></span>
							<div class="comments">
							<ul class="socialButtons">'
							.str_replace('</li>','',str_replace('<li>','',socialButtons($_SESSION['userID'],$row['ID'],true,'large'))).
							'</ul>
							</div>
							<a href="'.(($row["postType"] == 4) ? 'videopost?id=' : 'post?id=').$row["ID"].'" class="link">
							<div class="post-title"><h4 style=" display:inline-block; width:400px; white-space: nowrap; overflow:hidden !important; text-overflow: ellipsis;">'.$row["title"].'</h4></div>
							<p style=" display:inline-block; width:400px; white-space: nowrap; overflow:hidden !important; text-overflow: ellipsis;">'.$row["description"].'</p>

							</a>

						</div>
					</div>

			</div>';

		}

		if (mysqli_num_rows( $resultVIP ) == 0) {

				echo '<script>$("#carouselS1").hide();</script>';

		} else {
			
			foreach($array["postsVIP"] as $element){
			echo $element;
		}
			
		}
		
		 
		
		?>
		
		</div>


		<section id="topFieldS" class="bg-grey-50 border-bottom-1 border-grey-200 padding-top-25 padding-bottom-5">
			<div class="container">
				<div class="row">



				<?php while ($row=mysqli_fetch_array($resultVideo)) {
					$platform = new platforms($row["platformID"]);
					$actionLike = balance::getUserAction($_SESSION['userID'], $row["ID"], 1);
					$actionShare = balance::getUserAction($_SESSION['userID'], $row["ID"], 2);
					if($row['platformID'] == 4){
						$imgsrc= $row["imagePath"];
					}else{
						$imgsrc= project::uploadPath.$row["imagePath"];
					}
					
					if(empty($_SESSION['exceptIDs'])) {
						
						$_SESSION['exceptIDs'] = $row["ID"];
						
					} else {
						
						$_SESSION['exceptIDs'] .= ',' . $row["ID"];
						
					}
					
				?>



					<div id="post<?=$row["ID"]?>" class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="card card-video">
							<div class="card-img">
								<a href="<?php echo (($row["postType"] == 4)? "videopost?id=" : "post?id=");?><?php echo $row["ID"];?>"><img src="<?php echo ($row["imagePath"]!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" alt="<?php echo custom_echo($row["title"], 30);?>" style="height: 200px;"></a>
								<div class="time"><?=!isset($row["videoDuration"])?'00:00': ltrim($row["videoDuration"], '00:') ?></div>
							</div>
							<div class="caption">
								<h3 class="card-title"><a href="<?php if($row["postType"] == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $row["ID"];?>" style="overflow-wrap: break-word;font-size: 17px;"><?php echo custom_echo($row["title"], 30);?></a></h3>
							
									<ul class="socialButtons">
									<?php
									
									echo socialButtons($_SESSION['userID'],$row["ID"],true,'small');
									
									?>
								</ul>
								
							</div>
						</div>
					</div>


					<?php }?>

					<?php if (mysqli_num_rows( $resultVideo ) == 0) {?>

						<script>
							$("#topFieldS").hide();
						</script>


					<?php }
					
					/*
					<li><a href="javascript: platform(3,0);"><i class="fa fa-instagram"></i> <?php echo $loc->label("Instagram");?></a></li>
					
					<li><a href="javascript: platform(5,0);"><i class="fa fa-google-plus"></i> <?php echo $loc->label("Google+");?></a></li>
						<li><a href="javascript: platform(6,0);"><i class="fa fa-film"></i> <?php echo $loc->label("Video");?></a></li>
						<li><a href="javascript: platform(7,0);"><i class="fa fa-music"></i> <?php echo $loc->label("Music");?></a></li>
						<li><a href="javascript: platform(8,0);"><i class="fa fa-mouse-pointer"></i> <?php echo $loc->label("Website");?></a></li>
					*/
					?>


				</div>
			</div>
		</section>


		<section class="bg-white no-padding border-bottom-1 border-grey-300">
			<div class="tab-select sticky text-center">
				<div class="container">
					<ul class="nav nav-tabs">
						<li><a href="javascript: platform(0,0,1);"><?php echo $loc->label("ALL");?></a></li>
						<li><a href="javascript: platform(1,0,1);"><i class="ion-social-facebook"></i> <?php echo $loc->label("Facebook");?></a></li>
						<li><a href="javascript: platform(2,0,1);"><i class="fa fa-twitter"></i> <?php echo $loc->label("Twitter");?></a></li>
						<li><a href="javascript: platform(4,0,1);"><i class="fa fa-youtube"></i> <?php echo $loc->label("Youtube");?></a></li>
						
					</ul>
				</div>
			</div>
		</section>
		



		<?
		/*
		<section class="padding-top-25 no-padding-bottom border-bottom-1 border-grey-300">
			<div class="container">
				<div class="headline">

					<div class="btn-group pull-right">

						<a href="#" class="btn btn-default"><i class="fa fa-th-large no-margin"></i></a>
						<a href="index3.php" class="btn btn-default"><i class="fa fa-bars no-margin"></i></a>

					</div>


					<div class="dropdown pull-left">
						<a href="#" class="btn btn-default btn-icon-left btn-icon-right dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sort-amount-desc"></i> <?php echo $loc->label("Show Post");?> <i class="ion-android-arrow-dropdown"></i></a>
						<ul class="dropdown-menu">
							<li><a href="#"><?php echo $loc->label("All");?></a></li>
							<li><a href="#"><?php echo $loc->label("Only My Following on F&M");?></a></li>
						</ul>
					</div>

					<div class="dropdown pull-left">
						<a href="#" class="btn btn-default btn-icon-left btn-icon-right dropdown-toggle" data-toggle="dropdown"><i class="fa fa-star"></i> <?php echo $loc->label("Style");?> <i class="ion-android-arrow-dropdown"></i></a>
						<ul class="dropdown-menu">
							<li><a href="#"><?php echo $loc->label("All");?></a></li>
							<li><a href="#"><?php echo $loc->label("Like");?></a></li>
							<li><a href="#"><?php echo $loc->label("Follow");?></a></li>
							<li><a href="#"><?php echo $loc->label("Share");?></a></li>
							<li><a href="#"><?php echo $loc->label("Comment");?></a></li>
							<li><a href="#"><?php echo $loc->label("Subscribe");?></a></li>
							<li><a href="#"><?php echo $loc->label("Watch");?></a></li>
							<li><a href="#"><?php echo $loc->label("Click");?></a></li>
						</ul>
					</div>

					<div class="dropdown pull-left">
						<a href="#" class="btn btn-default btn-icon-left btn-icon-right dropdown-toggle" data-toggle="dropdown"><i class="fa fa-hashtag"></i> <?php echo $loc->label("For");?> <i class="ion-android-arrow-dropdown"></i></a>
						<ul class="dropdown-menu">
							<li><a href="#"><?php echo $loc->label("All");?></a></li>
							<li><a href="#"><?php echo $loc->label("Post");?></a></li>
							<li><a href="#"><?php echo $loc->label("Page");?></a></li>
							<li><a href="#"><?php echo $loc->label("Channel");?></a></li>
						</ul>
					</div>

					<div class="dropdown pull-left">
						<a href="#" class="btn btn-default btn-icon-left btn-icon-right dropdown-toggle" data-toggle="dropdown"><i class="fa fa-check"></i> <?php echo $loc->label("Fast Like");?>  <i class="ion-android-arrow-dropdown"></i></a>
						<ul class="dropdown-menu">
							<li><a href="#">5</a></li>
							<li><a href="#">10</a></li>
							<li><a href="#">15</a></li>
							<li><a href="#">30</a></li>
						</ul>
					</div>

					<div class="dropdown pull-left">
						<a href="#" class="btn btn-default btn-icon-left btn-icon-right dropdown-toggle" data-toggle="dropdown"><i class="fa fa-check"></i> <?php echo $loc->label("Fast Follow");?>  <i class="ion-android-arrow-dropdown"></i></a>
						<ul class="dropdown-menu">
							<li><a href="#">5</a></li>
							<li><a href="#">10</a></li>
							<li><a href="#">15</a></li>
							<li><a href="#">30</a></li>
						</ul>
					</div>

					<div class="dropdown pull-left">
						<a href="#" class="btn btn-default btn-icon-left btn-icon-right dropdown-toggle" data-toggle="dropdown"><i class="fa fa-check"></i> <?php echo $loc->label("Fast Subscribe");?>  <i class="ion-android-arrow-dropdown"></i></a>
						<ul class="dropdown-menu">
							<li><a href="#">5</a></li>
							<li><a href="#">10</a></li>
							<li><a href="#">15</a></li>
							<li><a href="#">30</a></li>
						</ul>
					</div>


				</div>
			</div>
		</section>
		*/
		?>


		<section class="bg-grey-50 border-bottom-1 border-grey-300 padding-top-40 padding-bottom-40 padding-top-sm-30">
			<div id="myContainer" class="container">
			<input type="hidden" id="which_platform" value="0">
				<div class="row masonry">
				
				 
				</div>
				<div id="loadMoreArea" class="text-center"><a id ="loadMore" href="javascript: platform(0,1)" class="btn btn-primary btn-lg btn-shadow btn-rounded btn-icon-right margin-top-10 margin-bottom-40"><?php echo $loc->label("Load More");?></a><div id="loader" style="display: none;"><img src="images/loader.gif" /></div></div>
				
			</div>
		</section>

	<!-- Javascript 
	<script src="../Library/bootstrap-3.3.6/plugins/twitter/twitter.js"></script>
	-->

	
	<script src="../Library/bootstrap-3.3.6/plugins/masonry/imagesloaded.pkgd.min.js"></script>
	<script src="../Library/bootstrap-3.3.6/plugins/masonry/masonry.pkgd.min.js"></script>
	<script src="https://cdn.jsdelivr.net/scrollreveal.js/3.1.4/scrollreveal.min.js"></script>
	
	
	<script>

	(function($) {
	"use strict";
		/*var config1 = {
			  "id": $('#twitter').data("twitter"),
			  "domId": 'twitter',
			  "maxTweets": 1,
			  "enableLinks": true
			};
		twitterFetcher.fetch(config1);*/
		
		$(".owl-carousel").owlCarousel({
			autoPlay: true,
			items : 4, //4 items above 1000px browser width
			itemsDesktop : [1600,3], //3 items between 1000px and 0
			itemsTablet: [940,1], //1 items between 600 and 0
			itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
		}); 
	})(jQuery);

	(function($) {
	"use strict";
		var $container = $('.masonry');
		$($container).imagesLoaded( function(){
			$($container).masonry({
				itemSelector: '.post-grid',
				columnWidth: '.post-grid'
			});
		});
	})(jQuery);
	function platform(id,next,positions,first) {
		
		positions = typeof positions !== 'undefined' ? positions : 0;
		first = typeof first !== 'undefined' ? first : 0;
		
		$('#loadMore').show();
		
		if(first == 0) {
			
			$('#loadMore').hide();
			$('#loader').show();
		
		}

					if(next == 0){
						$("#which_platform").val(id);
					}else{
					
						id= $("#which_platform").val();
					}
						
					$.ajax({
						type: 'POST',
						url: "../BL/showPosts.php",
						data: {platform: id, nextpage: next, allPositions: positions},
						success: function cevap(e){
							if(next == 0){
								$('.masonry').html(e.replace("NOPOST", ""));
								
							}else{
								$('.masonry').append(e.replace("NOPOST", ""));
								
							}
							
							if(e.indexOf("NOPOST") > -1){  
							
								$('#loadMore').hide();
								$('#loader').hide();
								
								$("#loadMoreArea").html("<?php echo $loc->label("The End Posts");?>");
								
								
							} else {
								
								$('#loader').hide();
								$('#loadMore').show();

							}
							
							
						$('.masonry').imagesLoaded( function(){
							if (typeof FB !== 'undefined') {
								FB.XFBML.parse();
							}
							$('.masonry').masonry('reloadItems');   
							$('.masonry').masonry('layout');
						});
						
					
						
						
						
						}
						});
					
				}
	$( document ).ready(function() {
		platform(0,0,1,1);
	});
	$(window).scroll(function() {  
       if($(window).scrollTop() + $(window).height() == $(document).height()) {
           platform(0,1);
       }
    });
</script>
	
