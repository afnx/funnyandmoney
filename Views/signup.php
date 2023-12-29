<?php

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";

if (isset($_SESSION["userID"])) {
	
	$fn = new functions();
	$fn->redirect("../signed");
	
}


if (isset($_GET["reference"])) {
	
	$refID = str_replace("/","",$_GET["reference"]);
	
	$userR = new users($refID);
	
if($userR->referrerON == 1) {   
	
	$_SESSION["referrerID"] = $refID;  
	
}

}

$loc = new localization ($_SESSION['language']); 

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
		<div
			class="col-lg-5 col-sm-6 col-xs-12 col-md-4 col-md-offset-4 pull-none margin-auto"> 
			<div class="panel panel-default panel-login" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,0);">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="fa fa-users"></i> <?php echo $loc->label("Sign Up For F&M");?></h3>
				</div>
				
			
				
				<div id="panel-body" class="panel-body">
				<form method='post'autocomplete='off'> 
					<input type="hidden" name="tableName" value="users" />
					
					<div class="form-actions" style="margin-top: 0px;">
								<div id="alert" class="alert alert-danger" role="alert" style="display:none; min-height: 53px;"></div>
							</div>

						<div id="fullNameInput" class="form-group input-icon-left">
							<i class="fa fa-user"></i> <input type="text"
								class="form-control" name="fullName" id="fullName" data-width="150px"
										title="<?php echo $loc->label("Your name or your nickname");?>" 
								placeholder="<?php echo $loc->label("Full Name");?>" required>
						</div>
						<div id="emailInput" class="form-group input-icon-left">
							<i class="fa fa-envelope"></i> <input type="email"
								class="form-control" name="email" id="email"
								placeholder="<?php echo $loc->label("E-mail");?>" required>
						</div>
						<div id="passwordInput" class="form-group input-icon-left">
							<i class="fa fa-lock"></i> <input data-width="150px"
										title="<?php echo $loc->label("Your passowrd must contain one number, one letter and one large letter at least");?>" type="password"
								class="form-control" name="password" id="password"
								placeholder="<?php echo $loc->label("Password");?>" required>
						</div>
						<div id="retypeInput" class="form-group input-icon-left">
							<i class="fa fa-check"></i> <input type="password"
								class="form-control" id="retype" name="retype"
								placeholder="<?php echo $loc->label("Repeat Password");?>" required>
						</div>

						<div class="controls form-group">
							<div class="row">

								<div class="col-lg-10">
									<div id="captchA" class="g-recaptcha" data-sitekey="6LfQdyYTAAAAAIwOkWLENellIEaVNJoMQQa1iGww" data-size="normal"></div>
								</div> 
							</div>
						</div>  
				
						<a href="javascript: submitsignup();"><button type="button"
								class="btn btn-primary btn-block"><?php echo $loc->label("Register");?></button></a>

				</form>
						<div class="form-actions">
							<div class="col-lg-10">
								<div class="row">
									<label><?php echo $loc->label("Accept Terms");?></label>
								</div>
							</div>
						</div>
				
				</div>
				<div id="panel-footer" class="panel-footer">
								<?php echo $loc->label("Already have an account?");?> <a
						href="login"><?php echo $loc->label("Log in Now");?></a>
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
   
    var fullName = document.getElementById('fullName');
	var att = document.createAttribute("data-toggle");
	att.value = "tooltip";
    fullName.setAttributeNode(att);
		
	var password = document.getElementById('password');
    var att1 = document.createAttribute("data-toggle");
	att1.value = "tooltip";
    password.setAttributeNode(att1);


  
} else { 
	
	document.getElementById('captchA').dataset.size = "normal";
	
	var fullName2 = document.getElementById('fullName');
	var attz = document.createAttribute("data-toggle");
	attz.value = "tooltip";
    fullName2.setAttributeNode(attz);
	
    var attp = document.createAttribute("data-placement");
    attp.value = "right";
    fullName2.setAttributeNode(attp);
	
	var password2 = document.getElementById('password');
    var attz1 = document.createAttribute("data-toggle");
	attz1.value = "tooltip";
    password2.setAttributeNode(attz1);

	var att1p = document.createAttribute("data-placement");
    att1p.value = "right";
    password2.setAttributeNode(att1p);
	
   
}
    });
	
	$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

$(document).keypress(function(e) {
		if(e.which == 13) {
				submitsignup();
		}
	});
	
	function submitsignup(){
		
		$('#panel-body').hide();
		$('#panel-footer').hide();
		$('#loading').show();
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=saveForm",
			data: $('#panel-body :input').serialize(),
			success: function cevap(e){
				if(e.match(/[a-z]/i)){

					grecaptcha.reset();
					$('#loading').hide();
					$('#panel-body').show();
					$('#panel-footer').show();
				
					$("#alert").html(e);
					$("#alert").show();
					
					shakeForm();
					

			   } else{
				    
				   window.location.href = "/";
			   }
			}
			})
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
