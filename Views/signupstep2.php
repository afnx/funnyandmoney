<?php

ini_set ( 'display_errors', 1 );

ini_set ( 'display_startup_errors', 1 );

error_reporting ( E_ALL );


if (!isset($_SESSION["userID"]) OR $usr->signupStep != 2) {
	$fn = new functions();
	$fn->redirect("/login");
}


require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Consts/consts.php";

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/localization.php";

$loc = new localization ($_SESSION['language']); 


?>


			
			<div class="container relative" style="margin-top: 20px;margin-bottom: 50px;">
			
				<div class="row">
				
					<div class="col-lg-5 col-md-6 col-sm-9 col-xs-12 pull-none margin-auto">
					
						<div class="panel panel-default panel-login" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,0);">
						
							<div class="panel-heading">
							
								<h3 style="text-align: center;"><?php echo $loc->label("SignUpStep2Head");?></h3><br />
								
								<p style="text-align: center;"><?php echo $loc->label("SignUpStep2Text");?></p> 
								

							</div>
							<div class="form-actions" style="margin-top: 15px;">
								<div id="alert" class="alert alert-danger" role="alert" style="display:none;"></div>
							</div>
							<div class="col-md-12 text-center col-sm-12 col-xs-12 pull-none margin-auto">
			
								<form class="form-inline">
								
									<div class="form-group" style="width: 100%; margin-bottom: 25px;">
								
										<h4 style="text-align: center;"><?php echo $loc->label("Choose Your Profile Photo");?></h3>
										
									</div>
								
								
										
										<div class="form-group" style="margin-bottom: 5px;">
										
											
												<div class="thumbnail">
													
														<img id="blah" src="../Library/bootstrap-3.3.6/img/user/profile.jpg">
													
												</div>
												
												</div>

								<div class="form-group" style="width: 100%; margin-bottom: 5px;">
										
										<div id="form1" runat="server" class="form-group" style="margin-top: 20px;">

													 <input type='file' id="imgInp" accept="image/*" />

												</div>
										
									</div>
									
										<div class="form-group" style="width: 100%; margin-bottom: 25px;">
										
											<a id="uploadlink"><button id="uploadButton" type="button" class="btn btn-primary btn-lg"><?php echo $loc->label("Upload Photo");?></button></a>
											
										</div>
									
							
								
								<div class="form-group" style="width: 100%; margin-bottom: 25px;">

										<h4 style="text-align: center;"><?php echo $loc->label("Choose Your Username");?></h3>
										
							</div>
										
										
								
									
										<div class="form-group" style="width: 100%; margin-bottom: 25px;">
																						
											<input id="username" type="text" class="form-control" name="username" placeholder="<?php echo $loc->label("Username");?>">
											
										</div>
					
							
									
									<div class="form-group" style="width: 100%;">
									
										<a href="javascript: submitsignup();"><button id="next" type="button" class="btn btn-primary btn-block"><?php echo $loc->label("Next");?><span>&rarr;</span></button></a>
									
										</div>
								</form>
								
								
							</div>
							
							<div class="col-lg-10">
							
								<a id="skip" href="javascript: submitsignup();"><?php echo $loc->label("Skip");?></a>
								
							</div>
							
							
						

						</div>
						
					</div>
					
				</div>
				
			</div>
			
		
	
<script>

	$("#imgInp").change(function(){
		var input= this;
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#blah').attr('src', e.target.result);
				$("#uploadlink").attr("href", "javascript: upload();");
			}

			reader.readAsDataURL(input.files[0]);
		}
		
	});

$( "#blah" ).click(function() {
  document.getElementById("imgInp").click();
});

function upload(){
	$("#uploadButton").html('<?php echo $loc->label("UploadingPlease Wait");?>');
	$( "#uploadButton" ).prop( "disabled", true );
	$( "#skip" ).hide();
	var formData = new FormData();
	formData.append('picture', 'profile');
	formData.append('tab', 'image');
	formData.append('image', $('input[type=file]')[0].files[0]); 
$( "#next" ).prop( "disabled", true );
$.ajax({
       url : '../../Controllers/formPosts.php?action=settings',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
			   if(!(data.indexOf("ok") > -1)){
				   $("#uploadButton").html('<?php echo $loc->label("Upload Photo");?>');
					$('#blah').attr('src', '../Library/bootstrap-3.3.6/img/user/profile.jpg');
					$('#imgInp').val('');
					$("#alert").html(data);
					$("#alert").show();
					shakeForm();
			   }else{
				   $("#uploadButton").html('<?php echo $loc->label("Uploaded");?>');
			   }$( "#next" ).prop( "disabled", false );
			   $( "#uploadButton" ).prop( "disabled", false );
			   $( "#skip" ).show();
       }
});
}

	
	function submitsignup(){
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=signupStep",
			data: {step: 2, username: $("#username").val()},
			success: function cevap(e){
				if(!(e.indexOf("ok") > -1)){
					$("#alert").html(e);
					$("#alert").show();
					shakeForm();
			   }else{
				   window.location.href = "/signupstep3";
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