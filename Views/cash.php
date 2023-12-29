<?php
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/currency.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payouts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}

$userid = $_SESSION['userID'];
$user = new users($userid);


if ($user->cash != 1) {
	$fn = new functions();
	$fn->redirect("404");
} else {

$loc = new localization ($_SESSION['language']);
$currency = new currency(2);

$payout = new payouts();
$resultPayout = $payout->getPayouts($userid);




?>
	
											


		<section>
			<div class="container">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="post post-single">



							<div class="panel panel-default panel-post">
							<div class="panel-body">
								<div class="post">





		<section style="padding-top: 20px;">
			<div class="container">
				<h3><?php echo $loc->label("Cash your &s to money");?></h3>
				<p><?php echo $loc->label("Earn money!");?></p>

				<div class="row">
				
				
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<div class="widget">
							<div class="panel panel-default">
								<div class="panel-heading"><?php echo $loc->label("shop with your &s");?></div>
								<div class="panel-body" style="padding: 20px;">
								<p><?php echo $loc->label("buy product you want");?></p>
									<a href="shop"><button type="button" class="btn btn-success btn-icon-left"><i class="fa fa-shopping-bag"></i><?php echo $loc->label("Go to Shop");?></button></a>
								</div>
							</div>
						</div>


					</div>

					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">



							<div class="widget">

							<div class="panel panel-default">

								<div class="panel-heading"><?php echo $loc->label("Cash Your Points to Money");?> <i
										class="fa fa-question" data-toggle="tooltip"
										title="<?php echo $loc->label("You can exchange your &s for cash money.");?>"
										style="font-size: 18px; margin-left: 5px;"></i></div>

								<div class="panel-body" style="padding: 20px;">

									<div class="form-group" style="position: relative;">

								<h4><?php echo $loc->label("& amount");?></h4>

								</div>
								

										<div class="form-group">

											<input type="number" name="cashPoint" id="cashPoint" oninput="calculate()" class="form-control" placeholder="<?php echo $loc->label("Entry a number");?>">

										</div>

										<div class="form-group">

										<h4><?php echo $loc->label("TL");?><h4>

										</div>

										<div class="form-group">

											<input type="number" id="moneyValue" class="form-control" placeholder="<?php echo $loc->label("Monetary Value");?>" disabled>

										</div>

									<a href="javascript: cashOut();"><button type="submit" class="btn btn-block btn-primary"><?php echo $loc->label("Cash");?></button></a>

								

								</div>

							</div>

						</div>

						
						
													<div id="modal" class="modal fade bs-modal" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title" style="text-align: center;"><?php echo $loc->label("ERROR");?></h4>
							</div>
							<div class="modal-body">
								
								<p id="modalText" style="text-align: center;"></p>			
								
							</div>
							<div class="modal-footer">

								<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("Close");?></button>

							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
				
				
				<input id="modalButton" type="hidden" data-toggle="modal" data-target=".bs-modal">

						
						
						
						
						

					</div>

				</div>



			</div>
		</section>

		
		
		
				
								<div class="panelIBAN" style="margin-bottom: 50px;">
												
											<div class="widget" style="padding-top: 12px;">

												<div class="panel panel-default">

													<div class="panel-heading"><?php echo $loc->label("Bank Account Info");?>
													
													</div>
													
													<div id="alert" class="alert alert-info alert-lg" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>

													<div class="panel-body" style="padding: 20px;">

														
											
															<input type="hidden" name="tableName" id="tableName" value="users"> <input
																									type="hidden" name="ID" id="ID" value="<?php echo $ID;?>">
															<input type="hidden" name="userID" id="userID" value="<?php echo $userID;?>">
														
															
															<p><?php echo $loc->label("In order to be transferred your money");?></p>
															
																<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															
						<?php if($user->IBAN != NULL || $user->IBAN != "") { ?>									
					
					<div class="col-lg-12" style="margin-bottom: 30px">
						<div class="panel panel-success">
							<div class="panel-heading">
								<h3 class="panel-title"><?php echo $loc->label("Current Bank Account");?></h3>
							</div>
							<div class="panel-body" style="padding-left: 20px;">
															
															
															<p>
															<br/>
															<b><?php echo $loc->label("First Name");?>:</b> <a style="color: red;"><?php echo $user->bankFirstName;?></a>
															<br/>
															<b><?php echo $loc->label("Last Name");?>:</b> <a style="color: red;"><?php echo $user->bankLastName;?></a>
															<br/>
															<b><?php echo $loc->label("IBAN");?>: </b><a style="color: red;"><?php echo $user->IBAN;?></a>
															</p>
															
												
															
														
							</div>
						</div>
					</div>

															
									<?php }?>							
															
													</div>
													
													</div>
														<div> 	
														
															<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															
															
														<h3><?php echo $loc->label("Add/Change Bank Account");?> </h3>
														
														</div>
														
														</div>
														
														<br/>
												
															<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														
														<div class="form-group">
															
															<?php echo $loc->label("New First Name");?> <input type="text" name="firstname" id="firstname" maxlength="100" placeholder="<?php echo $loc->label("Account Owners First Name");?>" class="form-control">
															
														</div>
														
														</div>
														</div>
														
															<div class="row">
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														
														<div class="form-group">
															
															<?php echo $loc->label("New Last Name");?> <input type="text" name="lastname" id="lastname" maxlength="100" placeholder="<?php echo $loc->label("Account Owners Last Name");?>" class="form-control">
								
														</div>
														
														</div>
														
														</div>
														
															<div class="row">
										
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
															
														<div class="form-group">
															
															<?php echo $loc->label("New IBAN");?> <i
										class="fa fa-question" data-toggle="tooltip"
										title="<?php echo $loc->label("IBANEXP");?>"
										style="font-size: 18px; margin-left: 5px; margin-right: 5px;"></i> <input type="text" name="iban" id="iban" maxlength="34" placeholder="XXXX XXXXX X XXXXXXXXXXXXXXXX" class="form-control">
								
										
														</div>
														
														</div>
														</div>
															
														<div class="form-group">
															
															<button id="submit" type="submit" class="btn btn-primary btn-rounded"><?php echo $loc->label("Save");?></button>
																
														</div>
														
														</div>
														
													</div>
													
												</div>
												
											</div>


</div>

									
											
											
										<div id="followCashouts">	
											
										<center><h4><?php echo $loc->label("Payment History");?></h4>
										<p><?php echo $loc->label("You can follow your payments the tabe below.");?></p>
										</center>
										
														
														<br/>
										
											<table class="table table-hover">
									<thead>
										<tr>
											<th><?php echo $loc->label("Transaction Number");?></th>
											<th><?php echo $loc->label("Date");?></th>
											<th><?php echo $loc->label("Bank Account Owner");?></th>
											<th><?php echo $loc->label("IBAN");?></th>
											<th><?php echo $loc->label("Amount");?></th>
											<th><?php echo $loc->label("Status");?></th>
										</tr>
									</thead>
									<tbody>
									
									<?php while ($row=mysqli_fetch_array($resultPayout)) {?>
									
									
										<tr>
											<td><?php echo $row["cashNo"]; ?></td>
											<td><?php echo $row["date_"]; ?></td>
											<td><?php echo $row["bankFirstName"]; ?> <?php echo $row["bankLastName"]; ?></td>
											<td><?php echo $row["IBAN"]; ?></td>
											<td><?php echo str_replace(".",",",$row["amount"]); ?> <?php echo $row["currency"]; ?></td>
											<td>
											
											<?php if($row["result"] == 1) { ?> 
											
											<a style="color: green;"><?php echo $loc->label("Completed");?></a> 
											
											<?php } else if($row["result"] == 2) { ?>
											
											<a style="color: orange;"><?php echo $loc->label("Waiting");?></a> 
											
											<?php } else if($row["result"] == 0) { ?>
											
											<a style="color: red;"><?php echo $loc->label("Cancelled");?></a> 
											
											<?php } ?>
											
											</td>                        
										</tr>
										
									
									<?php }?>

									<?php if (mysqli_num_rows( $resultPayout ) == 0) {?>
									
									<p><center><?php echo $loc->label("No Transaction");?></center></p>
									
									<?php }?>
									
									</tbody>
								</table>	

					</div>
							
				










								</div>
							</div>
							</div>




						</div>


					</div>
				</div>
			</div>
		</section>
	</div>

	
	
				
	
	
	
	<script>

		function calculate() {
		var box1 = document.getElementById('cashPoint').value;
		var box2 = <?php echo $currency->monetaryValue;?>;
		var result = document.getElementById('moneyValue');
		var myResult = box1 * box2;
		result.value = parseFloat(myResult).toFixed(2);

	}



$(function() {
    $('#iban').on('keypress', function(e) {
        if (e.which == 32)
            return false;
    });
});

				$("#submit").click(function(){
					submitsettings();
				});
				$(document).keypress(function(e) {
					if(e.which == 13) {
						submitsettings();
					}
				});
				
				function submitsettings(){
						$.ajax({
						type: 'POST',
						url: "../Controllers/formPosts.php?action=cashinfo",
						data: {firstname: $("#firstname").val(), lastname: $("#lastname").val(), iban: $("#iban").val()},
						success: function cevap(e){
						$("#alert-text").html(e);
						$("#alert").show();
						shakeForm();
						}
						})
					}
				
				
				function closeAlert(){
						$("#alert").hide();
					}
				
					function shakeForm() {
   var l = 20;  
   for( var i = 0; i < 6; i++ )   
     $( "#alert" ).animate( { 
         'margin-left': "+=" + ( l = -l ) + 'px',
         'margin-right': "-=" + l + 'px'
      }, 80);  

     }

					
						function cashOut(){
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=cash",
			data: {cashPoint: $("#cashPoint").val()},
			success: function cevap(e){
				if(!(e.indexOf("ok") > -1)){
					document.getElementById("modalText").innerHTML = e;
					document.getElementById("modalButton").click(); 
					
			   }else{
					document.getElementById("modalText").innerHTML = e;
					document.getElementById("modalButton").click(); 
					location.reload();
			   }
			}
			})
		}

	</script>
<?php } ?>