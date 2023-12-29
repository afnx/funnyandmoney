<?php

$postID = "";

if(isset($_GET['postID'])) {

if(is_numeric($_GET['postID'])) {
	$postID = $_GET['postID'];
	
	$sqlPost = "SELECT * FROM posts WHERE ID=$postID AND isDeleted<>1";
	$resultPost = $runsql->executenonquery ( $sqlPost, NULL, true );
	
	$fn2 = new functions();
	$fn3 = new functions();
	
	while($row=mysqli_fetch_array($resultPost)) { 

		$rowID = $row["ID"];
		$rowPostType = $row["postType"];
		$puserID = $row["userID"];
		$createddate_ = $row["createddate_"];
		$title = $row["title"];
		$description = $row["description"];
		$categoryID = $row["categoryID"];
		$imagePath = $row["imagePath"];
		$status = $row["status"]; 
		$likeCount = $row["likeCount"];
		$followCount = $row["followCount"];
		$shareCount = $row["shareCount"];
		$viewCount = $row["viewCount"];
		$postUrl = $row["postUrl"];
		$nowLike = $row["nowLike"];
		$nowFollow = $row["nowFollow"];
		$nowShare = $row["nowShare"];
		$nowView = $row["nowView"];
		$video = $row["videoDuration"];
		$lastEdited = $row["lastEdited"];  
		
		$genderArray = $row["gender"];
		$countryArray = $row["country"];
		$ageArray = $row["age"];
		
		$positionID = $row["positionID"];
		
		$shareSelectFollowers = $row["oneSharerFollowerCount"];
		
		$adminNote = $row["adminNote"];
		
		
		$postWUrl = "https://www.youtube.com/embed/" . substr($postUrl, strpos($postUrl, "=") + 1,11); 
		
		
		$completedPost = '';

					if($row['platformID'] == 4){
						$imgsrc= $row["imagePath"];
					}else{
						$imgsrc= project::uploadPath.$row["imagePath"];
					}

					$platform = new platforms($row["platformID"]);
					
					
					$category = new definitions($row["categoryID"]);

					$percLike = $fn2->calcPlatform($row["ID"],1);
					$percShare = $fn2->calcPlatform($row["ID"],2);
					$percFollower = $fn2->calcPlatform($row["ID"],3);
					$percView = $fn2->calcPlatform($row["ID"],4);
					
					$nowL = (is_null($row["nowLike"])) ? 0 : $row["nowLike"];
					$nowS = (is_null($row["nowShare"])) ? 0 : $row["nowShare"];
					$nowF = (is_null($row["nowFollow"])) ? 0 : $row["nowFollow"];
					$nowV = (is_null($row["nowView"])) ? 0 : $row["nowView"];
					
					if(is_null($row["likeCount"]) == false && $row["likeCount"] > $nowL) {

						$completedPost = 0;
						
					} else if(is_null($row["shareCount"]) == false && $row["shareCount"] > $nowS) {
						
						$completedPost = 0;
						
					} else if(is_null($row["followCount"]) == false && $row["followCount"] > $nowF) {
						
						$completedPost = 0;
						
					} else if(is_null($row["viewCount"]) == false && $row["viewCount"] > $nowV) {
						
						$completedPost = 0;
						
					}  else {
						
						$completedPost = 1;
						
					}
		
	}
	
	$definitionA = new definitions();
	$resultCDef = $definitionA->getAllDef(12);
	
	$definitionC = new definitions();
	$resultConDef = $definitionC->getAllDef(13);  
	
	$definitionG = new definitions();
	$resultGDef = $definitionG->getAllDef(1); 

	$definitionAg = new definitions();
	$resultAgDef = $definitionAg->getAllDef(6);  
	
	$definitionF = new definitions();
	$resultFDef = $definitionF->getAllDef(9);
	
	$sql = "SELECT * FROM positions";
	$resultP = $runsql->executenonquery ( $sql, NULL, true );  
	
	$owner = new users($puserID);
	

}

}

