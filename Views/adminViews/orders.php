<?php 

$runsql = new \data\DALProsess ();  

$result = $user->getAllUsers();

$sql = "SELECT * FROM gifts WHERE isDeleted<>1";  
$result2 = $runsql->executenonquery ( $sql, NULL, true );

?>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Siparişler </h3>

											<p style="margin-bottom: 5px;">Siparişleri filtreler ile sıralayın.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">
										
										<div id="filter">
	
											
											<div class="row">
			
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												

													<div class="form-group">
													
													<select class="form-control input-md" id="limit" name="limit">
													
															<option disabled>Gösterilecek Sayı</option>
															<option value="30">30</option>
															<option value="50">50</option>
															<option value="100">100</option>
															<option value="150">150</option>
															<option value="0">Hepsini Göster</option>


											
							
													</select>
													
													</div>
													
													
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

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
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												

													<div class="form-group">
													
													<select id="product" class="chosen-select" name="product">
															<option disabled>Ürün</option>
															<option value="0">Hepsi</option>
															
															<?php  while ($row=mysqli_fetch_array($result2)) { ?>
													
															<option value="<?php echo $row['ID']; ?>"><?php echo $row['name'] . " - " . $row['ID']; ?></option>
															
													<?php } ?>


											
							
													</select>
													
													</div>
													
													
											</div>
											
											
											
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												

													<div class="form-group">
													
													<select id="delivery" class="form-control input-md" name="delivery">
															<option disabled>Durum</option>
															<option value="4">Hepsi</option>
															<option value="0">Teslim Edilmiş</option>
															<option value="1">Kargolanmış</option>
															<option value="2">Bekliyor</option>
															<option value="3">İptal Edilmiş</option>

															
													</select>
													
													</div>
													
													
											</div>
											
											
											</div>
											
											<div class="row">
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">
													
													<input id="search" name="search" type="text" class="form-control" placeholder="Şipariş Numarası ile Ara" />  
													
													</div>
													
													
											</div>  
											
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												

													<div class="form-group">  
													
													<input id="searchCargoNo" name="searchCargoNo" type="text" class="form-control" placeholder="Kargo veya Tedarikçi Takip Numarası ile Ara" />  
													
													</div>
													
													
											</div> 
											
											
											
											</div>
											
											</div>
											
											<table class="table table-hover">
									<thead>
										<tr>
											<th>Sipariş Numarası</th>
											<th>Ürün ID</th>
											<th>Ürün İsmi</th>
											<th>Dijital</th>
											<th>Ücret(&)</th>
											<th>Kullanıcı ID</th>
											<th>Kullanıcı İsmi</th>
											<th>Tarih</th>
											<th>Durum</th>
										</tr>
									</thead>
									<tbody id="tableEnter">
									
									
										
									</tbody>
								</table>	

	
		
		
	
						</div>   
						
						
<script>




$("#user,#limit,#search,#searchCargoNo,#product,#delivery").on('change', function() {  
	
var user = $("#user").val();
var limit = $("#limit").val();
var search = $("#search").val();
var searchCargoNo = $("#searchCargoNo").val();
var product = $("#product").val();
var delivery = $("#delivery").val();

getRowsAuto("giftsRe",user,limit,search,searchCargoNo,product,delivery);

});

 

function getRowsAuto(table,user,limit,search,searchCargoNo,product,delivery) {
		
	
					$.ajax({
						type: 'POST',
						url: "../BL/showAdmin.php",
						data: {tableN: table, user: user, limit: limit, search: search, searchCargoNo: searchCargoNo, product: product, delivery: delivery},
						success: function cevap(e){
						
								$("#tableEnter").html(e);  

						
						}
						});
					
				}


$( document ).ready(function() {
		getRowsAuto("giftsRe",0,30,"","",0,4);
	});


</script>