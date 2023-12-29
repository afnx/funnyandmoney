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


function hesk_profile_tab($session_array='new',$is_profile_page=true)
{
	global $hesk_settings, $hesklang, $can_reply_tickets, $can_view_tickets, $can_view_unassigned;
	?>
	<!-- TABS -->
	<div class="tabber" id="tab1">

		<!-- PROFILE INFO -->
		<div class="tabbertab">
		<h2><?php echo $hesklang['pinfo']; ?></h2>

		&nbsp;<br />

		<table border="0" width="100%">
		<tr>
		<td width="200" style="text-align:right"><?php echo $hesklang['real_name']; ?>: <font class="important">*</font></td>
		<td align="left"><input type="text" name="name" size="40" maxlength="50" value="<?php echo $_SESSION[$session_array]['name']; ?>" /></td>
		</tr>
		<tr>
		<td width="200" style="text-align:right"><?php echo $hesklang['email']; ?>: <font class="important">*</font></td>
		<td align="left"><input type="text" name="email" size="40" maxlength="255" value="<?php echo $_SESSION[$session_array]['email']; ?>" /></td>
		</tr>
		<?php
		if ( ! $is_profile_page || $_SESSION['isadmin'])
		{
		?>
		<tr>
		<td width="200" style="text-align:right"><?php echo $hesklang['username']; ?>: <font class="important">*</font></td>
		<td><input type="text" name="user" size="40" maxlength="20" value="<?php echo $_SESSION[$session_array]['user']; ?>" /></td>
		</tr>
		<?php
		}
		?>
		<tr>
		<td width="200" style="text-align:right"><?php echo $is_profile_page ? $hesklang['new_pass'] : $hesklang['pass']; ?>:</td>
		<td><input type="password" name="newpass" autocomplete="off" size="40" value="<?php echo isset($_SESSION[$session_array]['cleanpass']) ? $_SESSION[$session_array]['cleanpass'] : ''; ?>" onkeyup="javascript:hesk_checkPassword(this.value)" /></td>
		</tr>
		<tr>
		<td width="200" style="text-align:right"><?php echo $hesklang['confirm_pass']; ?>:</td>
		<td><input type="password" name="newpass2" autocomplete="off" size="40" value="<?php echo isset($_SESSION[$session_array]['cleanpass']) ? $_SESSION[$session_array]['cleanpass'] : ''; ?>" /></td>
		</tr>
		<tr>
		<td width="200" style="text-align:right"><?php echo $hesklang['pwdst']; ?>:</td>
		<td>
		<div style="border: 1px solid gray; width: 100px;">
		<div id="progressBar" style="font-size: 1px; height: 14px; width: 0px; border: 1px solid white;">
		</div>
		</div>
		</td>
		</tr>
		<?php
		if ( ! $is_profile_page && $hesk_settings['autoassign'])
		{
			?>
			<tr>
			<td width="200" style="text-align:right">&nbsp;</td>
			<td>&nbsp;<br /><label><input type="checkbox" name="autoassign" value="Y" <?php if ( isset($_SESSION[$session_array]['autoassign']) && ! empty($_SESSION[$session_array]['autoassign']) ) {echo 'checked="checked"';} ?> /> <?php echo $hesklang['user_aa']; ?></label></td>
			</tr>
			<?php
		}
		?>
		</table>

		&nbsp;<br />&nbsp;

		</div>
		<!-- PROFILE INFO -->

		<?php
		if ( ! $is_profile_page)
		{
		?>
		<!-- PERMISSIONS -->
		<div class="tabbertab">
		<h2><?php echo $hesklang['permissions']; ?></h2>

		&nbsp;<br />

		<table border="0" width="100%">
		<tr>
		<td valign="top" width="200" style="text-align:right"><?php echo $hesklang['atype']; ?>:</td>
		<td valign="top">

		<?php
		/* Only administrators can create new administrator accounts */
		if ($_SESSION['isadmin'])
		{
			?>
		    <label><input type="radio" name="isadmin" value="1" onchange="Javascript:hesk_toggleLayerDisplay('options')" <?php if ($_SESSION[$session_array]['isadmin']) echo 'checked="checked"'; ?> /> <b><?php echo $hesklang['administrator'].'</b> '.$hesklang['admin_can']; ?></label><br />
			<label><input type="radio" name="isadmin" value="0" onchange="Javascript:hesk_toggleLayerDisplay('options')" <?php if (!$_SESSION[$session_array]['isadmin']) echo 'checked="checked"'; ?> /> <b><?php echo $hesklang['astaff'].'</b> '.$hesklang['staff_can']; ?></label>
		    <?php
		}
		else
		{
			echo '<b>'.$hesklang['astaff'].'</b> '.$hesklang['staff_can'];
		}
		?>

		</td>
		</tr>
		</table>

		&nbsp;<br />

		<div id="options" style="display: <?php echo ($_SESSION['isadmin'] && $_SESSION[$session_array]['isadmin']) ? 'none' : 'block'; ?>;">

		<table border="0" width="100%">
		<tr>
		<td valign="top" width="200" style="text-align:right"><?php echo $hesklang['allowed_cat']; ?>: <font class="important">*</font></td>
		<td valign="top">
		<?php
		foreach ($hesk_settings['categories'] as $catid => $catname)
		{
			echo '<label><input type="checkbox" name="categories[]" value="' . $catid . '" ';
			if ( in_array($catid,$_SESSION[$session_array]['categories']) )
			{
				echo ' checked="checked" ';
			}
			echo ' />' . $catname . '</label><br /> ';
		}
		?>
		&nbsp;
		</td>
		</tr>
		<tr>
		<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
		<td valign="top" width="200" style="text-align:right"><?php echo $hesklang['allow_feat']; ?>: <font class="important">*</font></td>
		<td valign="top">
		<?php
		foreach ($hesk_settings['features'] as $k)
		{
			echo '<label><input type="checkbox" name="features[]" value="' . $k . '" ';
			if (in_array($k,$_SESSION[$session_array]['features']))
			{
				echo ' checked="checked" ';
			}
			echo ' />' . $hesklang[$k] . '</label><br /> ';
		}
		?>
		&nbsp;
		</td>
		</tr>
		</table>

		</div>

		&nbsp;<br />&nbsp;

		</div>
		<!-- PERMISSIONS -->
		<?php
		}
		?>

		<!-- SIGNATURE -->
		<div class="tabbertab">
		<h2><?php echo $hesklang['sig']; ?></h2>

		&nbsp;<br />

		<table border="0" width="100%">
		<tr>
		<td valign="top" width="200" style="text-align:right">&nbsp;</td>
		<td><?php echo $hesklang['signature_max']; ?>:<br />&nbsp;<br /><textarea name="signature" rows="10" cols="60"><?php echo $_SESSION[$session_array]['signature']; ?></textarea><br />
		<?php echo $hesklang['sign_extra']; ?></td>
		</tr>
		</table>

		&nbsp;<br />&nbsp;

		</div>
		<!-- SIGNATURE -->

		<?php
		if ( ! $is_profile_page || $can_reply_tickets )
		{
		?>
		<!-- PREFERENCES -->
		<div class="tabbertab">
		<h2><?php echo $hesklang['pref']; ?></h2>

		&nbsp;<br />

		<table border="0" width="100%">
		<tr>
		<td style="text-align:right" valign="top" width="200"><?php echo $hesklang['aftrep']; ?>:</td>
		<td>
	    <label><input type="radio" name="afterreply" value="0" <?php if (!$_SESSION[$session_array]['afterreply']) {echo 'checked="checked"';} ?>/> <?php echo $hesklang['showtic']; ?></label><br />
	    <label><input type="radio" name="afterreply" value="1" <?php if ($_SESSION[$session_array]['afterreply'] == 1) {echo 'checked="checked"';} ?>/> <?php echo $hesklang['gomain']; ?></label><br />
	    <label><input type="radio" name="afterreply" value="2" <?php if ($_SESSION[$session_array]['afterreply'] == 2) {echo 'checked="checked"';} ?>/> <?php echo $hesklang['shownext']; ?></label><br />
	    </td>
		</tr>
		<tr>
		<td cellspan="2">&nbsp;</td>
		</tr>
		<tr>
		<td style="text-align:right" valign="top" width="200"><?php echo $hesklang['defaults']; ?>:</td>
		<td>
		<?php
		if ($hesk_settings['time_worked'])
		{
		?>
		<label><input type="checkbox" name="autostart" value="1" <?php if (!empty($_SESSION[$session_array]['autostart'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['autoss']; ?></label><br />
		<?php
		}
		?>
		<label><input type="checkbox" name="notify_customer_new" value="1" <?php if (!empty($_SESSION[$session_array]['notify_customer_new'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['pncn']; ?></label><br />
		<label><input type="checkbox" name="notify_customer_reply" value="1" <?php if (!empty($_SESSION[$session_array]['notify_customer_reply'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['pncr']; ?></label><br />
		<label><input type="checkbox" name="show_suggested" value="1" <?php if (!empty($_SESSION[$session_array]['show_suggested'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['pssy']; ?></label><br />
		</td>
		</tr>
		</table>

		&nbsp;<br />&nbsp;

		</div>
		<!-- PREFERENCES -->
		<?php
		}
		?>

		<!-- NOTIFICATIONS -->
		<div class="tabbertab">
		<h2><?php echo $hesklang['notn']; ?></h2>

		<p><?php echo $hesklang['nomw']; ?></p>

		<table border="0" width="100%">
		<tr>
		<td>
		<?php
        if ( ! $is_profile_page || $can_view_tickets)
		{
			if ( ! $is_profile_page || $can_view_unassigned)
			{
				?>
				<label><input type="checkbox" name="notify_new_unassigned" value="1" <?php if (!empty($_SESSION[$session_array]['notify_new_unassigned'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['nwts']; ?> <?php echo $hesklang['unas']; ?></label><br />
				<?php
			}
			?>
			<label><input type="checkbox" name="notify_new_my" value="1" <?php if (!empty($_SESSION[$session_array]['notify_new_my'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['nwts']; ?> <?php echo $hesklang['s_my']; ?></label><br />
			<hr />
			<?php
			if ( ! $is_profile_page || $can_view_unassigned)
			{
				?>
				<label><input type="checkbox" name="notify_reply_unassigned" value="1" <?php if (!empty($_SESSION[$session_array]['notify_reply_unassigned'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['ncrt']; ?> <?php echo $hesklang['unas']; ?></label><br />
				<?php
			}
			?>
			<label><input type="checkbox" name="notify_reply_my" value="1" <?php if (!empty($_SESSION[$session_array]['notify_reply_my'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['ncrt']; ?> <?php echo $hesklang['s_my']; ?></label><br />
			<hr />
			<label><input type="checkbox" name="notify_assigned" value="1" <?php if (!empty($_SESSION[$session_array]['notify_assigned'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['ntam']; ?></label><br />
			<label><input type="checkbox" name="notify_note" value="1" <?php if (!empty($_SESSION[$session_array]['notify_note'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['ntnote']; ?></label><br />
			<?php
		}
		?>
		<label><input type="checkbox" name="notify_pm" value="1" <?php if (!empty($_SESSION[$session_array]['notify_pm'])) {echo 'checked="checked"';}?> /> <?php echo $hesklang['npms']; ?></label><br />
		</td>
		</tr>
		</table>

		&nbsp;<br />&nbsp;

		</div>
		<!-- NOTIFICATIONS -->

	</div>
	<!-- TABS -->

	<script language="Javascript" type="text/javascript"><!--
	hesk_checkPassword(document.form1.newpass.value);
	//-->
	</script>

	<?php
} // END hesk_profile_tab()
