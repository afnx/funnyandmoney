<?php

$orderID = "";

if(isset($_GET['orderID'])) {
	
if(is_numeric($_GET['orderID'])) {
	
	$orderC = giftRequests::checkOrderWithID ( $_GET['orderID'] );
	
} else {

	$fn = new functions();  
	$fn->redirect("404");

}

if($orderC->ID > 0) {
	$orderID = $_GET['orderID'];
	
	$sqlGift = "SELECT * FROM giftRequests WHERE ID=$orderID AND isDeleted<>1";
	$resultOrder = $runsql->executenonquery ( $sqlGift, NULL, true );
	
	
	while($row=mysqli_fetch_array($resultOrder)) { 

		$rowID = $row["ID"]; 
		$giftID = $row["giftID"];
		$userID = $row["userID"];
		$orderNo = $row["orderNo"];
		$date_ = $row["date_"];
		$price = $row["price"];
		$addressID = $row["addressID"];
		$deliveryStatus = $row["deliveryStatus"]; 
		$cargoFirm = $row["cargoFirm"];
		$cargoNo = $row["cargoNo"];
		$providerNo = $row["providerNo"];
		$adminNote = $row["adminNote"];  
		$preturn = $row["preturn"];  
		
	}
	
	$gift = new gifts($giftID);
	$userG = new users($userID);
	$address = new address($addressID);
	$definitionC = new definitions($address->country);
	$digitalCode = new digitalGiftCodes();
	$digital = mysqli_fetch_array ( $digitalCode->findGiftCode($rowID) );
	
	$sqlGiftCode = "SELECT * FROM digitalGiftCodes WHERE giftID=".$giftID." AND isUsed<>1 AND expirationDate_ > NOW()";
	$resultCode = $runsql->executenonquery ( $sqlGiftCode, NULL, true );

	$sqlGiftCode2 = "SELECT * FROM digitalGiftCodes WHERE giftID=".$giftID." AND isUsed<>1 AND expirationDate_ > NOW()";
	$resultCode2 = $runsql->executenonquery ( $sqlGiftCode2, NULL, true );
	
} else {

	$fn = new functions();    
	$fn->redirect("404");  

}

} else {

	$fn = new functions();    
	$fn->redirect("404");  

}



?>

