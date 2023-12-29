<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "error_log");
session_start ();

require_once dirname ( __FILE__ ) . "/BL/Consts/consts.php";
require_once dirname ( __FILE__ ) . "/BL/Tables/localization.php";
require_once dirname ( __FILE__ ) . "/BL/functions.php";
require_once dirname ( __FILE__ ) . "/BL/Tables/users.php";
require_once dirname ( __FILE__ ) . "/BL/Tables/admins.php";
require_once dirname ( __FILE__ ) . "/BL/Tables/campaigns.php";
require_once dirname ( __FILE__ ) . "/BL/Tables/campaignsHistory.php";
$fn = new functions();
if (!isset ( $_SESSION ["userID"] ) AND !isset($_SESSION['language'])) {
	$_SESSION['language']= detectLang();
}



if (isset ( $_SESSION ["userID"] )) {
	$loc = new localization ($_SESSION['language']);
	$page = isset ( $_GET ["page"] ) ? $_GET ["page"] : "signed";
} else {
	$loc = new localization ($_SESSION['language']);
	$page = isset ( $_GET ["page"] ) ? $_GET ["page"] : "main";
	if(!isset($_SESSION["country"])) {
		$_SESSION["country"]=iptocountry(ip());
	}
}


$dsignup = array (
			"privacy",
			"about",
			"terms",
			"contact",
			"help",
			"404",
			"logout"

	);


if (isset ( $_SESSION ["userID"] )) {
	
	$usrid = $_SESSION ["userID"];
	
	$usr = new users ($usrid);
	
	if($usr->isDeleted == 1 && $page != "logout") {
		$fn->redirect("logout");
	}
	
	$getUserBalance = mysqli_fetch_array ( $usr->getBalance ( $usrid ) );
	$userBalance = $getUserBalance ["balance"];
	if(!in_array ( strtolower ( $page ), $dsignup )){
		if($usr->signupStep != -1 AND $page != "signupstep".$usr->signupStep){
			$fn->redirect("signupstep".$usr->signupStep);
		}
	}
}

$campaign = new campaigns(1);

if($campaign->status != 0) {
	
	$campaignh = new campaignsHistory();
	$result = $campaignh->countCampaign($campaign->ID,$campaign->startdate_,$campaign->duedate_);
	$rowcc = mysqli_fetch_array($result);
	$campCount = $rowcc[0];
	
} 
 
?>
 
<!DOCTYPE html>
<html lang="<?=$_SESSION['language']?>">
<head>
<!-- META -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="Reach the large masses that you can filter them out according to the countries, ages, genders and interests.">
<meta name="keywords" content="funny,and,money,&,f&m,fnm,social,media,like,follower,follow,subscribe,comment,page,channel,subscriber,share,view,video,post,facebook,youtube,twitter,instagram,google,plus,google+,get,buy,earn,cash,shop,shopping,music,mass,reach,ad,advertiser,network,people,sponser">
<meta name="author" content="">
<meta name="theme-color" content="#141619">

<title><?php echo project::name;?></title>
<base href="https://www.funnyandmoney.com/"/>
<!-- FAVICON -->
<link rel="shortcut icon"
	href="/Assets/favicon.ico">

<!-- CORE CSS -->
<link href="/Library/bootstrap-3.3.6/css/bootstrap.min.css"
	rel="stylesheet">
<link href="/Library/bootstrap-3.3.6/css/theme.css" rel="stylesheet">
<link href="/Library/bootstrap-3.3.6/css/custom.css" rel="stylesheet">
<link href="/Library/bootstrap-3.3.6/css/helpers.min.css"
	rel="stylesheet">
<link
	href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700'
	rel='stylesheet' type='text/css'>
<link href="/Library/bootstrap-3.3.6/plugins/docsupport/prism.css"
	rel="stylesheet">
<link href="/Library/bootstrap-3.3.6/css/bootstrap-datetimepicker.css"
	rel="stylesheet">

<!-- PLUGINS -->
<link
	href="/Library/bootstrap-3.3.6/plugins/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link
	href="/Library/bootstrap-3.3.6/plugins/ionicons/css/ionicons.min.css"
	rel="stylesheet">
<link href="/Library/bootstrap-3.3.6/plugins/animate/animate.min.css"
	rel="stylesheet">
<link href="/Library/bootstrap-3.3.6/plugins/animate/animate.delay.css"
	rel="stylesheet">
<link
	href="/Library/bootstrap-3.3.6/plugins/owl-carousel/owl.carousel.css"
	rel="stylesheet">
<script src="/Library/bootstrap-3.3.6/plugins/css_browser_selector.js"
	type="text/javascript"></script>

</head>

<body class="fixed-header">
<div id="fb-root"></div> 
<script>
window.fbAsyncInit = function() {
	
    FB.init({
        appId: '1752516384964672',
        status: true,
        cookie: false,
        xfbml: true,
        oauth: true
    });
	
<?php if (isset ( $_SESSION ["userID"] )) {?>

    FB.Event.subscribe('edge.create', function(response) {
		var myID= response.split("id=");
		action(3,1,myID[1]);
    });

<?php } ?>
	
};

