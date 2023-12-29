<?php

require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/products.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/currency.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/payments.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/banks.php";

if (!isset($_SESSION['userID'])) {
	$fn = new functions();
	$fn->redirect("login");
}

$loc = new localization ($_SESSION['language']);

$productCamp = new products();
$resultCamp = $productCamp->getProducts("campaign");

$bank = new banks();
$resultBanks = $bank->getBanks();

$productPoint = new products();
$resultPoint = $productPoint->getProducts("point");

$productPre = new products();
$resultPre = $productPre->getProducts("premium");

$userID = $_SESSION["userID"];
$user = new users ( $userID );
if ( preg_match('/\s/',$user->fullName) ) {
$nameArray= explode(' ', $user->fullName, 2);
} else {
$nameArray = array(0 => $user->fullName, 1 => " ");  
}
$widgetURL= "https://api.paymentwall.com/api/ps/?key=f8e9f2717eff280fa325950db6cf0655&uid=".$userID."&sign_version=3&email=".$user->email."&history[registration_date]=".DateTime::createFromFormat('Y-m-d H:i:s', $user->registerdate_)->getTimestamp()."&customer[birthday]=".DateTime::createFromFormat('Y-m-d', $user->birthDate)->getTimestamp()."&customer[sex]=".(($user->gender == 2) ? 'female' : 'male')."&history[registration_name]=".$nameArray[0]."&history[registration_lastname]=".$nameArray[1]."&widget=";

$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
	
	$mobile=1;
	if($user->country != 306) {
		$fn = new functions();
		$fn->redirect($widgetURL.'m2');
	}
	
}else{
	$mobile=0;
	$widgetURL.="p1_1";
}

?>

<?php /* <section class="hero parallax" style="background-image: url(../images/cover-buy.jpeg);"> 
		<div class="hero-bg"></div>
		<div class="container">
			<div class="page-header">
				<div class="page-title" style="font-size: 45px;"><?php echo $loc->label("Point");?></div>
				<ol class="breadcrumb" style="font-size: 30px;">
					<li><?php echo $loc->label("Buy Points and reach the large masses");?></li>
				</ol>	
			</div>
		</div>
	</section> */ ?>

	<?php /* if (mysqli_num_rows( $resultCamp ) != 0) {?>
	
		<section class="elements">
			<div class="container">
				<h3><?php echo $loc->label("Campaigns");?></h3>
				<p><?php echo $loc->label("Now buy!");?></p>
				<div class="row margin-top-40">
				
				
				<?php while ($row=mysqli_fetch_array($resultCamp)) { ?>
				
				
					<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 margin-bottom-sm-30">
						<div class="card card-hover">
							<div class="card-img">
								<img src="<?php echo project::assetImages.$row["imagePath"];?>" alt="">
							</div>
							<div class="caption">
								<p><?php echo $loc->label("Last Date:");?> <?php echo $row["duedate_"];?></p>
								<p></p>
								<h3 class="card-title"><?php echo $row["productName"];?></h3>
								<p>
								
								<?php
									// strip tags to avoid breaking any html
									$string = strip_tags($row["description"]);

									if (strlen($string) > 300) {

										// truncate string
										$stringCut = substr($string, 0, 300);

										// make sure it ends in a word so assassinate doesn't become ass...
										$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
									}
									echo $string; ?>
								
								</p>
								
								
				
								<button id="opener1" type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target=".bs-modalCamp<?php echo $row["ID"];?>"><?php echo $loc->label("View");?></button>
								
				<div class="modal fade bs-modalCamp<?php echo $row["ID"];?>" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $loc->label("Close");?></span></button>
								<h4 class="modal-title" style="text-align: center;"><?php echo $row["productName"];?></h4>
							</div>
							<div class="modal-body">
								<p style="text-align: center;"><img src="<?php echo project::assetImages.$row["imagePath"];?>" alt=""></p>
								
								<p style="text-align: center;"><?php echo $row["description"];?></p> 
								
				<h3 style="text-align: center; margin-top: 10px; margin-bottom: 10px; font-size: 32px;"><?php if($row["noDiscount"] != 0) { ?> <span style="color: #e74c3c; text-decoration:line-through; font-size: 24px;"> <?php echo number_format($row["noDiscount"], 2, ',', '');?><?php echo $loc->label("TLS");?></span>	<?php echo number_format($row["price"], 2, ',', '');?> <?php echo $loc->label("TLS");?> <?php } else { echo number_format($row["price"], 2, ',', ''); ?> <?php echo $loc->label("TLS"); } ?></h3>
								
							</div>
							<div class="modal-footer">

								<form method="post" name="buy" id="buy" action="Controllers/formPosts.php?action=buy">
								
									<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("Close");?></button>
	
									<button type="submit" name="productID" value="<?php echo $row["ID"];?>" class="btn btn-success btn-icon-right"><?php echo $loc->label("Buy Now");?><i class="fa fa-check-square-o"></i></button>
									
								</form>
								
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>

								
								
							</div>
						</div>
					</div>

					
				<?php }?>
					
				</div>
			</div>		
			
		</section>
		
	<?php } */?>
		
		
