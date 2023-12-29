<?php

$userID = "";

if(isset($_GET['userID'])) {
	
if(is_numeric($_GET['userID'])) {
	
	$userC = users::checkUserWithID ( $_GET['userID'] );
	
} else {

	$fn = new functions();  
	$fn->redirect("404");

}

if($userC->ID > 0) {
	$userID = $_GET['userID'];
	
	$sqlUser = "SELECT * FROM users WHERE ID=$userID";
	$resultUser = $runsql->executenonquery ( $sqlUser, NULL, true );
	
	$fn2 = new functions();
	$fn3 = new functions();
	
	while($row=mysqli_fetch_array($resultUser)) { 

		$rowID = $row["ID"]; 
		$email = $row["email"];
		$fullName = $row["fullName"];
		$email = $row["email"];
		$username = $row["username"];
		$picture = $row["picture"];
		$birthDate = $row["birthDate"];
		$gender = $row["gender"];
		$country = $row["country"]; 
		$about = $row["about"];
		$registerdate_ = $row["registerdate_"];
		$balance = $row["balance"];
		$categoryID = $row["categoryID"];
		$email_code = $row["email_code"];
		$signupStep = $row["signupStep"];
		$cash = $row["cash"];
		$referrerON = $row["referrerON"];
		$loginDate = $row["loginDate"];
		
	}
	
	$definitionC = new definitions($country);
	$definitionG = new definitions($gender);

	
	$postCategory = '';
					if(!empty($categoryID)){
						$categoryID= explode(',',$categoryID);  
						foreach ( $categoryID as $cat ) {
						
							$category = '';
							$category = new definitions($cat); 

							if(is_array($categoryID)) {
								$postCategory .= evalLoc($category->definition) .'<br/>';
							} else {
								$postCategory .= evalLoc($category->definition);
							}
		  
						}	 
					}
					
					

} else {

	$fn = new functions();    
	$fn->redirect("404");

}





$sqlF = "SELECT * FROM userSocials WHERE userID='" . $rowID . "' and platformID=1 and isDeleted<>1 LIMIT 1";  
$resultF = $runsql->executenonquery ( $sqlF, NULL, true );

$screenF = "";
$followF = "";

$screenT = "";
$followT = "";

$screenY = "";

while($row=mysqli_fetch_array($resultF)) { 
	
	$screenF = $row["screenName"];
	$followF = $row["friendsCount"];
	
}

$sqlT = "SELECT * FROM userSocials WHERE userID='" . $rowID . "' and platformID=2 and isDeleted<>1 LIMIT 1";  
$resultT = $runsql->executenonquery ( $sqlT, NULL, true );

while($row=mysqli_fetch_array($resultT)) { 
	
	$screenT = $row["screenName"];
	$followT = $row["followerCount"];
	
}

$sqlY = "SELECT * FROM userSocials WHERE userID='" . $rowID . "' and platformID=4 and isDeleted<>1 LIMIT 1";  
$resultY = $runsql->executenonquery ( $sqlY, NULL, true );

while($row=mysqli_fetch_array($resultY)) { 
	
	$screenY = $row["screenName"];
	
}

$sql2 = "SELECT * FROM IPlogs WHERE userID='". $_GET['userID'] ."' ORDER BY date_ DESC LIMIT 1";  
$result2 = $runsql->executenonquery ( $sql2, NULL, true );

$address = "";
$lastLogin = "";

while($row=mysqli_fetch_array($result2)) { 
	
	$address = $row["address"];
	$lastLogin = $row["date_"];
	
}

} 

$sql = "SELECT * FROM users WHERE isDeleted<>1";  
$result = $runsql->executenonquery ( $sql, NULL, true );

?>


