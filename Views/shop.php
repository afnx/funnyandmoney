<?php

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/gifts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/definitions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/address.php";

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}

$loc = new localization ($_SESSION['language']); 

$obj = new objects();

$_SESSION['pagenumG']=1;
$_SESSION['randomG']=rand(1,50);

if(isset($_GET["product"])) {
	
	if(is_numeric($_GET["product"])) {
	
		$productpage = $_GET["product"];
		
	} else {
		
		$productpage = 0;
		
	}
	
	
} else {
	
	$productpage = 0;
	
}


?>

	
<style>

#loading {
	display: none;
	position: fixed; 
	left: 0px; 
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 999;  
	opacity: 0.6;
	background: url(../images/loading2.gif) center no-repeat #101010;
}
</style>
	
	
<section id="scrollToHere" class="hero hero-games height-300" style="background-image: url(images/cover-gift2.jpg);" style="z-index: 9990;">
<div class="hero-bg"></div>
			<div class="container">
				<div class="page-header">
					<div class="page-title bold"><?php echo $loc->label("Shop"); ?></div>
					<p><?php echo $loc->label("you do not need money here"); ?></p>
				</div>
			</div>
		</section>
		
		<div id="gifts-panel">
		
		<section class="padding-top-25 no-padding-bottom border-bottom-1 border-grey-300" style="z-index: 9990;">
			<div class="container">
				<div class="headline">
					<h4 style="margin-bottom: 15px;"><a href="shop"><?php echo $loc->label("Products"); ?></a></h4>
					
					<a href="javascript: reset();" class="btn btn-default" style="float: right; margin-left: 10px; max-height: 40px;"><?php echo $loc->label("Reset Filters"); ?></a> 
						
					<div class="dropdown display-block" style="margin-bottom: 15px;">
						<a class="btn btn-default btn-icon-left btn-icon-right dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sort-amount-desc"></i> <?php echo $loc->label("Sort by"); ?> <i class="ion-android-arrow-dropdown"></i></a>
						<ul class="dropdown-menu">
							<li><a href="javascript: sortGifts(0,0,1,0,2);"><?php echo $loc->label("Newly added"); ?></a></li>
							<li><a href="javascript: sortGifts(0,0,2,0,2);"><?php echo $loc->label("& lowest first"); ?></a></li>
							<li><a href="javascript: sortGifts(0,0,3,0,2);"><?php echo $loc->label("& highest first"); ?></a></li>
							<li><a href="javascript: sortGifts(0,0,4,0,2);"><?php echo $loc->label("No shipping"); ?></a></li>
						</ul>
					</div>    
					
					<?php echo $obj->dropDownFillCategory("select ID,definition from definitions where definitionID=28 and isDeleted<>1 order by ID asc", "categoryID", 0);?>	
					
					
					
				<input id="searchText" type="text" class="form-control" placeholder="<?php echo $loc->label("What are you looking for?"); ?>" />
			
							 
				</div>
			</div>
		</section>
		
		<section class="bg-grey-50">
			<div class="container">
				<div id="giftscont" class="row gift">
				
		
					
				</div> 
				
