<?php 

$runsql = new \data\DALProsess ();  

if(isset($_GET['adminID']) && !empty($_GET['adminID'])) {
	
if(is_numeric($_GET['adminID'])) {
	
	$adminCS = admins::checkAdmin ( $_GET['adminID'] );
	if ($adminCS->ID > 0) {
		$adminID = $_GET['adminID'];
	} else {
	
		$adminID = 0;
	
	}
	
} else {

	$adminID = 0;

}

} else {
	
$adminID = 0;
	
}

if(isset($_GET['operation']) && !empty($_GET['operation'])) {
	
	if($_GET['operation']=="1" or $_GET['operation']=="2" or $_GET['operation']=="3") {
	
		$operationS = $_GET['operation'];
	
	} else {
	
		$operationS = 0;
	
	}
	
} else {
	
	$operationS = 0;
	
}

if(isset($_GET['date1']) && !empty($_GET['date1'])) {
	
	$date1 = date('Y-m-d H:i:s', strtotime($_GET['date1']));
	
} else {
	
	$date1 = 0;
	
}

if(isset($_GET['date2']) && !empty($_GET['date2'])) { 
	
	$date2 = date('Y-m-d H:i:s', strtotime($_GET['date2']));   
	
} else {
	
	$date2 = 0;
	
}

if(isset($_GET['limit']) && !empty($_GET['limit'])) {
	
	$limit = $_GET['limit'];
	
} else {
	
	$limit = 100;
	
}

if(isset($_GET['account']) && !empty($_GET['account'])) {
	
	$accountID = $_GET['account'];
	
} else {
	
	$accountID = 0;
	
}

$sql = "SELECT * FROM accountActivities WHERE (adminID='".$adminID."' or '".$adminID."'=0) AND (operation='".$operationS."' or '".$operationS."'=0) AND 
".(($date1!=0 && $date2!=0)?"(date_ BETWEEN '".$date1."' AND '".$date2."')":"0=0")." AND (waccountID='".$accountID."' or daccountID='".$accountID."' or '".$accountID."'=0) 
ORDER BY date_ DESC ".(($limit!=0) ? "LIMIT ".$limit."" : "")."";    
$result = $runsql->executenonquery ( $sql, NULL, true );  

$sqlAd = "SELECT * FROM admins";
$resultAd = $runsql->executenonquery ( $sqlAd, NULL, true );

$sqlAcc = "SELECT * FROM account";
$resultAcc= $runsql->executenonquery ( $sqlAcc, NULL, true );



?>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Hesaplar Hareket Dökümü </h3>

											<p style="margin-bottom: 5px;">Hesaplardaki parasal hareketleri görüntüleyin. Filtreleri kullanabilirsiniz.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">

											
										
											
											<div class="row">
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
													
														<select id="adminIDS" class="form-control input"> 
															<option disabled>Bir Yönetici Seç</option>
															<option value="0">Hepsi</option>
		
														<?php while($row=mysqli_fetch_array($resultAd)) { 
														
															$userAdmin = new users($row["userID"]);
															
														?>
															
															<option value="<?php echo $row["ID"]; ?>" <?php echo (($adminID==$row["ID"]) ? "selected" : "")?>><?php echo $userAdmin->fullName; ?> - ID: <?php echo $row["ID"];?></option>
															
														<?php } ?>	
												
															
							
													</select>
													
													</div>
													
													
											</div>  
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
													
													<select id="operationS" class="form-control input"> 
															<option disabled>Bir işlem seç</option>
															<option value="0">Hepsi</option>
		
												
															<option value="1" <?php echo (($operationS==1) ? "selected" : "")?>>Para Çekimi</option>
															<option value="2" <?php echo (($operationS==2) ? "selected" : "")?>>Para Yatırma</option>
															<option value="3" <?php echo (($operationS==3) ? "selected" : "")?>>Hesaplar Arası Transfer</option>
													
												
															
							
													</select>
													
													
													</div>
													
													
											</div> 
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
													
													<div class="row">
													
													<div class="col-md-6">
													
Tarihten:  
													
														<input type="date" id="date1S">
													
													</div>
													
													<div class="col-md-6">
													
