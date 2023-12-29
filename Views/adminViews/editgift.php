<?php

$giftID = "";

if(isset($_GET['giftID'])) {
	
if(is_numeric($_GET['giftID'])) {
	
	$giftC = gifts::checkGiftWithID ( $_GET['giftID'] );
	
} else {

	$fn = new functions();  
	$fn->redirect("404");

}

if($giftC->ID > 0) {
	$giftID = $_GET['giftID'];
	
	$sqlGift = "SELECT * FROM gifts WHERE ID=$giftID AND isDeleted<>1";
	$resultGift = $runsql->executenonquery ( $sqlGift, NULL, true );
	
	
	while($row=mysqli_fetch_array($resultGift)) { 

		$rowID = $row["ID"]; 
		$name = $row["name"];
		$description = $row["description"];
		$picture = $row["picture"];
		$price = $row["price"];
		$category = $row["category"];
		$quantity = $row["quantity"];
		$numberOfSales = $row["numberOfSales"]; 
		$provider = $row["provider"];
		$deliverySpeed = $row["deliverySpeed"];
		$isFeatured = $row["isFeatured"];
		$isDigital = $row["isDigital"];
		$availableZone = $row["availableZone"];
		$date_ = $row["date_"];
		$status = $row["status"];  
		
	}
	
	$definitionC = new definitions($category);
	
	$definitionC = new definitions();
	$resultConDef = $definitionC->getAllDef(28); 
	
	$sqlLike = "SELECT COUNT(*) FROM shopLikes WHERE giftID=$giftID AND userLike=1 AND isDeleted<>1";
	$resultLike = $runsql->executenonquery ( $sqlLike, NULL, true );
	$rowTotalL=mysqli_fetch_row($resultLike);
	$likes = $rowTotalL[0];	
	
	$sqlunLike = "SELECT COUNT(*) FROM shopLikes WHERE giftID=$giftID AND userLike=-1 AND isDeleted<>1";
	$resultunLike = $runsql->executenonquery ( $sqlunLike, NULL, true );
	$rowTotalunL=mysqli_fetch_row($resultunLike);
	$unlikes = $rowTotalunL[0];
	
	$sqlComment = "SELECT COUNT(*) FROM shopComments WHERE giftID=$giftID AND isDeleted<>1";
	$resultComment = $runsql->executenonquery ( $sqlComment, NULL, true );
	$rowTotalC=mysqli_fetch_row($resultComment);
	$comments = $rowTotalC[0];

} else {

	$fn = new functions();    
	$fn->redirect("404");  

}

}

$sql = "SELECT * FROM gifts WHERE isDeleted<>1";
$result = $runsql->executenonquery ( $sql, NULL, true );


?>


