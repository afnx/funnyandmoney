<?php
/*******************************************************************************
*  Title: Help Desk Software HESK
*  Version: 2.6.7 from 18th April 2016
*  Author: Klemen Stirn
*  Website: http://www.hesk.com
********************************************************************************
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2005-2016 Klemen Stirn. All Rights Reserved.
*  HESK is a registered trademark of Klemen Stirn.

*  The HESK may be used and modified free of charge by anyone
*  AS LONG AS COPYRIGHT NOTICES AND ALL THE COMMENTS REMAIN INTACT.
*  By using this code you agree to indemnify Klemen Stirn from any
*  liability that might arise from it's use.

*  Selling the code for this program, in part or full, without prior
*  written consent is expressly forbidden.

*  Using this code, in part or full, to create derivate work,
*  new scripts or products is expressly forbidden. Obtain permission
*  before redistributing this software over the Internet or in
*  any other medium. In all cases copyright and header must remain intact.
*  This Copyright is in full effect in any country that has International
*  Trade Agreements with the United States of America or
*  with the European Union.

*  Removing any of the copyright notices without purchasing a license
*  is expressly forbidden. To remove HESK copyright notice you must purchase
*  a license for this script. For more information on how to obtain
*  a license please visit the page below:
*  https://www.hesk.com/buy.php
*******************************************************************************/

/* Check if this is a valid include */
if (!defined('IN_SCRIPT')) {die('Invalid attempt');}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo (isset($hesk_settings['tmp_title']) ? $hesk_settings['tmp_title'] : $hesk_settings['hesk_title']); ?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $hesklang['ENCODING']; ?>" />
	<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="">
<meta name="author" content="">
<meta name="theme-color" content="#141619">
	<!-- Funny&Money THEME -->
	<!-- FAVICON -->
<link rel="shortcut icon"
	href="/Library/bootstrap-3.3.6/img/favicon.ico">

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
	
	<link
	href="/Library/bootstrap-3.3.6/plugins/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link
	href="/Library/bootstrap-3.3.6/plugins/ionicons/css/ionicons.min.css"
	rel="stylesheet">
<link href="/Library/bootstrap-3.3.6/plugins/animate/animate.min.css"
	rel="stylesheet">
	<!-- Funny&Money THEME -->
	<link href="<?php echo HESK_PATH; ?>hesk_style.css" type="text/css" rel="stylesheet" />
	<script language="Javascript" type="text/javascript" src="<?php echo HESK_PATH; ?>hesk_javascript.js"></script>

    <?php
	/* Prepare Javascript that browser should load on page load */
    $onload = "javascript:var i=new Image();i.src='" . HESK_PATH . "img/orangebtnover.gif';var i2=new Image();i2.src='" . HESK_PATH . "img/greenbtnover.gif';";

	/* Tickets shouldn't be indexed by search engines */
	if (defined('HESK_NO_ROBOTS'))
	{
		?>
		<meta name="robots" content="noindex, nofollow" />
		<?php
	}

	/* If page requires calendar include calendar Javascript and CSS */
	if (defined('CALENDAR'))
	{
		?>
		<script language="Javascript" type="text/javascript" src="<?php echo HESK_PATH; ?>inc/calendar/tcal.php"></script>
		<link href="<?php echo HESK_PATH; ?>inc/calendar/tcal.css" type="text/css" rel="stylesheet" />
		<?php
	}

	/* If page requires WYSIWYG editor include TinyMCE Javascript */
	if (defined('WYSIWYG') && $hesk_settings['kb_wysiwyg'])
	{
		?>
		<script type="text/javascript" src="<?php echo HESK_PATH; ?>inc/tiny_mce/3.5.11/tiny_mce.js"></script>
		<?php
	}

	/* If page requires tabs load tabs Javascript and CSS */
	if (defined('LOAD_TABS'))
	{
		?>
		<link href="<?php echo HESK_PATH; ?>inc/tabs/tabber.css" type="text/css" rel="stylesheet" />
		<?php
	}

	/* If page requires timer load Javascript */
	if (defined('TIMER'))
	{
		?>
		<script language="Javascript" type="text/javascript" src="<?php echo HESK_PATH; ?>inc/timer/hesk_timer.js"></script>
		<?php

        /* Need to load default time or a custom one? */
        if ( isset($_SESSION['time_worked']) )
        {
        	$t = hesk_getHHMMSS($_SESSION['time_worked']);
			$onload .= "load_timer('time_worked', " . $t[0] . ", " . $t[1] . ", " . $t[2] . ");";
            unset($t);
        }
        else
        {
        	$onload .= "load_timer('time_worked', 0, 0, 0);";
        }

		/* Autostart timer? */
		if ( ! empty($_SESSION['autostart']) )
		{
			$onload .= "ss();";
		}
	}

	// Use ReCaptcha API v2?
	if (defined('RECAPTCHA'))
	{
		echo '<script src="https://www.google.com/recaptcha/api.js?hl='.$hesklang['RECAPTCHA'].'" async defer></script>';
	}
	?>

</head>

<body onload="<?php echo $onload; unset($onload); ?>">
<style>
@media screen and (max-width: 990px) {
.fast {display: none;};
}
</style>

<?php
include(HESK_PATH . 'header.txt');  
?>

	
	<header class="bg-grey-50 border-bottom-1 border-grey-300">

		<div class="container">

			<a href="https://www.funnyandmoney.com" class="logo" style="color: #e6e6e6 !important;">Funny<img style="margin-bottom: 5px;" src="img/smallLogo.png" />Money</a> <div class="fast"><a href="/ticket" style="text-decoration:none; font-size: 20px;" class="logo"><span style="font-style: italic;">Fast Help</span> </a></div>

		
		 
		<div class="nav-right">

		<div class="nav-loginmenu" style="padding: 15px;">
		<?php hesk_showTopBar($hesk_settings['hesk_title']); ?>
		
		</div>
		
		</div>
		
		</div>
		
	</header>
	
	
<div style="margin-top: 10px;" align="center">
<table border="0" cellspacing="0" cellpadding="5" class="enclosing">
<tr>
<td>