<style>
.one-edge-shadow {
 -moz-box-shadow:    2px 2px 4px 5px #ccc;
  -webkit-box-shadow: 2px 2px 4px 5px #ccc;
  box-shadow:         2px 2px 4px 5px #ccc;
padding: 15px;
}
</style>
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Sipariş Numarası:  <?php echo $orderNo; ?></h3>

											<p style="margin-bottom: 5px;"><?php echo $orderNo; ?> nolu siparişi düzenleyin.<p>    

										</div>
			

										<div class="panel-body" id="panel-body">  
											<input id="tabput" type="hidden" name="tab" value="editOrder" />
											<input type="hidden" name="orderID" value="<?php echo $rowID; ?>" />
											<input type="hidden" id="deliveryStatus" name="deliveryStatus" value="<?php echo $deliveryStatus; ?>" />
											
				
						
		
						

						
						
						<div id="<?php echo $rowID;?>" class="post post-md">
							<div class="row">
								<div class="col-md-10">
									<div class="post-header">
										<div class="post-title">
											<h4><?php echo $gift->name; ?> - <?php if($deliveryStatus==3){echo "<span style='color: red;'>İptal Edildi</span>";} ?> <?php if($preturn==1){echo "<span style='color: red;'>VE PUAN İADE EDİLDİ</span>";} ?></h4>
											<ul class="post-meta">
											<li><b/><?php if($gift->isDigital==1) { ?>ÜRÜN DİJİTALDIR.<?php } else {?>ÜRÜN METARYALDİR.<?php }?></b></li>
												<li><a href="admin?tab=editgift&giftID=<?php echo $giftID; ?>">Ürünün detaylı bilgisi için tıklayın.</a></li>
											</ul>
										</div>

									</div>
									<p><?php echo $gift->description;?></p>   
									

							<table class="table"> 
									<thead>
										<tr>
											<th><h3>Sipariş Bilgileri</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>Sipariş Numarası:</th>
											<td><?php echo $orderNo; ?></td>
										</tr>
										<tr>
											<th>Sipariş Tarihi:</th>
											<td><?php echo date('d/m/Y H:i:s', strtotime($date_)); ?></td>
										</tr>
										<tr>
											<th>Kullanıcı - ID:</th>
											<td><a href="admin?tab=edituser&userID=<?php echo $userG->ID; ?>"><?php echo $userG->fullName; ?> - <?php echo $userG->ID; ?></a></td>
										</tr>
										<tr>
											<th>Ürün ID:</th>
											<td><?php echo $giftID; ?></td>
										</tr>
										
									<?php if($gift->isDigital==1) { ?>
										
										<tr>
											<th>Dijital Kod ID:</th>
											<td><?php if($digital["ID"] > 0) { ?> <? echo $digital["ID"];?> - <input id="modalButton" type="button" data-toggle="modal" value="Değiştir" data-target=".bs-modal"> <?php } else { echo "Yok"; } ?></td>
											
											<div id="modal" class="modal fade bs-modal" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $loc->label("Close");?></span></button>
								<h4 class="modal-title" style="text-align: center;">Kod değiştir</h4>
							</div>
							<div class="modal-body">
								
								<p id="modalText" style="text-align: center;">
								
								<select id="digitalGCode" class="chosen-select" name="digitalGCode">
															<option disabled selected>Bir dijital kod seçin</option>  
													
													<?php  while ($row2=mysqli_fetch_array($resultCode)) { ?>
													
															<option value="<?php echo $row2['ID']; ?>">ID: <?php echo $row2['ID'] . " - SKT: " . $row2['expirationDate_']; ?></option>
															
													<?php } ?>
													
													<?php if (mysqli_num_rows( $resultCode ) == 0) {?>	

															<option disabled>Kod kalmamış. Yeni bir kod oluşturup tekrar deneyin.</option> 
  
													<?php }?>	
															
								</select>
								
								</p>			
								
							</div>
							<div class="modal-footer">

								<a href="javascript: changeDigital();"><button id="changeCodeButton" type="button" onClick="this.disabled=true; this.innerHTML='İşlem gerçekleştiriliyor…';"  class="btn btn-primary">Değiştir</button></a> <button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("Close");?></button>

							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
				

											
										</tr>
										
									<? } ?>
										
										<tr>
											<th>Ücret(&):</th>
											<td><?php echo $price; ?></td>
										</tr>
										<tr>
											<th>Durum:</th>
											<td>
											
											<div class="form-group">
													
													<select id="deliveryStatusS" class="form-control input-md" name="deliveryStatusS">
															<option disabled>Teslimat durumunu seç</option>
															<option value="0" style="color: green; font-weight: bold;" <?php if($deliveryStatus==0){echo "selected";} ?>>Teslim Edildi</option>
					<?php if($gift->isDigital!=1) { ?>		<option value="1" style="color: grey; font-weight: bold;" <?php if($deliveryStatus==1){echo "selected";} ?>>Kargoya Verildi</option> <?php } ?>
															<option value="2" style="color: orange; font-weight: bold;" <?php if($deliveryStatus==2){echo "selected";} ?>>Bekliyor</option>  
															<option value="3" style="color: red; font-weight: bold;" <?php if($deliveryStatus==3){echo "selected";} ?>>İptal Edildi</option>  

															
													</select>  
													
													</div>
											
											
											</td>
										</tr>

									</tbody>  
								</table>
								
								
								<br />
								
								<?php if($gift->isDigital!=1) { ?>	
								
								<table class="table">   
									<thead>
										<tr>
											<th><h3>Adres Bilgileri</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>Alıcı İsmi:</th>
											<td><?php echo $address->recipientName; ?></td>
										</tr>
										<tr>
											<th>Telefon:</th>
											<td>+<?php echo $address->phone; ?></td>
										</tr>
										<tr>
											<th>Adres:</th>
											<td>
											<?php echo $address->addressLine1; ?>
											<br />
											<?php echo $address->addressLine2; ?>
											</td>
										</tr>
										<tr>
											<th>İlçe:</th>
											<td><?php echo $address->region; ?></td>
										</tr>
										<tr>
											<th>Posta Kodu:</th>
											<td><?php echo $address->postalCode; ?></td>
										</tr>
										<tr>
											<th>Şehir:</th>
											<td><?php echo $address->city; ?></td>
										</tr>
										<tr>
											<th>Ülke:</th>
											<td><?php echo evalLoc($definitionC->definition); ?></td>
										</tr>

									</tbody>  
								</table>
								
							
								<br />
								
								<table class="table"> 
									<thead>
										<tr>
											<th><h3>Ürün/Kargo Bilgileri</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>Tedarikçi:</th>
											<td><?php echo $gift->provider; ?></td>
										</tr>
										<tr>
											<th>Ürün Takip Numarası:</th>
											<td><input id="providerNo" maxlength="20" type="text" class="form-control" name="providerNo" value="<?php echo $providerNo; ?>" /></td>
										</tr>
										<tr>
											<th>Kargo Firması:</th>
											<td><input id="cargoFirm" type="text" class="form-control" name="cargoFirm" value="<?php echo $cargoFirm; ?>" /></td>
										</tr>
										<tr>
											<th>Kargo Takip Numarası:</th>
											<td><input id="cargoNo" maxlength="20" type="text" class="form-control" name="cargoNo" value="<?php echo $cargoNo; ?>" /></td>
										</tr>
										

									</tbody>    
								</table>
							
							<?php } ?>
							
								<br/>
								<br/>
								
								<div class="form-group" id="pointback" style="display:none;"> 
								<div class="row">
								
								<div class="checkbox checkbox-icon checkbox-inline checkbox-danger">
								<input type="checkbox" id="icon-checkbox1" name="pointBack" value="1"> 
								<label style="font-size: 14px;" for="icon-checkbox1">Kullanıcıya puanını geri iade et.</label>
							</div>
							
							</div>

								
								</div>

							<div id="sendMailPart">	
								<div class="form-group">
								<div class="row">
								
								<div class="checkbox checkbox-icon checkbox-inline checkbox-success">
								<input type="checkbox" id="icon-checkbox2" name="sendMail" value="1"> 
								<label style="font-size: 14px;" for="icon-checkbox2">Kullanıcıya bilgilendirme maili gönder.</label>
							</div>
							
							</div>

								
								</div>
								
								<div id="sendMailUser" class="one-edge-shadow" style="display: none;">
								
								<div class="form-group">
													
													<select id="mailTemp" class="form-control input-md" name="mailTemp">
															<option disabled selected>Mail seç</option>
															<option value="0" style="color: green; font-weight: bold;">Teslim Edildi Maili</option>
															<option value="1" style="color: grey; font-weight: bold;">Kargoya Verildi Maili</option>
															<option value="2" style="color: orange; font-weight: bold;">Bekliyor Maili</option>  
															<option value="3" style="color: red; font-weight: bold;">İptal Edildi Maili</option>  
															<option value="c">Custom Mail</option>

															
													</select>
													
													</div>
								
								
								
								<div id="sendMailCustom" style="display: none;">
								<div class="form-group">
								
								<b>Kullanıcının dili: <?php echo $userG->language; ?></b>
								
								</div>
								
								<div class="form-group"> 
								
								<b>Mail Başlığı:</b>
								<input maxlength="150" type="text" class="form-control" name="sendMailTitle" />
								</div>
								
								<div class="form-group">  
								
								
								
								<div class="row">
								
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
								
								<b>Metin:</b>
								<br/>
								
								Girdiğiniz metin mailin sadece gelişme bölümü olmalıdır. Merhaba, iyi günler vb. yazıları yazmayın. Doğrudan ilgili paragrafı yazın.
												

													<div class="form-group"> 
								<textarea name="sendMailText" class="form-control" maxlength="500"></textarea>
								</div>
								</div>
								</div>
								
								</div>
								
								</div>
								
								</div>
								
								</div>
								
								<div id="sendeMailPro" style="display: none;">
								
								<div class="row">
								<div class="col-lg-6">
								<b>Bir digital kod seçin:</b>
								<select id="digitalGCode" class="chosen-select" name="digitalGCode">
															<option disabled selected>Bir dijital kod seçin</option>  
													
													<?php  while ($row3=mysqli_fetch_array($resultCode2)) { ?>
													
															<option value="<?php echo $row3['ID']; ?>">ID: <?php echo $row3['ID'] . " - SKT: " . $row3['expirationDate_']; ?></option>
															
													<?php } ?>
													
													<?php if (mysqli_num_rows( $resultCode ) == 0) {?>	

															<option disabled>Kod kalmamış. Yeni bir kod oluşturup tekrar deneyin.</option> 
  
													<?php }?>	
															
								</select>
								*Dijital kodu teslim ettiğinizde kullanıcıya kod mail ile otomatik olarak iletilir.
								</div>
								</div>
								
								</div>
								
								<br/>
								<br/>
								
								<div class="form-group">
								
								<h4>Yönetici Notu</h4>
								
								</div>
								
								<div class="row">
								
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
												

													<div class="form-group"> 
								<textarea name="adminNote" class="form-control" maxlength="500"><?php echo $adminNote; ?></textarea>
								</div>
								</div>
								</div>

				</div>
				
				<br/>

								
						
							</div> 
							
						</div>
						
						<?php if($preturn!=1) { ?>
						
	<div class="form-group">
							
							<div class="row">
							
			
							
							
							<div class="col-lg-6" style="text-align: center;">
							
						

											
												<button id="changeButton" name="change" value="<?php echo $rowID; ?>" type="submit" onClick="this.disabled=true; this.innerHTML='İşlem gerçekleştiriliyor…'; this.form.submit();"   class="btn btn-primary">Değiştir</button>
								  
							 
											
												
												
							</div>
												
								</div>
											
											</div>
											
											<?php } ?>
							
					
		

									
										</div>
						</div>