<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Ürün Düzenle </h3>

											<p style="margin-bottom: 5px;">Ürünleri düzenleyin. Ürün bulmak için ürün ismini-id sini yazın.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">  
											<input id="tabput" type="hidden" name="tab" value="editGift" />
											<input type="hidden" name="giftID" value="<?php echo $rowID; ?>" />
											<input type="hidden" id="availableZone" name="availableZone" value="<?php echo $availableZone; ?>" />
											
											
											
																		
								<div class="form-group">
													
													<select id="userchoose" class="chosen-select" name="userchoose" onchange="location = this.value;"> 
															<option disabled selected>Ürün Seç</option>
															
													<?php while($row=mysqli_fetch_array($result)) { ?>
															
															<option value="admin?tab=editgift&giftID=<?php echo $row['ID']; ?>" <?php if($giftID == $row['ID']) { ?>selected<?php } ?>><?php echo $row['name']; ?> - ID: <?php echo $row['ID']; ?></option>
															
													<?php } ?> 
															
							
													</select>
													
													</div>
													
													<br/>
											
							
							<?php if(isset($giftID) && !empty($giftID) && $giftID != "") { ?>
						
		
						

						
						
						<div id="<?php echo $rowID;?>" class="post post-md">
							<div class="row">
								<div class="col-md-4">
									<div class="post-thumbnail">
										<img id="blah" src="<?php echo ($picture!="") ? project::uploadPath."/giftImg/".$picture : project::assetImages. "giftimage.jpg";?>" alt="">
										<br />
										<b>Resim değiştir:</b> 
										
										<div id="form1" runat="server" class="form-group">      

											<input name="image" type='file' accept="image/*" id="imgInp" />  

										</div>
										<div id="uploadText"></div>
										<a id="uploadlink"><button type="button" class="btn btn-primary btn-lg"><?php echo $loc->label("Upload Photo");?></button></a>
									</div>
								</div>
								<div class="col-md-8">
									<div class="post-header">
										<div class="post-title">
											<h4><input id="giftName" type="text" maxlength="60" class="form-control" name="giftName" value="<?php echo $name; ?>" /></h4>
											<ul class="post-meta">
												<li><i class="fa fa-calendar-o" data-toggle="tooltip" title="Eklenme Tarihi"></i> Eklenme Tarihi: <?php echo $date_;?></li>
											</ul>
										</div>

									</div>
									<p><textarea id="description" type="text" class="form-control" name="description"><?php echo $description;?></textarea> </p>   
									

							<table class="table"> 
									<thead>
										<tr>
											<th><h3>Detaylar</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>Ücret(&):</th>
											<td><input id="price" type="text" class="form-control" name="price" value="<?php echo $price; ?>" /> </td>
										</tr>
										<tr>
											<th>Kategori:</th>
											<td>
											
											<div class="form-group">
													
													<select id="category" class="form-control input-md" name="category">
															<option disabled>Kategori Seç</option>
															<?php while($row=mysqli_fetch_array($resultConDef)) { ?>
															<option value="<?php echo $row['ID']; ?>" <?php if($category==$row['ID']){echo"selected";} ?>><?php echo evalLoc($row['definition']); ?></option>
															<?php } ?>
													</select>
													
											</div>
											
											</td>
										</tr>
										<tr>
											<th>Miktar:</th>
											<td><input id="quantity" type="text" class="form-control" name="quantity" value="<?php echo $quantity; ?>" /></td>
										</tr>
										<tr> 
											<th>Satış Sayısı:</th>
											<td><input id="numberOfSales" type="text" class="form-control" name="numberOfSales" value="<?php echo $numberOfSales; ?>" /></td> 
										</tr>
										<tr> 
											<th>Tedarikçi:</th>
											<td><input id="provider" type="text" class="form-control" name="provider" value="<?php echo $provider; ?>" /></td> 
										</tr>
										<tr> 
											<th>Beğenenler:</th>
											<td><?php echo $likes; ?></td>   
										</tr>
										<tr> 
											<th>Beğenmeyenler:</th>
											<td><?php echo $unlikes; ?></td> 
										</tr>
										<tr> 
											<th>Yapılan Yorum:</th>
											<td><?php echo $comments; ?></td> 
										</tr>
										<tr> 
											<th>Özellikli:</th>
											<td>
											
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;">
		
					
							<div class="radio radio-inline" style="padding-left: 0px;">
								<input type="radio" name="isFeatured" value="1" id="inline-radio1" <?php if($isFeatured == 1) { echo "checked"; } ?>> 
								<label for="inline-radio1">Evet</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="isFeatured" value="0" id="inline-radio2" <?php if($isFeatured != 1) { echo "checked"; } ?>> 
								<label for="inline-radio2">Hayır</label>
							</div>
					
					
					</div>
											
											</td> 
										</tr>
										<tr> 
											<th>Dijital:</th>
											<td>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;">
		
					
							<div class="radio radio-inline" style="padding-left: 0px;">
								<input type="radio" name="digital" value="1" id="inline-radio3" <?php if($isDigital == 1) { echo "checked"; } ?>> 
								<label for="inline-radio3">Evet</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="digital" value="0" id="inline-radio4" <?php if($isDigital != 1) { echo "checked"; } ?>> 
								<label for="inline-radio4">Hayır</label>
							</div>
					
					
					</div>
											</td>  
										</tr> 
										
										<tr> 
											<th>Teslimat Hızı:</th>
											<td>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;">
		
					
							<div class="radio radio-inline" style="padding-left: 0px;">
								<input type="radio" name="deliverySpeed" value="1" id="inline-radio8" <?php if($deliverySpeed == 1) { echo "checked"; } ?>> 
								<label for="inline-radio8">Kargo</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="deliverySpeed" value="0" id="inline-radio9" <?php if($deliverySpeed == 0) { echo "checked"; } ?>> 
								<label for="inline-radio9">Anında Teslim</label> 
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="deliverySpeed" value="2" id="inline-radio17" <?php if($deliverySpeed == 2) { echo "checked"; } ?>> 
								<label for="inline-radio17">E-posta Teslim</label> 
							</div>
					
					
					</div>
											</td>  
										</tr> 
										<tr> 
											<th>Dağıtım Alanı:</th>
											<td><div class="form-group">
													
													<select id="zone" class="form-control input-md" name="zone">
															<option disabled>Dağıtım alanını seç</option>
															<option value="0" <?php if($availableZone==0){echo"selected";} ?>>Uluslararası</option>
															<option value="1" <?php if($availableZone==1){echo"selected";} ?>>Türkiye</option>
															<option value="2" <?php if($availableZone==2){echo"selected";} ?>>Yurtdışı</option>  

															
													</select>
													
													</div></td> 
										</tr>
										
										<tr> 
											<th>Durum:</th>
											<td>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;">
		
					
							<div class="radio radio-inline" style="padding-left: 0px;">
								<input type="radio" name="status" value="1" id="inline-radio5" <?php if($status == 1) { echo "checked"; } ?>> 
								<label for="inline-radio5">Yayında</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="status" value="0" id="inline-radio6" <?php if($status != 1) { echo "checked"; } ?>> 
								<label for="inline-radio6">Yayında değil</label>
							</div>
					
					
					</div>
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
													
												<button id="deleteSure" type="button" class="btn btn-danger" data-toggle="modal" data-target=".bs-modal-sm">Ürünü Sil</button>
				<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title">Ürünü sil?</h4>
							</div>
							<div class="modal-body">
								Bu ürünü silmek istediğinizden emin misiniz?
							</div>
							<div class="modal-footer">
								<button id="closeButton" type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
								<button style="margin-right: 15px;"  value="<?php echo $rowID; ?>" class="btn btn-danger">Ürünü Sil</button>
							</div>
						 </div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
								
				
											
												<button id="changeButton" name="change" value="<?php echo $rowID; ?>" type="submit" class="btn btn-primary">Değiştir</button>
								  
							</div>  
											
												
												
							</div>
												
								</div>
											
											</div>
							
					

				
						<?php } ?> 	
		

									
										</div>
						</div>
						
						
