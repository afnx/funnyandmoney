<?php

/*if (isset($_SESSION["userID"])) {
	
	$fn = new functions();
	$fn->redirect("../signed");
	
}*/

$loc = new localization ($_SESSION['language']);

if (isset($_SESSION['userID'])) {

	$user = new users($_SESSION['userID']);
	
}

?>

<style>
#loader {
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
				
					<div
			class="col-lg-5 col-sm-6 col-xs-12 col-md-4 col-md-offset-4 pull-none margin-auto"> 
					
						<div class="panel panel-default panel-login" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,0);">
						
							<div class="panel-heading">
							
								<h3 style="text-align: center;"><?php if (isset($_SESSION['userID'])) { echo $loc->label("ForgotHeadLoggedin"); } else { echo $loc->label("ForgotHead"); }?></h3>

							</div>
							
							<div class="form-actions" style="margin-top: 15px;">
							
							<div id="alert" class="alert alert-info alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>
								
							</div>
							
							<div id="formContainer" class="col-md-12 text-center col-sm-12 col-xs-12 pull-none margin-auto">
			
							<div id="sendAgainLink" class="panel-body">
								
									<div class="form-group">
								
									<p style="text-align: center;"><?php echo $loc->label("ForgotText");?></p>
										
									</div>
								
									
										
										<div class="form-group">
										
											<input type="text" class="form-control" name="usernameoremail" id="usernameOrEmail" <?php if (isset($_SESSION['userID'])) { ?> value="<?php echo $user->email;?>" <?php } ?> placeholder="<?php if (isset($_SESSION['userID'])) { echo $user->email; } else { echo $loc->label("Username or E-mail"); } ?>" <?php if (isset($_SESSION['userID'])) { ?> disabled <?php } ?>>
										
										</div>
									
									<div style="margin-top: 30px;">
									
										<div class="form-group" style="margin-bottom: 25px;">
										
											<div id="captchA" class="g-recaptcha" data-sitekey="6Lfj9iYTAAAAAJTjy32VLbdUdTQvfQambacqzcc5" data-size="normal"  style="margin-left: 25px;"></div>
										
										</div>
						
										<a id="submitButton" href="javascript: submitforgot();"><button type="button" class="btn btn-primary btn-block"><?php echo $loc->label("Submit");?></button></a>
								
										
									</div>
									
									</div>

								
								
							</div>
							
						

						</div>
						
					</div>
					
				</div>
				
			</div>
			
		<div id="loader"></div>
			
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
						if($("#usernameOrEmail").val() != ""){
							submitforgot();
						}
					}
				});
				
				function submitforgot(){
					
					closeAlert();
					$("#sendAgainLink").hide();
					$('#loader').show();
					
					var vcap = grecaptcha.getResponse();
					
						$.ajax({
						type: 'POST',
						url: "../Controllers/formPosts.php?action=forgot",
						data: {usernameOrEmail: $("#usernameOrEmail").val(), getCaptcha: vcap},
						success: function cevap(e){

						$('#loader').hide();
						
						if (!(e.indexOf("XxforgotokxX") > -1)) {
							
							grecaptcha.reset();
							$("#sendAgainLink").show();
						
						} 

						$("#alert-text").html(e.replace("XxforgotokxX", ""));
						$("#alert").show();
						shakeFormInfo();

						
						}
						})
					}
				
				
					function closeAlert(){
						$("#alert").hide();
					}
					
					function shakeFormInfo() {
   var l = 20;  
   for( var i = 0; i < 6; i++ )   
     $( "#alert" ).animate( { 
         'margin-left': "+=" + ( l = -l ) + 'px',
         'margin-right': "-=" + l + 'px'
      }, 80);  

     }
					
			</script>
			
			
	