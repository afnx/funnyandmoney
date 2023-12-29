<?php

/*if (isset($_SESSION["userID"])) {
	
	$fn = new functions();
	$fn->redirect("../signed");
	
}*/
if (!isset($_GET['email']) OR !isset($_GET['code'])) {
	
	$fn = new functions();
	$fn->redirect("/");
	
}
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";

$loc = new localization ($_SESSION['language']);
?>
		<section class="hero bg-white border-bottom-1 border-grey-200">
			<div class="container">
				<div class="page-header">
					<div class="page-title"><?php echo $loc->label("Reset Password");?></div>
				</div>
			</div>
		</section>
		
									
		
		<div id="formContainer">
		<section class="border-bottom-1 border-grey-400 padding-30">
			<div class="container text-center">
				<h2 class="font-size-22 font-weight-300">
<?php

$email = isset ( $_GET ["email"] ) ? $_GET ["email"] : "";
		$user = users::checkUserWithoutPass ($email);
		if ($user->ID > 0 AND $user->email_code != NULL AND $user->email_code == $_GET["code"]) {
			echo $loc->label("Reset Success");
			?>
			</h2>
			</div>
		</section>
		
		
		<div class="container relative" style="margin-top: 20px;">
			
				<div class="row">
		
		
		<div
			class="col-lg-5 col-sm-6 col-xs-12 col-md-4 col-md-offset-4 pull-none margin-auto"> 
					
						<div class="panel panel-default panel-login" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,0);">
						
			
							<div id="alert" class="alert alert-info alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>
				
		
		<div id="passwordArea" class="panel-body">

				
				<div class="form-group">
				<input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="<?php echo $loc->label("New Password");?>">
				</div>
				<div class="form-group">
				<input type="password" class="form-control" name="newpassword2" id="newpassword2" placeholder="<?php echo $loc->label("Retype New Password");?>">
				</div>
			<div class="form-group">
			<a href="javascript: submitreset();"><button type="button" class="btn btn-primary btn-block"><?php echo $loc->label("Submit");?></button></a>
		</div>
	
		
		</div>
		</div>
		</div>
		
		</div>
		</div>
		
		<script>
				$(document).keypress(function(e) {
					if(e.which == 13) {
						if($("#newpassword").val() != "" && $("#newpassword2").val() != ""){
							submitreset();
						}
					}
				});
				
				function submitreset(){

					
							$.ajax({
							type: 'POST',
							url: "../Controllers/formPosts.php?action=reset",
							data: {newpassword: $("#newpassword").val(),newpassword2: $("#newpassword2").val(),email: "<?php echo $_GET['email']; ?>",code: "<?php echo $_GET['code']; ?>"},
							success: function cevap(e){
								
							if (e.indexOf("XxresetokZzxX") > -1) {
							
								$("#passwordArea").hide();
						
							} 
								
							$("#alert").hide();
							$("#alert-text").html(e.replace("XxresetokZzxX", ""));
							$("#alert").show();
							shakeForm();
							}
							})
						
					}
				
				
					function closeAlert(){
						$("#alert").hide();
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
		}else{
			echo $loc->label("Reset Error");
			?>
			</h2>
			</div>
	
			<?php
		}
?>

