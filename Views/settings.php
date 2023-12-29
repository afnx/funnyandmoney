<?php
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/users.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/userSocials.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/payouts.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/definitions.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/giftRequests.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/gifts.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/digitalGiftCodes.php';
require_once dirname ( dirname ( __FILE__ ) ) . '/BL/Tables/address.php';

if (!isset($_SESSION['userID'])) {
	
	$url = trim($_SERVER["REQUEST_URI"], '/');
	
	$fn = new functions();
	$fn->redirect("login?pre=" . $url);
}

$settingsPage = isset ( $_GET ["tab"] ) ? $_GET ["tab"] : "account";

$userid = $_SESSION['userID'];

$user = new users($userid);

$loc = new localization ($_SESSION['language']);
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

.tab-select .nav-tabs > li.active > a, 
.tab-select .nav-tabs > li > a:hover, 
.tab-select .nav-tabs > li > a:focus,
.tab-select .nav-tabs > li.active > a:hover, 
.tab-select .nav-tabs > li.active > a:focus {
	border: 2px;
	box-shadow: inset 1px 1px 1px 1px #fd943f;
	-webkit-box-shadow: inset 0 -3px -3px 0 #fd943f;
	border-radius: 10px;
	-webkit-border-radius: 50px;
	padding: 12px;
	background-color: transparent;
	
}
li {
font-size: 18px !important;
}
</style>



<section class="bg-default padding-top-30 padding-bottom-30 bg-grey-50 border-bottom-1 border-grey-200" style="background-color: rgb(250, 250, 250) !important;">

	<div class="container">

		<h2 class="font-size-24 color-inverse font-weight-300"><?php echo $loc->label("Settings");?></h2>

	</div>

</section>

<section class="bg-white no-padding margin-top-20">
			<div class="tab-select sticky text-center">
				<div class="container">
					<ul class="nav nav-tabs">
						<li <?php if($settingsPage == "account" || $settingsPage == "") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="settings?tab=account"> <?php echo $loc->label("Account");?></a></li>
						<li <?php if($settingsPage == "personal") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="settings?tab=personal"> <?php echo $loc->label("Personal");?></a></li>
						<li <?php if($settingsPage == "password") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="settings?tab=password"> <?php echo $loc->label("Password");?></a></li>
						<li <?php if($settingsPage == "social") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="settings?tab=social"> <?php echo $loc->label("Social Accounts");?></a></li>
						<li <?php if($settingsPage == "interests") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="settings?tab=interests"> <?php echo $loc->label("Interests");?></a></li>
						<li <?php if($settingsPage == "address") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="settings?tab=address"> <?php echo $loc->label("Address");?></a></li>
						<li <?php if($settingsPage == "myorders") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="settings?tab=myorders"> <?php echo $loc->label("My Orders");?></a></li> 
						<li <?php if($settingsPage == "premium") {?> class="active" <?php } ?> ><a style="font-size: 16px;" href="settings?tab=premium"> <?php echo $loc->label("Premium");?></a></li>
					</ul>
				</div>
			</div>
</section>

<section class="padding-top-30 padding-bottom-50 padding-top-sm-30" id="settings-panel">


	<div class="container">

		<?php
		
		$settingsPageArray = array (
			"account",
			"password",
			"personal",
			"social",
			"interests",
			"posts",
			"giftsinfo",
			"premium"
	);
	
	
	
	$settingsPageArray = dirname ( __FILE__ ) . "/settingsViews/" . $settingsPage . ".php";
	
	if (file_exists ( $settingsPageArray )) {
		
		require_once dirname ( __FILE__ ) . "/settingsViews/" . $settingsPage . ".php";
		
	} else {
		
		$fnn = new functions();
		$fnn->redirect("404");
		
	}
	?>
	

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
<script>
				$("#submit").click(function(){
					$("#whichone").val(1);
					submitsettings();
				});
				
				$("#submit2").click(function(){
					$("#whichone").val(2);
					submitsettings();
				});
				
				$("#submit3").click(function(){
					$("#whichone").val(4);
					submitsettings();
				});
				
				
				function submitsettings(){
					
					$('#settings-panel').hide();
					$('#loading').show();
					
						$.ajax({
						type: 'POST',
						url: "../Controllers/formPosts.php?action=settings",
						data: $('#panel-body :input').serialize(),
						success: function cevap(e){
							
						$('#loading').hide();
						$('#settings-panel').show();
							
						$("#alert-text").html(e);
						$('html, body').animate({ scrollTop: 0 }, 'fast');
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
	 <?php
	 if(isset($_GET['error'])){ ?>
		$( document ).ready(function() {
		$("#alert-text").html("This social media account linked to another user at past. For that reason you have to try another social media account.");
		$('html, body').animate({ scrollTop: 0 }, 'fast');
		$("#alert").show();
		shakeForm();
		});
	 <?php } ?>
			</script>