<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Üye Düzenle </h3>

											<p style="margin-bottom: 5px;">Üyeleri düzenleyin. Üye bulmak için üye ismini-kullanıcı adını veya id sini yazın. Kullanıcıların bakiyesini düzenleyebilirsiniz.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">  
											<input id="tabput" type="hidden" name="tab" value="editUser" />
											<input type="hidden" name="euserID" value="<?php echo $rowID; ?>" />
											
											
											
																		
								<div class="form-group">
													
													<select id="userchoose" class="chosen-select" name="userchoose" onchange="location = this.value;"> 
															<option disabled selected>Üye Seç</option>
															
													<?php while($row=mysqli_fetch_array($result)) { ?>
															
															<option value="admin?tab=edituser&userID=<?php echo $row['ID']; ?>" <?php if($userID == $row['ID']) { ?>selected<?php } ?>><?php echo $row['fullName']; ?> - ID: <?php echo $row['ID']; ?></option>
															
													<?php } ?> 
															
							
													</select>
													
													</div>
													
													<br/>
											
							
							<?php if(isset($userID) && !empty($userID) && $userID != "") { ?>
						
		
						

						
						
						<div id="<?php echo $rowID;?>" class="post post-md">
							<div class="row">
								<div class="col-md-4">
									<div class="post-thumbnail">
										<a href="profile?id=<?php echo $rowID;?>"><img src="<?php echo ($picture!="") ? project::uploadPath."/userImg/".$picture : "../Assets/images/profile.jpg";?>" alt=""></a>
									</div>
								</div>
								<div class="col-md-8">
									<div class="post-header">
										<div class="post-title">
											<h4><a href="profile?id=<?php echo $rowID;?>"><?php echo $fullName; ?></a></h4>
											<ul class="post-meta">
												<li><i class="fa fa-calendar-o" data-toggle="tooltip" title="Kayıt Tarihi"></i> Kayıt Tarihi: <?php echo $registerdate_;?></li>
											</ul>
										</div>

									</div>
									<p><?php echo $about;?> </p>  
									

							<table class="table">
									<thead>
										<tr>
											<th><h3>Bilgiler</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>Kullanıcı adı:</th>
											<td><?php echo $username ; ?></td>
										</tr>
										<tr>
											<th>E-posta adresi:</th>
											<td><?php echo $email ; ?></td>
										</tr>
										<tr>
											<th>Doğum tarihi:</th>
											<td><?php echo  date( 'l, F d, Y', strtotime( $birthDate ) );?></td>
										</tr>
										<tr>
											<th>Cinsiyet:</th>
											<td><?php echo evalLoc( $definitionG->definition ); ?></td>
										</tr>
										<tr> 
											<th>Ülke:</th>
											<td><?php echo evalLoc( $definitionC->definition ); ?></td> 
										</tr>
										<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>
										<tr> 
											<th>İlgi Alanları:</th>
											<td><?php echo $postCategory ; ?></td> 
										</tr>
										<?php } ?>
										<tr> 
											<th>Facebook Hesabı:</th>
											<td><?php if($screenF != "") { echo $screenF; } else { echo "Yok"; } ?></td> 
										</tr>
										<tr> 
											<th>Twitter Hesabı:</th>
											<td><?php if($screenT != "") { echo $screenT; } else { echo "Yok"; } ?></td> 
										</tr>
										
										<tr> 
											<th>Youtube Hesabı:</th>
											<td><?php if($screenY != "") { echo $screenY; } else { echo "Yok"; } ?></td>  
										</tr>
										<tr> 
											<th>Facebook Arkadaş Sayısı:</th>
											<td><?php if($followF != "") { echo $followF; } else { echo "Yok"; } ?></td> 
										</tr>
										<tr> 
											<th>Twitter Takipçi Sayısı:</th>
											<td><?php if($followT != "") { echo $followT; } else { echo "Yok"; } ?></td> 
										</tr>
										<tr> 
											<th>Son IP:</th>
											<td><?php echo $address ; ?> 
											<br/><a href="admin?tab=iplogs&searchIP=<?php echo $address ; ?>">(Bu adrese ait tüm ip kayıtlarını gör)</a>
											<br/><a href="admin?tab=iplogs&searchUser=<?php echo $rowID ; ?>">(Bu kullanıcıya ait tüm ip kayıtlarını gör)</a></td> 
										</tr>
										<tr> 
											<th>Son Giriş Tarihi:</th>
											<td><?php echo $lastLogin ; ?></td> 
										</tr>
										<tr> 
											<th>Kod:</th>
											<td><?php if($email_code != "") { echo $email_code;} else { echo "Yok"; }; ?></td> 
										</tr>
										<tr> 
											<th>Hesap Durumu:</th>
											<td><?php if($signupStep == -1) { echo "<b style='color: green;'>Onaylanmış</b>" ;} else { echo "<span style='color: red;'>Onaylanmamış</span>"; } ?></td> 
										</tr>
										<tr> 
											<th>Para Çekme:</th>
											<td>
											<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;">
		
					
							<div class="radio radio-inline" style="padding-left: 0px;">
								<input type="radio" name="cash" value="1" id="inline-radio3" <?php if($cash == 1) { echo "checked"; } ?>> 
								<label for="inline-radio3">Aktif</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="cash" value="0" id="inline-radio4" <?php if($cash != 1) { echo "checked"; } ?>> 
								<label for="inline-radio4">Pasif</label>
							</div>
					
					
					</div>
											<?php } else if($cash == 1) { echo "<b>Aktif</b>"; } else if($cash != 1) { echo "<b>Pasif</b>"; } ?>
											</td>  
										</tr> 
										<tr> 
											<th>Referans:</th>
											<td><?php if($referrerON == 1) { echo "<b>Evet</b>" ;} else { echo "Hayır"; } ?></td> 
										</tr>
										<tr> 
											<th>Bakiye</th>
											<td>
											<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>
											<input style="display: none;" id="balance" name="balance" value="<?php echo $balance ; ?>" type="text" class="form-control" />
											<div id="pointText"><?php echo $balance; ?> <a style="color: blue;" href="javascript: point(1);">Değiştir</a> | <a style="color: blue;" href="javascript: point(2);">Puan Ekle</a></div>
											<div id="addPointText" style="display:none">
											<div class="row">
											<div class="col-md-12">
											<input id="addBalance" type="number" class="form-control" placeholder="Puan Ekle"/> 
											</div>
											</div>
											<div class="row margin-top-5">
											<div class="col-md-8">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;">
		
					
							<div class="radio radio-inline" style="padding-left: 0px;">
								<input type="radio" name="addPointC" value="1" id="inline-radio55"> 
								<label for="inline-radio55">Ürün</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="addPointC" value="0" id="inline-radio56"> 
								<label for="inline-radio56">Hediye</label>
							</div>
					
					
					</div>
											</div>
											<div class="col-md-4">
											<a style="color: blue; font-weight: bold;" href="javascript: addPoint();">Onayla</a>
											</div>
											</div>
											</div>
											<?php } else if($adminSM->ID > 0) { echo $balance; } else { echo "Bu alan için yetkiniz yok."; } ?>
											</td> 
										</tr>
									</tbody>  
								</table>


				</div>
				
				<br/>

								
						
							</div> 
							
						</div>
						
	<div class="form-group">
							
							<div class="row">
							
							<div class="col-lg-6">  
											
												
							</div>
							
							
							<div class="col-lg-6">
							
							<div class="col-lg-12">  
											
								<?php if($adminA->ID > 0) { ?>				
												
												
												<button id="deleteSure" type="button" class="btn btn-danger" data-toggle="modal" data-target=".bs-modal-sm">Üyeyi Sil</button>
				<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title">Üyeyi sil?</h4>
							</div>
							<div class="modal-body">
								Bu üyeyi silmek istediğinizden emin misiniz?
							</div>
							<div class="modal-footer">
								<button id="closeButton" type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
								<button style="margin-right: 15px;"  value="<?php echo $rowID; ?>" class="btn btn-danger">Üyeyi Sil</button>
							</div>
						 </div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
											<?php } ?> 
												
							<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>	
											
												<button id="changeButton" name="change" value="<?php echo $rowID; ?>" type="submit" class="btn btn-primary">Değiştir</button>
										<?php } ?> 		  
							</div>
											
												
												
							</div>
												
								</div>
											
											</div>
							
							<?php } ?>

				
						
		

									
										</div>
						</div>
						
						
<script>  

$("#closeButton").click(function (event) {  
    $("#tabput").val("editUser"); 
});
$("#deleteSure").click(function (event) {
    $("#tabput").val("deleteUser"); 
});

function point(tip) {
	
	if(tip == 1) {
		
		$("#pointText").hide(); 
		$("#balance").show(); 
	
	} else if(tip == 2) {
		
		$("#pointText").hide();
		$("#addPointText").show();
		
	}
	
}

function addPoint() {
	
	if($("#addBalance").val() == "") {
		
		alert("Eklenecek puanın miktarını girin!");
	
	} else {
	
		if(document.getElementById('inline-radio55').checked || document.getElementById('inline-radio56').checked ) {
	
			var oldB = parseFloat($("#balance").val());
			var newB = parseFloat($("#addBalance").val());
	
			var newBalance = parseFloat(( oldB + newB ));  
			$("#balance").val(newBalance);
			$("#addPointText").hide();
			$("#balance").show(); 
		
		} else {
		
			alert("Eklenen puanın ürün teslimatı veya hediye olduğunu belirtin!");
		
		}
	
	}
}

</script>