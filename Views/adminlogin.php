<?php  

if (!isset($_SESSION['userID'])) {
	
	$fn = new functions();
	$fn->redirect("404");
	
} else if(isset($_SESSION['adminID'])) { 
	
	$fn = new functions();
	$fn->redirect("admin");
	
} else if(isset($_SESSION['userID'])) {
	
	$adminC = admins::checkAdmin ( $_SESSION['userID'] );
	if ($adminC->ID > 0) {
		
	} else {
		
		$fn = new functions();
		$fn->redirect("404");
		
	}
	
}

$loc = new localization ($_SESSION['language']); 

?>
<div class="container relative" style="margin-top: 20px;">
	<div class="row">
		<div
			class="col-lg-5 col-sm-6 col-xs-12 col-md-4 col-md-offset-4 pull-none margin-auto"> 

			<div class="panel panel-default panel-login" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,0);">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="fa fa-user"></i> Yönetici Girişi</h3>
				</div>
				<div class="panel-body">
					
					<form method='post'autocomplete='off'> 
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
							maxlength="50" required />
					</div>
					<div class="form-group input-icon-left">
						<i class="fa fa-lock"></i> <input type="password"
							class="form-control" name="password" id="password"
							placeholder="<?php echo $loc->label("Password");?>"
							maxlength="20" required />
					</div>
					<div class="row">
					<div class="col-lg-5 col-sm-8 col-xs-10 col-md-8">
					
					<div class="form-group input-icon-left">
						<i class="fa fa-lock"></i> <input type="password"
							class="form-control" name="pin" id="pin"
							placeholder="Pin"
							maxlength="4" required />
					</div>
					
					</div>
					</div>
					
					<a class="btn btn-primary btn-block" style="color: #FFFFFF;"
						href="javascript:login();"><?php echo $loc->label("Log in");?></a>
					</form>
					
				</div>
				

			
			</div>
		</div>
	</div>
</div>




<script>

			
				$(document).keypress(function(e) {
					if(e.which == 13) {
						if($("#usernameOrEmail").val() != "" && $("#password").val() != "" && $("#pin").val() != ""){
							login();
						}
					}
				});
				
				
				function login(){
		
					var usernameOrEmail = $("#usernameOrEmail").val();
					var password=$("#password").val();
					var pin=$("#pin").val();
					if (usernameOrEmail!='' && password!='' && pin!='') {
		
						$.ajax({
						type: 'POST',
						url: "Controllers/formPosts.php?action=adminlogin",
						data: {usernameOrEmail:usernameOrEmail,password:password, pin: pin},
						success: function cevap(e){
							if (!(e.indexOf("ok") > -1)) {

									$(".alert").html("Giriş işlemi geçersiz.");
									$(".alert").show();
									shakeForm();

							} else if (e.indexOf("ok") > -1) {
								
								document.location.href="/admin";
								
							}
								
						}
						})
							
					} else {
						
						$(".alert").html("Tüm alanları doldurun.");
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
				
