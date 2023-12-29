<?php
if (!isset($_SESSION["userID"]) OR $usr->signupStep != 0) {
	$fn = new functions();
	$fn->redirect("/login");
}
if (!isset($_GET["code"])) {
	$_GET["code"]="";
} 
 


$userid = $_SESSION['userID'];    
$user = new users($userid);

?>
	
<style>
#loading {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 100;
    width: 100vw;
    height: 70vh;
    background-image: url("../images/loader.gif");
    background-repeat: no-repeat;
    background-position: center;
}
</style>
	

		
			
			<div class="container relative" style="margin-top: 20px;">
			
				<div class="row">
				
					<div class="col-lg-5 col-md-6 col-sm-9 col-xs-12 pull-none margin-auto">
					
						<div class="panel panel-default panel-login" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,0);">
						
							<div class="panel-heading">
							
								<h3 style="text-align: center;"><?php echo $loc->label("SignUpStep0Head");?></h3>  

							</div>
							
							<div id="panel">
							
							<div class="form-actions" style="margin-top: 15px;">
							
								<div id="alert" class="alert alert-danger" role="alert" style="display:none;"></div>
								
								<div id="alert-info" class="alert alert-info alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>
								
								</div>
				
							<div class="col-md-12 text-center col-sm-8 col-xs-12 pull-none margin-auto">
			
								
								
									<div class="panel-body">
								
										<h4 style="text-align: center;"><?php if (empty($_GET["code"])) { echo $loc->label("Please check your inbox and type the activation code below."); }else{echo $loc->label("Please click next and continue.");} ?></h4>
										
									</div>
								
									<div class="panel-body">
									
										<div class="form-group">
										
											<p><?php echo $loc->label("Your e-mail address for activation");?>: <?php echo $user->email; ?></p>  
										
										</div>
									
										<div class="form-group">
																						
											<input type="text" class="form-control" name="code" id="code" placeholder="<?php echo $loc->label("Email Activation Code");?>" value="<?php echo $_GET["code"]; ?>">
											
										</div>
									
										<div class="form-group" style="margin-top: 30px;">
										
											<div class="widget">
												<div class="panel panel-default">
													<div class="panel-body" style="padding: 5px;">
								
														<div id="sendAgainShow" class="form-group" style="margin-top: 15px;">
										
															<a href="javascript: sendAgainShow();"><?php echo $loc->label("If you did not get a mail, click here.");?></a>
											
														</div>
											
														<div id="sendAgainLink" style="display: none; margin-top: 15px;">
										
															<div class="form-group">
										
																<div id="captchA" class="g-recaptcha" data-sitekey="6Lc-hSYTAAAAACTm2kr9hB-uEpkSelR0Sc9Yy-4q" data-size="normal"  style="margin-left: 25px;"></div>
										
															</div>
										
															<div class="form-group">
										
																<a href="javascript: sendAgainCode();"><?php echo $loc->label("Send again");?></a>
													
															</div>
										
														</div>
								
								
													</div>
												</div>
											</div>
										
										</div>
										
										
					
									</div>	
									
									

														
										<a href="javascript: submitsignup();"><button type="button" class="btn btn-primary btn-block"><?php echo $loc->label("Next");?><span>&rarr;</span></button></a>
									
										
								
								
								
							</div>
							</div>

						</div>
						
					</div>
					
				</div>
				
			</div>
			

	
	<div id="loading"></div>
	
	<script src='https://www.google.com/recaptcha/api.js'></script>
	
<script>

$(document).ready(function(){
if ($(window).width() < 960) {
   document.getElementById('captchA').dataset.size = "compact";
}
else {
   document.getElementById('captchA').dataset.size = "normal";
}
    });


	$(document).keypress(function(e) {
		if(e.which == 13) {
			if($("#code").val() != ""){
				submitsignup();
			}
		}
	});
	
	function sendAgainShow() {
			
		$("#sendAgainShow").hide();
		$("#sendAgainLink").show();
	
	}
	
	function submitsignup(){
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=signupStep",
			data: {step: 0, code: $("#code").val()},
			success: function cevap(e){
				if(!(e.indexOf("activitionXXxzzxoksssX") > -1)){  
					
					$("#alert-info").hide();
					$("#alert").html(e);
					$("#alert").show();
					
					shakeForm(); 
					
			   }else{
				   window.location.href = "/signupstep1";
			   }
			}
			})
		}
	
	function sendAgainCode(){
		
		$('#panel').hide();
		$('#loading').show();
		
		$("#alert").hide();
		$("#alert-info").show();
		
		shakeForm();
		
		var vcap = grecaptcha.getResponse();
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=sendConfirmCode",
			data: {userID: <?php echo $userid;?>, getCaptcha: vcap},
			success: function cevap(e){
				
				grecaptcha.reset();
				$("#sendAgainLink").hide();
				$("#sendAgainShow").show();

				$("#alert-text").html(e);
				
				$('#loading').hide(); 
				$('#panel').show();
				
				$("#alert-info").show()
			
				shakeFormInfo();
		
			}
			})
		}
	
	function closeAlert(){
						$("#alert-info").hide();
					}
	
	
		function shakeForm() {
   var l = 20;  
   for( var i = 0; i < 6; i++ )   
     $( "#alert" ).animate( { 
         'margin-left': "+=" + ( l = -l ) + 'px',
         'margin-right': "-=" + l + 'px'
      }, 80);  

     }
		
				function shakeFormInfo() {
   var l = 20;  
   for( var i = 0; i < 6; i++ )   
     $( "#alert-info" ).animate( { 
         'margin-left': "+=" + ( l = -l ) + 'px',
         'margin-right': "-=" + l + 'px'
      }, 80);  

     }
		
</script>
	
<?php

if(!empty($_GET["code"])) {
   echo "<script>";
   echo "submitsignup();";
   echo "</script>";
} 

?>