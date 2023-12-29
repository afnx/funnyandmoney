<?php 

   

$sqlTotalUser = "SELECT COUNT(*) FROM users WHERE isDeleted<>1";
$resultTotalUser = $runsql->executenonquery ( $sqlTotalUser, NULL, true );
$rowTotalUser=mysqli_fetch_row($resultTotalUser);
$totalUser = $rowTotalUser[0];

$sqlTotalPost = "SELECT COUNT(*) FROM posts WHERE isDeleted<>1 AND status=1";
$resultTotalPost = $runsql->executenonquery ( $sqlTotalPost, NULL, true );
$rowTotalPost=mysqli_fetch_row($resultTotalPost);
$totalPost = $rowTotalPost[0];

$sqlTotalN = "SELECT SUM(balance) FROM users WHERE isDeleted<>1";
$resultTotalN = $runsql->executenonquery ( $sqlTotalN, NULL, true );
$rowTotalN=mysqli_fetch_row($resultTotalN);
$totalN = $rowTotalN[0];

$sqlTotalAction = "SELECT COUNT(*) FROM balance WHERE actionID=1 or actionID=2 or actionID=3 or actionID=4";
$resultTotalAction = $runsql->executenonquery ( $sqlTotalAction, NULL, true );
$rowTotalAction=mysqli_fetch_row($resultTotalAction);
$totalAction = $rowTotalAction[0];

$sqlTotalRN = "SELECT SUM(point) FROM balance WHERE actionID=5";
$resultTotalRN = $runsql->executenonquery ( $sqlTotalRN, NULL, true );
$rowTotalRN=mysqli_fetch_row($resultTotalRN);
$totalRN = $rowTotalRN[0];

$sqlTotalG = "SELECT COUNT(*) FROM gifts WHERE isDeleted<>1";
$resultTotalG = $runsql->executenonquery ( $sqlTotalG, NULL, true );
$rowTotalG=mysqli_fetch_row($resultTotalG);
$totalG = $rowTotalG[0];

$sqlTotalPG = "SELECT COUNT(*) FROM giftRequests WHERE isDeleted<>1";
$resultTotalPG = $runsql->executenonquery ( $sqlTotalPG, NULL, true );
$rowTotalPG=mysqli_fetch_row($resultTotalPG);
$totalPG = $rowTotalPG[0];

$sqlWG = "SELECT COUNT(*) FROM giftRequests WHERE deliveryStatus=2 AND isDeleted<>1";
$resultWG = $runsql->executenonquery ( $sqlWG, NULL, true );
$rowWG=mysqli_fetch_row($resultWG);
$WG = $rowWG[0];

$sqlUFe = "SELECT COUNT(*) FROM users WHERE gender=2 AND isDeleted<>1";
$resultUFe = $runsql->executenonquery ( $sqlUFe, NULL, true );
$rowUFe=mysqli_fetch_row($resultUFe);
$femaleUsers = $rowUFe[0];

$sqlUM = "SELECT COUNT(*) FROM users WHERE gender=77 AND isDeleted<>1";
$resultUM = $runsql->executenonquery ( $sqlUM, NULL, true );
$rowUM=mysqli_fetch_row($resultUM);
$maleUsers = $rowUM[0];

$percentF = $femaleUsers/($femaleUsers+$maleUsers);
$percentFemale = number_format( $percentF * 100, 2 ) . '%';

$percentM = $maleUsers/($femaleUsers+$maleUsers);
$percentMale = number_format( $percentM * 100, 2 ) . '%';

$sqlCode = "
SELECT COUNT(*)
FROM(
SELECT giftID, COUNT(*) AS cnt
FROM digitalGiftCodes
WHERE isUsed<>1 AND giftID IN( SELECT ID FROM gifts WHERE isDigital=1 AND isDeleted<>1)
GROUP BY giftID
HAVING cnt < 5
)A;
";
$resultCode = $runsql->executenonquery ( $sqlCode, NULL, true );
$rowCode=mysqli_fetch_row($resultCode);
$code = $rowCode[0];


$sqlCodeN = "

SELECT
  digitalGiftCodes.*
FROM   
  digitalGiftCodes
    CROSS JOIN 
      (
SELECT giftID AS IDas, COUNT(*) AS c
FROM digitalGiftCodes
WHERE isUsed<>1 AND giftID IN( SELECT ID FROM gifts WHERE isDigital=1 AND isDeleted<>1)
GROUP BY giftID
HAVING c < 5 OR c=0
      ) AS init
WHERE
  giftID=IDas;

";
$resultCodeN = $runsql->executenonquery ( $sqlCodeN, NULL, true );


$sqlCodeN2 = "



SELECT
  digitalGiftCodes.*
FROM   
  digitalGiftCodes
    CROSS JOIN 
      (
SELECT giftID AS IDas, COUNT(*) AS c
FROM digitalGiftCodes
WHERE isUsed<>1 AND giftID IN( SELECT ID FROM gifts WHERE isDigital=1 AND isDeleted<>1)
GROUP BY giftID
HAVING c < 5 OR c=0
      ) AS init
WHERE
  giftID=IDas;  
