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

// The purpose of this file is to hide ticket tracking ID in HTTP_REFERER when querying the IP WHOIS service

define('IN_SCRIPT',1);
define('HESK_PATH','./');

// Get all the required files and functions
require(HESK_PATH . 'hesk_settings.inc.php');

// Set correct Content-Type header
header('Content-Type: text/html; charset=utf-8');

// Most people will never see this text, so it is not included in text.php
// (saves resources as we don't need to call common.inc.php and load language)
$hesklang['1']='Page Redirection';
$hesklang['2']='If you are not redirected automatically, follow <a href="%s">this link</a>'; // %s will be replaced with URL

// Don't bother validating IP address format, just sure no invalid chars are sent
if ( isset($_GET['ip']) && preg_match('/^[0-9A-Fa-f\:\.]+$/', $_GET['ip']) )
{
	// Create redirect URL
	$url = str_replace('{IP}', str_replace(':', '%3A', $_GET['ip']), $hesk_settings['ip_whois']);

	// Redirect to the IP whois
	?>
	<!DOCTYPE HTML>
	<html lang="en-US">
		<head>
			<meta charset="UTF-8">
			<meta http-equiv="refresh" content="1;url=<?php echo $url; ?>">
			<script type="text/javascript">
			window.location.href = "<?php echo $url; ?>"
			</script>
			<title><?php echo $hesklang['1']; ?></title>
		</head>
		<body>
			<?php echo sprintf($hesklang['2'], $url); ?>
		</body>
	</html>
	<?php
}

// Exit
exit;
