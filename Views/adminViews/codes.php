<?php 

$runsql = new \data\DALProsess ();  

if(isset($_GET['search'])) {
	
	$search = $_GET['search'];
	
	$gift = new gifts($_GET['search']);
	
} else {
	
	$search = "";
	
}


if(isset($_GET['expire'])) {
	
	switch($_GET['expire']) {
	
		case 1 :
			$expire = " AND expirationDate_ > NOW()";
			
			break;
			
		case 2 :
			$expire = "";
			
			break;
			
		case 0;
			$expire = " AND expirationDate_ <= NOW()";  
		
			break;

		default: 
			$expire = "";
			
			break;
	
	}
	
} else {
	
	$expire = "";
	$_GET["expire"] = 2;
	
}

if(isset($_GET['isUsedF'])) {
	
if($_GET['isUsedF'] == 1 or $_GET['isUsedF'] == 0) {
	
	$isUsed = $_GET['isUsedF'];

} else {
	
	$isUsed = 2;
	
}

} else {
	
	$isUsed = 2;
	
}

$sql = "
SELECT * FROM digitalGiftCodes  
CROSS JOIN 
(
	SELECT ID AS IDas FROM gifts WHERE (name LIKE '%".$search."%' or description LIKE '%".$search."%') AND isDigital=1
) AS init
WHERE (0=ifnull(IDas,0) or giftID=IDas)" . (($isUsed != 2) ? "". "AND isUsed='". $isUsed ."'" : "") . "" . $expire . "";    
$result = $runsql->executenonquery ( $sql, NULL, true );



?>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Dijital Kodlar </h3>

											<p style="margin-bottom: 5px;">Dijital kodları listeleyin.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">
											
										
											
											<div class="row">
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<input id="search" type="text" class="form-control" placeholder="Ürün ara" />
													
													</div>
													
													
											</div>  
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<select id="isUsedS" class="chosen-select" name="isUsedS" onchange="location = this.value;"> 
															<option disabled selected>Kullanılmış mı?</option>
															
												
															<option value="admin?tab=codes&isUsedF=2">Hepsi</option>
															<option value="admin?tab=codes&isUsedF=0" <?php if($isUsed == 0) { ?>selected<?php } ?>>Hayır</option>
															<option value="admin?tab=codes&isUsedF=1" <?php if($isUsed == 1) { ?>selected<?php } ?>>Evet</option>
												
															
							
													</select>
													
													</div>
													
													
											</div>  
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<select id="isUsedS" class="chosen-select" name="isUsedS" onchange="location = this.value;"> 
															<option disabled selected>Son kullanma tarihi geçmiş mi?</option>
															
												
															<option value="admin?tab=codes&expire=2">Hepsi</option>
															<option value="admin?tab=codes&expire=1" <?php if($_GET["expire"] == 1) { ?>selected<?php } ?>>Hayır</option>
															<option value="admin?tab=codes&expire=0" <?php if($_GET["expire"] == 0) { ?>selected<?php } ?>>Evet</option>
												
															
							
													</select>
													
													</div>
													
													
											</div>  
											
											</div>

											
											<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>Ürün ID</th>
											<th>Ürün</th>
											<th>Son Kullanma Tarihi</th>
											<th>Kullanılmış</th>
											<th>İşlem</th>
										</tr>
									</thead>
									<tbody>
									
									<?php while($row=mysqli_fetch_array($result)) { 
										
										
										$giftD = new gifts($row["giftID"]);
							
									
									?>
									
										<tr id="tableRow_<?php echo $row["ID"];?>">
											<td><?php echo $row["ID"];?></td> 
											<td><?php echo $row["giftID"];?></td>  
											<td><?php echo $giftD->name;?></td>    
											<td><?php echo $row["expirationDate_"];?></td>  
											<td><?php if($row["isUsed"]==1){echo "Evet";}else{echo "Hayır";};?></td>  
											<td><button style="margin-right: 15px;" id="deleteSure" type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-sm-<?php echo $row["ID"];?>">Detay</button><a href="javascript: deleteCode(<?php echo $row["ID"];?>);"><button style="margin-right: 15px;" class="btn btn-danger">Kodu Sil</button></a></td>
				<div class="modal fade bs-modal-sm-<?php echo $row["ID"];?>" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title">Kod Detay</h4>
							</div>  
							<div class="modal-body">
								<p><b>Açıklama</b></p>
								<p><?php echo $row["descriptionText"];?></p>
								<br/>
								<p><b>Kod</b></p>
								<p><?php echo $row["giftCode"];?></p>
								<br/>
								<p><b>Son Kullanma Tarihi</b></p>
								<p><?php echo $row["expirationDate_"];?></p>
							</div>
							<div class="modal-footer">
								<button id="closeButton" type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
							</div>
						 </div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
		 
										</tr> 
									
									<?php } ?>
									
									
										
									</tbody>
								</table>	

	<?php if (mysqli_num_rows( $result ) == 0) {?>	

	<?php echo "<p>Kayıt bulunamadı.</p>"; ?>
  
<?php }?>
	 
		
	
						</div>
						
<script>

$("#search").change(function()
{
    document.location.href = "https://www.funnyandmoney.com/admin?tab=codes&search=" + $(this).val();
});

function deleteCode(id){

		$.ajax({
		type: 'POST',
		url: "../Controllers/formPosts.php?action=admin",
		data: {tab: "deleteCode", deleteID: id},
		success: function cevap(e){
			
			if(!(e.indexOf("ok") > -1)){  
				
				alert("Hata: Silme işlemi gerçekleştirilemedi. Lütfen sorunu teknik departmana bildirin.");  
				
			} else {

				$( "#tableRow_" + id ).toggle( "highlight" );
				$( "#tableRow_" + id ).remove();
			
				
				
			}
		}
		})
}
</script>