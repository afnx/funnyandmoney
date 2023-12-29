<?php

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/platforms.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/positions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/definitions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/DL/DAL.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}




$loc = new localization ($_SESSION['language']);

$usrid = $_SESSION["userID"];


$fn2 = new functions();
$fn3 = new functions();



if(isset($_GET['delete'])){


 $postIDr = $_GET['delete'];

 $postDeleted = new posts($postIDr);


}


?>
		<input type="hidden" id="pagenum" value="0"/>
		<input type="hidden" id="isActive" value="0"/>

		
<section class="bg-default padding-top-30 padding-bottom-30 bg-grey-50 border-bottom-1 border-grey-200" style="background-color: rgb(250, 250, 250) !important;">

	<div class="container">

		<h2 class="font-size-24 color-inverse font-weight-300" style="float: left;"><?php echo $loc->label("My Posts");?></h2>
		<a href="addpost"><button style="float: right;" type="button" class="btn btn-default"><?php echo $loc->label("Add Post");?></button></a>

	</div>

</section>



		<?php  if(isset($_GET['delete']) && $postDeleted->userID == $usrid){?>


				<div class="container">
					<div class="row">

		<?php if($postDeleted->isDeleted == 1 && $usrid == $postDeleted->userID ){?>

			<div class="alert alert-success alert-lg fade in margin-top-30">
						<h4 class="alert-title"><i class="fa fa-trash" style="margin-right: 5px;"></i><?php echo $loc->label("You deleted a post successfully!");?></h4>
						<p><?php echo $loc->label("Post Name");?>:  &nbsp;<?php echo $postDeleted->title;?></p>
			</div>


		<?php } else if($postDeleted->isDeleted == 0 || $userid == $postDeleted->userID) { ?>

			<div class="alert alert-danger alert-lg fade in margin-top-30">
						<h4 class="alert-title"><i class="fa fa-trash" style="margin-right: 5px;"></i><?php echo $loc->label("Deleting Transaction Failed!");?></h4>
						<p><?php echo $loc->label("Post Name");?>:  &nbsp;<?php echo $postDeleted->title;;?></p>
			</div>


		<?php }?>



		</div>
		</div>


		<?php }?>

		<section>
			<div class="container">
				
					<div class="col-md-12 leftside">

						<div id="mypostsContainer"></div>

					<!--
					<ul class="pagination">
							<li><a href="#"><span>&laquo;</span></a></li>
							<li><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li><a href="#">...</a></li>
							<li><a href="#">10</a></li>
							<li><a href="#">11</a></li>
							<li><a href="#"><span>&raquo;</span></a></li>
						</ul>
					-->
					</div>


			

<center><a id="showMoreLink" href="javascript: showmore();" class="btn btn-primary btn-lg btn-shadow btn-rounded"><?php echo $loc->label("Show More");?></a><div id="loader" style="display: none;"><img src="images/loader.gif" /></div><div id="noPostText" style="display: none;"><?php echo $loc->label("NOPOSTP"); ?></div></center>



			</div>


		</section>

	<script>
	function showmore(){
		 
		$('#noPostText').hide(); 
		$('#showMoreLink').hide();
		$('#loader').show(); 
		
			$("#pagenum").val(+$("#pagenum").val()+1);
			var pageNUM= $("#pagenum").val();
			$.ajax({
				type: 'POST',
				url: "../BL/showMyposts.php",
				data: {page: pageNUM},
				success: function cevap(e){
					if(!(e.indexOf("NOPOST") > -1)){
						$('#mypostsContainer').append(e);
						$("#isActive").val(1);
						
						$('#loader').hide(); 
						$('#showMoreLink').show();
						
					}else{
						$("#isActive").val(0);
						
						$('#loader').hide(); 
						$('#noPostText').show(); 
						$("#showMoreLink").hide();
					}
					
				}
			
		});
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