<script>  

$("#zone").change(function(){
	
	document.getElementById("availableZone").value = $("#zone").val();  ;

	
});

$("#closeButton").click(function (event) {  
    $("#tabput").val("editGift"); 
});
$("#deleteSure").click(function (event) {
    $("#tabput").val("deleteGift"); 
});


function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

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


function upload(){
	$("#uploadlink").hide();
	$("#uploadText").html("Resim yükleniyor...");
	var formData = new FormData();
	formData.append('tab', 'imageGift');
	formData.append('giftID', '<?php echo $rowID; ?>');
	formData.append('image', $('input[type=file]')[0].files[0]); 
$( "#changeButton" ).prop( "disabled", true );
$.ajax({
       url : '../../Controllers/formPosts.php?action=admin',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
			   if(!(data.indexOf("ok") > -1)){
					$('#blah').attr('src', '<?php echo ($picture!="") ? project::uploadPath."/giftImg/".$picture : project::assetImages. "giftimage.jpg";?>');
					$('#imgInp').val('');
					alert(data);
			   }else{ 
				   $("#uploadText").html("");
				   alert("Resim Yüklendi. Not: Sayfadan ayrılsanız bile resim yükleme işlemi kaybolmaz, resim kaydedilmiştir.");
				   $("#uploadlink").show();
			   }$( "#changeButton" ).prop( "disabled", false );  
       }
});
};


$("input[name=deliverySpeed]:radio,input[name=digital]:radio").change(function(){
	
	if(document.getElementById('inline-radio9').checked || document.getElementById('inline-radio17').checked) {
		
		document.getElementById("inline-radio3").checked = true;
		
	} else if(document.getElementById('inline-radio8').checked) {
		
		document.getElementById("inline-radio4").checked = true;
		
	} 
	
});

</script>