<div style="margin-bottom: 50px; margin-top: 50px;" id="loadMoreArea" class="text-center"></div>
				
			</div>
		</section>
		
		</div>
		
		
		
		<div id="modal" class="modal fade bs-modal" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $loc->label("Close");?></span></button>
								<h4 class="modal-title" style="text-align: center;"><?php echo $loc->label("Error");?></h4>
							</div>
							<div class="modal-body">
								
								<p id="modalText" style="text-align: center;"></p>			
								
							</div>
							<div class="modal-footer">

								<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("Close");?></button>

							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
				
				
				<input id="modalButton" type="hidden" data-toggle="modal" data-target=".bs-modal">
		
		
			<div id="success-panel" style="display: none; margin-top: 15px;">
	
	<div class="container"> 
	

	
	<div class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">
								

								
								<div class="panel-body">
								
								
								<section class="bg-success subtitle-lg margin-bottom-30">
			<div class="container" style="float: left;">
				<h2><i class="fa fa-check-circle" style="color: white; margin-right: 5px;"></i><?php echo $loc->label("You get a product!");?></h2>
				<?php echo $loc->label("You can check on My Orders");?>
				</div>
		</section>

									<div class="post">

										<div class="form-group" style="position: relative;">

					
									<p style="margin-bottom: 5px;" id="giftResultText"></p>
									<br/>
									
									
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 35px; margin-top: 15px;">
						<a href="javascript: backShopping();"><button type="button" class="btn btn-lg btn-block btn-rounded btn-shadow btn-primary btn-icon-left"><i class="fa fa-arrow-circle-left"></i><?php echo $loc->label("Click here to continue shopping");?></button></a>
					</div>
  
									
									
			
				
				</div>
				</div>
				</div>
				
				</div>
	

	</div>
	
	</div>
	
	
	<div id="giftdetail-panel" style="display: none; margin-top: 30px; margin-bottom: 30px;">
	
	<div class="container"> 
	

	
	<div class="panel panel-default panel-post"
								style="border-color: #EEEEEE;">
								

								
								<div class="panel-body">
								

									<div class="post">

										<div class="form-group" style="position: relative;">  

					
									<p style="margin-bottom: 5px;" id="giftDetailResult"></p>
									<br/>
									
									
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 35px; margin-top: 15px;">
						<a href="javascript: backShopping();"><button type="button" class="btn btn-lg btn-block btn-rounded btn-shadow btn-primary btn-icon-left"><i class="fa fa-arrow-circle-left"></i><?php echo $loc->label("Click here to continue shopping");?></button></a>
					</div>
  
									
									
			
				
				</div>
				</div>
				</div>
				
				</div>
	

	</div>
	
	</div>
	  
	
	<div id="getModal"></div> 
	
				
				<input id="opener" type="hidden" data-toggle="modal" data-target=".bs-modalAddress">

		
		<div id="loading"></div>
		
		<input type="hidden" id="which_category" value="0">
		<input type="hidden" id="which_sort" value="0">
		<input type="hidden" id="w_search" value="">
		<input type="hidden" id="giftPoint" value="">

		
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/jquery/jquery-1.11.1.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/owl-carousel/owl.carousel.min.js"></script>
	
	<script src="/Library/bootstrap-3.3.6/plugins/isotope/jquery.isotope.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/masonry/imagesloaded.pkgd.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/masonry/masonry.pkgd.min.js"></script>
		
<script>

var productpage = <?php echo $productpage; ?>;

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
function showmorecomment(id){
		 
		$('#showMoreButtonC').show(); 
		
			$("#pagenum").val(+$("#pagenum").val()+1);
			var pageNUM= $("#pagenum").val();
			$.ajax({
				type: 'POST',
				url: "../BL/showShopComments.php",
				data: {page: pageNUM, giftID: id},
				success: function cevap(e){
					if(!(e.indexOf("NOCOMMENT") > -1)){
						$('#commentContainer').append(e);
						$("#showMoreButtonC").hide();
						$("#showMoreCommentLink").show();

					}else{
						if( $('#commentContainer').is(':empty') ) {
							$('#commentContainer').html("<p id='nocommenttext'><?php echo $loc->label("Be the first to leave a comment!"); ?></p>");
						}
						$("#showMoreButtonC").hide();
						$("#showMoreCommentLink").hide();
						
					}
					
				}
			
		});
		}


function newcomment(id){
		
$( "#commentArea" ).prop( "disabled", true );

			$.ajax({
				type: 'POST',
				url: "../Controllers/formPosts.php?action=newShopComment",
				data: {giftID: id, comment: $("#commentArea").val()},
				success: function cevap(e){
					
					$("#nocommenttext").hide();  
					
					if(!(e.indexOf("xxASSokS322_!!Xsaxx") > -1)){
						$('#alertCommentText').html(e);
						$("#alertComment").show();

					}else{  
						$('#commentContainer').prepend($(e.replace("xxASSokS322_!!Xsaxx","")).fadeIn('slow'));
						$("#alertComment").hide();
						$( "#commentArea" ).val("");
						$("#commentCount").text(Math.round(+$("#commentCount").text() + 1));
						
					}
					
					$( "#leaveCommentB" ).prop( "disabled", false );
					$( "#commentArea" ).prop( "disabled", false );
					$( "#leaveCommentB" ).html("<?php echo $loc->label("Submit"); ?>");
					
				}
			
		});
		}

