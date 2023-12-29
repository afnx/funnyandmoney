<?php 

$siteStatus = new adminSettings(1);

?>

<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Ayarlar</h3>

											<p style="margin-bottom: 5px;">Sitenin ayarlarını değiştirin.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">  
										<input id="tabput" type="hidden" name="tab" value="settings" />

		
										<table class="table"> 
									<thead>
										<tr>
											<th><h3>Genel Ayarlar</h3></th> 
										</tr>
									</thead>

									<tbody>
										<tr>
											<th>Site Durumu(ON/OFF)</th>
											<td>
											
											<div class="row">
								
								<div class="checkbox checkbox-icon checkbox-inline checkbox-success">
								<input type="checkbox" id="icon-checkbox1" name="siteStatusC" value="1" <?php if($siteStatus->status == 1) { echo "checked"; } ?>> 
								<label style="font-size: 14px;" for="icon-checkbox1"></label>
							</div>
							
							</div>

											
											</td>
										</tr>

									</tbody>  
								</table>
								
								
								
									<div class="form-group">
							
							<div class="row">
							
		
							
							
							<div class="col-lg-4">
							
							</div>
							
							<div class="col-lg-4">  
											
								<?php if($adminA->ID > 0) { ?>				
												
												
												<button id="deleteSure" type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-sm">Kaydet</button>
				<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title">Kaydetmek için şifrenizi girin.</h4>
							</div>
							<div class="modal-body">
							<div class="form-group input-icon-left">
						<i class="fa fa-lock"></i> <input type="password"
							class="form-control" name="password" id="password"
							placeholder="<?php echo $loc->label("Password");?>"
							maxlength="20" required />
					</div>
							</div>
							<div class="modal-footer">
								<button id="closeButton" type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
								<button id="changeButton" name="change" value="<?php echo $rowID; ?>" type="submit" class="btn btn-primary">Onayla</button>
							</div>
						 </div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
											<?php } ?> 
												
				  
							</div>
							
							<div class="col-lg-4">
							
							</div>
											
												
												
							
												
								</div>
											
											</div>

									
										</div>
						</div>
						
<input id="siteStatus" type="hidden" name="siteStatus" value="<?php echo $siteStatus->status; ?>" />

<script>

$("#icon-checkbox1").change(function() {
	
	if (document.getElementById("icon-checkbox1").checked) {
		$("#siteStatus").val(1);
    } else {
		$("#siteStatus").val(0);
    }

});

</script>