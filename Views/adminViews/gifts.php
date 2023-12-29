<?php 

$runsql = new \data\DALProsess ();  


$sql = "SELECT provider FROM gifts WHERE isDeleted<>1";  
$result = $runsql->executenonquery ( $sql, NULL, true );

$array = array();

$definitionC = new definitions();
$resultConDef = $definitionC->getAllDef(28); 

?>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Ürünler </h3>

											<p style="margin-bottom: 5px;">Ürünleri filtreler ile sıralayın.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">
											
											
											<div class="row">
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
											
													<select id="provider" class="chosen-select" name="provider">
															<option disabled>Tedarikçi</option>  
															<option value="0">Hepsi</option>
													
													<?php  while ($row=mysqli_fetch_array($result)) { ?>
															
															<?php if(!in_array($row["provider"],$array)) { ?>
													
																<option value="<?php echo $row['provider']; ?>"><?php echo $row['provider']; ?></option>
																
																<?php array_push($array, $row["provider"]); ?>
																
															<?php } ?> 
															
													<?php } ?>
															
													</select>
										
											
											</div>
											
											</div>
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
													
													<select id="category" class="chosen-select" name="category">
													
															<option disabled>Kategori</option>
															<option value="0">Hepsi</option>
														
														<?php  while ($row=mysqli_fetch_array($resultConDef)) { ?>
															
															<option value="<?php echo evalLoc($row['ID']); ?>"><?php echo evalLoc($row['definition']); ?></option>
															
														<?php } ?>


											
							
													</select>
													
													</div>
													
													
											</div>
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

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
											
										</div>
										
										<div class="row">
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
													
													<select id="digital" class="form-control input-md" name="digital">
															<option disabled>Dijital ürün mü?</option>
															<option value="3">Hepsi</option>
															<option value="1">Evet</option>
															<option value="0">Hayır</option>


											
							
													</select>
													
													</div>
													
													
											</div>
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
													
													<select id="zone" class="form-control input-md" name="zone">
															<option disabled>Dağıtım Alanı</option>
															<option value="3">Hepsi</option>
															<option value="0">Uluslararası</option>
															<option value="1">Türkiye</option>
															<option value="2">Yurtdışı</option>

															
													</select>
													
													</div>
													
													
											</div>
											
											
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
												

													<div class="form-group">
													
													<select id="sort" class="form-control input-md" name="sort">
															<option value="0">En son eklenenler</option>
															<option value="1">En çok satılanlar</option>
															<option value="2">En pahalı olanlar</option>

															
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
											<th>Ürün İsmi</th>
											<th>Açıklama</th>
											<th>Ücret(&)</th>
											<th>Kategori</th>
											<th>Miktar</th>
											<th>Satış Sayısı</th>
											<th>Tedarikçi</th>
											<th>Dijital</th>
											<th>Dağıtım Alanı</th>
											<th>Eklenme Tarihi</th>
										</tr>
									</thead>
									<tbody id="tableEnter">
									
									
										
									</tbody>
								</table>	

	
		
		
	
						</div>
						
						
<script>




$("#provider,#limit,#search,#category,#digital,#zone,#sort").on('change', function() {
	
var provider = $("#provider").val();
var limit = $("#limit").val();
var search = $("#search").val();
var category = $("#category").val();
var digital = $("#digital").val();
var zone = $("#zone").val();
var sort = $("#sort").val();

getRowsAuto("gifts",provider,limit,search,category,digital,zone,sort);

});

 

function getRowsAuto(table,provider,limit,search,category,digital,zone,sort) {
		
	
					$.ajax({
						type: 'POST',
						url: "../BL/showAdmin.php",
						data: {tableN: table, provider: provider, limit: limit, search: search, category: category, digital: digital, zone: zone, sort: sort},
						success: function cevap(e){
						
								$("#tableEnter").html(e);  

						
						}
						});
					
				}


$( document ).ready(function() {
		getRowsAuto("gifts",0,10,"",0,3,3,0);
	});


</script>