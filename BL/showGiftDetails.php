<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start ();
require_once "Tables/gifts.php";
require_once "Tables/shopComments.php";
require_once "Tables/shopLikes.php";
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
	
$loc = new localization ($_SESSION['language']); 
	
$error = "";
if(!isset($_POST['giftID']) or !empty($_POST['giftID'])){
	$giftID=$_POST['giftID'];
	$error = "";
}else{
	echo "Error. Product not founded.";
	$error = 1;
}


if($error == "") {
	
	$gift = new gifts($giftID);
	
	$category = new definitions($gift->category);
	
	$commentCount = new shopComments();
	$row = mysqli_fetch_array( $commentCount->getCommentCount($giftID) );
	
	$likeSCount = new shopLikes();
	$row2 = mysqli_fetch_array( $likeSCount->getLikeCount($giftID) );
	
	$unLikeSCount = new shopLikes();
	$row3 = mysqli_fetch_array( $unLikeSCount->getunLikeCount($giftID) );
	
	$like = new shopLikes();
	$userL = mysqli_fetch_array( $like->getUserLike($_SESSION['userID'],$giftID) );
		
	if($userL["ID"] > 0) {

		$like = $userL["userLike"];
		
	} else {
		
		$like = 0;
		
	}
?>

<input type="hidden" id="pagenum" value="0"/>

	<div>
				<div class="row">
				<div class="col-md-4">
									<div class="post-thumbnail">
									<a href="<?php echo ($gift->picture!="") ? project::uploadPath."/giftImg/".$gift->picture : project::assetImages. "giftimage.jpg";?>" data-toggle="lightbox">
								<img src="<?php echo ($gift->picture!="") ? project::uploadPath."/giftImg/".$gift->picture : project::assetImages. "giftimage.jpg";?>">
									</a>
									<center><?php echo $loc->label("Category");?>: <b><?php echo evalLoc($category->definition);?></b></center>
									</div>
								</div>
					<div class="col-md-8">
						<div class="post post-single">
							<div class="post-header">
								<div class="post-title">
									<h2><?php echo evalLoc($gift->name); ?></h2>  
								</div>
								
								<span> <?php if($gift->deliverySpeed == 0) { echo '<i style="margin-right: 5px;" class="glyphicon glyphicon-gift"></i>' . $loc->label("Spot delivery"); } else if($gift->deliverySpeed == 1) { echo '<i style="margin-right: 5px;" class="fa fa-truck"></i>' . $loc->label("Sent by cargo in 24 hours at the latest"); } else if($gift->deliverySpeed == 2) { echo '<i style="margin-right: 5px;" class="fa fa-envelope"></i>' . $loc->label("E-mail delivery"); } ?>
								
							</div>
							<p><?php echo evalLoc($gift->description); ?></p>
										
							<div class="row margin-top-40">
							<div class="col-md-12">
							<span style="font-size: 32px; font-weight: bold; margin-right: 5px;"><?php echo $gift->price; ?> & </span>  
							</div>
							<br/>
							<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
															<a href="javascript: backShopping();"><button class="btn btn-primary"><?php echo $loc->label("Back");?></button></a>     
								<a href="javascript: getTheGift(<?php echo $gift->ID; ?>,'',1,<?php echo $gift->price; ?>);"><button type="submit" class="btn btn-success btn-icon-left"><i class="fa fa-check-circle-o"></i><?php echo $loc->label("Buy");?></button></a>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
									<ul class="share">	
										<li><a href="javascript: like(<?php echo $giftID; ?>,1);"><i data-toggle="tooltip" title="Like" id="likeicon" style="font-size: 30px; color: <?php if($like == 1) { echo '#2196f3'; } else { echo "#9E9E9E"; } ?>;" class="fa fa-thumbs-o-up"></i></a><span style="font-size: 15px;" id="shopLike"><?php echo $row2[0]; ?></span></li>
										<li><a href="javascript: like(<?php echo $giftID; ?>,-1)"><i data-toggle="tooltip" title="Unlike" id="unlikeicon" style="font-size: 30px; color: <?php if($like == -1) { echo '#2196f3'; } else { echo "#9E9E9E"; } ?>;" class="fa fa-thumbs-o-down"></i></a><span style="font-size: 15px;" id="shopUnlike"><?php echo $row3[0]; ?></span></li>
									</ul>	  
								</div>
							</div>	
						</div>
							
						<div class="comments">
							<h4 class="page-header"><i class="fa fa-comment-o"></i> <?php echo $loc->label("Comments"); ?> (<span id="commentCount"><?php echo $row[0]; ?></span>)</h4> 	
							
							<div class="comment-form" style="margin-top: 20px;">  
								<h4 class="page-header"><?php echo $loc->label("Leave A Comment"); ?></h4>
								<div id="alertComment" class="alert alert-danger alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alertCommentText"></div>
								</div>
								<form>
									<div class="form-group">
										<textarea id="commentArea" class="form-control bg-white" rows="6" placeholder="<?php echo $loc->label("Write something..."); ?>"></textarea>
									</div>
									<a href="javascript: newcomment(<?php echo $giftID; ?>);"><button id="leaveCommentB" onClick="this.disabled=true; this.innerHTML='<?php echo $loc->label("Sending"); ?>...';" type="button" class="btn btn-primary btn-rounded btn-shadow pull-right"><?php echo $loc->label("Submit"); ?></button></a>
								</form> 
							</div>
						
							<div id="commentContainer"></div>
							<a id="showMoreCommentLink"  style="display: none;" href="javascript: showmorecomment(<?php echo $giftID; ?>);" class="btn btn-block btn-primary text-left margin-bottom-30"><i id="showMoreButtonC" style="display: none;" class="fa fa-spinner fa-pulse margin-right-10"></i><?php echo $loc->label("Load more comments"); ?></a>

							
							
							
						</div>
					</div>
					
					
			
				</div>
			</div>
			
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/jquery/jquery-1.11.1.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/owl-carousel/owl.carousel.min.js"></script>
	
	<script src="/Library/bootstrap-3.3.6/plugins/isotope/jquery.isotope.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/masonry/imagesloaded.pkgd.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/masonry/masonry.pkgd.min.js"></script>
		
<script>
(function($) {
	"use strict";

			
		/*	Lightbox
		/*----------------------------------------------------*/
		$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) { 
			event.preventDefault(); 
			$(this).ekkoLightbox();
		}); 
		
		/*	Isotope
		/*----------------------------------------------------*/
		$.fn.hideReveal = function( options ) {
		  options = $.extend({
			filter: '*',
			hiddenStyle: { opacity: 0.05 },
			visibleStyle: { opacity: 1 },
		  }, options );
		  this.each( function() {
			var $items = $(this).children();
			var $visible = $items.filter( options.filter );
			var $hidden = $items.not( options.filter );
			// reveal visible
			$visible.animate( options.visibleStyle );
			// hide hidden
			$hidden.animate( options.hiddenStyle );
		  });
		};

		var $container = $('.isotope');
		var $container_masonry = $('.masonry');
			  $container_masonry.imagesLoaded( function(){
				$container_masonry.isotope({
				filter: '*',
				animationOptions: {
				  easing: 'linear',
				  queue: false,
			   }
			});
		});
		
		// filter functions
		var filterFns = {
			// show if number is greater than 50
			numberGreaterThan50: function() {
			  var number = $(this).find('.number').text();
			  return parseInt( number, 10 ) > 50;
			},
			// show if name ends with -ium
			ium: function() {
			  var name = $(this).find('.name').text();
			  return name.match( /ium$/ );
			}
		};
  
		// bind filter button click
		$('#filter').on( 'click', 'a', function() {
			var filterValue = $( this ).attr('data-filter');
			// use filterFn if matches value
			filterValue = filterFns[ filterValue ] || filterValue;
			$container.hideReveal({ filter: filterValue });
			$container_masonry.hideReveal({ filter: filterValue });
			return false;
		  });

		// change is-checked class on buttons
		$('#filter').each( function( i, buttonGroup ) {
			var $buttonGroup = $( buttonGroup );
			$buttonGroup.on( 'click', 'a', function() {
			  $buttonGroup.find('.active').removeClass('active');
			  $( this ).addClass('active');
			  return false;
			});
		});
	})(jQuery);
</script>
	



<?php } ?>

<?php } ?>