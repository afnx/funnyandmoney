<?php
$tr_selected='';
$eng_selected='';
if($user->language == 'tr'){
	$tr_selected= 'selected ';
}elseif($user->language == 'en'){
	$eng_selected= 'selected ';
}

if($user->pendingEmail != "" || !is_null($user->pendingEmai)) {

if (!isset($_GET["code"])) {
	$_GET["code"]="";
} 

}
?>				
					

	

						<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">

											<h3><?php echo $loc->label("Account");?> </h3>

											<p style="margin-bottom: 5px;"><?php echo $loc->label("Edit your account information");?><p>

										</div>

										
								<div id="alert" class="alert alert-info alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>

					

										<div class="panel-body" id="panel-body">
											<input type="hidden" name="tab" value="account" />

											<div class="row">
            
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

												<div class="form-group">

													<h4><?php echo $loc->label("E-mail");?></h4>

												</div>

												<div class="form-group">

													<input type="email" name="email" id="email" placeholder="<?php echo $loc->label("New E-mail Address");?>" class="form-control">

													<p><?php echo $loc->label("Your current e-mail address:");?> <a style="color: #4FB5FD;"><?php echo $user->email;?></a> <p>

												</div>
												
										</div>
										
										</div>
										
										
										<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												
												<?php if($user->pendingEmail != "" || !is_null($user->pendingEmai)) { ?>
												
												
												<div class="widget">
							<div class="panel panel-default">
								<div class="panel-heading"><i style="font-size: 15px; color: #e74c3c; margin-right: 5px;" class="glyphicon glyphicon-alert"></i><b><?php echo $loc->label("Verify your new e-mail address");?></b></div>
								<div class="panel-body">
								
								
								<div id="pendingEmailVerifyPanel" class="form-group">

													<input type="emailcode" name="emailcode" id="emailcode" placeholder="<?php echo $loc->label("Enter e-mail code");?>" class="form-control" value="<?php echo $_GET["code"]; ?>" />
													
													<p><?php echo $loc->label("Pending e-mail address:");?> <a style="color: #e74c3c;"><?php echo $user->pendingEmail;?></a><br/>

										<a href="javascript: sendAgainShow();" class="btn btn-primary btn-xs"><?php echo $loc->label("Send again email code");?></a> &nbsp; &nbsp;<a href="javascript: cancelPendingEmail();" class="btn btn-primary btn-xs"><?php echo $loc->label("Cancel change");?></a></p>
													
													<a href="javascript: pendingEmail();"><button id="submitEmailCode" type="button" class="btn btn-success btn-rounded"><?php echo $loc->label("Verify");?></button></a>

												</div>
												
												
												<div  id="sendAgainLink" class="form-group" style="display: none">
												
												<div>
										
															<div class="form-group">
										
																<div id="captchA" class="g-recaptcha" data-sitekey="6LeliiYTAAAAABHsI4D6NbMYTSqlUwsllpIz98be" data-size="normal"></div>
										
															</div>
										
															<div class="form-group">
										
																<a href="javascript: sendCodePendingEmail();"><?php echo $loc->label("Send again");?></a>
													
															</div>
										
														</div>
												
												
												</div>
												
												
								</div>
							</div>
						</div>
												
									
												
												
												
												<?php } ?>
												
												</div>
												</div>
							<?php 
							//Country Control
							$userCCont = new users($_SESSION["userID"]); 
							?>
							<?php if($userCCont->country == 306) {?>
												<div class="row margin-top-30">
												<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

												<div class="form-group">

													<h4><?php echo $loc->label("Language");?></h4>

												</div>

												<div class="form-group">

													<select name="language" class="chosen-select-no-single">
													
										<option <?php echo $eng_selected; ?>id="en" value="en"> <?php echo $loc->label("English");?></option>

										<option <?php echo $tr_selected; ?>id="tr" value="tr"> <?php echo $loc->label("Turkish");?></option>

									</select>

												</div>
												</div>
												
										</div>
							<?php } ?>

											
										</div>
										
										<div class="panel-footer" id="panel-footer" style="padding-top: 15px;">
											<center><div class="form-group">
												
												<button id="submit" type="submit" class="btn btn-primary btn-rounded"><?php echo $loc->label("Save");?></button>
												
											</div></center>
											
											</div>
						</div>
						
	<script src='https://www.google.com/recaptcha/api.js'></script>

<script>

<?php if($user->pendingEmail != "" || !is_null($user->pendingEmai)) { ?>
$(document).ready(function(){
if ($(window).width() < 960) {
   document.getElementById('captchA').dataset.size = "compact";
}
else {
   document.getElementById('captchA').dataset.size = "normal";
}
    });  
	
<?php } ?>

	function sendAgainShow() {
			
		$("#pendingEmailVerifyPanel").hide();
		$("#sendAgainLink").show();
	
	}

				function pendingEmail(){
					
					$('#settings-panel').hide();
					$('#loading').show();
					
						$.ajax({
						type: 'POST',
						url: "../Controllers/formPosts.php?action=changeEmail",
						data: {emailcode: $("#emailcode").val()},
						success: function cevap(e){
							
						$('#loading').hide();
						$('#settings-panel').show();
							
						$("#alert-text").html(e);
						$('html, body').animate({ scrollTop: 0 }, 'fast');
						$("#alert").show();  
						shakeForm();
						}
						})
					}
				
					function cancelPendingEmail(){
					
					$('#settings-panel').hide();
					$('#loading').show();
					
						$.ajax({
						type: 'POST',
						url: "../Controllers/formPosts.php?action=cancelPendingEmail",
						success: function cevap(e){
							
						$('#loading').hide();
						$('#settings-panel').show();
							
						$("#alert-text").html(e);
						$('html, body').animate({ scrollTop: 0 }, 'fast');
						$("#alert").show();
						shakeForm();
						
						setInterval(function(){location.reload();},1000);
						}
						})
					}
					
					function sendCodePendingEmail(){
					
					$('#settings-panel').hide();
					$('#loading').show();
					
					var vcap = grecaptcha.getResponse();
					
						$.ajax({
						type: 'POST',
						url: "../Controllers/formPosts.php?action=sendCodePendingEmail",
						data: {getCaptcha: vcap},
						success: function cevap(e){
						
						grecaptcha.reset();
						$("#sendAgainLink").hide();
						$("#pendingEmailVerifyPanel").show();
							
						$('#loading').hide();
						$('#settings-panel').show();
							
						$("#alert-text").html(e);
						$('html, body').animate({ scrollTop: 0 }, 'fast');
						$("#alert").show();
						shakeForm();
						}
						})
					}
				
	/*$(document).keypress(function(e) {
		if(e.which == 13) {
			if($("#emailcode").val() != ""){
				pendingEmail();
			}
		}
	});
	
	$(document).keypress(function(e) {
		if(e.which == 13) {

				cancelPendingEmail();
	
		}
	});
	
	$(document).keypress(function(e) {
		if(e.which == 13) {

				sendCodePendingEmail();
		
		}
	});*/
				
</script>
	
<?php

if($user->pendingEmail != "" || !is_null($user->pendingEmai)) {

if($_GET["code"] != "") {  
   echo "<script>";  
   echo "pendingEmail();";
   echo "</script>";
}

}

?>
