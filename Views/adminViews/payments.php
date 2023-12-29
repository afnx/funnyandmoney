<?php 

$runsql = new \data\DALProsess ();  

if(isset($_GET['searchSale'])) {
	
	$searchSale = $_GET['searchSale'];
	
} else {
	
	$searchSale = "";
	
}

if(isset($_GET['searchUser'])) {
	
	$searchUser = $_GET['searchUser'];
	
} else {
	
	$searchUser = "";
	
}


$sql = "
SELECT * FROM payments " .
(($searchSale == "" && $searchUser != "") ? "INNER JOIN users ON (fullName LIKE '%".$searchUser."%' or username LIKE '%".$searchUser."%' or email LIKE '%".$searchUser."%')" : "")
. " WHERE " .
(($searchSale == "" && $searchUser != "") ? "users.ID=payments.userID" : "(salesNo LIKE '%".$searchSale."%')") 
. " AND payments.isDeleted<>1 ORDER BY date_ DESC";
$result = $runsql->executenonquery ( $sql, NULL, true );



?>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Gelen Ödemeler </h3>

											<p style="margin-bottom: 5px;">Gelen ödemeleri listeleyin.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">
											
										
											
											<div class="row">
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<input id="searchSale" type="text" class="form-control" placeholder="İşlem numarası ara" />
													
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
											<th>E-mail</th>
											<th>Ürün(ID)</th>
											<th>Method</th>
											<th>Tutar</th>
											<th>Tarih</th>
											<th>Durum</th>
											<th>Aksiyon</th>
										</tr>
									</thead>
									<tbody>
									
									<?php while($row=mysqli_fetch_array($result)) { 
										
										
										$product = new products($row["productID"]);
										$user = new users($row["userID"]);
							
										if($row["method"]=="bankTransfer"){
											
											$bank = new banks($row["bankID"]);
											
										}
									
									?>
									
										<tr id="tableRow_<?php echo $row["ID"];?>">
										<td><?php echo $row["ID"];?></td> 
											<td><?php echo $row["salesNo"];?></td> 
											<td><?php echo $user->fullName . "(" . $user->ID . ")";?></td>  
											<td><?php echo $user->email;?></td>    
											<td><?php echo $product->productName. "(" . $product->ID . ")";?></td>  
											<td><?php if($row["method"]=="bankTransfer"){echo $bank->name . "(" . $bank->ID . ")";}else if($row["method"]=="widget"){echo "Paymentwall";};?></td>  
											<td><?php echo $row["amount"] . " " . $row["currency"];?></td> 
											<td><?php echo $row["date_"];?></td> 
											<td><?php if($row["reason"]==0){echo "<b style='color: orange;'>Bekliyor</b>";}else if($row["reason"]==1){echo "<b style='color: green;'>Ödendi</b>";};?></td>  
											<td><?php if($row["method"]=="bankTransfer" && $row["reason"]==0){?><button style="margin-right: 15px; margin-bottom: 5px;" id="paid" type="button" class="btn btn-success" data-toggle="modal" data-target=".bs-modal-sm-paid<?php echo $row["ID"];?>">Onayla</button><?php } ?><button style="margin-right: 15px;" class="btn btn-danger"data-toggle="modal" data-target=".bs-modal-sm-delete<?php echo $row["ID"];?>">Sil</button></td>
				
		<?php if($row["method"]=="bankTransfer"){ ?>
				<div class="modal fade bs-modal-sm-paid<?php echo $row["ID"];?>" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title">Ödemeyi Onayla</h4>
							</div>  
							<div class="modal-body">
								<p><b>Ödemenin yapıldığını onaylıyor musunuz?</b></p>
							</div>
							<div class="modal-footer">
								<a href="javascript: getpaid(<?php echo $row["ID"];?>);"><button type="button" class="btn btn-success">Evet</button></a>
								<button id="closeButton1" type="button" class="btn btn-warning" data-dismiss="modal">Hayır</button>
							</div>
						 </div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
		<?php } ?>	
				
				<div class="modal fade bs-modal-sm-delete<?php echo $row["ID"];?>" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title">Sil</h4>
							</div>  
							<div class="modal-body">
								<p><b>Bu ödemeyi silmek istediğinizden emin misiniz?</b></p>
							</div>
							<div class="modal-footer">
								<a href="javascript: deleteP(<?php echo $row["ID"];?>);"><button type="button" class="btn btn-primary">Evet</button></a>
								<button id="closeButton2" type="button" class="btn btn-warning" data-dismiss="modal">Hayır</button>
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

$("#searchSale").change(function()
{
    document.location.href = "https://www.funnyandmoney.com/admin?tab=payments&searchSale=" + $(this).val();
});

$("#searchUser").change(function()
{
    document.location.href = "https://www.funnyandmoney.com/admin?tab=payments&searchUser=" + $(this).val();
});

function getpaid(id){

		$.ajax({
		type: 'POST',
		url: "../Controllers/formPosts.php?action=admin",
		data: {tab: "payment", paymentID: id},
		success: function cevap(e){
			
			if(!(e.indexOf("ok") > -1)){  
				
				alert("Hata: Onaylama işlemi gerçekleşemedi. Lütfen sorunu teknik departmana bildirin.");  
				
			} else {
			
				alert("Ödeme onaylandı!");
				location.reload();
				
				
			}
		}
		})
}

function deleteP(id){

		$.ajax({
		type: 'POST',
		url: "../Controllers/formPosts.php?action=admin",
		data: {tab: "deletePayment", deleteID: id},
		success: function cevap(e){
			
			if(!(e.indexOf("ok") > -1)){  
				
				alert("Hata: Silme işlemi gerçekleştirilemedi. Lütfen sorunu teknik departmana bildirin.");  
				
			} else {

				alert("Odeme silindi!");
				location.reload();
			
				
				
			}
		}
		})
}
</script>