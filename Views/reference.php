<?php

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}


$loc = new localization ($_SESSION['language']);

$usrid = $_SESSION["userID"];

$user = new users($usrid );



?>

	
			
<section class="bg-default padding-top-30 padding-bottom-30 bg-grey-50 border-bottom-1 border-grey-200" style="background-color: rgb(250, 250, 250) !important;">

	<div class="container">

		<h2 class="font-size-24 color-inverse font-weight-300"><?php echo $loc->label("Reference");?></h2>

	</div>

</section>

	<?php if($user->referrerON != 1) { ?>
		
	<section class="bg-success subtitle" style="padding: 80px;">
	
		<div class="container">
		
		
		
			<div class="row">
			
			<div class="col-lg-12 text-center"> 
			
				<p style="font-size: 80px;"><i class="ion-arrow-graph-up-right"></i></p>
			
				<h1 style="color: #FFF;"><?php echo $loc->label("Reference SloganH2");?></h1> 
				
			</div>
				
			</div>  
			
			
				</div>
		
	</section>
			
			
			<section class="elements">
	
		<div class="container">
		

				
				<div class="row margin-bottom-30">
			
			<div class="col-lg-6 text-center">
			
				<h3><?php echo $loc->label("Reference Title1");?></h3>
				
				<p><?php echo $loc->label("Reference Text1");?></p>
				
			</div>
			
			
			<div class="col-lg-6 text-center"> 
			
				<h3><?php echo $loc->label("Reference Title2");?></h3>
				
				<p><?php echo $loc->label("Reference Text2");?></p>
				
			</div>
				
				
				
			</div> 
			
			
						<div class="row" style="margin-bottom: 50px; margin-top: 50px;">
			
			<div class="col-lg-12 text-center">
			<div class="panel panel-default padding-bottom-30 padding-top-30">
					<div class="panel-body">

					<form method="post" action = "Controllers/formPosts.php?action=onReference">
				<center><button style="width: 50%;" type="submit" class="btn btn-lg btn-block btn-rounded btn-shadow btn-primary"><?php echo $loc->label("Start Now");?></button></center>  
				</form>
					
					</div>  
				</div>

				
				</div>
				
				</div>
				
				
				
				<div class="row margin-top-50">  
				
					<div class="col-lg-12 text-center">  
						<h3><?php echo $loc->label("Details");?></h3>
						<p><?php echo $loc->label("You can contact us if not enough");?></p>
						<div class="panel-group" id="accordion">
							<div class="panel panel-default">
								<div class="panel-heading" id="headingOne">
									<h4 class="panel-title">
										<a href="#collapseOne" data-toggle="collapse" data-parent="#accordion">
											<b><?php echo $loc->label("RefFAQTitle1");?></b>
										</a>
									</h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body">
										<?php echo $loc->label("RefFAQText1");?>
									</div>
								</div>
							</div>
						  <div class="panel panel-default">
							<div class="panel-heading" id="headingTwo">
							  <h4 class="panel-title">
								<a href="#collapseTwo" class="collapsed" data-toggle="collapse" data-parent="#accordion">
									<b><?php echo $loc->label("RefFAQTitle2");?></b>
								</a>
							  </h4>
							</div>
							<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel">
								<div class="panel-body">
								<?php echo $loc->label("RefFAQText2");?>
								</div>
							</div>
						  </div>
						  <div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingThree">
							  <h4 class="panel-title">
								<a href="#collapseThree" class="collapsed" data-toggle="collapse" data-parent="#accordion">
									<b><?php echo $loc->label("RefFAQTitle3");?></b>
								</a>
							  </h4>
							</div>
							<div id="collapseThree" class="panel-collapse collapse">
								<div class="panel-body">
								<?php echo $loc->label("RefFAQText3");?>
								</div>
							</div>
						  </div>
						  <div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingFour">
							  <h4 class="panel-title">
								<a href="#collapseFour" class="collapsed" data-toggle="collapse" data-parent="#accordion">
									<b><?php echo $loc->label("RefFAQTitle4");?></b>  
								</a>
							  </h4>
							</div>
							<div id="collapseFour" class="panel-collapse collapse">
								<div class="panel-body">
								<?php echo $loc->label("RefFAQText4");?>  
								</div>
							</div>
						  </div>
						</div>
					</div>

				</div>
			
			
			
		</div>
		
		</section>
			
			
		<?php } else { ?>
		
		
		<section>
	
		<div class="container">
		
		
		<div class="row margin-top-30">
					<div class="col-lg-12 text-center">  
						<h3 class="margin-bottom-15"><?php echo $loc->label("Reference Panel");?></h3>
						<p><?php echo $loc->label("ReferencePanelText1");?></p>
					</div>
				</div>

				
		<div class="row margin-top-30">
			
			<div class="col-lg-6 text-center margin-top-30">
			
			<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title"><?php echo $loc->label("Earned N");?></h3>
							</div>
							<div class="panel-body">
								<h1 id="earn"></h1>
							</div>
						</div>

				
			</div>
			
			
			<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title"><?php echo $loc->label("Reference");?></h3>
							</div>
							<div class="panel-body">
								<h1 id="refCount"></h1>
							</div>
						</div>

			
			
			</div>
				
				
				
		</div> 
		
		
		<div class="row" style="margin-top: 80px;">
					<div class="col-lg-12 text-center">
						<p><?php echo $loc->label("ReferencePanelText2");?></p> 
					</div>
				</div>
		
		
		<div class="row margin-top-50">
		
			<div class="col-lg-12 text-center">  
			<div class="panel panel-default">
							<div class="panel-heading"> 
								<h3 class="panel-title"><?php echo $loc->label("Link");?></h3>
							</div>
							<div class="panel-body"> 
								<h2 style="font-weight: bold; margin-top: 30px; margin-bottom: 30px">www.funnyandmoney.com/signup?reference=<?php echo $user->ID; ?></h2>
							</div>
						</div>


				
				</div> 
		
		</div>
		
		<div class="row margin-top-50">
		
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3 text-center">  
		</div>
		
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">  
			
			
				<center><button type="button" class="btn btn-lg btn-block btn-rounded btn-shadow btn-danger" data-toggle="modal" data-target=".bs-modal-sm"><?php echo $loc->label("Leave Reference");?></button></center>  
				
				
				<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title"><?php echo $loc->label("Leave Reference");?>?</h4>
							</div>
							<div class="modal-body">
								<?php echo $loc->label("Leave Reference Alert");?>
							</div>
							<div class="modal-footer">
							<form method="post" action = "Controllers/formPosts.php?action=offReference">
								<button id="closeButton" type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("No");?></button>
								<button type="submit" class="btn btn-danger"><?php echo $loc->label("Yes");?></button>
							</form>
							</div>
						 </div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
			
			</div>
			
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3 text-center">  
		</div>
			
		</div>
		
		</div>
			
		</section> 
		
		<input type="hidden" id="strV" value="0" />
		
		<?php } ?>
		

