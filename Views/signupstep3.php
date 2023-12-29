<?php

if (!isset($_SESSION["userID"]) OR $usr->signupStep != 3) {
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
							
								<h3 style="text-align: center;"><?php echo $loc->label("SignUpStep3Head");?></h3><br />
								
								<p style="text-align: center;"><?php echo $loc->label("SignUpStep3Text");?></p>

							</div>
							<div class="form-actions" style="margin-top: 15px;">
								<div id="alert" class="alert alert-danger" role="alert" style="display:none;"></div>
							</div>
							<div class="col-md-12 text-center col-sm-12 col-xs-12 pull-none margin-auto">
			
								
								
									<div class="panel-body">
								
										<h4 style="text-align: center;"><?php echo $loc->label("Choose Your Interests");?></h4>
										
									</div>
								
											<div class="form-group" style="float: left; margin-bottom: 15px; width: 100%;">
											
												<a id="select_all" style="cursor: pointer;"><?php echo $loc->label("Select All");?></a>&nbsp;|&nbsp;<a id="deselect_all" style="cursor: pointer;"><?php echo $loc->label("Deselect All");?></a>
											
											</div>
									
										
										<div class="form-group">
										
										<div class="row">
										
<?
$runsql = new \data\DALProsess ();
$result = $runsql->executenonquery ("select ID,definition from definitions where definitionID=12 and isDeleted<>1", "categoryID", 0);
while ( list ( $ID, $item ) = mysqli_fetch_array ( $result ) ) {
echo'
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	<div class="checkbox checkbox-control checkbox-inline checkbox-success" style="margin-left: 20px;">
	
		<input type="checkbox" id="color-checkbox'.$ID.'" name="interests[]" value="'.$ID.'"> 
		<label for="color-checkbox'.$ID.'">'.evalLoc($item).'</label>
		
	</div>
	</div>
	';
}
?>
	
	</div>
											
										</div>
									
									

														
										<a href="javascript: submitsignup();"><button type="button" class="btn btn-primary btn-block"><?php echo $loc->label("Next");?><span>&rarr;</span></button></a>
									

								
								
								
							</div>
						

						</div>
						
					</div>
					
				</div>


</div>
<script>

	
	$('#select_all').click(function(event) {
      $(':checkbox').each(function() {
          this.checked = true;
      });
});

$('#deselect_all').click(function(event) {
      $(':checkbox').each(function() {
          this.checked = false;
      });
});
	
	function submitsignup(){
		var myCheckboxes = new Array();
		var ctr=0; 
		$("input:checked").each(function() {
			myCheckboxes.push($(this).val());
			ctr++;
		});
			if(ctr < 2){
				$("#alert").html('<?php echo $loc->label("Please choose at least 2 of them.");?>');
				$("#alert").show();
				shakeForm();
			}else{
				$.ajax({
				type: 'POST',
				url: "../Controllers/formPosts.php?action=signupStep",
				data: {step: 3, interests : myCheckboxes},
				success: function cevap(e){
					   window.location.href = "/signupstep4";
				   }
				});
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