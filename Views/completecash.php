<?php

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/products.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payouts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}

$userid = $_SESSION['userID'];
$user = new users($userid);

if ($user->publisher != 1) {
	$fn = new functions();
	$fn->redirect("404");
} else {

$loc = new localization($_SESSION['language']);


$userid = $_SESSION["userID"];
$payout = payouts::setUserPayout ( $userid, 1 );

 if($payout->ID == "") {
	 
	$fn = new functions();
	$fn->redirect("404"); 
 }


?>


		
<section class="bg-default padding-top-30 padding-bottom-30 bg-grey-50 border-bottom-1 border-grey-200" style="background-color: rgb(250, 250, 250) !important;">

	<div class="container">

		<h2 class="font-size-24 color-inverse font-weight-300"><?php echo $loc->label("Your Cash Transaction");?></h2>

	</div>

</section>


<section class="border-bottom-1 border-grey-300 padding-top-10 padding-bottom-10">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="cash"><?php echo $loc->label("Cash");?></a></li>
							<li class="active"><?php echo $loc->label("Transaction Request");?></a></li>
						</ol>	
					</div>
				</div>
			</div>
		</section>


	
		<section class="elements" style="padding: 40px;">
			<div class="container">
				<div class="row">
				
				
				
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				

<?php if($payout->method == "bankTransfer" && $payout->cashNo != "" && $payout->result == 1) { ?>		

					<h3><?php echo $loc->label("Received Transaction Request");?></h3>
					<p><?php echo $loc->label("Your transaction number");?>: <a style="color: red; font-weight: bold;"><?php echo $payout->cashNo; ?></a></p>
					<p><?php echo $loc->label("Your money will be transferred your bank");?>
					<br/>
					<?php echo $loc->label("You can check transfer status with your trans");?></p>
				
<?php } ?>
				
				</div>


				
				</div>
			</div>		
			
		</section>
		


	
	
<?php } ?>
