<?php

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/platforms.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/balance.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/definitions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/publishers.php";
$runsql = new \data\DALProsess ();

date_default_timezone_set('Europe/Istanbul');

$loc = new localization ($_SESSION['language']);

if (!isset($_SESSION['userID'])) { 
	$url = trim($_SERVER["REQUEST_URI"], '/');
	
	$fn = new functions();
	$fn->redirect("login?pre=" . $url);
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
	
	$userNa = new users($_GET['id']);
	
	if(!empty($userNa->username) && !is_null($userNa->username)) {
		
		$findPublisher = new publishers ();
		$isPublisher = $findPublisher::checkPublisherWithUserID($_GET['id']);
		
		if($isPublisher->ID > 0) {
			$fn = new functions();
			$fn->redirect("publisher/" . $userNa->username);
		} else {
			$fn = new functions();
			$fn->redirect("user/" . $userNa->username);
		}
		
		
	} else {
		
		$ID  = $_GET['id'];
		$res = new users($ID);
	
		if ($res->email != "") {
		
			$usrid = $_GET['id'];
		
			$user = new users( $usrid );
		
			$myP = 0;

			if($usrid == $_SESSION["userID"]) {
	
				$myP = 1;
			}
		

		}
		else {
        
			$fn = new functions();
			$fn->redirect("404");
		
		}
		
		
	}
	

} else if(isset($_GET['username'])){
	
	$findUser = new users ();
	$gotUserID = $findUser::checkUserWithUsername($_GET['username'])->ID;
	$ID  = $gotUserID;
	$res = new users($ID);
	
	if ($res->email != "") {
		
		$findPublisher = new publishers ();
		$isPublisher = $findPublisher::checkPublisherWithUserID($ID);
	
		if($isPublisher->ID > 0) {
			$fn = new functions();
			$fn->redirect("publisher/" . $res->username);
		} else {
		
			$usrid = $gotUserID;
		
			$user = new users( $usrid );
				
			$myP = 0;

			if($usrid == $_SESSION["userID"]) {
	
				$myP = 1;
			}
		}

	} else {
        
		$fn = new functions();
		$fn->redirect("404");
		
	}
		
	
	
} else if(isset($_GET['publisher'])){
	
	$findUser = new users ();
	$gotUserID = $findUser::checkUserWithUsername($_GET['publisher'])->ID;
	$ID  = $gotUserID;
	$res = new users($ID);
	
	if ($res->email != "") {
		
		$findPublisher = new publishers ();
		$isPublisher = $findPublisher::checkPublisherWithUserID($ID);
	
		if($isPublisher->ID > 0) {
			
			$usrid = $gotUserID;
		
			$user = new users( $usrid );
		
			$myP = 0;

			if($usrid == $_SESSION["userID"]) {
	
				$myP = 1;
			}
		
		} else {
        
			$fn = new functions();
			$fn->redirect("404");
		
		}

	} else {
        
		$fn = new functions();
		$fn->redirect("404");
		
	}
		
	
} else {
	
	$fn = new functions();
	$fn->redirect("profile?id=" . $_SESSION["userID"]);
		
}

$definitionC = new definitions($user->country);
$definitionG = new definitions($user->gender);

?>
	
<link href="/Library/bootstrap-3.3.6/plugins/ekko-lightbox/ekko-lightbox.min.css" rel="stylesheet">
<style type="text/css">
 .profileN { display: none;}
 .profileCover { display: inline;}
	body {
	background-color: #FAFAFA;
}
	