$sql = "SELECT * FROM posts WHERE isDeleted<>1";  
$result = $runsql->executenonquery ( $sql, NULL, true );



?>


<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Gönderi Düzenle </h3>

											<p style="margin-bottom: 5px;">Gönderileri düzenleyin. Gönderiyi bulmak için gönderi başlığını veya id sini yazın.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">  
											<input type="hidden" id="tabput" name="tab" value="editPost" />
											<input type="hidden" name="postID" value="<?php echo $rowID; ?>" />
											<input type="hidden" name="puserID" value="<?php echo $puserID; ?>" />
											<input type="hidden" name="platformID" value="<?php echo $platform->ID; ?>" />
											<input type="hidden" name="postDate" value="<?php echo $createddate_; ?>" />
											<input type="hidden" name="video" value="<?php echo $video; ?>" /> 
											
											
											
																		
								<div class="form-group">
													
													<select id="postchoose" class="chosen-select" name="postchoose" onchange="location = this.value;"> 
															<option disabled selected>Gönderi Seç</option>
															
													<?php while($row=mysqli_fetch_array($result)) { ?>
															
															<option value="admin?tab=editpost&postID=<?php echo $row['ID']; ?>" <?php if($postID == $row['ID']) { ?>selected<?php } ?>><?php echo $row['title']; ?> - ID: <?php echo $row['ID']; ?></option>
															
													<?php } ?>
															
							
													</select>
													
													</div>
													
													<br/>
											
							
							<?php if(isset($postID) && !empty($postID) && $postID != "") { ?>
						
		
						

						
						
						<div id="<?php echo $rowID;?>" class="post post-md">
							<div class="row">
								<div class="col-md-4">
									<div class="post-thumbnail">
										<a href="<?php if($rowPostType == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $rowID;?>"><img src="<?php echo ($imagePath!="") ? $imgsrc : project::assetImages.$platform->platformBlankPicture;?>" alt=""></a>
									</div>
								</div>
								<div class="col-md-8">
									<div class="post-header">
									<div style="float: right; font-size: 30px; padding-right:5px; color: #777;">
									<div class="post-header post-author">
										<a href="admin?tab=edituser&userID=<?php echo $owner->ID;?>" class="author" data-toggle="tooltip" title="<?php echo $owner->fullName;?>"><img src="<?php echo ($owner->picture!="") ? project::uploadPath."/userImg/".$owner->picture : "../Assets/images/profile.jpg";?>" alt="" /></a>
									</div>
									</div>
										<div class="post-title">
											<h4><a href="<?php if($rowPostType == 4) {?> <?php echo "videopost?id=";?><?php } else {?><?php echo "post?id=";?><?php }?><?php echo $rowID;?>"><?php echo $title; ?></a></h4>
											<ul class="post-meta">
												<li><i class="fa fa-calendar-o" data-toggle="tooltip" title="<?php echo $loc->label("Start date");?>"></i>  <?php echo $createddate_;?></li>
												<li id="<?php echo $rowID;?>icon"></li>
												<li><i class="fa fa-user"></i><?php echo $owner->fullName;?></li>
												<li><?php echo $video; ?></li>  
												<li>Son güncelleme: <?php echo $lastEdited; ?></li>
											</ul>
										</div>

									</div>
									<p><?php echo $description;?> </p>  
									
					<?php if($platform->ID == 4) { ?>
					<div class="row" style="margin-bottom: 30px;">
									<div class="col-md-12">
						<div class="embed-responsive embed-responsive-16by9">
							<iframe class="embed-responsive-item" src="<?php echo $postWUrl; ?>" allowfullscreen></iframe>
						</div>  
					</div>
					</div>
					
					<?php } ?>


							
					
						<div class="panel panel-default" style="margin-bottom: 30px;">
							<div class="panel-body">

							<?php if ($status == 1) {?>
							
							<?php if ($completedPost != 1) { ?>

								<?php if ($likeCount > 0) {?>

								<div class="form-group">
								
								<p class="progress-label"><?php echo $loc->label("Likes");?> &nbsp; - &nbsp; <?php echo(($percLike == 100.00) ? '<b style="color: #0e9a49;">' . $loc->label("Completed") . '!</b>' : '<b style="color: #52baff;">' . $loc->label("Continuing") . '...</b>');?> <span><?php echo $percLike;?>%</span></p>
								<div class="progress progress-animation">
									<div class="progress-bar progress-bar-<?php echo(($percLike == 100.00) ? "success" : "info");?> progress-bar-striped" aria-valuenow="<?php echo $percLike;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percLike;?>%"></div>
								</div>
								
								</div>

								<?php }?>  

								<?php if ($followCount > 0) {?>

								<div class="form-group">
								
								<p class="progress-label"><?php echo $loc->label("Followers");?> &nbsp; - &nbsp; <?php echo(($percFollower == 100.00) ? '<b style="color: #0e9a49;">' . $loc->label("Completed") . '!</b>' : '<b style="color: #52baff;">' . $loc->label("Continuing") . '...</b>');?> <span><?php echo $percFollower;?>%</span></p>
								<div class="progress progress-animation">
									<div class="progress-bar progress-bar-<?php echo(($percFollower == 100.00) ? "success" : "info");?> progress-bar-striped" aria-valuenow="<?php echo $percFollower;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percFollower;?>%"></div>
								</div>

								</div>
								
								<?php }?>
 
								<?php if ($shareCount > 0) {?>
 
								<div class="form-group">
								
								<p class="progress-label"><?php echo $loc->label("Shares");?> &nbsp; - &nbsp; <?php echo(($percShare == 100.00) ? '<b style="color: #0e9a49;">' . $loc->label("Completed") . '!</b>' : '<b style="color: #52baff;">' . $loc->label("Continuing") . '...</b>');?> <span><?php echo $percShare;?>%</span></p>
								<div class="progress progress-animation">
									<div class="progress-bar progress-bar-<?php echo(($percShare == 100.00) ? "success" : "info");?> progress-bar-striped" aria-valuenow="<?php echo $percShare;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percShare;?>%"></div>
								</div>
								
								</div>

								<?php }?>
								
								<?php if ($viewCount > 0) {?>

								<div class="form-group">
								
								<p class="progress-label"><?php echo $loc->label("Views");?> &nbsp; - &nbsp; <?php echo(($percView == 100.00) ? '<b style="color: #0e9a49;">' . $loc->label("Completed") . '!</b>' : '<b style="color: #52baff;">' . $loc->label("Continuing") . '...</b>');?> <span><?php echo $percView;?>%</span></p>
								<div class="progress progress-animation">
									<div class="progress-bar progress-bar-<?php echo(($percView == 100.00) ? "success" : "info");?> progress-bar-striped" aria-valuenow="<?php echo $percView;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percView;?>%"></div>
								</div>
								
								</div>

								<?php }?> 
								
							<?php } else { ?> 
								
								<h3 style="color: #0e9a49; text-align: center;"><i class="fa fa-check-circle" style="color: #0e9a49;"></i><?php echo $loc->label("COMPLETED");?></h3> 
								<p style="text-align: center;"><?php echo $loc->label("Your post closed due to reaching intended numbers. You can delete your post no longer.");?></p>
							
							<?php }?> 
								
							<?php } else if($status == 2) {?> 
								
								<h3 style="color: orange; text-align: center;"><?php echo $loc->label("SUSPENDED");?></h3> 
							
							<?php } else if($status == 0) {?> 
								
								<h3 style="color: #0e9a49; text-align: center;"><i class="fa fa-check-circle" style="color: #0e9a49;"></i><?php echo $loc->label("COMPLETED");?></h3> 
								<p style="text-align: center;"><?php echo $loc->label("Your post closed due to reaching intended numbers. You can delete your post no longer.");?></p>
							
							<?php } else if($status == 3) {?> 
								
								<h3 style="color: red; text-align: center;"><?php echo $loc->label("BANNED");?></h3> 
							
							<?php }?>
							
								

							</div>
							</div>
							
							
							<table class="table">
									<thead>
										<tr>
											<th><h3>Gönderi URL</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<td><?php echo $postUrl; ?></td>
										</tr>
									</tbody>
							</table>

							
							
							
							
							<table class="table">
									<thead>
										<tr>
											<th><h3><?php echo $loc->label("Target Group");?></h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th><?php echo $loc->label("Category");?></th>
											<td>
											
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												

													<div class="form-group">
											
													<select id="category" class="chosen-select" name="category">
													
													<?php  while ($row=mysqli_fetch_array($resultCDef)) { ?>
													
															<option value="<?php echo $row['ID']; ?>" <?php if($row['ID'] == $category->ID) {?>selected<?php } ?>><?php echo evalLoc($row['definition']); ?></option>
															
													<?php } ?>
															
													</select>
										
											
											</div>
											
											</div></td>
										</tr>
										<tr>
											<th><?php echo $loc->label("Country"); ?></th>
											<td><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												

													<div class="form-group">
											
													<select id="country" class="chosen-select" name="country[]" multiple>
													
													<?php  while ($row=mysqli_fetch_array($resultConDef)) { ?>
													
															<option value="<?php echo $row['ID']; ?>"  
															

															<?php $arrayC = explode(',',$countryArray);
																if (in_array($row['ID'], $arrayC)) {
																	echo "selected";
																} ?> 
															
															
															><?php echo evalLoc($row['definition']); ?></option>
															
													<?php } ?>
															
													</select>
										
											
											</div>
											
											</div></td>
										</tr>
										<tr>
											<th><?php echo $loc->label("Gender");?></th>
											<td><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												

													<div class="form-group">
											
													<select id="gender" class="chosen-select" name="gender[]" multiple>
													
													<?php  while ($row=mysqli_fetch_array($resultGDef)) { ?>
													
															<option value="<?php echo $row['ID']; ?>"
															
															
															<?php $arrayG = explode(',',$genderArray);
																if (in_array($row['ID'], $arrayG)) {
																	echo "selected";
															} ?> 
															
															
															
															><?php echo evalLoc($row['definition']); ?></option>
															
													<?php } ?>
															
													</select>
										
											
											</div>
											
											</div></td>
										</tr>
										<tr> 
											<th><?php echo $loc->label("Age");?></th>
											<td><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												

													<div class="form-group">  
													<select id="age" class="chosen-select" name="age[]" multiple>
													
													<?php  while ($row=mysqli_fetch_array($resultAgDef)) { ?>
													
															<option value="<?php echo $row['ID']; ?>" 
															
															<?php $arrayA = explode(',',$ageArray);
																if (in_array($row['ID'], $arrayA)) {
																	echo "selected"; 
														 } ?> 
															
															
															><?php echo evalLoc($row['definition']); ?></option>
															
													<?php } ?>
															
													</select> 
										
											
											</div>
											
											</div></td> 
										</tr>
									</tbody>
								</table>

					<br/>
					
								<div class="row">
				<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-body">
				<h4>
				
				<div class="row">
				
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

<?php echo $loc->label("Position");?>:
				</div>
				
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
												

													<div class="form-group">  
													
													<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>
											
													<select id="position" class="chosen-select" name="position">
													
													<?php  while ($row=mysqli_fetch_array($resultP)) { ?>
													
															<option value="<?php echo $row['ID']; ?>" 
															
																<?php if($positionID == $row["ID"]) { echo "selected"; } ?>
															
															>
															
															<?php 
															
															
															switch ($row["ID"]) {  
						
						case 4 :
							echo $loc->label("V.I.P Field and Standart Field");
							break;
				
						case 3 :
							echo $loc->label("Top Video Field and Standart Field");
							break;
				
						case 2 :
							echo $loc->label("Top Standart Field");
							break;
				
						case 1 :
							echo $loc->label("Standart Field");
							break;

					}
															
															
															?>
															
															
															</option>
															
													<?php } ?>
															
													</select> 
										
										<?php } else { echo "Bu alan için yetkiniz yok."; } ?>
											
											</div>
											</div>
											
											</div>
				
				
				
				</h4>
				</div>
				</div>
				</div>
				</div>
				
				<br/>
				
				
				<table class="table">
				
				<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>
									<thead>
										<tr>
											<th><h3><?php echo $loc->label("Statistics ");?><h3></th>
										</tr>
									</thead>
									
									

									<tbody>  
									
									
									
									
									<?php if($rowPostType == 2 or $rowPostType == 4) { ?>  
										<tr>
											<th><?php echo $loc->label("Likes");?></th>
											<td><div class="row"><div class="col-lg-5"><p style="font-size: 28px; float: right; margin-top: 15px;"><?php if(is_null($nowLike)) { echo 0; } else { echo $nowLike; } ?>/</p></div><div class="col-lg-7"><input id="like" type="number" min="0" maxlength="11" class="form-control" name="like"
														value="<?php if(is_null($likeCount)) { echo 0; } else { echo $likeCount; } ?>"></div></div></td>
										</tr>
									<?php } ?>
									<?php if($rowPostType == 1 or $rowPostType == 3) { ?>
										<tr>
											<th><?php echo $loc->label("Followers");?></th>
											<td><div class="row"><div class="col-lg-5"><p style="font-size: 28px; float: right; margin-top: 15px;"><?php if(is_null($nowFollow)) { echo 0; } else { echo $nowFollow; }?>/</p></div><div class="col-lg-7"><input id="follow" type="number" min="0" maxlength="11" class="form-control" name="follow"
														value="<?php if(is_null($followCount)) { echo 0; } else { echo $followCount; } ?>"></div></div></td>
										</tr>
									<?php } ?>  
					
										<tr> 
											<th><?php echo $loc->label("Shares");?></th>
											<td><div class="row"><div class="col-lg-5"><p style="font-size: 28px; float: right; margin-top: 15px;"><?php if(is_null($nowShare)) { echo 0; } else { echo $nowShare; }?>/</p></div><div class="col-lg-7"><input id="share" type="number" min="0" maxlength="11" class="form-control" name="share"
														value="<?php if(is_null($shareCount)) { echo 0; } else { echo $shareCount; } ?>"></div></div>
														
														<div class="row">
														
														
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												

													<div class="form-group">  
											
													<select id="shareSelectFollowers" class="chosen-select" name="shareSelectFollowers">
													
													<option disabled <?php if($shareSelectFollowers == "" or is_null($shareSelectFollowers) === true) { echo "selected"; } ?> value></option>

													
													<?php  while ($row=mysqli_fetch_array($resultFDef)) { ?>
													
													
															<option value="<?php echo $row['ID']; ?>" 
														
															<?php if($shareSelectFollowers == $row['ID']) { echo "selected"; } ?>
															
															><?php echo evalLoc($row['definition']); ?></option>
															
													<?php } ?>
															
													</select> 
										
											
											</div>
											
											</div>
														
														
														</div>
														
														</td>
										</tr>
									
									<?php if($platform->ID == 4 && $rowPostType == 4) { ?>
										<tr>
											<th><?php echo $loc->label("Views");?></th>
											<td><div class="row"><div class="col-lg-5"><p style="font-size: 28px; float: right; margin-top: 15px;"><?php if(is_null($nowView)) { echo 0; } else { echo $nowView; }?>/</p></div><div class="col-lg-7"><input id="view" type="number" min="0" maxlength="11" class="form-control" name="view"
														value="<?php if(is_null($viewCount)) { echo 0; } else { echo $viewCount; } ?>"></div></div></td> 
										</tr>
									<?php } ?>
									
										
										
									</tbody>  
									
									<?php } else { echo "<h3 style='margin-top: 15px; margin-bottom: 15px;'>İstatistik alanı için yetkiniz yok.</h3>"; } ?>
									
								</table>
								
								