function like(id,like){

			$.ajax({
				type: 'POST',
				url: "../Controllers/formPosts.php?action=likeShop",
				data: {giftID: id, like: like},
				success: function cevap(e){
					
					if(e.indexOf("ok") > -1 && like == 1){
						
						$("#shopLike").text(Math.round(+$("#shopLike").text() + 1));
						
						document.getElementById("likeicon").style.color = "#2196f3";
						document.getElementById("unlikeicon").style.color = "#9E9E9E";
						
					} else if(e.indexOf("ok") > -1 && like == -1){
						
						$("#shopUnlike").text(Math.round(+$("#shopUnlike").text() + 1));
						
						document.getElementById("unlikeicon").style.color = "#2196f3";
						document.getElementById("likeicon").style.color = "#9E9E9E";
						
					} else if(e.indexOf("tk") > -1 && like == 1){
						
						$("#shopLike").text(Math.round(+$("#shopLike").text() + 1));
						$("#shopUnlike").text(Math.round(+$("#shopUnlike").text() - 1));
						
						document.getElementById("likeicon").style.color = "#2196f3";
						document.getElementById("unlikeicon").style.color = "#9E9E9E";
						
					} else if(e.indexOf("tk") > -1 && like == -1){
						
						$("#shopUnlike").text(Math.round(+$("#shopUnlike").text() + 1));
						$("#shopLike").text(Math.round(+$("#shopLike").text() - 1)); 
						
						document.getElementById("unlikeicon").style.color = "#2196f3";
						document.getElementById("likeicon").style.color = "#9E9E9E";
						
					} 
					
					
				}
			
		});
		}

function closeAlert(){
						$("#alertComment").hide();
					}  
</script>
		