.hero-bga {
	
	background-image: -o-linear-gradient(bottom, #000000b3 40%, rgba(0,0,0,0) 100%);
    background-image: -moz-linear-gradient(bottom, #000000b3 40%, rgba(0,0,0,0) 100%);
    background-image: -webkit-linear-gradient(bottom, rgba(0, 0, 0, 0.52) 40%, rgba(0,0,0,0) 100%);
    background-image: -ms-linear-gradient(bottom, #000000b3 40%, rgba(0,0,0,0) 100%);
	background-image: linear-gradient(bottom, #000000b3 40%, rgba(0,0,0,0) 100%);
	position:absolute;
	left:0px;
	width: 100%;
	bottom:0px;
	height:50px;

}
	
@media screen and (max-width: 770px) { 
.profileN {
	display: inline;
}
.profileCover {
	display: none;
}
.profileTop {
	margin-bottom: 100px;
}
}
   

</style>
	<input type="hidden" id="platformID" value="0"/>
	<input type="hidden" id="pagenum" value="0"/>
	<input type="hidden" id="isActive" value="0"/>  
		<section class="hero cover profileTop" id="heroTop" style="<?php echo ($user->coverPicture!="") ? "background-image: url(" . project::uploadPath."/userCoverImg/".$user->coverPicture . ");" : "background-color: rgba(0,0,0,0.6);";?>">
			<div class="container relative">
				<div class="page-header">
				<?php if($myP == 1 && $user->coverPicture=="") {?> <a class="profileCover" href="settings?tab=personal"><?php echo $loc->label("Click here to upload a cover image");?></a><?php } ?>
					<div class="page-title hidden-xs">
					
					
					
					<?php echo $user->fullName;?> 

					
					</div>	
					<div class="profile-avatar">
						<div class="thumbnail" data-toggle="tooltip" title="<?php echo $user->fullName;?>">
						<a <?php if($user->picture=="" && $myP == 1) {?>href="settings?tab=personal"<?php } else if($user->picture!="") {?>href="<?php echo project::uploadPath."/userImg/".$user->picture; ?>" <?php if($myP == 1) {?>data-title="<a href='settings?tab=personal' style='color: white; font-weight: bold; font-size: 16px;'><i class='fa fa-edit'></i><?php echo $loc->label("Edit");?></a>"<?php } ?> data-toggle="lightbox"<?php } ?>>
								<img src="<?php echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg";?>">
						</a>
						</div>
					</div>
				</div>
			</div>
				
			<div class="hero-bga"></div>
		
		</section>
		
	
	
		
		
		<section class="profile-nav height-50 border-bottom-1 border-grey-300 pageM">
			<div class="tab-select sticky">
				<div class="container">
					<ul class="nav nav-tabs" role="tablist">
						<a id="profilePS" style="margin-right: 15px; display: none;" href="profile?id=<?php echo $user->ID; ?>" class="author" data-toggle="tooltip" title="<?php echo $user->fullName;?>"><img class="img-circle" width="42" height="42" src="<?php echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg";?>" alt=""></a>
						<li class="active"><a href="#1" data-toggle="tab" onclick="showhide('1');"><?php echo $loc->label("Posts");?></a></li>
						<li><a href="#2" id="about" data-toggle="tab" onclick="showhide('2');"><?php echo $loc->label("About");?></a></li>
						
					</ul>
				</div>
			</div>
			
		</section>
		
		<section class="bg-grey-50 padding-top-60 padding-top-sm-30">
			<div class="container">
				<div class="row">
				
				
					
					<div class="tab-pane fade in active" id="1">
					
					<div class="col-md-3 col-sm-4" style="margin-bottom: 20px;">
					
					<div class="widget profileN">
							<div class="panel panel-default">
							<div class="panel-body" style="overflow-wrap: break-word;">
							<h3><?php echo $user->fullName;?></h3>
							</div>
							
							</div>
					</div>
					
						<div class="widget">
							<div class="panel panel-default">
								<div class="panel-heading"><?php echo $loc->label("About");?></div>
								<div class="panel-body">
									<?php custom_echo($user->about, 150);?>
									<?php if($user->username != "funnyandmoney") { ?>
									<ul class="panel-list margin-top-25">
									
										<li><i class="fa fa-map-marker"></i> <?php echo $user->city;?><?php if ($user->city !="") { ?>,<?php } ?><?php echo evalLoc( $definitionC->definition );?></li>
									
										<li><i class="fa fa-clock-o"></i> <?php echo  date( 'l, F d, Y', strtotime( $user->birthDate ) );?></li>
										
										<?php if($user->gender == 2) { ?>
										<li><i class="fa fa-venus"></i> <?php echo evalLoc( $definitionG->definition ); ?></li>
										<?php } else { ?>
										<li><i class="fa fa-mars"></i> <?php echo evalLoc( $definitionG->definition ); ?></li>
										<?php } ?>
										
										<?php if ($user->status !="") {?>
										
										<li><i class="fa fa-chain-broken"></i> <?php echo $user->status;?></li>
										
										<?php }?> 
										
									</ul>
									<?php } ?>
								</div>
							</div>
						</div>
						
						<div class="widget">
							<div class="panel panel-default">
								<div class="panel-heading"><?php echo $loc->label("Platforms");?></div>
								<div class="panel-body no-padding">
									<ul class="panel-list-bordered">
										<li><a href="javascript: changePlatform(0);"><i class="ion-social-facebook"></i><i class="fa fa-twitter"></i><i class="fa fa-youtube"></i><?php echo $loc->label("ALL");?></a></li>
										<li><a href="javascript: changePlatform(1);"><i class="ion-social-facebook"></i> <?php echo $loc->label("Facebook");?></a></li>
										<li><a href="javascript: changePlatform(2);"><i class="fa fa-twitter"></i> <?php echo $loc->label("Twitter");?></a></li>
										<li><a href="javascript: changePlatform(4);"><i class="fa fa-youtube"></i> <?php echo $loc->label("Youtube");?></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-9 col-sm-8" style="margin-top: -10px;">
					
					<?php if ($myP == 1) {?>
					
						<div class="panel panel-default margin-bottom-40">
							<div class="panel-body">
								<div class="form-group">
									<h3><?php echo $loc->label("Now Add New Post!");?></h3>
								</div>
								<button type="button"  onclick="location.href='addpost'" class="btn btn-lg btn-block btn-shadow btn-primary btn-icon-left"><i class="fa fa-edit"></i> <?php echo $loc->label("Add New Post");?></button>
								<button type="buttom"  onclick="location.href='myposts'" class="btn btn-lg btn-block btn-shadow btn-default btn-icon-left"><i class="ion-ios-grid-view"></i> <?php echo $loc->label("View All Post");?></button>
							</div>
						</div>
						
					<?php } ?>
					
					<div id="profileContainer"></div>
					
					<center><a id="showMoreLink" href="javascript: showmore();" class="btn btn-primary btn-lg btn-shadow btn-rounded"><?php echo $loc->label("Show More");?></a> <div id="loader" style="display: none;"><img src="images/loader.gif" /></div><div id="noPostText" style="display: none;"><?php echo $loc->label("NOPOSTP"); ?></div></center>
					
					</div>
					
					</div>
					
					<div class="tab-pane fade" id="2" style="display: none;">
					
					<div class="col-md-12 col-sm-8">
					
					
					<div class="widget">
							<div class="panel panel-default">
								<div class="panel-heading"><?php echo $loc->label("About");?></div>
								<div class="panel-body">
									<?php echo $user->about;?>
									<?php if($user->username != "funnyandmoney") { ?>
									<ul class="panel-list margin-top-25">
										<li><i class="fa fa-map-marker"></i> <?php echo $user->city;?><?php if ($user->city !="") { ?>,<?php } ?><?php echo evalLoc( $definitionC->definition );?></li>
									
										<li><i class="fa fa-clock-o"></i> <?php echo  date( 'l, F d, Y', strtotime( $user->birthDate ) );?></li>
										
										<?php if($user->gender == 2) { ?>
										<li><i class="fa fa-venus"></i> <?php echo evalLoc( $definitionG->definition ); ?></li>
										<?php } else { ?>
										<li><i class="fa fa-mars"></i> <?php echo evalLoc( $definitionG->definition ); ?></li>
										<?php } ?>
										
										<?php if ($user->status !="") {?>
										
										<li><i class="fa fa-chain-broken"></i> <?php echo $user->status;?></li>
										
										<?php }?> 
									</ul>
									<?php }?> 
								</div>
							</div>
						</div>
					
					
					</div>

					</div>

					
				</div>
			</div>
		</section>
	
	<!-- Javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/jquery/jquery-1.11.1.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/owl-carousel/owl.carousel.min.js"></script>
	
	<script src="/Library/bootstrap-3.3.6/plugins/isotope/jquery.isotope.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/masonry/imagesloaded.pkgd.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/masonry/masonry.pkgd.min.js"></script>
	
	<script>
var sizeW;
	
$(document).ready(function(){
	
if ($(window).width() < 960) {
   document.getElementById("heroTop").style.height = "300px";
   sizeW = 300;
}
else {
   document.getElementById("heroTop").style.height = "500px";  
   sizeW = 500;
}

});
	
$(document).ready(function(){
window.onresize = function(event) {
	
if ($(window).width() < 960) {
   document.getElementById("heroTop").style.height = "300px";
   sizeW = 300;
}
else {
   document.getElementById("heroTop").style.height = "500px";  
   sizeW = 500;
}

};

});
	
	(function($) {
	"use strict";
		$(window).scroll(function(){
			if ($(this).scrollTop() > sizeW) {
				$('body').addClass('fixed-tab');
				document.getElementById("profilePS").style.display = "";
			} else {
				$('body').removeClass('fixed-tab');
				document.getElementById("profilePS").style.display = "none";
			}
		});
	})(jQuery);
	</script>
		
		
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
	
	<script>

	
	function showhide(id){
        if (document.getElementById) {
          var divid = document.getElementById(id);

		  
		  for(var i=1;i<=2;i++) {
			 document.getElementById(i).style.display = "none";
          }

		  
          divid.style.display = "block";
        } 
        return false;
 }
		function showmore(){
			
			$('#noPostText').hide(); 
			$('#showMoreLink').hide();
			$('#loader').show(); 
			
			
			var platformID= $("#platformID").val();
			$("#pagenum").val(+$("#pagenum").val()+1);
			var pageNUM= $("#pagenum").val();
			$.ajax({
				type: 'POST',
				url: "../BL/showProfile.php",
				data: {platform: platformID, page: pageNUM, id: <?php echo $ID; ?>},
				success: function cevap(e){
					if(!(e.indexOf("NOPOST") > -1)){
						$('#profileContainer').append(e);
						$("#isActive").val(1);
						
						
						$('#loader').hide(); 
						$('#showMoreLink').show();
						
					}else{
						$("#isActive").val(0);
						

						$('#loader').hide(); 
						$("#showMoreLink").hide();
						$('#noPostText').show(); 
						
					}
					
				}
			
		});
		}function changePlatform(ID){
			$('#profileContainer').html('');
			$('#platformID').val(ID);
			$('#pagenum').val(0);
			$("#isActive").val(0);
			showmore();
		}
		$( document ).ready(function() {
			showmore();
		});
		$(window).scroll(function() {  
			if(($(window).scrollTop() + $(window).height() == $(document).height()) && $("#isActive").val() == 1) {
				showmore();
			}
		});
	</script>