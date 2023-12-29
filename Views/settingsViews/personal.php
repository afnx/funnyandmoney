<?php
$male_checked='';
$female_checked='';

if($user->gender == 77){
	$male_checked='checked ';
}elseif($user->gender == 2){
	$female_checked='checked ';
}
date_default_timezone_set ( 'Europe/Istanbul' );

$time=strtotime($user->birthDate);
$month=date("m",$time);
$year=date("Y",$time);
$day=date("d",$time);

?>						
					

			

						<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">

											<h3><?php echo $loc->label("Personal");?> </h3>

											<p style="margin-bottom: 5px;"><?php echo $loc->label("Edit your personal information");?><p>

										</div>
										
										<div id="alert" class="alert alert-info alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>


										<div class="panel-body" id="panel-body">
											<input type="hidden" name="tab" value="personal" />
											<input type="hidden" id="day" name="day" value="<?php echo $day; ?>" />
											<input type="hidden" id="month" name="month" value="<?php echo $month; ?>" />
											<input type="hidden" id="year" name="year" value="<?php echo $year; ?>" />
											
												<div class="form-group">

													<h4><?php echo $loc->label("Profile Photo");?></h4>

												</div>
												
												<div class="form-group">
												
												<div class="profile-avatar">

													<img id="blah" src="<?php echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg";?>" alt="your image" height="120" width="120"/>
												160x160<br/>
												<?php if($user->picture!="") { ?>
													<a href="javascript: removeImage('profile');"><?php echo $loc->label('Remove');?></a>
												<?php } ?>
												</div>

												</div>


												<div id="form1" runat="server" class="form-group">

													 <input type='file' id="imgInp" accept="image/*" />

												</div>
												
												<div class="form-group">

													<h4><?php echo $loc->label("Cover Photo");?></h4>

												</div>
												
												<div class="form-group">
												
												<div class="profile-avatar"> 

												
												<img id="blah2" src="<?php echo ($user->coverPicture!="") ? project::uploadPath."/userCoverImg/".$user->coverPicture : "../Assets/images/cover.jpg";?>" alt="your image" height="120" width="120"/>
												
												1920x500<br/>
												<?php if($user->coverPicture!="") { ?>
													<a href="javascript: removeImage('cover');"><?php echo $loc->label('Remove');?></a>
												<?php } ?>
												</div>

												</div>


												<div id="formCover1" runat="server" class="form-group">

													 <input type='file' id="imgInpCover" accept="image/*" />

												</div>
											
												<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

												<div class="form-group">

													<h4><?php echo $loc->label("Full Name");?></h4>

												</div>

												<div class="form-group">

													<input type="text" name="fullName" id="fullName" value="<?php echo $user->fullName; ?>" class="form-control">

												</div>

											</div>
											
										</div>
										
											<div class="row">
										
										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										
												<div class="form-group">

													<h4><?php echo $loc->label("Bio");?></h4>

												</div>

												<div class="form-group">

													<textarea style="height: 120px;" type="text" name="bio" id="bio" value="<?php echo $user->about; ?>" class="form-control" rows="2" maxlength="260"></textarea>

												</div>
												
											</div>
											</div>
											
												<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
												<div class="form-group">

													<h4><?php echo $loc->label("Gender");?></h4>

												</div>

												</div>
												
												</div>

												<div class="row">
												
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												<div class="form-group">

												
												<div class="radio radio-inline">

							<input type="radio" name="gender" id="genderF" value="2" <?php echo $female_checked; ?>/>

							<label for="genderF"> <?php echo $loc->label("female");?></label>

						</div>
													
						</div>
						</div>
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
													<div class="form-group">
													<div class="radio radio-inline">

							<input type="radio" name="gender" id="genderM" value="77" <?php echo $male_checked; ?>/>

					   <label for="genderM"> <?php echo $loc->label("male");?> </label>

						</div>

						


												</div>
												
												</div>
												
											
												
												</div>
											
												
												<div class="row">
												
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

												<div class="form-group">

													<h4><?php echo $loc->label("Birth Date");?></h4>

												</div>
												
												</div>

												</div>
												<div class="row">
												
										<?php if($_SESSION['language'] == "tr") { ?>
										
										
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												

													<div class="form-group">
														
												
												
												<select id="dayS" name="dayS" class="form-control input-md">

													
														<option disabled><?php echo $loc->label("Day");?></option>

														<?php for($i=1;$i<32;$i++){
															echo '<option value="'. $i .'" ' . ($i == $day ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
														
											

															
												</select>

												
												
										</div>
										
										</div>
											
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												
										<div class="form-group">
													
											<select id="monthS" name="monthS" class="form-control input-md">

										
														<option disabled><?php echo $loc->label("Month");?></option>
													
													
														<?php for($i=1;$i<13;$i++){
															echo '<option value="'. $i .'" ' . ($i == $month ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
															
												

															
											</select>

													 
										</div>
										
										</div>
										
										<?php } else { ?>
										
										
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												
										<div class="form-group">
													
											<select id="monthS" name="monthS" class="form-control input-md">

										
														<option disabled><?php echo $loc->label("Month");?></option>
													
													
														<?php for($i=1;$i<13;$i++){
															echo '<option value="'. $i .'" ' . ($i == $month ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
															
												

															
											</select>

													 
										</div>
										
										</div>
										
										
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												

													<div class="form-group">
														
												
												
												<select id="dayS" name="dayS" class="form-control input-md">

													<option disabled><?php echo $loc->label("Day");?></option>
														

														<?php for($i=1;$i<32;$i++){
															echo '<option value="'. $i .'" ' . ($i == $day ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
														
											

															
												</select>

												
												
										</div>
										
										</div>
										
										
										<?php } ?> 

										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
										<div class="form-group">
						
											<select id="yearS" name="yearS" class="form-control input-md">

											
													<option disabled><?php echo $loc->label("Year");?></option>
										
													
														<?php for($i=date("Y")-13;$i>date("Y")-80;$i--){
															echo '<option value="'. $i .'" ' . ($i == $year ? " selected" : "") . '>'.$i.'</option>'."\n";
														}?>
												
											</select>	
													
										</div>
										</div>

												</div>
												
										

										
										</div>
										
									
										<div class="panel-footer" id="panel-footer" style="padding-top: 15px;">
										
											<center><div class="form-group">
												
												<button id="submit" type="submit" class="btn btn-primary btn-rounded"><?php echo $loc->label("Save");?></button>
												
											</div></center>
											
										</div>									
									
						</div>
						
						
						<div id="modal" class="modal fade bs-modal" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title" style="text-align: center;"><?php echo $loc->label("ERROR");?></h4>
							</div>
							<div class="modal-body">
								
								<p id="modalText" style="text-align: center;"></p>			
								
							</div>
							<div class="modal-footer">

								<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("Close");?></button>

							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
				
				
				<input id="modalButton" type="hidden" data-toggle="modal" data-target=".bs-modal">


					

<script>
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function readURL2(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah2').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


$("#dayS").change(function(){
	document.getElementById("day").value = $("#dayS").val();
});

$("#monthS").change(function(){
	document.getElementById("month").value = $("#monthS").val();
});

$("#yearS").change(function(){
	document.getElementById("year").value = $("#yearS").val();
});

$("#imgInp").change(function(){ 
    readURL(this);
	var formData = new FormData();
	formData.append('tab', 'image');
	formData.append('picture', 'profile');
	formData.append('image', $('#imgInp')[0].files[0]); 
	$( "#submit" ).prop( "disabled", true );
	$("#submit").html("<?php echo $loc->label('UploadingPlease Wait');?>");
$.ajax({
       url : '../Controllers/formPosts.php?action=settings',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
			   if(!(data.indexOf("ok") > -1)){
				    $('#blah').attr('src', '<? echo ($user->picture!="") ? project::uploadPath."/userImg/".$user->picture : "../Assets/images/profile.jpg"; ?>');
					$('#imgInp').val('');
					document.getElementById("modalText").innerHTML = data;
					document.getElementById("modalButton").click(); 
			   }$( "#submit" ).prop( "disabled", false );
			   $("#submit").html("<?php echo $loc->label('Save');?>");
			
       }
});  
});

$("#imgInpCover").change(function(){ 
    readURL2(this);
	var formData = new FormData();
	formData.append('tab', 'image');
	formData.append('picture', 'cover');
	formData.append('image', $('#imgInpCover')[0].files[0]); 
	$( "#submit" ).prop( "disabled", true );
	$("#submit").html("<?php echo $loc->label('UploadingPlease Wait');?>");
$.ajax({
       url : '../Controllers/formPosts.php?action=settings',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
			   if(!(data.indexOf("ok") > -1)){
				    $('#blah2').attr('src', '<? echo ($user->coverPicture!="") ?  project::uploadPath."/userCoverImg/".$user->coverPicture : "../Assets/images/cover.jpg"; ?>');
					$('#imgInpCover').val('');
					document.getElementById("modalText").innerHTML = data;
					document.getElementById("modalButton").click(); 
			   }$( "#submit" ).prop( "disabled", false );
			   $("#submit").html("<?php echo $loc->label('Save');?>");
       }
});  
});

document.getElementById("bio").defaultValue = "<?php echo $user->about; ?>";

function removeImage(image){
					
						$.ajax({
						type: 'POST',
						url: "../Controllers/formPosts.php?action=removeUserImage",
						data: {image: image},
						success: function cevap(e){
						
							if(e.indexOf("ok") > -1){

							if(image == "profile") {
								$('#blah').attr('src', "../Assets/images/profile.jpg");
							} else if(image == "cover") {
								$('#blah2').attr('src', "../Assets/images/cover.jpg");
							}
								
							}
						
						}
						})
}
</script>