(function(d) {
    var js, id = 'facebook-jssdk';
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";  
    d.getElementsByTagName('head')[0].appendChild(js);
}(document));
</script>
	

	
	<header>
	

		<div class="container">   
		
			<?php if(isset($_SESSION["userID"])) { ?><span class="bar hide" style="color: rgba(0,0,0, 0.7);"></span><?php } ?>
			<a href="/" class="logo" style="color: #e6e6e6 !important;">Funny<img style="margin-bottom: 2px;" src="Assets/smallLogo.png" />Money</a>   

			<nav>						
			
			<?php if (isset($_SESSION["userID"])) {?>  
				
				<div class="nav-control"> 

					<ul>

						<li id="showCamp"><a href="addpost" <?php if($campaign->status == 1) { ?>style="color: #0e9a49; font-weight: bold;"<? } ?>><?php if($campaign->status == 1) { ?><i class="fa fa-hand-o-right" style="margin-right: 5px; color: #0e9a49;"></i><? } ?><?php echo $loc->label("Add Post");?><?php if($campaign->status == 1) { ?><?php if($campaign->limit != NULL){ echo "(" . ($campaign->limit-$campCount) . ")"; } ?><? } ?></a></li> 
						<li><a href="buy"><?php echo $loc->label("Buy");?>&nbsp;<?php echo $loc->label("Point");?></a></li> 
						<li><a href="shop"><?php echo $loc->label("Shop");?></a></li>  
						
						<?php if ($usr->cash == 1) { ?>
							
								<li><a href="cash" ><?php echo $loc->label("Cashout");?></a></li> 	  
							
						<?php } ?>
						
						
						
						<?php if (isset ( $_SESSION ["userID"] )) { 
							
							$adminC = admins::checkAdmin (  $_SESSION ["userID"] );
						
						?>
						
							<?php if ($adminC->ID > 0) { ?>
								
							<?php if (isset ( $_SESSION ["adminID"] )) { ?>
							
								<li><a href="admin" style="color: black;">Admin</a></li> 	
							
							<?php } else { ?>
							
								<li><a href="adminlogin" style="color: black;">Admin</a></li> 	
							
							<?php } ?>

							
							<?php } ?>
					
						<?php } ?>

					</ul>

				</div>	
				
				<?php }?>
				
			</nav>
			<div class="nav-right">  
			
				<?php if (isset($_SESSION["userID"])) {?>																																										
				
				<a href="balance"><div class="nav-point">
						<span id="newPoints" class="label label-success" style="margin-right:5px; display:none;">-</span><span><span id="currentPoints"><?php echo bcdiv($userBalance, 1, 2); ?></span> & </span>
					</div></a>

				<div class="nav-profile dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img
						src="<?php $user = new users($_SESSION["userID"]); if($user->picture == NULL OR empty($user->picture)){echo "images/user/avatar.jpg";} else {echo "Uploads/userImg/".$user->picture;}  ?>" alt=""><span><?php echo $_SESSION["fullName"];?></span></a>

					<ul class="dropdown-menu">
						<li class="dropdown-header"><a href="balance"><?php echo bcdiv($userBalance, 1, 2); ?> & </a></li>
						<li><a href="profile?id=<?=$_SESSION["userID"]?>"><i class="fa fa-user"></i><?php echo $loc->label("View Profile");?></a></li>
						<li><a href="balance"><i class="fa fa-get-pocket"></i><?php echo $loc->label("Balance");?></a></li>
						<li><a href="myposts"><i class="fa fa-files-o"></i><?php echo $loc->label("My Posts");?></a></li> 
						<li><a href="reference"><i class="fa fa-group"></i><?php echo $loc->label("Reference");?> <span style="color: #ffa14f;">(<?php echo $loc->label("NEW");?>!)</span></a></li>
						<li><a href="help"><i class="ion-ios-help-outline"></i><?php echo $loc->label("Help");?></a></li>
						<li><a href="settings"><i class="fa fa-gear"></i><?php echo $loc->label("Settings");?></a></li>
						<li class="divider"></li>
						<li><a href="logout"><i class="fa fa-power-off"></i><?php echo $loc->label("Sign Out");?></a></li>
					</ul>
				</div>
  

				<?php if($page == "signed"){/*echo'<a href="#" data-toggle="modal-search"><i class="fa fa-search"></i></a>';*/}?>
				
				<?php } else {?>		
				
						<div class="nav-loginmenu">

					<a href="login"><i class="glyphicon glyphicon-log-in" style="color: #f5f5f5;"></i> <span><?php echo $loc->label("Login");?></span></a>
					<a href="signup"><i class="glyphicon glyphicon-plus-sign" style="color: #f5f5f5;"></i> <span><?php echo $loc->label("Sign Up");?></span></a> 
					<!--	<li><a href="javascript:callModal('register','Register')"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $loc->label("Sign Up");?></a></li> 
				</div> -->
				<?php }?>
				
			</div>

		</div>

	</header>
	
	<?php if($campaign->status == 1) { ?>
	
			
			<?php if($campaign->locLabelT != NULL && $campaign->locLabel != NULL ) { ?>
	
			<div id="notify" style="display: none; overflow: hidden; position: fixed; z-index: 999; width: 100%;">
				
				<div class="row" >
				<div class="col-lg-12">
				<div class="alert alert-success alert-lg fade in">
					<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
					<center><i class="fa fa-gift" style="font-size: 100px;color: #e91e63;"></i></center>
					</div>
					<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
					<h4 class="alert-title"><?php echo $loc->label($campaign->locLabelT); ?></h4>
					<p><?php echo $loc->label($campaign->locLabel); ?></p>
				<?php if($campaign->limit != NULL) { ?>
					<p><?php echo $loc->label("campaignLastCountText") . ": <strong>" . ($campaign->limit-$campCount) . "</strong>"; ?></p>
				<?php } ?>
					</div>
					</div>
				</div>  
				</div>
				</div>
			</div>
			
			<?php } ?>
		
			
	<?php } ?>  

	<!-- /header -->

	<div class="modal-search">
		<div class="container">
			<input type="text" class="form-control"
				placeholder="<?php echo $loc->label("Type to search...");?>"> <i
				class="fa fa-times close"></i>
		</div>
	</div>
	<!-- /.modal-search -->

	<!-- Javascript -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script
		src="/Library/bootstrap-3.3.6/plugins/owl-carousel/owl.carousel.min.js"></script>

	<!-- Page Detail -->
	
	<?php
	$filename = dirname ( __FILE__ ) . "/Views/" . $page . ".php";
	if (file_exists ( $filename )) {
		require_once dirname ( __FILE__ ) . "/Views/" . $page . ".php";
	} else {
		require_once dirname ( __FILE__ ) . "/Views/404.php";
	}
	?>
	
	
	<!-- footer -->
	
	<?php
	$footerArray = array (
			"main",
			"privacy",
			"about",
			"terms",
			"contact",
			"help",
			"buy",
			"notifications",
			"balance",
			"shop",
			"videopost",
			"post",
			"cashout",
			"reference",
			"bepublisher"
			 
	);
	if (in_array ( strtolower ( $page ), $footerArray )) {
		require_once dirname ( __FILE__ ) . "/Views/footer.php";
	}
	?>			
		
		
	<?php
	if ($page == "signed") {
		require_once dirname ( __FILE__ ) . "/Views/footerSigned.php";
	}
	?>	
		
	
	<!-- Javascript -->
	<script
		src="/Library/bootstrap-3.3.6/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="/Library/bootstrap-3.3.6/plugins/core.js"></script>
	<script src="/Library/custom.js"></script>
	<script src="/Library/bootstrap-3.3.6/js/moment-with-locales.js"></script>
	<script src="/Library/bootstrap-3.3.6/js/bootstrap-datetimepicker.js"></script>
	<script src="/Library/bootstrap-3.3.6/js/validator.min.js"></script>

	<script>			
	$(document).ready(function(){						
	$('[data-toggle="tooltip"]').tooltip();   					
	});			
	</script>
	<script>			
	function changeLang(lang){
		if(lang == 'tr' || lang == 'en'){
			document.location.href="Controllers/formPosts.php?action=changeLang&lang="+lang+"&go="+window.location.pathname;
		}
	}
	
