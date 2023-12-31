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

define('IN_SCRIPT',1);
define('HESK_PATH','../');
define('LOAD_TABS',1);

/* Get all the required files and functions */
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
require(HESK_PATH . 'inc/admin_functions.inc.php');
require(HESK_PATH . 'inc/profile_functions.inc.php');
hesk_load_database_functions();

hesk_session_start();
hesk_dbConnect();
hesk_isLoggedIn();

/* Check permissions */
$can_view_tickets = hesk_checkPermission('can_view_tickets',0);
$can_reply_tickets = hesk_checkPermission('can_reply_tickets',0);
$can_view_unassigned = hesk_checkPermission('can_view_unassigned',0);

/* Update profile? */
if ( ! empty($_POST['action']))
{
	// Demo mode
	if ( defined('HESK_DEMO') )
	{
		hesk_process_messages($hesklang['sdemo'], 'profile.php', 'NOTICE');
	}

	// Update profile
	update_profile();
}
else
{
	$res = hesk_dbQuery('SELECT * FROM `'.hesk_dbEscape($hesk_settings['db_pfix'])."users` WHERE `id` = '".intval($_SESSION['id'])."' LIMIT 1");
	$tmp = hesk_dbFetchAssoc($res);

	foreach ($tmp as $k=>$v)
	{
		if ($k == 'pass')
        {
			if ($v == '499d74967b28a841c98bb4baaabaad699ff3c079')
			{
				define('WARN_PASSWORD',true);
			}
			continue;
        }
        elseif ($k == 'categories')
		{
			continue;
		}
		$_SESSION['new'][$k]=$v;
	}
}

if ( ! isset($_SESSION['new']['username']))
{
	$_SESSION['new']['username'] = '';
}

/* Print header */
require_once(HESK_PATH . 'inc/header.inc.php');

/* Print admin navigation */
require_once(HESK_PATH . 'inc/show_admin_nav.inc.php');
?>

</td>
</tr>
<tr>
<td>

<?php
/* This will handle error, success and notice messages */
hesk_handle_messages();

if (defined('WARN_PASSWORD'))
{
	hesk_show_notice($hesklang['chdp2'],'<span class="important">'.$hesklang['security'].'</span>');
}
?>

	<h3><?php echo $hesklang['profile_for'].' <b>'.$_SESSION['new']['user']; ?></b></h3>

	<?php
	if ($hesk_settings['can_sel_lang'])
	{
		/* Update preferred language in the database? */
		if (isset($_GET['save_language']) )
		{
			$newlang = hesk_input( hesk_GET('language') );

			/* Only update if it's a valid language */
			if ( isset($hesk_settings['languages'][$newlang]) )
			{
            	$newlang = ($newlang == HESK_DEFAULT_LANGUAGE) ? "NULL" : "'" . hesk_dbEscape($newlang) . "'";
				hesk_dbQuery("UPDATE `".hesk_dbEscape($hesk_settings['db_pfix'])."users` SET `language`=$newlang WHERE `id`='".intval($_SESSION['id'])."' LIMIT 1");
			}
		}

		$str  = '<form method="get" action="profile.php" style="margin:0;padding:0;border:0;white-space:nowrap;">';
        $str .= '<input type="hidden" name="save_language" value="1" />';
        $str .= '<p>'.$hesklang['chol'].': ';

        if ( ! isset($_GET) )
        {
        	$_GET = array();
        }

		foreach ($_GET as $k => $v)
		{
			if ($k == 'language' || $k == 'save_language')
			{
				continue;
			}
			$str .= '<input type="hidden" name="'.htmlentitieshesk_htmlentities($k).'" value="'.hesk_htmlentities($v).'" />';
		}

        $str .= '<select name="language" onchange="this.form.submit()">';
		$str .= hesk_listLanguages(0);
		$str .= '</select>';

	?>
        <script language="javascript" type="text/javascript">
		document.write('<?php echo str_replace(array('"','<','=','>',"'"),array('\42','\74','\75','\76','\47'),$str . '</p></form>'); ?>');
        </script>
        <noscript>
        <?php
        	echo $str . '<input type="submit" value="'.$hesklang['go'].'" /></p></form>';
        ?>
        </noscript>
	<?php
	}
    ?>

<p><?php echo $hesklang['req_marked_with']; ?> <span class="important">*</span><br />&nbsp;</p>

<form method="post" action="profile.php" name="form1">

