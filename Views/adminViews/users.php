<?php

	$definitionC = new definitions();
	$resultConDef = $definitionC->getAllDef(13);  

?>
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Üyeler </h3>

											<p style="margin-bottom: 5px;">Üyeleri düzenlemek için üstlerine tıklayın.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">
											<input type="hidden" name="tab" value="users" />
											
											<div class="row">
											
											
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
													
													<select class="form-control input-md" id="country" name="country">
													
															<option disabled>Ülke</option>
															<option value="0">Hepsi</option>
															<?php  while ($row=mysqli_fetch_array($resultConDef)) { ?>
													
															<option value="<?php echo $row['ID']; ?>"><?php echo evalLoc($row['definition']); ?></option>
															
													<?php } ?>



											
							
													</select>
													
													</div>
													
													
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
												

													<div class="form-group">
													
													<select class="form-control input-md" id="age" name="age">
													
															<option disabled>Yaş</option>
															<option value="0">Hepsi</option>
															
															<?php for($i=date("Y")-13;$i>date("Y")-80;$i--){
															echo '<option value="'. $i .'">'.(date("Y") - $i).'</option>'."\n";
														}?>


											
							
													</select>
													
													</div>
													
													
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
												

													<div class="form-group">
													
													<select class="form-control input-md" id="gender" name="gender">
													
															<option disabled>Cinsiyet</option>
															<option value="0">Hepsi</option>
															<option value="2">Kadın</option>
															<option value="77">Erkek</option>



											
							
													</select>
													
													</div>
													
													
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
												

													<div class="form-group">
													
													<select id="status" class="form-control input-md" name="status">
															<option disabled>Durum</option>
															<option value="0">Aktif</option>
															<option value="1">Silinmiş</option>


											
							
													</select>
													
													</div>
													
													
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">  
												

													<div class="form-group">
													
													<select id="sort" class="form-control input-md" name="sort">
															<option disabled>Sırala</option>
															<option value="1">Son üye olanlar</option>
															<option value="2">Önce üye olanlar</option>
															<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>	
															<option value="3">En çok & sahip olanlar</option>  
															<?php } ?>


											
							
													</select>
													
													</div>
													
													
											</div>
											
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
											<th>İsim</th>
											<th>E-posta</th>
											<th>Kullanıcı adı</th>
											<th>Doğum Tarihi</th>
											<th>Cinsiyet</th>
											<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>	
											<th>Bakiye</th>
											<?php } ?>
											<th>Kayıt Tarihi</th>
										</tr>
									</thead>
									<tbody id="tableEnter">
									
									
										
									</tbody>
								</table>	

	
		
		
	
						</div>
						
						
<script>




$("#limit,#search,#country,#age,#gender,#status,#sort").on('change', function() {
	
var limit = $("#limit").val();
var search = $("#search").val();
var country = $("#country").val();
var age = $("#age").val();
var gender = $("#gender").val();
var status = $("#status").val();
var sort = $("#sort").val();

getRowsAuto("users",limit,search,country,age,gender,status,sort);

});

 

function getRowsAuto(table,limit,search,country,age,gender,status,sort) {
		
	
					$.ajax({
						type: 'POST',
						url: "../BL/showAdmin.php",
						data: {tableN: table, limit: limit, search: search, country: country, age: age, gender: gender, status: status, sort: sort},
						success: function cevap(e){
						
								$("#tableEnter").html(e);

						
						}
						});
					
				}


$( document ).ready(function() {
		getRowsAuto("users",10,"",0,0,0,0,1);
	});


</script>