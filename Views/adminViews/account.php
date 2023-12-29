
<style>

#loading {
	display: none;
	left: 0px; 
	top: 0px;
	width: 100%;
	height: 100%;
	opacity: 0.6;
	pointer-events: none;
	cursor: default; 
	background: url(../images/loading2.gif) center no-repeat #101010;  
}
</style>
 
<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">  

											<h3>Kasa </h3>

											<p style="margin-bottom: 5px;">Kasadaki parayı görüntüleyin. Kasadan para çekin.<p>  

										</div>
			

										<div class="panel-body" id="panel-body">
										
										
							
									<div id="case">
									
										<div class="row">
										
										<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-success">
							<div class="panel-heading">
								<h3 class="panel-title">Kar</h3>
							</div>
							<div class="panel-body">
								<h1 id="profit"></h1>
							</div>
						</div>

			
			
			</div>
			
			<div class="col-lg-6 text-center  margin-top-30"> 
			
				<div class="panel panel-danger">
							<div class="panel-heading">
								<h3 class="panel-title">Ürün Bütçesi</h3>
							</div>
							<div class="panel-body">
								<h1 id="product"></h1>
							</div>
						</div>

			
			
			</div>  
										
										</div>
										
										<div class="row">    
										
										<div class="col-lg-3"> 
										</div>
									
										<div class="col-lg-6 text-center margin-top-30"> 
			
				<div class="panel panel-inverse text-center" style="text-align: center;">  
							<div class="panel-heading">
								<h3 class="panel-title">Kasada Olması Gereken Toplam Para</h3>  
							</div> 
							<div class="panel-body">
								<h1 id="total"></h1>
							</div>
						</div>
						
						<div class="col-lg-3">   
										</div>

			
			
			</div>  
										
								
				
				</div>
				
		
							

					
					
	
		<div class="row margin-top-50">
		
			<div class="col-lg-4 text-center"> 
			
				<a href="javascript: runAccountFunc();"><button type="button" class="btn btn-primary btn-shadow">Kasayı Güncelle</button></a>
			
			</div>

			<div class="col-lg-4 text-center"> 
			
				<a href="admin?tab=newtransaction"><button type="button" class="btn btn-primary btn-shadow">Yeni İşlem Gerçekleştir</button></a>
			
			</div>
			
			<div class="col-lg-4 text-center"> 
			
				<a href="admin?tab=accountactivities"><button type="button" class="btn btn-primary btn-shadow">Hesap Hareketler Dökümü</button></a> 
			
			</div>
		
		
		</div>
		</div>
		
						</div>
						
						<div id="loading"></div>
</div>





<script>


$( document ).ready(function() {
	
	runAccountFunc();
	
});

function runAccountFunc() {
	
	
	$("#loading").show();
	
	getAccount(1); 
	getAccount(2);
	getAccount("total");
	
}

function getAccount(account){
	
		

		$.ajax({
		type: 'POST',
		url: "../Controllers/formPosts.php?action=admin",
		data: {tab: "getAccounts", whichAc: account},
		success: function cevap(e){
			
			if(e.indexOf("PROFIT") > -1){  
				
				$("#profit").html(e.replace("PROFIT","") + " ₺");    
				
			} else if(e.indexOf("PRODUCT") > -1){  

				$("#product").html(e.replace("PRODUCT","") + " ₺");  
				
			} else if(e.indexOf("TOTAL") > -1){  

				$("#total").html(e.replace("TOTAL","") + " ₺");  
				
				$("#loading").hide();
				

			}
			

		}
		})
}
</script>