<script language="Javascript" type="text/javascript"><!--
var tabberOptions = {
	'cookie':"tabberpr",
	'onLoad': function(argsObj)
	{
		var t = argsObj.tabber;
		var i;
		if (t.id) {
		t.cookie = t.id + t.cookie;
	}

	i = parseInt(getCookie(t.cookie));
	if (isNaN(i)) { return; }
		t.tabShow(i);
	},

	'onClick':function(argsObj)
	{
		var c = argsObj.tabber.cookie;
		var i = argsObj.index;
		setCookie(c, i);
	}
};
//-->
</script>

<script language="Javascript" type="text/javascript" src="<?php echo HESK_PATH; ?>inc/tabs/tabber-minimized.js"></script>

<?php hesk_profile_tab(); ?>

<!-- Submit -->
<p align="center"><input type="hidden" name="action" value="update" />
<input type="hidden" name="token" value="<?php hesk_token_echo(); ?>" />
<input type="submit" value="<?php echo $hesklang['update_profile']; ?>" class="orangebutton" onmouseover="hesk_btn(this,'orangebuttonover');" onmouseout="hesk_btn(this,'orangebutton');" /></p>
</form>

<p>&nbsp;</p>
<p>&nbsp;</p>

<?php
require_once(HESK_PATH . 'inc/footer.inc.php');
exit();


/*** START FUNCTIONS ***/

