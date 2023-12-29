<?php

if (!isset($_SESSION["userID"]) OR $usr->signupStep != 4) {
	$fn = new functions();
	$fn->redirect("/login");
}

require_once ( dirname ( dirname ( __FILE__ ) ))."/BL/Tables/definitions.php";

?>

			
			<div class="container relative" style="margin-top: 20px;margin-bottom: 50px;">
			
				<div class="row">
				
					<div class="col-lg-5 col-md-6 col-sm-9 col-xs-12 pull-none margin-auto">
					
						<div class="panel panel-default panel-login" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,0);">
						
							<div class="panel-heading">
							
								<h3 style="text-align: center;"><?php echo $loc->label("Add Your Social Media Accounts");?></h3><br />
								
								<p style="text-align: center;"><?php echo $loc->label("SignUpStep4Text");?></p> 

							</div>
							<div class="col-lg-12 col-md-12 text-center col-sm-12 col-xs-12 pull-none margin-auto">				
									
									<div class="widget" style="margin-top: 15px;">
												<div class="panel panel-default">
													<div class="panel-body" style="padding: 5px;">
													
													
													<div class="form-group" style="margin-top: 15px; text-align: center;">
													
														<i style="font-size: 50px; color: #52baff;" class="fa fa-exclamation-circle"></i>
													
													</div>
													
													
													<div class="form-group" style="text-align: center;">
													
														<?php echo $loc->label("We do not use your password or personal information");?>
													
													</div>
													
													
													</div>
													
												</div>
												
											</div> 
									
										
										<div class="form-group">
										
											<a href="../Controllers/facebook.php" target="_blank"><button type="button" class="btn btn-primary btn-block" style="width: 70%; margin: 0 auto;"><?php echo $loc->label("Add a Facebook account");?></button></a><br />
											<a href="../Controllers/twitter.php" target="_blank"><button type="button" class="btn btn-primary btn-block" style="width: 70%; margin: 0 auto;"><?php echo $loc->label("Add a Twitter account");?></button></a><br />
											<a href="../Controllers/youtube.php" target="_blank"><button type="button" class="btn btn-primary btn-block" style="width: 70%; margin: 0 auto;"><?php echo $loc->label("Add a Youtube account");?></button></a><br />
											
										</div>
									
									

														
										<a href="javascript: submitsignup();"><button type="button" class="btn btn-primary btn-block"><?php echo $loc->label("Next");?><span>&rarr;</span></button></a>
									

								
								
								
							</div>
							
							<div class="col-lg-10 margin-top-15">
							
								<a id="skip" href="javascript: submitsignup();"><?php echo $loc->label("Skip");?></a>
								
							</div>
						

						</div>
						
					</div>
					
				</div>
				
			</div>
			
		
<script>
	
	function submitsignup(){
		$.ajax({
		type: 'POST',
		url: "../Controllers/formPosts.php?action=signupStep",
		data: {step: 4},
		success: function cevap(e){
			   window.location.href = "/signupstep5";
		   }
		});
		}
</script>