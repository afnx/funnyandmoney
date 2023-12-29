<?php 

$runsql = new \data\DALProsess ();  

if(isset($_GET['searchPayout'])) {
	
	$searchPayout = $_GET['searchPayout'];
	
} else {
	
	$searchPayout = "";
	
}

if(isset($_GET['searchUser'])) {
	
	$searchUser = $_GET['searchUser'];
	
} else {
	
	$searchUser = "";
	
}


$sql = "
SELECT * FROM payouts " .
(($searchPayout == "" && $searchUser != "") ? "INNER JOIN users ON (fullName LIKE '%".$searchUser."%' or username LIKE '%".$searchUser."%' or email LIKE '%".$searchUser."%')" : "")
. " WHERE " .
(($searchPayout == "" && $searchUser != "") ? "users.ID=payouts.userID" : "(cashNo LIKE '%".$searchPayout."%')") 
. " AND payouts.isDeleted<>1 ORDER BY date_ DESC";
$result = $runsql->executenonquery ( $sql, NULL, true );



?>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Giden Ödemeler </h3>

											<p style="margin-bottom: 5px;">Giden ödemeleri listeleyin.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">
											
										
											
											<div class="row">
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<input id="searchPayout" type="text" class="form-control" placeholder="İşlem numarası ara" />
													
													</div>
													
													
											</div>  
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<input id="searchUser" type="text" class="form-control" placeholder="Kullanıcı ara" />
													
													</div>
													
													
											</div>  
											
											
											</div>

											
											<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>İşlem Numarası</th>
											<th>Kullanıcı(ID)</th>
											<th>Hesap Numarası</th>
											<th>Hesap Sahibi</th>
											<th>Miktar(&)</th>
											<th>Tutar</th>
											<th>Tarih</th>
											<th>Durum</th>
											<th>Aksiyon</th>
										</tr>
									</thead>
									<tbody>
									
									<?php while($row=mysqli_fetch_array($result)) { 
										
									
										$user = new users($row["userID"]);
									
									?>
									
										<tr id="tableRow_<?php echo $row["ID"];?>">
											<td><?php echo $row["ID"];?></td> 
											<td><?php echo $row["cashNo"];?></td> 
											<td><?php echo $user->fullName . "(" . $user->ID . ")";?></td>  
											<td><?php echo $row["IBAN"];?></td>  
											<td><?php echo $row["bankFirstName"] . " " . $row["bankLastName"];?></td>  
											<td><?php echo $row["point"];?>& </td>  
											<td><?php echo $row["amount"] . " " . $row["currency"];?></td> 
											<td><?php echo $row["date_"];?></td> 
											<td><?php if($row["result"]==2){echo "<b style='color: orange;'>Bekliyor</b>";}else if($row["result"]==1){echo "<b style='color: green;'>Ödendi</b>";}else if($row["result"]==0){echo "<b style='color: red;'>İptal Edildi</b>";}?></td>  
											<td>
											
												<select style="margin-bottom: 5px;" id="status_<?php echo $row["ID"];?>" class="form-control" name="status_<?php echo $row["ID"];?>">
															<option disabled selected>Ödeme durumunu seç</option>
															<option value="1" style="color: green; font-weight: bold;">Ödendi</option>
															<option value="2" style="color: orange; font-weight: bold;">Bekliyor</option>  
															<option value="0" style="color: red; font-weight: bold;">İptal Edildi</option>  

															
													</select>  
													
													<a href="javascript: getPayout(<?php echo $row['ID'];?>)" class="btn btn-success">Onayla</a>
											
											</td>
										</tr> 
									
									<?php } ?>
									
									
										
									</tbody>
								</table>	

	<?php if (mysqli_num_rows( $result ) == 0) {?>	

	<?php echo "<p>Kayıt bulunamadı.</p>"; ?>
  
<?php }?>
	 
		
	
						</div>
						
<script>

$("#searchPayout").change(function()
{
    document.location.href = "https://www.funnyandmoney.com/admin?tab=payouts&searchPayout=" + $(this).val();
});

$("#searchUser").change(function()
{
    document.location.href = "https://www.funnyandmoney.com/admin?tab=payouts&searchUser=" + $(this).val();
});

function getPayout(id){

	var status = $("#status_" + id).val();
	
if(document.getElementById('status_' + id).options.length == 0) {
	
	alert("İşlem seçin");
	
} else {
	
		$.ajax({
		type: 'POST',
		url: "../Controllers/formPosts.php?action=admin",
		data: {tab: "payout", payoutID: id, status: status},
		success: function cevap(e){
			
			if(!(e.indexOf("ok") > -1)){  
				
				alert("Hata: İşlem gerçekleşemedi. Lütfen sorunu teknik departmana bildirin. " + e);  
				
			} else {
			
				alert("Durum güncellendi!");
				location.reload();
				
				
			}
		}
		
		})
}
}


</script>