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
		
	}
	

} else {

	$fn = new functions();    
	$fn->redirect("404");  

}

} 

$sql = "SELECT * FROM gifts WHERE isDigital=1 AND isDeleted<>1";
$result = $runsql->executenonquery ( $sql, NULL, true );
$gift = new gifts($giftID);

?>


<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Yeni Kod Oluştur </h3>

											<p style="margin-bottom: 5px;">Yeni kod oluşturun.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">  
											<input id="tabput" type="hidden" name="tab" value="newCode" />
											<input type="hidden" id="giftID" name="giftID" value="<?php echo $giftID; ?>" />
											<input type="hidden" id="day" name="day" value="1" />
											<input type="hidden" id="month" name="month" value="1" />
											<input type="hidden" id="year" name="year" value="<?php echo date('Y'); ?>" />

											
			

												<div class="form-group">
													
													<select id="postchoose" class="chosen-select" name="postchoose" onchange="location = this.value;"> 
															<option disabled selected>Ürün Seç</option>
															
													<?php while($row=mysqli_fetch_array($result)) { ?>
															
															<option value="admin?tab=newcode&giftID=<?php echo $row['ID']; ?>" <?php if($giftID == $row['ID']) { ?>selected<?php } ?>><?php echo $row['name']; ?> - ID: <?php echo $row['ID']; ?></option>
															
													<?php } ?>
															
							
													</select>
													
													</div>
													
													<br/>
											
							
							<?php if(isset($giftID) && !empty($giftID) && $giftID != "") { ?>
						
						<div class="post post-md">  
							<div class="row" style="text-align: center;">  
								<div class="col-md-10">
									<div class="post-header">
										<div class="post-title">
											<h4 style="float: left;"><?php echo $gift->name; ?> ürünü için dijital kod oluşturun</h4>
											<ul class="post-meta">
										
											</ul>
										</div>

									</div>
									<p><textarea id="description" type="text" class="form-control" name="description">Kod ile ilgili açıklama girin</textarea> </p>  
									

							<table class="table"> 
									<thead>
										<tr>
											<th><h3>Detaylar</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>Kod:</th>
											<td><input maxlength="45" id="code" type="text" class="form-control" name="code" /></td>
										</tr>
										<tr> 
											<th>Son Kullanma Tarihi:</th>
											<td>
											
											<div class="row">
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												

													<div class="form-group">
														
												
												
												<select id="dayS" name="dayS" class="form-control input-md">

													<option disabled>Gün</option>
										
														

														<?php for($i=1;$i<32;$i++){
															echo '<option value="'. $i .'">'.$i.'</option>'."\n";
														}?>
														
											

															
												</select>

												
												
										</div>
										
										</div>
											
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
												
										<div class="form-group">
													
											<select id="monthS" name="monthS" class="form-control input-md">

										
														
													<option disabled>Ay</option>
										
													
														<?php for($i=1;$i<13;$i++){
															echo '<option value="'. $i .'">'.$i.'</option>'."\n";
														}?>
															
												

															
											</select>

													 
										</div>
										
										</div>

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
										<div class="form-group">
						
											<select id="yearS" name="yearS" class="form-control input-md">

											
													<option disabled>Yıl</option>
										
													
														<?php for($i=date("Y");$i<date("Y")+80;$i++){
															echo '<option value="'. $i .'">'.$i.'</option>'."\n";
														}?>
												
											</select>	
													
										</div>
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
							
							<div class="col-lg-12">  
							
											
												<button name="change" value="<?php echo $rowID; ?>" type="submit" class="btn btn-primary">Oluştur</button>
												
							</div>
											
												
												
							</div>
												
								</div>
											
											</div>
											
											<?php } ?>
							
					

				
	
		

									
										</div>
						</div>
						
<script>

$("#dayS").change(function(){
	document.getElementById("day").value = $("#dayS").val();
});

$("#monthS").change(function(){
	document.getElementById("month").value = $("#monthS").val();
});

$("#yearS").change(function(){
	document.getElementById("year").value = $("#yearS").val();
});

</script>
						