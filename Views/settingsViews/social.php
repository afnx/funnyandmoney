<?php

$userSocialFacebook = new userSocials();
$resultFacebook = $userSocialFacebook->getUserSocial($userid, 1);

$userSocialTwitter = new userSocials();
$resultTwitter = $userSocialTwitter->getUserSocial($userid, 2);

$userSocialInstagram = new userSocials();
$resultInstagram = $userSocialInstagram->getUserSocial($userid, 3);

$userSocialYoutube = new userSocials();
$resultYoutube = $userSocialYoutube->getUserSocial($userid, 4);

$userSocialGoogleplus = new userSocials();
$resultGoogleplus = $userSocialGoogleplus->getUserSocial($userid, 5);

?>
		
		
					

				

						<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">

											<h3><?php echo $loc->label("Social Accounts");?> </h3>

											<p style="margin-bottom: 5px;"><?php echo $loc->label("Edit your social media accounts");?><p>

										</div>
										
										
											<div id="alert" class="alert alert-info alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>


										<div class="panel-body" id="panel-body">
											<input type="hidden" name="whichone" id="whichone" value="">
											<input type="hidden" name="tab" value="social" />

											
											<div class="widget" style="margin-top: 15px;">
												<div class="panel panel-default">
													<div class="panel-body" style="padding: 5px;">
													
													
													<div class="form-group" style="margin-top: 15px; text-align: center;">
													
														<i style="font-size: 50px; color: #52baff;" class="fa fa-exclamation-circle"></i>
													
													</div>
													
													
													<div class="form-group" style="text-align: center;">
													
														<?php echo $loc->label("We do not use your password or personal information");?>
													
													</div>
													
													
													</div>
													
												</div>
												
											</div>
								

												<div class="form-group">

													<h4><?php echo $loc->label("Facebook");?></h4>

												</div>

												<div class="form-group">

													<?php while ($row=mysqli_fetch_array($resultFacebook)) {?>


														<table class="table">
									<tbody>
										<tr>
											<td><?php echo $row["screenName"]; ?></td>
											<td>
												<input type="hidden" name="deleteFacebook" value="<?php echo $row["ID"]; ?>" />
												<button id="submit" type="submit" class="btn btn-inverse btn-circle btn-sm"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
									</tbody>
								</table>

													<?php }?>

													<?php if (mysqli_num_rows( $resultFacebook ) == 0) {?>


														<p><a href="../Controllers/facebook.php" class="btn btn-primary  btn-shadow btn-rounded"><?php echo $loc->label("Add a Facebook account");?> </a></p>

													<?php }?>

												</div>

												<div class="form-group">

													<h4><?php echo $loc->label("Twitter");?></h4>

												</div>

												<div class="form-group">

													
													<?php while ($row=mysqli_fetch_array($resultTwitter)) {?>


														<table class="table">
								
									<tbody>
										<tr>
											<td><?php echo $row["screenName"]; ?></td>
											<td>
												<input type="hidden" name="deleteTwitter" value="<?php echo $row["ID"]; ?>" />
												<button id="submit2" type="submit" class="btn btn-inverse btn-circle btn-sm"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
									</tbody>
								</table>

													<?php }?>

													<?php if (mysqli_num_rows( $resultTwitter ) == 0) {?>


														<p><a href="../Controllers/twitter.php" class="btn btn-primary  btn-shadow btn-rounded"><?php echo $loc->label("Add a Twitter account");?></a></p>

													<?php }?>
													

												</div>

												<div class="form-group">

													<h4><?php echo $loc->label("Youtube");?></h4>

												</div>

												<div class="form-group">

												
												<?php while ($row=mysqli_fetch_array($resultYoutube)) {?>


														<table class="table">
						
									<tbody>
										<tr>
											<td><?php if(!empty($row["screenName"])){
												echo $row["screenName"];
											}else{
												echo $loc->label("Your Youtube Account");
											} ?></td>
											<td>
												<input type="hidden" name="deleteYoutube" value="<?php echo $row["ID"]; ?>" />
												<button id="submit3" type="submit" class="btn btn-inverse btn-circle btn-sm"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
									</tbody>
								</table>

													<?php }?>

													<?php if (mysqli_num_rows( $resultYoutube ) == 0) {?>


														<p><a href="../Controllers/youtube.php" class="btn btn-primary  btn-shadow btn-rounded"><?php echo $loc->label("Add a Youtube account");?></a></p>

													<?php }?>


												</div>


											

									
										</div>
						</div>


				
		