Tarihe kadar:
													
														<input type="date" id="date2S">
													
													</div>
													
													</div>
													
													
													</div>
													
													
											</div>  
											
											
											
											</div>
											
											<div class="row">
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
													
													<select id="accountS" class="form-control input"> 
															<option disabled>Bir hesap seç</option>
															<option value="0">Hepsi</option>
		
												
															<?php while($row=mysqli_fetch_array($resultAcc)) { ?>
															
															<option value="<?php echo $row["ID"]; ?>" <?php echo (($accountID==$row["ID"]) ? "selected" : "")?>><?php echo $row["accountName"]; ?></option>
															
														<?php } ?>	
													
												
															
							
													</select>
													
													
													</div>
													
													
											</div> 
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
													
													<input id="limitS" value="<?php echo $limit; ?>" class="form-control input" /> 
													
													</div>
													
													
											</div> 
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											
												<a href="javascript: submitFilter();" class="btn btn-primary">Sırala</a> 
											
											</div>
											
											
											
											</div>
											
											<div class="row">
											
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											
											<form method="post" action = "Controllers/formPosts.php?action=admin" name="downloadadmin" id="downloadadmin">
											<input type="hidden" name="tab" value="download" />
											<input type="hidden" name="sql" value="<?php echo $sql; ?>" />  
 
		
					<button type="submit" id="export" name="export" class="btn btn-success" data-loading-text="Yükleniyor...">Excel olarak indir</button>
			
		
		</form>
								
											
											</div>
											
											</div>

											
											<table class="table table-hover">
									<thead>
										<tr>
											<th>İşlem</th>
											<th>Para Çekilen Hesap</th>
											<th>Para Yatırılan Hesap</th>
											<th>Miktar</th>
											<th>Para Birimi</th>
											<th>Açıklama</th>
											<th>Yetkili</th>
											<th>Tarih</th>
										</tr>
									</thead>
									<tbody>
									
									<?php while($row=mysqli_fetch_array($result)) { 
									
										if($row["waccountID"]==1) {
											$account = new account(1);
											$waccount = $account->accountName;
										} else if($row["waccountID"]==2) {
											$account = new account(2);
											$waccount = $account->accountName;
										} else {
											$waccount = "Yok";
										}
										if($row["daccountID"]==1) {
											$account2 = new account(1);
											$daccount = $account2->accountName;
										} else if($row["daccountID"]==2) {
											$account2 = new account(2);
											$daccount = $account2->accountName;
										} else {
											$daccount = "Yok";
										}
										
										if($row["operation"]==1) { 
											$operation = "Para Çekimi";
										} else if($row["operation"]==2) {
											$operation = "Para Yatırma";
										} else if($row["operation"]==3) {
											$operation = "Hesaplar Arası Transfer";
										} else {
											$operation = "Yok";
										}
										
										$adminW = new admins($row["adminID"]);
										$userW = new users ($adminW->ID);
									
									?>
									
										<tr>
											<td><?php echo $operation;?></td> 
											<td><?php echo $waccount;?></td>
											<td><?php echo $daccount;?></td>
											<td><?php echo number_format((float)$row["amount"], 2, ',', '.');?></td>
											<td><?php echo $row["currency"];?></td>
											<td><?php echo $row["description"];?></td>
											<td><?php echo $user->fullName;?></td>
											<td><?php echo date("d/m/Y H:i:s", strtotime( $row["date_"] ));?></td>
										</tr> 
									
									<?php } ?>
									
									
										
									</tbody>
								</table>	

	<?php if (mysqli_num_rows( $result ) == 0) {?>	

	<?php echo "<p>Hareket bulunamadı.</p>"; ?>
  
<?php }?>
	 
		
	
						</div> 
						
<script>

var link = "https://www.funnyandmoney.com/admin?tab=accountactivities";   

$("#adminIDS, #operationS, #date1S, #date2S, #accountS, #limitS").change(function()
{
	var admin = $("#adminIDS").val() !==  null ? $("#adminIDS").val() : 0;
	var operation = $("#operationS").val() !== null ? $("#operationS").val() : 0;
	var date1 = $("#date1S").val() !== null ? $("#date1S").val() : 0;
	var date2 = $("#date2S").val() !== null ? $("#date2S").val() : 0;
	var account = $("#accountS").val() !== null ? $("#accountS").val() : 0;
	var limit = $("#limitS").val() !== null ? $("#limitS").val() : 0;
	
    link = "https://www.funnyandmoney.com/admin?tab=accountactivities&adminID=" + admin + "&operation=" + operation + "&date1=" + date1 + "&date2=" + date2 + "&account=" + account + "&limit=" + limit;  
});

function submitFilter() {
    document.location.href = link;
};

</script>