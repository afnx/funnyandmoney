<?php

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/products.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}

$loc = new localization ($_SESSION['language']);  


$userid = $_SESSION["userID"];
$payment = payments::setUserPayment ( $userid, 1 );

 if($payment->ID != ""){
	
	$productID = $payment->productID;
	
	$product = new products($productID);

 } else {
	$fn = new functions();
	$fn->redirect("404"); 
 }


?>


<section class="bg-default padding-top-30 padding-bottom-30 bg-grey-50 border-bottom-1 border-grey-200" style="background-color: rgb(250, 250, 250) !important;">

	<div class="container">

		<h2 class="font-size-24 color-inverse font-weight-300"><?php echo $loc->label("Completed S Purchasing");?></h2>

	</div>

</section> 

<section class="border-bottom-1 border-grey-300 padding-top-10 padding-bottom-10">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="buy"><?php echo $loc->label("Buy");?></a></li>
							<li><?php echo $loc->label("Payment");?></li>
							<li class="active"><?php echo $loc->label("Completed");?></li>
						</ol>	
					</div>
				</div>
			</div>
		</section>


	
		<section class="elements" style="padding: 40px;">
			<div class="container">
				<div class="row">
				
				
				
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				

<?php if ($payment->method == "card" && $payment->result == 1) { ?>
				
				
					<h3><?php echo $loc->label("CompletedPayment");?></h3>
					<p><?php echo $loc->label("Your product is available now.");?></p>
					<p><b><?php echo $loc->label("Product Name");?>:</b> &nbsp;<?php echo $product->productName;?><br/>
				<b><?php echo $loc->label("Product Description");?>:</b> &nbsp;<?php echo $product->description;?><br/>
					<b><?php echo $loc->label("Price");?>:</b> &nbsp;<?php echo $payment->amount;?> <?php echo $payment->currency;?></p>
				
<?php } ?>
				
				</div>


				
				</div>
			</div>		
			
		</section>
		


	
	

