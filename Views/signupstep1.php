  <?php
if (!isset($_SESSION["userID"]) OR $usr->signupStep != 1) {
	$fn = new functions();
	$fn->redirect("/");
}
?>

			
			<div class="container relative" style="margin-top: 20px;margin-bottom: 50px;">
			
				<div class="row">
				
					<div class="col-lg-5 col-md-6 col-sm-9 col-xs-12 pull-none margin-auto">
					
						<div class="panel panel-default panel-login" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,0);">
						
							<div class="panel-heading">
							
								<h3 style="text-align: center;"><?php echo $loc->label("SignUpStep1Head");?></h3>  <br />   
								
								<p style="text-align: center;"><?php echo $loc->label("SignUpStep1Text");?></p>

							</div>
							
							<div class="form-actions" style="margin-top: 15px;">
							
								<div id="alert" class="alert alert-danger" role="alert" style="display:none;"></div>
								
							</div>
						
							<div class="col-md-12 text-center col-sm-6 col-xs-12 pull-none margin-auto">
			
								<form class="form-inline">
								
									<div class="panel-body">
								
										<h4 style="text-align: center;"><?php echo $loc->label("Gender");?></h3>
										
									</div>
								
									<div class="panel-body">
										
										<div class="form-group">
										
													<div class="radio radio-inline">
													
														<input type="radio" name="gender" id="inline-radio1" value="female"> 
														
														<label for="inline-radio1"><?php echo $loc->label("Female");?></label>
														
													</div>
													
													<div class="radio radio-inline">
													
														<input type="radio" name="gender" id="inline-radio2" value="male"> 
														
														<label for="inline-radio2"><?php echo $loc->label("Male");?></label>
														
													</div>
								
										</div>
									
									</div>
								
									<div class="panel-body">

										<h4 style="text-align: center;"><?php echo $loc->label("Birthday");?></h3>
										
									</div>
										
										
									<div class="panel-body">
									
									<div class="row">
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										
										<div class="form-group">
														
												
												
												<select id="day" name="day" class="form-control input-md">

													<optgroup>
													
														<option disabled selected><?php echo $loc->label("Day");?></option>

														<?php for($i=1;$i<32;$i++){
															echo '<option value="'. $i .'" >'.$i.'</option>'."\n";
														}?>
														
													</optgroup>

															
												</select>

												
												
										</div>
										
										</div>
											
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										
										<div class="form-group">
													
											<select id="month" name="month" class="form-control input-md">

													<optgroup>
														
														<option disabled selected><?php echo $loc->label("Month");?></option>
													
														<?php for($i=1;$i<13;$i++){
															echo '<option value="'. $i .'" >'.$i.'</option>'."\n";
														}?>
															
													</optgroup>

															
											</select>

													 
										</div>
										
										</div>
										
										
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

										<div class="form-group">
						
											<select id="year" name="year" class="form-control input-md">

													<optgroup>
													
														<option disabled selected><?php echo $loc->label("Year");?></option>
													
														<?php for($i=date("Y")-13;$i>date("Y")-80;$i--){
															echo '<option value="'. $i .'" >'.$i.'</option>'."\n";
														}?>
												
											</select>	
											
										</div>
										
										</div>
										
									</div>
										
									</div>	
									
									<a href="javascript: submitsignup();"><button type="button" class="btn btn-primary btn-block"><?php echo $loc->label("Next");?><span>&rarr;</span></button></a>
									

								</form>
								
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
			data: {step: 1, gender: $("input[name=gender]:radio:checked").val(), day: $("#day").val(), month: $("#month").val(), year: $("#year").val()},
			success: function cevap(e){
				if(!(e.indexOf("ok") > -1)){
					$("#alert").html(e);
					$("#alert").show();
					shakeForm();
			   }else{
				   window.location.href = "/signupstep2";
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