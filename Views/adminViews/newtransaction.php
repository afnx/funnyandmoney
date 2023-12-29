<?php

if(isset($_GET["which"])) {
	
	if(is_numeric($_GET["which"])) {
		
		if($_GET["which"] == 0 or $_GET["which"] == 1 or $_GET["which"] == 2 or $_GET["which"] == 3)
		
		$transac = $_GET["which"];  
		
	} else {
		
		$transac = 0;
		
	}
	
} else {

	$transac = 0;

}

?>


<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Yeni İşlem Gerçekleştir </h3>

											<p style="margin-bottom: 5px;">Yeni bir işlem gerçekleştirin. Hesaplardan para çekin, para yükleyin veya transfer edin.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">  
											<input id="tabput" type="hidden" name="tab" value="newTransaction" />
											<input id="transactionID" type="hidden" name="transactionID" value="<?php echo $transac; ?>" />

						
						<div class="post post-md">   
							<div class="row" style="text-align: center;">  
								<div class="col-md-10">
									<div class="post-header">
										<div class="post-title">
											<h4 style="float: left;">Gerçekleştirilecek İşlemi Seçin</h4>
										
										</div>

									</div>
									<div class="col-lg-8" style="margin-left: -15px;">
												

													<div class="form-group">
													
													<select id="transactionIDa" class="form-control input" name="transactionIDa" onchange="location = this.value;"> 
															<option disabled selected>İşlem Seç</option>
		
															<option value="admin?tab=newtransaction&which=1" <?php if($transac == 1) { ?>selected<?php } ?>>Para Çek</option>
															<option value="admin?tab=newtransaction&which=2" <?php if($transac == 2) { ?>selected<?php } ?>>Para Yatır</option>
															<option value="admin?tab=newtransaction&which=3" <?php if($transac == 3) { ?>selected<?php } ?>>Hesaplar Arası Para Transferi</option>
												
															
							
													</select>
													
													</div>
													
													
											</div>   
							<?php if($transac != 0) { ?>		

							<table class="table"> 
									<thead>
										<tr>
											<th><h3>Seçenekler</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>Miktar:</th>
											<td><input id="amount" type="text" class="form-control" name="amount" /></td>
										</tr>
										<?php if($transac == 3) { ?>
										<tr>
											<th>Para Çekilen Hesap:</th>
											<td>
											
											
											<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"  style="margin-left: -15px;">
												

													<div class="form-group">
													
													<select id="withdrawAcID" class="form-control input" name="withdrawAcID"> 
															<option disabled selected>Hesap Seç</option>
		
															<option value="1">Kar Hesabı</option>
															<option value="2">Ürün Bütçe Hesabı</option>
												
															
							
													</select>
													
													</div>
													
													
											</div>  
											
											</td>
										</tr>
										<tr>
											<th>Para Aktarılan Hesap:</th>
											<td>
											
											
											<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"  style="margin-left: -15px;">
												

													<div class="form-group">
													
													<select id="depositAcID" class="form-control input" name="depositAcID"> 
															<option disabled selected>Hesap Seç</option>
		
															<option value="1">Kar Hesabı</option>
															<option value="2">Ürün Bütçe Hesabı</option>
												
															
							
													</select>
													
													</div>
													
													
											</div>  
											
											
											</td>
										</tr>
										<?php } else { ?>
										
										<tr>
											<th>İşlem Yapılacak Hesap:</th>
											<td>
										<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"  style="margin-left: -15px;">
												

													<div class="form-group">
													
													<select id="accountID" class="form-control input" name="accountID"> 
															<option disabled selected>Hesap Seç</option>
		
															<option value="1">Kar Hesabı</option>
															<option value="2">Ürün Bütçe Hesabı</option>
												
															
							
													</select>
													
													</div>
													
													
											</div>  
											</td>
										</tr>
										<tr>
										
										<?php } ?>
										<tr>
											<th>Para birimi:</th>
											<td>
											
											<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"  style="margin-left: -15px;">
												

													<div class="form-group">
													
													<select id="currency" class="form-control input" name="currency"> 
															<option disabled>Para Birimi Seç</option>
		
															<option value="TRY" selected>TRY(Türk Lirası)</option>  
														
												
															
							
													</select>
													
													</div>
													
													
											</div>  
											
											</td>
										</tr>
										<tr>
											<th>Açıklama:</th>
											<td><textarea id="description" height="300px" type="text" class="form-control" name="description"></textarea></td>
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
							
											
												<button name="change" value="<?php echo $rowID; ?>" type="submit" onClick="this.disabled=true; this.innerHTML='İşlem gerçekleştiriliyor…'; this.form.submit();" class="btn btn-primary">Onayla</button>
												
							</div>
											
												
												
							</div>
												
								</div>
											
											</div>
					
					

				
	<?php } ?>
		

									
										</div>
						</div>
