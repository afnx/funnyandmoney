<?php

if (!isset($_SESSION["userID"]) OR $usr->signupStep != 5) {
	$fn = new functions();
	$fn->redirect("/login");
}

require_once ( dirname ( dirname ( __FILE__ ) ))."/BL/Tables/definitions.php";

?>
<section  style="margin-top: 20px;margin-bottom: 50px;">
			
			<div class="container">
			
				<div class="row sidebar margin-bottom-50">
				
					<div class="col-lg-12 col-md-12 text-center col-sm-12 col-xs-12 margin-auto">
					
						<img src="../images/okey.jpg" alt="ok" / style="margin-bottom: 10px;">

						<h3 style="text-align: center;"><?php echo $loc->label("SignUpStep4Head");?></h3>
				
					</div>
				
				</div>
					
				<div class="row sidebar margin-bottom-50">
				
					<div class="col-md-6 leftside margin-auto">
						
							<?php echo $loc->label("SignUpStep5Text1");?>
							

					</div>
					
					<div class="col-md-6 rightside margin-auto">

							<?php echo $loc->label("SignUpStep5Text2");?>
	
					</div>
						
				</div>
				
				<div class="row sidebar">
				
					<div class="col-lg-6 col-md-6 text-center col-sm-12 col-xs-12 pull-none margin-auto">

						<a href="javascript: submitsignup();"><button type="button" class="btn btn-primary btn-block"><?php echo $loc->label("Here we go!");?></button></a>

					</div>
					
					
				</div>
				
			</div>
			
</section>	
<script>
	
	function submitsignup(){
		$.ajax({
		type: 'POST',
		url: "../Controllers/formPosts.php?action=signupStep",
		data: {step: 5},
		success: function cevap(e){
			   window.location.href = "/";
		   }
		});
		}
</script>