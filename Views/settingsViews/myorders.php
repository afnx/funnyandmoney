<?php

$gifts = new giftRequests();
$resultGifts = $gifts->getGiftRequests($userid);

$obj = new objects ();
 
?>
	
	
	
<link rel="stylesheet" href="../Library/bootstrap-3.3.6/css/chosen.css">

<link rel="stylesheet"
	href="../Library/bootstrap-3.3.6/css/chosen.bootstrap.min.css">

<link rel="stylesheet"
	href="../Library/bootstrap-3.3.6/css/chosen.bootstrap.css">

		


						<div class="panel panel-default">


										<div class="panel-heading"
											style="padding-bottom: 0px;">

											<h3><?php echo $loc->label("My Orders");?> </h3>

											<p style="margin-bottom: 5px;"><?php echo $loc->label("Edit your address or view status of your orders");?><p>

										</div>

										<div id="alert" class="alert alert-info alert-dismissible" role="alert" style="display:none;">
									<a href="javascript: closeAlert();"><button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>

									<div id="alert-text"></div>
								</div>

										<div class="panel-body">

	
											<center><h4><?php echo $loc->label("Order History");?></h4>
										<p><?php echo $loc->label("You can follow your order status and know information of your digital products on the table below");?></p>
										</center>
											
											<table class="table table-hover">
									<thead>
										<tr>
											<th><?php echo $loc->label("Order Number");?></th>
											<th><?php echo $loc->label("Date");?></th>
											<th><?php echo $loc->label("Product Name");?></th>
											<th><?php echo $loc->label("Recipient");?></th>
											<th><?php echo $loc->label("Address");?></th>
											<th><?php echo $loc->label("Price");?></th>
											<th><?php echo $loc->label("Status");?></th>
										</tr>
									</thead>
									<tbody>
									
									<?php while ($row=mysqli_fetch_array($resultGifts)) {
										
										$giftN = new gifts($row["giftID"]);
										$address = new address ($row["addressID"]);
										
										?>
									
									
										<tr style="cursor:pointer;" data-toggle="modal" data-target=".bs-modal<?php echo $row["ID"];?>">
		
											<td><?php echo $row["orderNo"]; ?></td>
											<td><?php echo $row["date_"]; ?></td>
											<td><i style="color: #337ab7; margin-right:2px;" class="fa fa-file-text-o"></i><?php echo $giftN->name; ?></td>
											<td><?php if($giftN->isDigital != 1) { echo $address->recipientName; } else { echo "-";} ?></td>  
											<td><?php if($giftN->isDigital != 1) { echo $address->addressLine1; ?><br/><?php echo $address->addressLine2; ?><br/><?php echo $address->region; ?>&nbsp;<?php echo $address->city; ?>&nbsp;<?php echo $address->country; ?><br/>+<?php echo $address->phone; } else { echo "-";} ?></td>
											<td><?php echo $row["price"]; ?>& </td>

											<td>
											
											<?php if($row["deliveryStatus"] == 0) { ?> 
											
											<a style="color: green;"><?php echo $loc->label("Delivered");?></a> 
											
											<?php } else if($row["deliveryStatus"] == 1) { ?>
											
											<a style="color: green;"><?php echo $loc->label("Shipped");?></a>   
											
											
											<?php } else if($row["deliveryStatus"] == 2) { ?>
											
											<a style="color: orange;"><?php echo $loc->label("Waiting");?></a> 
											
											<?php } else if($row["deliveryStatus"] == 3) { ?>   
											
											<a style="color: red;"><?php echo $loc->label("Cancelled");?></a> 
											
											<?php } ?>
											
											</td>   
										
										</tr>
									 
										
										
										
										<div class="modal fade bs-modal<?php echo $row["ID"];?>" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $loc->label("Close");?></span></button>
								<h4 class="modal-title" style="text-align: center;"><?php echo $giftN->name;;?></h4>
							</div>
							<div class="modal-body">
							
							<?php if($giftN->deliverySpeed == 1) { ?>
							
								<p style="text-align: center;"><img src="<?php echo ($giftN->picture!="") ? project::uploadPath."/giftImg/".$giftN->picture : project::assetImages. "giftimage.jpg";?>" alt="<?php echo $giftN->name;?>" style="max-height:300px; height: 100%;"></p>
								
								<p style="text-align: center;"><?php echo $giftN->description;?></p> 
								
							<?php } ?>
							
								
								<?php if($giftN->isDigital == 1) { 
								
								$giftCode = new digitalGiftCodes();
								$gcode = mysqli_fetch_array ( $giftCode->findGiftCode($row["ID"]) );
								?>
								
								<?php if($giftN->deliverySpeed == 0) { ?>
									
								<h3 style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><?php echo $loc->label("Product Code");?>: <?php echo $gcode["giftCode"];?></h3>
								
								<p style="text-align: center;"><?php echo $gcode["descriptionText"];?></p>   
								
								<p style="text-align: center;"><?php echo $loc->label("Expiration Date");?>: <?php echo $gcode["expirationDate_"];?></p> 
								
								
								<?php } else if($giftN->deliverySpeed == 2){?>
								
								<?php if($row["deliveryStatus"] == 0) { ?>
								
									<div style="text-align: center; margin-top: 10px; margin-bottom: 10px;"><?php echo $gcode["giftCode"];?></div>  
									
									<p style="text-align: center;"><?php echo $gcode["descriptionText"];?></p> 
									
									<p style="text-align: center;"><?php echo $loc->label("Expiration Date");?>: <?php echo $gcode["expirationDate_"];?></p> 
								
									
								
								<?php } else {?>
							
									<p style="text-align: center; font-weight: bold;"><?php echo $loc->label("pendingEmailProText");?></p> 
								
								<?php } ?>
								
								<?php } ?>
								
								<?php } ?>
								
							</div>
							<div class="modal-footer">

									<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("Close");?></button>
								
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
										
									
									<?php }?>
									
									</tbody>
								</table>	

					
										</div>
						
						</div>
						
						
		