<br/>



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-sm-30">
		
						<div class="row margin-bottom-60">
							<div class="radio radio-inline">
								<input type="radio" name="status" value="1" id="inline-radio1" <?php if($status == 1) { echo "checked"; } ?>> 
								<label for="inline-radio1">Yayında</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="status" value="2" id="inline-radio2" <?php if($status == 2) { echo "checked"; } ?>> 
								<label for="inline-radio2">Askıya alınmmış</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="status" value="3" id="inline-radio3" <?php if($status == 3) { echo "checked"; } ?>> 
								<label for="inline-radio3">Engellenmiş</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="status" value="0" id="inline-radio4" <?php if($status == 0) { echo "checked"; } ?>> 
								<label for="inline-radio4">Tamamlanmış</label>
							</div>
						</div>
					</div>

					
					
					<div class="form-group">
								
								<h4>Yönetici Notu</h4>
								
								</div>
								
								<div class="row">
								
								<div class="col-lg-12">
												

													<div class="form-group"> 
								<textarea name="adminNote" class="form-control" maxlength="500"><?php echo $adminNote; ?></textarea>
								</div>
								</div>
								</div>

								
						
							</div> 
							
						</div>
						
	<div class="form-group">
							
							<div class="row">
							
							<div class="col-lg-6">  
											
												
							</div>
							
							
							<div class="col-lg-6">
							
							<div class="col-lg-12">  
							
							<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>	
							
							<button id="deleteSure" type="button" class="btn btn-danger" data-toggle="modal" data-target=".bs-modal-sm">Gönderiyi Sil</button>
				<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title">Gönderi sil?</h4>
							</div>
							<div class="modal-body">
								Bu gönderiyi silmek istediğinizden emin misiniz?
							</div>
							<div class="modal-footer">
								<button id="closeButton" type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
								<button style="margin-right: 15px;"  value="<?php echo $rowID; ?>" class="btn btn-danger">Gönderiyi Sil</button>
							</div>
						 </div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
											
												
												
															<?php } ?>
							
											
												<button name="change" value="<?php echo $rowID; ?>" type="submit" class="btn btn-primary">Değiştir</button>
												
							</div>
											
												
												
							</div>
												
								</div>
											
											</div>
							
							<?php } ?>

				
						
		

									
										</div>
						</div>
						
						
