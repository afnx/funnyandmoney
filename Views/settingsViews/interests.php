<?php

$userC = new users($_SESSION["userID"]);
$categories = $userC->categoryID;

$cArray = array();

$categories= explode(',',$categories);
foreach ( $categories as $category ) {

	array_push($cArray, $category);

}

?>
					



						<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">

											<h3><?php echo $loc->label("Interests");?> </h3>

											<p style="margin-bottom: 5px;"><?php echo $loc->label("Select your interests");?><p>

										</div>


										<div id="alert" class="alert alert-info alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>
										
										
										<div class="panel-body" id="panel-body">
										<input type="hidden" name="tab" value="interest" />
									
											<div class="form-group" style="float: left; margin-bottom: 15px; margin-left: 30px; width: 100%;">
											
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
	
		<input type="checkbox" id="color-checkbox'.$ID.'" name="interests[]" value="'.$ID.'"';
		 
			
	if (in_array($ID, $cArray)) { echo "checked"; } 
	
	echo'> 
		<label for="color-checkbox'.$ID.'">'.evalLoc($item).'</label>
		
	</div>
	</div>
	';
}
?>
	</div>
											
										</div>

								
										</div>
										
										<div class="panel-footer" id="panel-footer" style="padding-top: 15px;">
										
											<center><div class="form-group">
												
												<a href="javascript: submitInterest();"><button id="submitInterestButton" type="submit" class="btn btn-primary btn-rounded"><?php echo $loc->label("Save");?></button></a>
												
											</div></center>
											
										</div>
						</div>

<script>
	$(document).keypress(function(e) {
		if(e.which == 13) {
			submitInterest();
		}
	});
	
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
	
	function submitInterest(){
		
		$('#settings-panel').hide();
		$('#loading').show();
		
		var interest = "interest";
		var myCheckboxes = new Array();
		var ctr=0; 
		$("input:checked").each(function() {
			myCheckboxes.push($(this).val());
			ctr++;
		});
			if(ctr < 2){
				
				$('#loading').hide();
				$('#settings-panel').show();
				
				$("#alert-text").html('<?php echo $loc->label("Please choose at least 2 of them.");?>');
				$("#alert").show();
				shakeForm();
			}else{
				$.ajax({
				type: 'POST',
				url: "../Controllers/formPosts.php?action=settings",
				data: {tab: interest, interests : myCheckboxes},
				success: function cevap(e){
					
						$('#loading').hide();
						$('#settings-panel').show();
					
					   if (!(e.indexOf("ok") > -1)) {
						   
							$("#alert-text").html('<?php echo $loc->label("There is a problem.");?>');
							$("#alert").show();
							shakeForm();
						   
					   } else {
						    
							$("#alert-text").html('<?php echo $loc->label("Your interests changed successfully.");?>');
							$("#alert").show();
							shakeForm();
						   
					   }
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