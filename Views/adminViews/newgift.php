<?php
	
	$definitionC = new definitions();
	$resultConDef = $definitionC->getAllDef(28); 

?>


<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Yeni Ürün Ekle </h3>

											<p style="margin-bottom: 5px;">Yeni ürün ekleyin.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">  
											<input id="tabput" type="hidden" name="tab" value="newGift" />
											<input type="hidden" id="availableZone" name="availableZone" />
									
											
			

						
						<div class="post post-md">  
							<div class="row" style="text-align: center;">  
								<div class="col-md-10">
									<div class="post-header">
										<div class="post-title">
											<h4><input id="giftName" type="text" maxlength="60" class="form-control" name="giftName" placeholder="Ürün ismini girin" /></h4>
											<ul class="post-meta">
										
											</ul>
										</div>

									</div>
									<p><textarea id="description" type="text" class="form-control" name="description" placeholder="Ürün ile ilgili açıklama girin"></textarea> </p>  
									

							<table class="table"> 
									<thead>
										<tr>
											<th><h3>Detaylar</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>Ücret(&):</th>
											<td><input id="price" type="text" class="form-control" name="price" /> </td>
										</tr>
										<tr>
											<th>Kategori:</th>
											<td>
											
											<div class="form-group">
													
													<select id="category" class="form-control input-md" name="category">
															<option disabled>Kategori Seç</option>
															<?php while($row=mysqli_fetch_array($resultConDef)) { ?>
															<option value="<?php echo $row['ID']; ?>"><?php echo evalLoc($row['definition']); ?></option>
															<?php } ?>
													</select>
													
											</div>
											
											</td>
										</tr>
										<tr>
											<th>Miktar:</th>
											<td><input id="quantity" type="text" class="form-control" name="quantity" /></td>
										</tr>
										<tr> 
											<th>Tedarikçi:</th>
											<td><input id="provider" type="text" class="form-control" name="provider" /></td> 
										</tr>
										<tr> 
											<th>Özellikli:</th>
											<td>
											
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;">
		
					
							<div class="radio radio-inline" style="padding-left: 0px;">
								<input type="radio" name="isFeatured" value="1" id="inline-radio1"> 
								<label for="inline-radio1">Evet</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="isFeatured" value="0" id="inline-radio2" checked> 
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
								<input type="radio" name="digital" value="1" id="inline-radio3"> 
								<label for="inline-radio3">Evet</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="digital" value="0" id="inline-radio4" checked> 
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
								<input type="radio" name="deliverySpeed" value="1" id="inline-radio8" checked> 
								<label for="inline-radio8">Kargo</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="deliverySpeed" value="0" id="inline-radio9"> 
								<label for="inline-radio9">Anında Teslim</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="deliverySpeed" value="2" id="inline-radio17"> 
								<label for="inline-radio17">E-posta Teslimatı</label>
							</div>
					
					
					</div>
											</td>  
										</tr> 
										<tr> 
											<th>Dağıtım Alanı:</th>
											<td><div class="form-group">
													
													<select id="zone" class="form-control input-md" name="zone">
															<option disabled>Dağıtım alanını seç</option>
															<option value="0">Uluslararası</option>
															<option value="1">Türkiye</option>
															<option value="2">Yurtdışı</option>  

															
													</select>
													
													</div></td> 
										</tr>
										
										<tr> 
											<th>Durum:</th>
											<td>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;">
		
					
							<div class="radio radio-inline" style="padding-left: 0px;">
								<input type="radio" name="status" value="1" id="inline-radio5" checked> 
								<label for="inline-radio5">Yayında</label>
							</div>
							<div class="radio radio-inline">
								<input type="radio" name="status" value="0" id="inline-radio6"> 
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

							
							
							<div class="col-lg-12 text-center">
							
						
								
											
												<button id="changeButton" name="change" type="submit" class="btn btn-primary">Ekle</button>
										  
						
											
												
												
							</div>
												
								</div>
											
											</div>
							
					

				
	
		

									
										</div>
						</div>
						
						
<script>  
 

$("#zone").change(function(){
	
	document.getElementById("availableZone").value = $("#zone").val();  ;

	
});

$("input[name=deliverySpeed]:radio,input[name=digital]:radio").change(function(){
	
	if(document.getElementById('inline-radio9').checked || document.getElementById('inline-radio17').checked) {
		
		document.getElementById("inline-radio3").checked = true;
		
	} else if(document.getElementById('inline-radio8').checked) {
		
		document.getElementById("inline-radio4").checked = true;
		
	} 
	
});

</script>