<?php if($campaign->status == 1) { ?>
	
<?php if($page != "addpost" && $page != "addpublisherpost") { ?>

$(document).ready(function(){
  $("#showCamp,#notify").mouseover(function(){
   $("#notify").stop().slideDown("slow");
  });
  $("#showCamp,#notify").mouseout(function(){
   $("#notify").stop().slideUp("slow");
  });
 });
	

<? } ?>
	
<? } ?>

function getBalance(handleData) {
  $.ajax({
    url:"Controllers/formPosts.php?action=getNowBalance",  
    success:function(data) {
      handleData(data); 
    }
  });
}  





	</script>
	<?php
	if(isset($_SESSION ["userID"]) AND (!isset($_GET['page']) OR $_GET['page']=='signed' OR $_GET['page']=='post' OR $_GET['page']=='videopost' OR $_GET['page']=='profile')){
		echo'
		<div id="modal" class="modal fade bs-modal" tabindex="-1" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">'.$loc->label("Close").'</span></button>
								<h4 class="modal-title" style="text-align: center;">'.$loc->label("Error").'</h4>
							</div>
							<div class="modal-body">
								
								<p id="modalText" style="text-align: center;"></p>			
								
							</div>
							<div class="modal-footer">

								<button type="button" class="btn btn-warning" data-dismiss="modal">'.$loc->label("Close").'</button>

							</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div>
				
				
				<input id="modalButton" type="hidden" data-toggle="modal" data-target=".bs-modal">
		<script>
		var actionConfig = {};
		actionConfig.pagename = "'.(empty($_GET['page'])?'signed':$_GET['page']).'";
		</script>
		<script src="/BL/actions.js?ver=1.0.0"></script>';
	}
	?>

</body>

</html>