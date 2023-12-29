<?php 

$runsql = new \data\DALProsess ();  



if(isset($_GET['userID'])) {
	
if(is_numeric($_GET['userID'])) {
	
	$userC = users::checkUserWithID ( $_GET['userID'] );
	
	if($userC->ID > 0) {

		$userID = $_GET['userID'];
		
	} else {
		
		$userID = 0;
		
	}
	
} else {

	$fn = new functions();  
	$fn->redirect("404"); 

}

$userID = 0;

} else {
	
$userID = 0;
	
}

if(isset($_GET['searchUser'])) {
	
$searchType = 1;
	
} else if(isset($_GET['searchIP'])) {
	
$searchType = 2;
	
} else {
	
$searchType = 0;
	
}

switch($searchType) {
			
	case 0 :
		$str= "0=0";
		break;
				
	case 1 :
		$str= "(userID LIKE '" . $_GET['searchUser'] . "')";
		break;
				
	case 2 :
		$str= "(address LIKE '" . $_GET['searchIP'] . "')";
		break;
	default:
		$str= "0=0";
		break;
			
}

$sql = "SELECT * FROM IPlogs WHERE " . (($userID == 0) ? "0=0 " : "userID='". $userID . "'" ) . " AND " . $str . " ORDER BY date_ DESC LIMIT 1000";  
$result = $runsql->executenonquery ( $sql, NULL, true );



?>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>IP Logs </h3>

											<p style="margin-bottom: 5px;">IP adreslerini görüntüleyin. Üyeleri arayın.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">
											
										
											
											<div class="row">
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<input id="searchIp" type="text" class="form-control" placeholder="IP adresi ara" />
													
													</div>
													
													
											</div>  
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<input id="searchUser" type="text" class="form-control" placeholder="Üye ID ara" />
													
													</div>
													
													
											</div> 
											
											</div>

											
											<table class="table table-hover">
									<thead>
										<tr>
											<th>IP Adresi</th>
											<th>Tarih</th>
											<th>Kullanıcı ID</th>
											<th>Kullanıcı adı</th>
											<th>İsim</th>
											<th>Üyelik Tarihi</th>
										</tr>
									</thead>
									<tbody>
									
									<?php while($row=mysqli_fetch_array($result)) { 
									
										$userIPID = $row["userID"];
										$userIP = new users($userIPID);
									
									?>
									
										<tr>
											<td><?php echo $row["address"];?></td> 
											<td><?php echo $row["date_"];?></td>
											<td><?php echo $row["userID"];?></td>
											<td><?php echo $userIP->username;?></td>
											<td><?php echo $userIP->fullName;?></td>
											<td><?php echo $userIP->registerdate_;?></td>
										</tr> 
									
									<?php } ?>
									
									
										
									</tbody>
								</table>	

	<?php if (mysqli_num_rows( $result ) == 0) {?>	

	<?php echo "<p>Kayıt bulunamadı.</p>"; ?>
  
<?php }?>
	 
		
	
						</div>
						
<script>

$("#searchUser").change(function()
{
    document.location.href = "https://www.funnyandmoney.com/admin?tab=iplogs&searchUser=" + $(this).val();
});

$("#searchIp").change(function()
{
    document.location.href = "https://www.funnyandmoney.com/admin?tab=iplogs&searchIP=" + $(this).val();
});

</script>