<script> 



	function getTheGift(id,addressID,modal,price){
		
		addressID = typeof addressID !== 'undefined' ? addressID : '';
		modal = typeof modal !== 'undefined' ? modal : 0;
		
		$("#giftPoint").text(price);
		
	
			$("#loading").show();
	
		
		if(addressID != "") {
			
			document.getElementById("closeA").click(); 
			
		}

	
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=getGift",
			data: {giftID: id, addressID: addressID},
			success: function cevap(e){
				
				
				
		
				if(e.match(/[a-z]/i) && !(e.indexOf("okGiftxX232") > -1) && !(e.indexOf("XXsqwq-_!343**3434sdXxADs") > -1)){  
					
					$('#loading').hide();
					document.getElementById("modalText").innerHTML = e;
					document.getElementById("modalButton").click(); 
					
				}else if(e.indexOf("okGiftxX232") > -1 && !(e.indexOf("XXsqwq-_!343**3434sdXxADs") > -1)){
					
					var now;
					getBalance(function(output){
						now = output;  
					});
					
					$("html, body").animate({ scrollTop: $("#scrollToHere")[0].scrollHeight }, "slow");

					$('#loading').hide();
					document.getElementById("giftResultText").innerHTML = e.replace("okGiftxX232", "");
					$('#gifts-panel').hide();
					$('#giftdetail-panel').hide(); 
					$('#success-panel').show();   
					$("#newPoints").text('-'+$("#giftPoint").text()).css("display", "inline");
					$("#newPoints").removeClass("label-success").addClass("label-danger");
					$("#currentPoints").fadeOut(500, function(){$("#currentPoints").text(now)}).fadeIn(1500, function(){$("#newPoints").css("display", "none");});
					
				} else if(e.indexOf("XXsqwq-_!343**3434sdXxADs") > -1) {
					
					$('#loading').hide();
					document.getElementById("getModal").innerHTML = e.replace("XXsqwq-_!343**3434sdXxADs", "");
					document.getElementById("opener").click();  
					
				}
				
				$('#loading').hide();

			}
			}) 
		}
	
		function backShopping() {
			
if(productpage == 0) {
		
	$('#success-panel').hide(); 
	$('#giftdetail-panel').hide(); 
	$('#gifts-panel').show();
	
} else {
	

window.history.pushState("object or string", "Title", "/"+window.location.href.substring(window.location.href.lastIndexOf('/') + 1).split("?")[0]);
sortGifts(0,1,0,0,0,1);
productpage = 0;

}
	
			
			
		}
		

  
	function sortGifts(id,next,sort,searchV,filter,first,nextON) {

		first = typeof first !== 'undefined' ? first : 0;


		var searchNoText = 0;
		
					if(next == 0){
						
					switch(filter) {
						
						case 1 :
							
							searchNoText = 1;
							$("#which_category").val(id);
							sort= $("#which_sort").val();
							searchV= $("#w_search").val();
							break;
							
						case 2 :
							
							searchNoText = 1;
							$("#which_sort").val(sort);
							id= $("#which_category").val();
							searchV= $("#w_search").val();  
							break;
							
						case 3 :
							
							searchNoText = 1;
							$("#w_search").val(searchV); 
							id= $("#which_category").val();
							sort= $("#which_sort").val();  
							break;
							
						default:
							
							$("#which_category").val(id);
							$("#which_sort").val(sort);  
							$("#w_search").val(searchV); 
							break;
						
						
					}
						
						next = 1;
						
					}else{
					
						id= $("#which_category").val();
						sort= $("#which_sort").val();
						searchV= $("#w_search").val();
					}

						
					$.ajax({
						type: 'POST',
						url: "../BL/showGifts.php",
						data: {category: id, nextpageG: next, sortby: sort, search: searchV}, 
						success: function cevap(e){



							if(e.indexOf("NOPRODUCT") > -1){  
								
								$("#giftscont").html('<div style="margin-bottom: 150px; margin-top: 100px; text-align: center;"><h1><i class="fa fa-thumbs-o-up" style="font-size: 250px;"></i></h1><br/>' + "<?php echo $loc->label('No product you are looking for in Shop. Nevertheless'); ?>" + '</div>');
								
							} else {
								
								$('#giftscont').html(e.replace("NOPRODUCT", ""));
								
							}
							
							if(first != 1) {
								
								$("html, body").animate({ scrollTop: $("#scrollToHere")[0].scrollHeight }, "slow");
							
							}
						
						}
						});
						
						
						$.ajax({
						type: 'POST',
						url: "../BL/showGifts.php",
						data: {category: id, nextpageG: next, sortby: sort, search: searchV, pegiON: 1}, 
						success: function answer(a){

							$("#loadMoreArea").html(a);
							
						}
						});
						
					
				}
	
	function reset() {
		
		$('#searchText').val("");
		sortGifts(0,0,0,0,0,0,1);
		
	}
	
	var stop;
	
	$(document).keypress(function(e) { 
					if(e.which == 13) {
						if($("#searchText").val() != ""){
							var thisVal = $("#searchText").val();
							sortGifts(0,0,0,thisVal,3);
							stop = 1;
						}
					}
				});
	
	
	$("#searchText").keyup(function() {
    delay(function(){
	if(stop != 1) {
     var thisVal = $("#searchText").val();  
	sortGifts(0,0,0,thisVal,3);
	} else {
		
		stop = 0;
		
	}
  
    }, 400 );
});


	var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();
	
	
	/*$("#searchText").change(function(){
		var thisVal = $("#searchText").val();
		sortGifts(0,0,0,thisVal,3);
});*/
	
<?php	if($productpage == 0) { ?>
		
	$( document ).ready(function() {
		sortGifts(0,1,0,0,0,1);
	});
	
<?php } else { ?>
	
	getTheGiftDetails(<?php echo $productpage; ?>);  
	
<?php } ?>
	
	

	function getTheGiftDetails(id){
		$("#loading").show();
			$.ajax({
			type: 'POST',
			url: "../BL/showGiftDetails.php",
			data: {giftID: id},
			success: function cevap(e){
				$("html, body").animate({ scrollTop: $("#scrollToHere")[0].scrollHeight }, "slow");
				document.getElementById("giftDetailResult").innerHTML = e;
				$('#gifts-panel').hide();
				$('#success-panel').hide();
				$('#giftdetail-panel').show();   
				showmorecomment(id);   
$("#loading").hide();
			}
			}) 
		}
		

</script>