";
$resultCodeN2 = $runsql->executenonquery ( $sqlCodeN2, NULL, true );

$array = array();
$array2 = array();

$admin = new admins($_SESSION['adminID']);  

?>

<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Dashboard </h3>

											<p style="margin-bottom: 5px;">Sitenin son durumunu görüntüleyin.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">  
											<input type="hidden" name="tab" value="dashboard" />
											
											<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>
											
											<?php if($WG > 0) { ?>
											
											<div class="alert alert-danger"> 
									<strong>UYARI!</strong> Kargoya verilmeyi bekleyen <strong stlye="color: red;"><?php echo $WG; ?></strong> tane sipariş var. <a href="admin?tab=orders" class="alert-link">Düzenlemek için tıklayın.</a>
								</div>

											<?php } ?>  
											
											<?php if($code > 0) { ?>
										
											<div class="alert alert-danger"> 
									<strong>UYARI!</strong> Dijital kodları tükenmek üzere olan <strong stlye="color: red;"><?php echo $code; ?></strong> tane ürün var. <a href="admin?tab=codes" class="alert-link">Detaylı görüntülemek için tıklayın.</a>
									
								
									<br/>
									<strong>Ürünler(ID-İsim)</strong>
									
									<br/>
									
									<?php while($row=mysqli_fetch_array($resultCodeN)) {  
								
									$giftC = new gifts($row["giftID"]);
									
									if(!in_array($row["giftID"],$array)) {
								
								?>

								<?php echo $giftC->ID; ?> - <?php echo $giftC->name; ?> <br/>  
								
								<?php } 
								
									array_push($array, $row["giftID"]); 
								
								?>  
								
								<?php } ?>  
								
								<br/>
									<strong>Tükenen Ürünler(ID-İsim)</strong>
									
									<br/>
								
								<?php while($row2=mysqli_fetch_array($resultCodeN2)) {  
									
									if($row["c"] == 0) {
								
										$giftC2 = new gifts($row2["giftID"]);
									
										if(!in_array($row2["giftID"],$array2)) {
								
								?>

									<?php echo $giftC2->ID; ?> - <?php echo $giftC2->name; ?> <br/>  
								
									<?php } 
								
										array_push($array2, $row2["giftID"]); 
										
									}
								
								?>  
								
								<?php } ?>  
									
								</div>
								
											<?php } ?>  
											
											<?php } ?>  
											
											<div class="form-group">
											
												<h4>Notlar </h3>
											
											</div>

											<div class="form-group">  
											
											 <textarea style="height: 300px;" id="adminNotes" class="form-control" name="adminNotes" type="text"><?php if(isset($admin->adminNotes)) { echo $admin->adminNotes; }?></textarea>
											
											
											</div>     
											
											<div class="form-group">
											
												<input id="noteButton" style="width: 50%;" type="submit" class="btn btn-primary" value="Kaydet">
											
											</div>
											
											<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>

												<div class="row margin-bottom-30">
			
			<div class="col-lg-6 text-center margin-top-30">
			
			<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Toplam Üye Sayısı</h3>
							</div>
							<div class="panel-body">
								<h1><?php echo $totalUser; ?></h1>
							</div>
						</div>

				
			</div>
			
			
			<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Yayındaki Toplam Gönderi Sayısı</h3>
							</div>
							<div class="panel-body">
								<h1><?php echo $totalPost; ?></h1>
							</div>
						</div>

			
			
			</div>
			
			<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Site İçerisindeki Toplam & Miktarı</h3>
							</div>
							<div class="panel-body">
								<h1><?php echo floor($totalN); ?></h1>
							</div>
						</div>

			
			
			</div>  
			
			
			<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Toplam Aksiyon Sayısı(Referans Olmadan)</h3>
							</div>
							<div class="panel-body">
								<h1><?php echo $totalAction; ?></h1>
							</div>
						</div>

			
			
			</div>
			
			<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Referans İle Verilen Toplam & Miktarı</h3>
							</div>
							<div class="panel-body">
								<h1><?php echo floor($totalRN); ?></h1>
							</div>
						</div>

			
			
			</div>
			
			
			<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Toplam Hediye Sayısı</h3>
							</div>
							<div class="panel-body">
								<h1><?php echo $totalG; ?></h1>
							</div>
						</div>

			
			
			</div>
			
			<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Toplam Satılan Hediye Sayısı</h3>
							</div>
							<div class="panel-body">
								<h1><?php echo $totalPG; ?></h1>
							</div>
						</div>

			
			
			</div>
				
			<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Kadın Kullanıcı Yüzdesi</h3>
							</div>
							<div class="panel-body">
								<h1><?php echo $percentFemale; ?></h1>
							</div>
						</div>

			
			
			</div>
			
			<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Erkek Kullanıcı Yüzdesi</h3>
							</div>
							<div class="panel-body">
								<h1><?php echo $percentMale; ?></h1>
							</div>
						</div>

			
			
			</div>
				
				
		</div> 
		
		<?php } ?>   
		

									
										</div>
						</div>
						
<script>

$("#adminNotes").on('change', function() {
	
 document.getElementById("noteButton").click();

});

</script>