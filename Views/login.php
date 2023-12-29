<?php

if (isset($_SESSION["userID"])) {
	
	$fn = new functions();
	$fn->redirect("../signed");
	
}

$loc = new localization ($_SESSION['language']); 

if (!isset($_GET["pre"])) {
	$_GET["pre"]="";
}

if(!isset($_COOKIE['username'])){
	$username="";
} else {
	$username = $_COOKIE['username'];
}

?>
<div class="container relative" style="margin-top: 20px;">
	<div class="row">
		<div
			class="col-lg-5 col-sm-6 col-xs-12 col-md-4 col-md-offset-4 pull-none margin-auto"> 

			<div class="panel panel-default panel-login" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,0);">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="fa fa-user"></i> <?php echo $loc->label("Log in to F&M");?></h3>
				</div>
				<div class="panel-body">
					<?php
					/*
					<a href="/Controllers/facebook.php" class="btn btn-block btn-social btn-facebook"><i
						class="fa fa-facebook"></i> <?php echo $loc->label("Connect with Facebook");?></a>
					<a class="btn btn-block btn-social btn-twitter"><i
						class="fa fa-twitter"></i> <?php echo $loc->label("Connect with Twitter");?></a>
					<div class="separator">
						<span><?php echo $loc->label("or");?></span>
					</div>
					*/ ?>
					
					<form method='post'autocomplete='on'> 
					<input type="hidden" name="tableName" id="tableName" value="users">
					<input type="hidden" name="ID" id="ID" value="0">
					<div class="form-actions" style="margin-top: 0px;">
						<div id="alert" class="alert alert-danger" role="alert"
							style="display: none; min-height: 53px;"></div>
					</div>
					<div class="form-group input-icon-left">
						<i class="fa fa-user"></i> <input type="text" class="form-control"
							name="usernameOrEmail" id="usernameOrEmail"
							placeholder="<?php echo $loc->label("Username or E-mail");?>"
							value="<?php echo $username; ?>"
							maxlength="50" required />
					</div>
					<div class="form-group input-icon-left">
						<i class="fa fa-lock"></i> <input type="password"
							class="form-control" name="password" id="password"
							placeholder="<?php echo $loc->label("Password");?>"
							maxlength="20" required />
					</div>
					
					<div id="showReCaptcha" class="controls form-group" style="display: none;">
							<div class="row">

								<div class="col-lg-10">
									<div id="captchA" class="g-recaptcha" data-sitekey="6LfSiyYTAAAAAEu6O_8IBiNaG0QT3XKdo2lQ69tC" data-size="normal"></div>
								</div> 
							</div>
					</div>  
					
					
					<a class="btn btn-primary btn-block" style="color: #FFFFFF;"
						href="javascript:login();"><?php echo $loc->label("Log in");?></a>
					</form>
					
					
					<div class="form-actions">

					 <a href="forgot"><?php echo $loc->label("Forgot Password?");?></a>

					</div>
				</div>
				
				<div class="panel-footer">

								<?php echo $loc->label("DontHave");?> <a href="signup"><?php echo $loc->label("Sign Up Now");?>!</a>
				</div>
			
			</div>
		</div>
	</div>
</div>


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
						if($("#usernameOrEmail").val() != "" && $("#password").val() != ""){
							login();
						}
					}
				});
				
				
				function login(){
		
					var usernameOrEmail = $("#usernameOrEmail").val();
					var password=$("#password").val();
					var vcap = grecaptcha.getResponse();
					if (usernameOrEmail!='' && password!='') {
		
						$.ajax({
						type: 'POST',
						url: "Controllers/formPosts.php?action=login",
						data: {usernameOrEmail:usernameOrEmail,password:password,getCaptcha: vcap},
						success: function cevap(e){
							if (!(e.indexOf("ok") > -1)) {
								
								if (e.indexOf("loginAttemptCaptchaError") > -1) {
									
									grecaptcha.reset();
									$("#showReCaptcha").show();
									
									$(".alert").html(e.replace("loginAttemptCaptchaError", ""));
									$(".alert").show();
									shakeForm();
								 
								} else {
									
									$(".alert").html(e);
									$(".alert").show();
									shakeForm();
									
								}

							} else if (e.indexOf("ok") > -1) {
								
								document.location.href="/<?php echo $_GET["pre"]; ?>";
								
							}
								
						}
						})
							
					} else {
						
						$(".alert").html("<?php echo $loc->label("Enter your username and password.");?>");
						$(".alert").show();
						shakeForm();
						
					}
				}
					

				
					function shakeForm() {
   var l = 20;  
   for( var i = 0; i < 6; i++ )   
     $( "#alert" ).animate( { 
         'margin-left': "+=" + ( l = -l ) + 'px',
         'margin-right': "-=" + l + 'px'
      }, 80);  

     }

</script>
				
	<?php 
	if (!empty($_GET["pre"])) {
		echo "<script>";
		echo "$('.alert').html('";
		echo $loc->label("Please log in.");
		echo"');
		$('.alert').show();
		shakeForm();";
		echo "</script>";
	}
	?>