function update_profile() {
	global $hesk_settings, $hesklang, $can_view_unassigned;

	/* A security check */
	hesk_token_check('POST');

    $sql_pass = '';
    $sql_username = '';

    $hesk_error_buffer = '';

	$_SESSION['new']['name']  = hesk_input( hesk_POST('name') ) or $hesk_error_buffer .= '<li>' . $hesklang['enter_your_name'] . '</li>';
	$_SESSION['new']['email'] = hesk_validateEmail( hesk_POST('email'), 'ERR', 0) or $hesk_error_buffer = '<li>' . $hesklang['enter_valid_email'] . '</li>';
	$_SESSION['new']['signature'] = hesk_input( hesk_POST('signature') );

	/* Signature */
	if (strlen($_SESSION['new']['signature'])>1000)
    {
		$hesk_error_buffer .= '<li>' . $hesklang['signature_long'] . '</li>';
    }

    /* Admins can change username */
    if ($_SESSION['isadmin'])
    {
		$_SESSION['new']['user']  = hesk_input( hesk_POST('user') ) or $hesk_error_buffer .= '<li>' . $hesklang['enter_username'] . '</li>';

	    /* Check for duplicate usernames */
		$result = hesk_dbQuery("SELECT `id` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."users` WHERE `user`='".hesk_dbEscape($_SESSION['new']['user'])."' AND `id`!='".intval($_SESSION['id'])."' LIMIT 1");
		if (hesk_dbNumRows($result) != 0)
		{
	        $hesk_error_buffer .= '<li>' . $hesklang['duplicate_user'] . '</li>';
		}
        else
        {
        	$sql_username =  ",`user`='" . hesk_dbEscape($_SESSION['new']['user']) . "'";
        }
    }

	/* Change password? */
    $newpass = hesk_input( hesk_POST('newpass') );
    $passlen = strlen($newpass);
	if ($passlen > 0)
	{
        /* At least 5 chars? */
        if ($passlen < 5)
        {
        	$hesk_error_buffer .= '<li>' . $hesklang['password_not_valid'] . '</li>';
        }
        /* Check password confirmation */
        else
        {
        	$newpass2 = hesk_input( hesk_POST('newpass2') );

			if ($newpass != $newpass2)
			{
				$hesk_error_buffer .= '<li>' . $hesklang['passwords_not_same'] . '</li>';
			}
            else
            {
				$newpass_hash = hesk_Pass2Hash($newpass);
				if ($newpass_hash == '499d74967b28a841c98bb4baaabaad699ff3c079')
				{
					define('WARN_PASSWORD',true);
				}
				$sql_pass = ',`pass`=\''.$newpass_hash.'\'';
            }
        }
	}

    /* After reply */
    $_SESSION['new']['afterreply'] = intval( hesk_POST('afterreply') );
    if ($_SESSION['new']['afterreply'] != 1 && $_SESSION['new']['afterreply'] != 2)
    {
    	$_SESSION['new']['afterreply'] = 0;
    }

    // Defaults
    $_SESSION['new']['autostart']				= isset($_POST['autostart']) ? 1 : 0;
    $_SESSION['new']['notify_customer_new']		= isset($_POST['notify_customer_new']) ? 1 : 0;
    $_SESSION['new']['notify_customer_reply']	= isset($_POST['notify_customer_reply']) ? 1 : 0;
    $_SESSION['new']['show_suggested']			= isset($_POST['show_suggested']) ? 1 : 0;

    /* Notifications */
    $_SESSION['new']['notify_new_unassigned']	= empty($_POST['notify_new_unassigned']) || ! $can_view_unassigned ? 0 : 1;
    $_SESSION['new']['notify_new_my'] 			= empty($_POST['notify_new_my']) ? 0 : 1;
    $_SESSION['new']['notify_reply_unassigned'] = empty($_POST['notify_reply_unassigned']) || ! $can_view_unassigned ? 0 : 1;
    $_SESSION['new']['notify_reply_my']			= empty($_POST['notify_reply_my']) ? 0 : 1;
    $_SESSION['new']['notify_assigned']			= empty($_POST['notify_assigned']) ? 0 : 1;
    $_SESSION['new']['notify_note']				= empty($_POST['notify_note']) ? 0 : 1;
    $_SESSION['new']['notify_pm']				= empty($_POST['notify_pm']) ? 0 : 1;

    /* Any errors? */
    if (strlen($hesk_error_buffer))
    {
		/* Process the session variables */
		$_SESSION['new'] = hesk_stripArray($_SESSION['new']);

		$hesk_error_buffer = $hesklang['rfm'].'<br /><br /><ul>'.$hesk_error_buffer.'</ul>';
		hesk_process_messages($hesk_error_buffer,'NOREDIRECT');
    }
    else
    {
		/* Update database */
		hesk_dbQuery(
		"UPDATE `".hesk_dbEscape($hesk_settings['db_pfix'])."users` SET
		`name`='".hesk_dbEscape($_SESSION['new']['name'])."',
		`email`='".hesk_dbEscape($_SESSION['new']['email'])."',
		`signature`='".hesk_dbEscape($_SESSION['new']['signature'])."'
		$sql_username
		$sql_pass ,
		`afterreply`='".($_SESSION['new']['afterreply'])."' ,
		".($hesk_settings['time_worked'] ? "`autostart`='".($_SESSION['new']['autostart'])."'," : '')."
		`notify_customer_new`='".($_SESSION['new']['notify_customer_new'])."' ,
		`notify_customer_reply`='".($_SESSION['new']['notify_customer_reply'])."' ,
		`show_suggested`='".($_SESSION['new']['show_suggested'])."' ,
		`notify_new_unassigned`='".($_SESSION['new']['notify_new_unassigned'])."' ,
		`notify_new_my`='".($_SESSION['new']['notify_new_my'])."' ,
		`notify_reply_unassigned`='".($_SESSION['new']['notify_reply_unassigned'])."' ,
		`notify_reply_my`='".($_SESSION['new']['notify_reply_my'])."' ,
		`notify_assigned`='".($_SESSION['new']['notify_assigned'])."' ,
		`notify_pm`='".($_SESSION['new']['notify_pm'])."',
		`notify_note`='".($_SESSION['new']['notify_note'])."'
		WHERE `id`='".intval($_SESSION['id'])."' LIMIT 1"
		);

		/* Process the session variables */
		$_SESSION['new'] = hesk_stripArray($_SESSION['new']);

		// Do we need a new session_veify tag?
		if ( strlen($sql_username) && strlen($sql_pass) )
		{
			$_SESSION['session_verify'] = hesk_activeSessionCreateTag($_SESSION['new']['user'], $newpass_hash);
		}
		elseif ( strlen($sql_pass) )
		{
			$_SESSION['session_verify'] = hesk_activeSessionCreateTag($_SESSION['user'], $newpass_hash);
		}
		elseif ( strlen($sql_username) )
		{
			$res = hesk_dbQuery('SELECT `pass` FROM `'.hesk_dbEscape($hesk_settings['db_pfix'])."users` WHERE `id` = '".intval($_SESSION['id'])."' LIMIT 1");
			$_SESSION['session_verify'] = hesk_activeSessionCreateTag($_SESSION['new']['user'], hesk_dbResult($res) );
		}

        /* Update session variables */
        foreach ($_SESSION['new'] as $k => $v)
        {
        	$_SESSION[$k] = $v;
        }
        unset($_SESSION['new']);

		hesk_cleanSessionVars('as_notify');

	    hesk_process_messages($hesklang['profile_updated_success'],'profile.php','SUCCESS');
    }
} // End update_profile()

?>
