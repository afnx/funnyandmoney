<?php 

$runsql = new \data\DALProsess ();  


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

if($date1 == 0 && $date2 == 0) {
	$limit = 100;
} else {
	$limit = 0;
}


$sql = "SELECT * FROM adminHistory WHERE
".(($date1!=0 && $date2!=0)?"(updated BETWEEN '".$date1."' AND '".$date2."')":"0=0")." ORDER BY updated DESC ".(($limit!=0) ? "LIMIT ".$limit."" : "")."";    
$result = $runsql->executenonquery ( $sql, NULL, true );  



?>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Yetkili Kayıtları </h3>

											<p style="margin-bottom: 5px;">Tüm yetkililerin gerçekleştirdiği işlemleri görebilirsiniz.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">

											
										
											
											<div class="row">
											
											
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
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											
												<a href="javascript: submitFilter();" class="btn btn-primary">Sırala</a> 
											
											</div>
											
											
											</div>
											
											

											
											<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>Tablo</th>
											<th>Tablo ID</th>
											<th>Operasyon</th>
											<th>Yetkili</th>
											<th>Tarih</th>
										</tr>
									</thead>
									<tbody>
									
									<?php while($row=mysqli_fetch_array($result)) { 
									
										$admin = new admins ($row["adminID"]);
										$user = new users ($admin->userID);
									
									?>
									
										<tr>
											<td><?php echo $row["ID"];?></td>
											<td><?php echo $row["tableName"];?></td>
											<td><?php echo $row["tableID"];?></td>
											<td><?php echo $row["operation"];?></td>
											<td><?php echo $user->fullName;?></td>
											<td><?php echo date("d/m/Y H:i:s", strtotime( $row["updated"] ));?></td>
										</tr> 
									
									<?php } ?>
									
									
										
									</tbody>
								</table>	

	<?php if (mysqli_num_rows( $result ) == 0) {?>	

	<?php echo "<p>Kayıt bulunamadı.</p>"; ?>
  
<?php }?>
	 
		
	
						</div> 
						
<script>

var link = "https://www.funnyandmoney.com/admin?tab=adminHistory"; 

$("#date1S, #date2S").change(function()
{
	var date1 = $("#date1S").val() !== null ? $("#date1S").val() : 0;
	var date2 = $("#date2S").val() !== null ? $("#date2S").val() : 0;
	
    link = "https://www.funnyandmoney.com/admin?tab=adminHistory&date1=" + date1 + "&date2=" + date2;  
});

function submitFilter() {
    document.location.href = link;
};

</script>