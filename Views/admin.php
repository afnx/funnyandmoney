<?php
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/users.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/balance.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/posts.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/platforms.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/admins.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/userSocials.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/payouts.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/definitions.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/giftRequests.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/gifts.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/digitalGiftCodes.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/address.php'; 
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/account.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/accountActivities.php';
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/adminSettings.php";
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/products.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/payments.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/banks.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/adminHistory.php';


if (!isset($_SESSION['userID'])) {
	
	$fn = new functions();  
	$fn->redirect("404");
	
} else if(isset($_SESSION['userID']) && isset($_SESSION['adminID'])) {
	
	$adminC = admins::checkAdmin ( $_SESSION['userID'] );
	if ($adminC->ID > 0) {
		
	} else {
		
		$fn = new functions();
		$fn->redirect("404");
		
	}
	
} else {
	
	$fn = new functions();  
	$fn->redirect("404");
	
}

if (isset($_GET['error'])) {
	
	$error = $_GET['error'];

} else {
	$error = "";
}

$adminPage = isset ( $_GET ["tab"] ) ? $_GET ["tab"] : "dashboard";

$userid = $_SESSION['userID'];

$user = new users($userid);  

$loc = new localization ($_SESSION['language']);

$runsql = new \data\DALProsess (); 

$adminA = admins::checkRank ( $userid, "A");
$adminSM = admins::checkRank ( $userid, "SM");
$adminM = admins::checkRank ( $userid, "M");


function custom_echo($x, $length)
{
  if(strlen($x)<=$length)
  {
    echo $x;
  }
  else
  {
    $y=substr($x,0,$length) . '&nbsp;...';
    echo $y;
  }
}

?>

<head>


<link rel="stylesheet" href="../Library/bootstrap-3.3.6/css/chosen.css">

<link rel="stylesheet"
	href="../Library/bootstrap-3.3.6/css/chosen.bootstrap.min.css">

<link rel="stylesheet"
	href="../Library/bootstrap-3.3.6/css/chosen.bootstrap.css">



</head>


<style>
#loading {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 100;
    width: 100vw;
    height: 100vh;
    background-image: url("../images/loader.gif");
    background-repeat: no-repeat;
    background-position: center;
}
</style>



<section class="bg-default padding-top-30 padding-bottom-30 bg-grey-50 border-bottom-1 border-grey-200" style="background-color: rgb(250, 250, 250) !important;">

	<div class="container">

		<h2 class="font-size-24 color-inverse font-weight-300">Admin Panel</h2>

	</div>

</section>

<?php  if(isset($_GET['message'])){?>

	<?php  if($_GET['message'] == "success" || $_GET['message'] == "fail"){?>

				<div class="container">
					<div class="row">

		<?php if($_GET['message'] == "success"){?>

			<div class="alert alert-success alert-lg fade in margin-top-30">
						<h4 class="alert-title">BAŞARILI</h4>
						<p>İşem gerçekleştirildi.</p>
			</div>


		<?php } else if($_GET['message'] == "fail") { ?>

			<div class="alert alert-danger alert-lg fade in margin-top-30">
						<h4 class="alert-title">HATA</h4>
					<?php if($error == "") { ?>
						<p>İşlem gerçekleştirilemedi.</p>
					<?php }?> 
						<p><?php echo $error; ?></p>
			</div>


		<?php }?>   



		</div>
		</div>
		
		
		<?php }?>
		
	<?php }?>

<section>

<div class="container">

<div class="row">

