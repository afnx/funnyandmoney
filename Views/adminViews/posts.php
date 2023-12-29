<?php 

$runsql = new \data\DALProsess ();  


$result = $user->getAllUsers();


?>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Gönderiler </h3>

											<p style="margin-bottom: 5px;">Aşağıdaki filtreler ile istediğiniz özellikte gönderileri sıralayabilirsiniz. Gönderileri düzenlemek için üstlerine tıklayın.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">
											<input type="hidden" name="tab" value="posts" />
											
											<div class="row">
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
												

													<div class="form-group">
											
													<select id="user" class="chosen-select" name="user">
															<option disabled>Üye</option>  
															<option value="0">Hepsi</option>
													
													<?php  while ($row=mysqli_fetch_array($result)) { ?>
													
															<option value="<?php echo $row['ID']; ?>"><?php echo $row['fullName'] . " - " . $row['ID']; ?></option>
															
													<?php } ?>
															
													</select>
										
											
											</div>
											
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
												

													<div class="form-group">
													
													<select id="platform" class="form-control input-md" name="platform">
													
															<option disabled>Sosyal Ağ</option>
															<option value="0">Hepsi</option>
															<option value="1">Facebook</option>
															<option value="2">Twitter</option>
															<option value="4">Youtube</option>


											
							
													</select>
													
													</div>
													
													
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
												

													<div class="form-group">
													
													<select class="form-control input-md" id="limit" name="limit">
													
															<option disabled>Gösterilecek Sayı</option>
															<option value="10">10</option>
															<option value="30">30</option>
															<option value="50">50</option>
															<option value="100">100</option>
															<option value="0">Hepsini Göster</option>


											
							
													</select>
													
													</div>
													
													
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
												

													<div class="form-group">
													
													<select id="status" class="form-control input-md" name="status">
															<option disabled>Durum</option>
															<option value="1">Yayındakiler</option>
															<option value="0">Tamamlananlar</option>
															<option value="2">Askıya Alınanlar</option>
															<option value="3">Engellenenler</option>


											
							
													</select>
													
													</div>
													
													
											</div>
											</div>
											
											<div class="row">
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<input id="search" name="search" type="text" class="form-control" placeholder="Ara" />
													
													</div>
													
													
											</div>  
											
											
											
											</div>
											
											<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>Fotoğraf</th>
											<th>Başlık</th>
											<th>Açıklama</th>
											<th>Platform</th>
											<th>Eklenme Tarihi</th>
											<th>Gönderi Sahibi</th>
										</tr>
									</thead>
									<tbody id="tableEnter">
									
									
										
									</tbody>
								</table>	

	
		
		
	
						</div>
						
						
<script>




$("#user,#limit,#search,#platform,#status").on('change', function() {
	
var user = $("#user").val();
var limit = $("#limit").val();
var search = $("#search").val();
var platform = $("#platform").val();
var status = $("#status").val();

getRowsAuto("posts",user,limit,search,platform,status);

});

 

function getRowsAuto(table,user,limit,search,platform,status) {
		
	
					$.ajax({
						type: 'POST',
						url: "../BL/showAdmin.php",
						data: {tableN: table, userID: user, limit: limit, search: search, platformID: platform, status: status},
						success: function cevap(e){
						
								$("#tableEnter").html(e);

						
						}
						});
					
				}


$( document ).ready(function() {
		getRowsAuto("posts",0,10,"",0);
	});


</script>