<script>

<?php if($preturn==1) { ?>

var form = document.getElementById("adminpanel");
var elements = form.elements;
for (var i = 0, len = elements.length; i < len; ++i) {
    elements[i].readOnly = true;
}

$('select').attr('disabled', 'disabled');


<?php } ?>

$("#deliveryStatusS").change(function(){ 
	
	document.getElementById("deliveryStatus").value = $("#deliveryStatusS").val();

	
});

$("#mailTemp").change(function(){ 
	
	
	if(document.getElementById("mailTemp").value == "c") { 

		$("#sendMailCustom").show();
		
	} else {
		
		$("#sendMailCustom").hide();
		
	}

	
});

$("#deliveryStatusS").change(function(){ 
	
	
	if(document.getElementById("deliveryStatusS").value == 3) { 

		$("#pointback").show();
		
	} else {
		
		$("#pointback").hide();
		$("#icon-checkbox1").prop("checked", false);
		
	}
	
	<?php if($gift->deliverySpeed == 2) { ?>
		
	<?php if($digital["ID"] > 0) { ?>
	
	<?php } else { ?>
	
	if(document.getElementById("deliveryStatusS").value == 0) { 

		$("#sendMailPart").hide();
		$("#sendeMailPro").show();
		
	} else {
		
		$("#sendeMailPro").hide();
		$("#sendMailPart").show();
		
	}
	
	<?php } ?>

	<?php } ?>
	
});

$('#icon-checkbox2').click(function() {
    $("#sendMailUser").toggle(this.checked);
});

<?php if($gift->isDigital==1) { ?>
<?php if($digital["ID"] > 0) { ?>

function changeDigital(){
					
						$.ajax({
						type: 'POST',
						url: "../Controllers/formPosts.php?action=admin",
						data: {tab: "changeDigitalCode", cdigitalCode: $("#digitalGCode").val(), oldcdigitalCode: <? echo $digital["ID"]; ?>, agiftRequestID: <? echo $rowID; ?>}, 
						success: function cevap(e){
						
							if(!(e.indexOf("XXsdsaok322XXX") > -1)){
								
								location.href = window.location.href + "&message=fail&error=" + e;
								
							} else {
								
								location.href = window.location.href + "&message=success";
								
							}
						
							
						}
						})
}

<?php } ?>
<? } ?>
</script>