<style>
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
	background-color: #FFF !important;
}
.noselect {
  -webkit-touch-callout: none; /* iOS Safari */
  -webkit-user-select: none;   /* Chrome/Safari/Opera */
  -khtml-user-select: none;    /* Konqueror */
  -moz-user-select: none;      /* Firefox */
  -ms-user-select: none;       /* Internet Explorer/Edge */
  user-select: none;           /* Non-prefixed version, currently
                                  not supported by any browser */
}
</style>
		
		<section style="padding-top: 40px;">
			<div class="container">
				<h3><?php echo $loc->label("What is n?");?></h3>
				<p><?php echo $loc->label("Buy point and add your post");?></p>
				<div class="row margin-top-40">
				
				<div class="col-lg-12">
				
<?php if($user->country == 306) { ?>
			
				

				
							<ul class="nav nav-tabs" style="background-color: rgba(0,0,0, 0.1);">
							<li class="active" style="width: 33.333333333%;"><a href="#itab1" data-toggle="tab" style="font-size: 18px; color: #777; font-weight: 400;"><i class="fa fa-bank"></i> Banka Transferi</a></li>  
							<li style="width: 33.333333333%;"><a <?php if($mobile==1) { ?>href="<?php echo ($widgetURL.'m2');?>"<?php } else { ?> href="#itab2" data-toggle="tab" <?php } ?> style="font-size: 18px; color: #777; font-weight: 400;"><i class="fa fa-mobile-phone" style="font-weight: 400;"></i>Mobil ve Alternatif Ödeme</a></li>
							<li style="width: 33.333333333%;"><a href="#itab3" data-toggle="tab" style="font-size: 18px; color: #777; font-weight: 400;"><i class="fa fa-envelope"></i> Teklif Al</a></li>
						</ul>
						<div class="tab-content" style="background-color: #FFF; min-height: 500px;">   
							<div class="tab-pane fade in active" id="itab1">  
							
							<div id="showBankPanel" style="display: none;">
							
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<a href="javascript: changeBank();" style="float: right;">Banka Değiştir</a>
								</div>
								</div>
								
								<div id="bankDetails">
								
								</div>
							
							</div>
								
								<div id="showBanks" style="display: none;">
								
								
								<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<p>Lütfen bir banka seçin</p>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<a href="javascript: showBank(0);" style="float: right;">Paket Değiştir</a>
								</div>
								</div>
								<div class="row">
								
								<?php 
				while ($rowB=mysqli_fetch_array($resultBanks)) { ?>
					<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 margin-bottom-sm-30">
					<div class="panel panel-inverse noselect" style="border-color: rgba(0, 0, 0, 0.1); cursor: pointer;">  
							<div class="panel-body text-center" style="padding: 5px;">
								<a href="javascript: bankTransfer(<?php echo $rowB["ID"]; ?>);"><img src="../Assets/images/banks/<?php echo $rowB["image"]; ?>" alt="<?php echo $rowB["name"]; ?>" /></a>
							</div>
						</div>
					</div>
				<?php } ?>
				</div>
				</div>
				
				<div id="showBankProducts">
				<div class="row">
									<?php 
				while ($row=mysqli_fetch_array($resultPoint)) { ?>
				
					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 margin-bottom-sm-30">
						<div class="card card-game card-primary">
							<div class="card-header" <?php if($row["noDiscount"] != 0) { ?> style="background-color: #e74c3c !important;" <?php } ?> ><i class="fa fa-shopping-bag"></i><?php echo $row["productName"];?></div>
							<div class="card-img" style="background-color: rgba(39, 118, 220, 0.03);">
								<img src="<?php echo project::assetImages.$row["imagePath"];?>" alt="">
							</div>
							<div class="caption">
								<div class="card-title" style="text-align: center;">
								
								<h3 style="font-size: 32px;"><?php if($row["noDiscount"] != 0) { ?> <span style="color: #e74c3c; text-decoration:line-through; font-size: 24px;"> <?php echo number_format($row["noDiscount"], 2, ',', '');?><?php echo $loc->label("TLS");?></span>	<?php echo number_format($row["price"], 2, ',', '');?> <?php echo $loc->label("TLS");?> <?php } else { echo number_format($row["price"], 2, ',', ''); ?> <?php echo $loc->label("TLS"); }?></h3>
								
								<a href="javascript: showBank(<?php echo $row["ID"];?>);" class="btn btn-success btn-icon-right"><?php echo $loc->label("Buy Now");?><i class="fa fa-check-square-o"></i></a>
								
								
								</div>  
							</div>
						</div>
					</div>
					
				<?php } ?>
				</div>
				</div>
						
								</div>
								<div class="tab-pane fade" id="itab2">
									<iframe src="<?=$widgetURL?>" width="750" height="800" frameborder="0"></iframe>
								</div>				
								<div class="tab-pane fade" id="itab3">  
									<div style="margin-top: 50px; margin-bottom: 50px;">
									
									<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 margin-bottom-sm-30">
					<div class="panel panel-inverse" style="border-color: rgba(0, 0, 0, 0.1);">  
						<div class="panel-body text-center" style="padding: 35px;">
									
									<center style="font-size: 18px; font-weight: 500;">Bize bir e-posta göndererek özel teklif talep edebilirsiniz. Reklam planınızı, ulaşmak istediğiniz kitlenin tahmini sayısını ve bütçenizi <a href="mailto: payment@funnyandmoney.com;">offer@funnyandmoney.com</a> adresine gönderin. Detaylı bilgi için kısa süre içerisinde size dönüş yapacağız.</center>
									
									</div>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
				</div>
			</div>
									
					</div>
								</div>
						</div>
			
			
				
<?php } else { ?>

<iframe src="<?=$widgetURL?>" width="750" height="800" frameborder="0"></iframe>

<?php } ?>

	</div>  
				
					
				</div>
				
			</div>
				
		</section>
		
		
		<?php /* if (mysqli_num_rows( $resultPre ) != 0) {?>
		
		
		<section class="elements">
			<div class="container">
				<h3><?php echo $loc->label("Premium");?></h3>
				<p><?php echo $loc->label("Premium Accounts provide great convenience for you.");?></p>
				<div class="row margin-top-40">
				
				<?php while ($row=mysqli_fetch_array($resultPre)) { ?>
				
					<div class="col-lg-3">
						<div class="card card-list">
							<div class="card-img">
								<img src="<?php echo project::assetImages.$row["imagePath"];?>" alt="">
								<span class="label label-success"><i class="glyphicon glyphicon-star"></i></span>
							</div>
							<div class="caption">
								<h4 class="card-title"><?php echo $row["productName"];?></h4>
								<p>
								
								<?php
									// strip tags to avoid breaking any html
									$string = strip_tags($row["description"]);

									if (strlen($string) > 200) {

										// truncate string
										$stringCut = substr($string, 0, 200);

										// make sure it ends in a word so assassinate doesn't become ass...
										$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
									}
									echo $string; ?>
							
								</p>
								<center><button id="opener3" type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target=".bs-modal<?php echo $row["ID"];?>"><?php echo $loc->label("View");?></button></center>
								
								
				<div class="modal fade bs-modal<?php echo $row["ID"];?>" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $loc->label("Close");?></span></button>
								<h4 class="modal-title" style="text-align: center;"><?php echo $row["productName"];?></h4>
							</div>
							<div class="modal-body">
							
								<p style="text-align: center;"><img src="<?php echo project::assetImages.$row["imagePath"];?>" alt=""></p>
								<p style="text-align: center;"><?php echo $row["description"];?></p>
								<h3 style="text-align: center; font-size: 32px;"><?php if($row["noDiscount"] != 0) { ?> <span style="color: #e74c3c; text-decoration:line-through; font-size: 24px;"> <?php echo number_format($row["noDiscount"], 2, ',', '');?><?php echo $loc->label("TLS");?></span>	<?php echo number_format($row["price"], 2, ',', '');?> <?php echo $loc->label("TLS");?> <?php } else { echo number_format($row["price"], 2, ',', ''); ?> <?php echo $loc->label("TLS"); }?></h3>
							</div>
							<div class="modal-footer">

								<form method="post" name="buy" id="buy" action="Controllers/formPosts.php?action=buy">
								
									<button type="button" class="btn btn-warning" data-dismiss="modal"><?php echo $loc->label("Close");?></button>
								
									<button type="submit" name="productID" value="<?php echo $row["ID"];?>" class="btn btn-success btn-icon-right"><?php echo $loc->label("Buy Now");?><i class="fa fa-check-square-o"></i></button>
									
								</form>	
									
							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
								
								
							</div>
						</div>
					</div>
					
					
					<?php }?>
						

				</div>
			</div>
		</section>
		
		<?php } */?>
		
		
		
	
	<!-- Javascript -->
	<script src="../Library/bootstrap-3.3.6/plugins/countdown/jquery.countdown.min.js"></script>
	<input type="hidden" id="bankProductID" name="bankProductID" value="" />
	
	
<script>

function showBank(id) {
	if(id == 0) {
	
		$("#bankProductID").val("");
		$("#showBanks").hide();
		$("#showBankProducts").show();  
		
	} else {
		
		$("#bankProductID").val(id);
		$("#showBankProducts").hide();
		$("#showBanks").show();
	
	}
}

function bankTransfer(id){
		
		$("#showBankProducts").hide();  
		$("#showBanks").hide();
		
			$.ajax({
			type: 'POST',
			url: "../Controllers/formPosts.php?action=buyTransfer",
			data: {product: $("#bankProductID").val(), bankID: id},
			success: function cevap(e){
				
			
				$("#bankDetails").html(e);
				$("#showBankPanel").show();


			}
			}) 
}

function changeBank() {

		$("#bankDetails").val("");
		$("#showBanks").hide();
		$("#showBankPanel").hide();
		$("#showBanks").show();  

}

</script>