<?php if($user->referrerON == 1) { ?>		
		
	<script>
	
	
		function getRefEarn(){
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=refEarn",
			success: function cevap(e){
				
				var str = document.getElementById("strV").value;
				
				if(e > str) { 
					
				var t = parseFloat(e + str);
				
				function startCounter(){
				$("#earn").each(function (index) {
	    $(this).prop('Counter',str).animate({  
	        Counter: t
	    }, {
	        duration: 1000,
	        easing: 'swing',
	        step: function (now) {
	            $(this).text(parseFloat(now).toFixed(2));  
	        }
	    });
	});
}	


			startCounter(); }
				
			document.getElementById("strV").value = e; 
				
				 // decimal yazım için düzeltme yap bu kod hata veriyor. zamanlı artışı ve if ile kontrol ekle. if yeni değer ile eski değeri kontrol etsin
		


			}      
			})
		}
		
		
		function getRefCount(){  
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=refCount",
			success: function cevap(e){
				
				function startCounter(){
				$("#refCount").each(function (index) {
	    $(this).prop('Counter',0).animate({
	        Counter: e
	    }, {
	        duration: 1000,
	        easing: 'swing',
	        step: function (now) {
	            $(this).text(parseFloat(now).toFixed());
	        }
	    });
	});
}	

startCounter();
				

			}
			})
		}
	
		
	$(window).load(function () {

		getRefEarn();
		getRefCount();
	});
	
	$(document).ready(function() {
   var refreshId = setInterval(function() {
      getRefEarn();
   }, 5000);  
});
  
	
	</script>
		
<?php } ?>