<script>

$( document ).ready(function() {

		var platformIcon;

		switch(<?php echo $platform->ID ;?>) {

			case 1 :
				platformIcon = '<i class="fa fa-facebook"></i>facebook';
				break;

			case 2 :
				platformIcon = '<i class="fa fa-twitter"></i>twitter';
				break;

			case 3 :
				platformIcon = '<i class="fa fa-instagram"></i>instagram';
				break;

			case 4 :
				platformIcon = '<i class="fa fa-youtube"></i>youtube';
				break;

			case 5 :
				platformIcon = '<i class="fa fa-google-plus"></i>googleplus';
				break;

			case 6 :
				platformIcon = '<i class="fa fa-film"></i>video';
				break;

			case 7 :
				platformIcon = '<i class="fa fa-music"></i>music';
				break;

			case 8 :
				platformIcon = '<i class="fa fa-mouse-pointer"></i>website';
				break;
		}

		document.getElementById("<?php echo $rowID;?>icon").innerHTML = platformIcon;
		
	});

	
	$(document).ready(function () {
	

	
	
	$("#country,#gender,#age").change(function () {
		
	var selectsoptions = [
	
		"1",
		"17",
		"55"

	];
		if($(this).val() == null){
			var ptimes= 0;
		}else{
			var ptimes= $(this).val().length;
		}
		for (i = 0; i < selectsoptions.length; i++) { 
			for (p = 0; p < ptimes; p++) { 
				if($(this).val()[p] == selectsoptions[i])	{
					$(this).val(selectsoptions[i]);
				}
			}
		}
		$(this).trigger("chosen:updated");
	
		
		
	
	});
	
});

$("#closeButton").click(function (event) {  
    $("#tabput").val("editPost"); 
});
$("#deleteSure").click(function (event) {
    $("#tabput").val("deletePost"); 
});

</script>