<div class="col-md-2 leftside margin-bottom-30">

	<div class="widget">
		<div class="panel panel-default">
			<div class="panel-heading">Admin Menu</div>
			<div class="panel-body no-padding">
				<ul class="panel-list-bordered">
				
					<li <?php if($adminPage == "dashboard" || $adminPage == "") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=dashboard">Dashboard</a></li>	
					<li> 
						
						<a style="font-size: 16px;" href="#collapseThree" data-toggle="collapse" data-parent="#accordion">
							Gönderiler
						</a>
					
						<div id="collapseThree" class="panel-collapse collapse <?php if($adminPage == "posts" or $adminPage == "editpost") {?> in<?php } ?>" role="tabpanel">
							<ul>  
								<li <?php if($adminPage == "posts") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=posts">Gönderiler Listesi</a></li>
								<li <?php if($adminPage == "editpost") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=editpost">Gönderi Düzenle</a></li>
							</ul>
						</div>  
					</li>
					<li> 
						
						<a style="font-size: 16px;" href="#collapseOne" data-toggle="collapse" data-parent="#accordion">
							Üyeler
						</a>
					
						<div id="collapseOne" class="panel-collapse collapse <?php if($adminPage == "users" or $adminPage == "edituser") {?> in<?php } ?>" role="tabpanel">
							<ul>  
								<li <?php if($adminPage == "users") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=users">Üyeler Listesi</a></li>
								<li <?php if($adminPage == "edituser") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=edituser">Üye Düzenle</a></li>
							</ul>
						</div>  
					</li>
					
					<?php if($adminA->ID > 0 or $adminSM->ID > 0) { ?>

					<li> 
						
						<a style="font-size: 16px;" href="#collapseTwo" data-toggle="collapse" data-parent="#accordion">
							Ürünler 
						</a>
					
						<div id="collapseTwo" class="panel-collapse collapse <?php if($adminPage == "gifts" or $adminPage == "editgift" or $adminPage == "newgift" or $adminPage == "codes" or $adminPage == "newcode") {?> in<?php } ?>" role="tabpanel">
							<ul>  
								<li <?php if($adminPage == "gifts") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=gifts">Ürünler Listesi</a></li>
								<li <?php if($adminPage == "newgift") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=newgift">Ürün Ekle</a></li>
								<li <?php if($adminPage == "editgift") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=editgift">Ürün Düzenle</a></li>
								<li <?php if($adminPage == "codes") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=codes">Dijital Kodları Listele</a></li>
								<li <?php if($adminPage == "newcode") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=newcode">Dijital Kod Oluştur</a></li>
							</ul>
						</div>   
					</li>

					<li <?php if($adminPage == "orders") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=orders">Siparişler</a></li>
					
					<li <?php if($adminPage == "payments") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=payments">Gelen Ödemeler</a></li>
					<li <?php if($adminPage == "payouts") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=payouts">Giden Ödemeler</a></li>
					
					<li> 
						
						<a style="font-size: 16px;" href="#collapseFour" data-toggle="collapse" data-parent="#accordion">
							Kasa
						</a>
					
						<div id="collapseFour" class="panel-collapse collapse <?php if($adminPage == "account" or $adminPage == "newtransaction" or $adminPage == "accountactivities") {?> in<?php } ?>" role="tabpanel">
							<ul>  
								<li <?php if($adminPage == "account") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=account">Kasa</a></li>
								<li <?php if($adminPage == "newtransaction") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=newtransaction">Yeni İşlem Gerçekleştir</a></li>
								<li <?php if($adminPage == "accountactivities") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=accountactivities">Hesaplar Hareket Dökümü</a></li>

							</ul>
						</div>  
					</li>
					
					<li> 
						
						<a style="font-size: 16px;" href="#collapseFive" data-toggle="collapse" data-parent="#accordion">
							Kayıtlar
						</a>
					
						<div id="collapseFive" class="panel-collapse collapse <?php if($adminPage == "adminHistory" or $adminPage == "saleHistory" or $adminPage == "giftHistory") {?> in<?php } ?>" role="tabpanel">
							<ul>  
								<li <?php if($adminPage == "adminHistory") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=adminHistory">Yetkili Kayıtları</a></li>
								<li <?php if($adminPage == "saleHistory") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=saleHistory">Satış Kayıtları</a></li>
								<li <?php if($adminPage == "giftHistory") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=giftHistory">Hediye Kayıtları</a></li>  

							</ul>
						</div>  
					</li>
					
					<?php } ?>
					
					<li <?php if($adminPage == "iplogs") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=iplogs">IP Logs</a></li>
					
					<?php if($adminA->ID > 0) { ?>
					
					<li <?php if($adminPage == "settings") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="admin?tab=settings">Ayarlar</a></li>
					
					<?php } ?>
					
					<li><a style="font-size: 16px;" href="Controllers/formPosts.php?action=adminSignOut">Çıkış yap</a></li>  

				</ul>   
			</div>
		</div>  
	</div>
	
</div>

<div class="col-md-10 rightside" id="settings-panel">

<form method="post" action = "Controllers/formPosts.php?action=admin" name="adminpanel" id="adminpanel"  autocomplete="off">  
 

		<?php
		
		$adminPageArray = array (
			"dashboard",
			"editpost",
			"edituser"
	);

	if($adminA->ID > 0 or $adminSM->ID > 0) {  

		array_push($adminPageArray, "editgift");
		array_push($adminPageArray, "newgift");
		array_push($adminPageArray, "editorder");
		array_push($adminPageArray, "newcode");
		array_push($adminPageArray, "newtransaction");
		
	}
	
	if($adminA->ID > 0) {  
		
		array_push($adminPageArray, "settings");
	
	}
	
	if(in_array($adminPage, $adminPageArray)) {
	
		$adminPageArray = dirname ( __FILE__ ) . "/adminViews/" . $adminPage . ".php";
	
		if (file_exists ( $adminPageArray )) {
		
			require_once dirname ( __FILE__ ) . "/adminViews/" . $adminPage . ".php";
		
		} else {  
		
			$fnn = new functions();
			$fnn->redirect("404");    
		
		}
	
	}
		
	
	
	?>

</form>

		<?php
		
		$adminPageArray2 = array ( 
			"posts",
			"users",
			"iplogs"
	);

	if($adminA->ID > 0 or $adminSM->ID > 0) {  

		array_push($adminPageArray2, "gifts");
		array_push($adminPageArray2, "orders");
		array_push($adminPageArray2, "codes");
		array_push($adminPageArray2, "account");
		array_push($adminPageArray2, "accountactivities");		
		array_push($adminPageArray2, "payments");	
		array_push($adminPageArray2, "adminHistory");	
		array_push($adminPageArray2, "saleHistory");	
		array_push($adminPageArray2, "giftHistory");	
		array_push($adminPageArray2, "payouts");
		
	}
	
	if(in_array($adminPage, $adminPageArray2)) {
	
		$adminPageArray2 = dirname ( __FILE__ ) . "/adminViews/" . $adminPage . ".php";
	
		if (file_exists ( $adminPageArray2 )) {
		
			require_once dirname ( __FILE__ ) . "/adminViews/" . $adminPage . ".php";
		
		} else {
		
			$fnn = new functions();
			$fnn->redirect("404");
		
		}
	
	} 
	
	
	
	$totalArray  = array ( 
			"dashboard",
			"editpost",
			"posts",
			"users",
			"edituser",
			"iplogs"
			
	);

	if($adminA->ID > 0 or $adminSM->ID > 0) {

		array_push($totalArray, "gifts");
		array_push($totalArray, "orders");
		array_push($totalArray, "editgift");
		array_push($totalArray, "newgift");
		array_push($totalArray, "editorder");
		array_push($totalArray, "newcode");
		array_push($totalArray, "codes");
		array_push($totalArray, "account");  
		array_push($totalArray, "newtransaction");  
		array_push($totalArray, "accountactivities");  
		array_push($totalArray, "payments");  
		array_push($totalArray, "adminHistory");	
		array_push($totalArray, "saleHistory");	
		array_push($totalArray, "giftHistory");	
		array_push($totalArray, "payouts");	
		
	}
	
	if($adminA->ID > 0) {  
		
		array_push($totalArray, "settings");
	
	}

	if(in_array($adminPage, $totalArray) === false) {
		
		$fnn = new functions();
		$fnn->redirect("404");
		
	}
	
	?>

</div>

</div>

</div>

</section>

<div id="loading"></div>

<!-- Javascript -->

<script src="../Library/bootstrap-3.3.6/plugins/jquery/chosen.jquery.js"
	type="text/javascript"></script>
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>




