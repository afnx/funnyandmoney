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

/*** FUNCTIONS ***/


function hesk_testMySQL()
{
	global $hesk_settings, $hesklang, $set, $mysql_error, $mysql_log;

	define('REQUIRE_MYSQL_VERSION','5.0.7');

	// Use MySQLi extension to connect?
	$use_mysqli = function_exists('mysqli_connect') ? true : false;

	// Get variables
	$set['db_host'] = hesk_input( hesk_POST('s_db_host'), $hesklang['err_dbhost']);
	$set['db_name'] = hesk_input( hesk_POST('s_db_name'), $hesklang['err_dbname']);
	$set['db_user'] = hesk_input( hesk_POST('s_db_user'), $hesklang['err_dbuser']);
	$set['db_pass'] = hesk_input( hesk_POST('s_db_pass') );
	$set['db_pfix'] = preg_replace('/[^0-9a-zA-Z_]/', '', hesk_POST('s_db_pfix', 'hesk_') );

	// Allow & in password
    $set['db_pass'] = str_replace('&amp;', '&', $set['db_pass']);

	// MySQL tables used by HESK
	$tables = array(
		$set['db_pfix'].'attachments',
		$set['db_pfix'].'banned_emails',
		$set['db_pfix'].'banned_ips',
		$set['db_pfix'].'categories',
		$set['db_pfix'].'kb_articles',
		$set['db_pfix'].'kb_attachments',
		$set['db_pfix'].'kb_categories',
		$set['db_pfix'].'logins',
		$set['db_pfix'].'mail',
		$set['db_pfix'].'notes',
		$set['db_pfix'].'online',
		$set['db_pfix'].'pipe_loops',
		$set['db_pfix'].'replies',
		$set['db_pfix'].'reply_drafts',
		$set['db_pfix'].'reset_password',
		$set['db_pfix'].'service_messages',
		$set['db_pfix'].'std_replies',
		$set['db_pfix'].'tickets',
		$set['db_pfix'].'ticket_templates',
		$set['db_pfix'].'users',
	);

	$connection_OK = false;
    $mysql_error = '';

	ob_start();

	// Connect to MySQL
	if ($use_mysqli)
	{
		// Do we need a special port? Check and connect to the database
		if ( strpos($set['db_host'], ':') )
		{
			list($set['db_host_no_port'], $set['db_port']) = explode(':', $set['db_host']);
			$set_link = mysqli_connect($set['db_host_no_port'], $set['db_user'], $set['db_pass'], $set['db_name'], intval($set['db_port']) );
		}
		else
		{
			$set_link = mysqli_connect($set['db_host'], $set['db_user'], $set['db_pass'], $set['db_name']);
		}

		if ( ! $set_link)
		{
			ob_end_clean();
			$mysql_error = $hesklang['err_dbconn'];
			$mysql_log = "(".mysqli_connect_errno().") ".mysqli_connect_error();
			return false;
		}

		$res = mysqli_query($set_link, 'SHOW TABLES FROM `'.mysqli_real_escape_string($set_link, $set['db_name']).'`');
		while ($row = mysqli_fetch_row($res))
		{
			foreach($tables as $k => $v)
			{
				if ($v == $row[0])
				{
					unset($tables[$k]);
					break;
				}
			}
		}

		// Get MySQL version
		$mysql_version = mysqli_fetch_assoc( mysqli_query($set_link, 'SELECT VERSION() AS version') );

		// Close connections
		mysqli_close($set_link);
	}
	else
	{
		$set_link = mysql_connect($set['db_host'], $set['db_user'], $set['db_pass']);

		if ( ! $set_link)
		{
			ob_end_clean();
			$mysql_error = $hesklang['err_dbconn'];
			$mysql_log = mysql_error();
			return false;
		}

		// Select database
		if ( ! mysql_select_db($set['db_name'], $set_link) )
		{
			ob_end_clean();
			$mysql_error = $hesklang['err_dbsele'];
			$mysql_log = mysql_error();
			return false;
		}

		$res = mysql_query('SHOW TABLES FROM `'.mysql_real_escape_string($set['db_name']).'`', $set_link);
		while ($row = mysql_fetch_row($res))
		{
			foreach($tables as $k => $v)
			{
				if ($v == $row[0])
				{
					unset($tables[$k]);
					break;
				}
			}
		}

		// Get MySQL version
		$mysql_version = mysql_fetch_assoc( mysql_query('SELECT VERSION() AS version') );

		// Close connections
		mysql_close($set_link);
	}

	// Check MySQL version
	if ( version_compare($mysql_version['version'], REQUIRE_MYSQL_VERSION, '<') )
	{
		ob_end_clean();
		$mysql_error = $hesklang['err_dbversion'] . ' ' . $mysql_version['version'];
		$mysql_log = '';
		return false;
	}

	// Check PHP version for the mysql(i)_set_charset function
	$set['db_vrsn'] = ( version_compare(PHP_VERSION, '5.2.3') >= 0 ) ? 1 : 0;

	// Some tables weren't found, show an error
	if (count($tables) > 0)
	{
    	ob_end_clean();
		$mysql_error = $hesklang['err_dpi2'].'<br /><br />'.implode(',<br />', $tables);
		$mysql_log = '';
		return false;
	}
    else
    {
    	$connection_OK = true;
    }

    ob_end_clean();

    return $connection_OK;
} // END hesk_testMySQL()


function hesk_testPOP3($check_old_settings=false)
{
	global $hesk_settings, $hesklang, $set;

	$set['pop3_host_name']	= hesk_input( hesk_POST('s_pop3_host_name', 'mail.domain.com') );
	$set['pop3_host_port']	= intval( hesk_POST('s_pop3_host_port', 110) );
	$set['pop3_tls']		= empty($_POST['s_pop3_tls']) ? 0 : 1;
    $set['pop3_keep']		= empty($_POST['s_pop3_keep']) ? 0 : 1;
	$set['pop3_user']		= hesk_input( hesk_POST('s_pop3_user') );
	$set['pop3_password']	= hesk_input( hesk_POST('s_pop3_password') );

	// Are new settings the same as old? If yes, skip testing connection, assume it works
	if ($check_old_settings)
	{
		$set['tmp_pop3_host_name']	= hesk_input( hesk_POST('tmp_pop3_host_name', 'mail.domain.com') );
		$set['tmp_pop3_host_port']	= intval( hesk_POST('tmp_pop3_host_port', 110) );
		$set['tmp_pop3_tls']		= empty($_POST['tmp_pop3_tls']) ? 0 : 1;
		$set['tmp_pop3_keep']		= empty($_POST['tmp_pop3_keep']) ? 0 : 1;
		$set['tmp_pop3_user']		= hesk_input( hesk_POST('tmp_pop3_user') );
		$set['tmp_pop3_password']	= hesk_input( hesk_POST('tmp_pop3_password') );

		if (
			$set['tmp_pop3_host_name'] != 'mail.domain.com'      && // Default setting
			$set['tmp_pop3_host_name'] == $set['pop3_host_name'] &&
			$set['tmp_pop3_host_port'] == $set['pop3_host_port'] &&
			$set['tmp_pop3_tls']       == $set['pop3_tls']       &&
			$set['tmp_pop3_keep']      == $set['pop3_keep']      &&
			$set['tmp_pop3_user']      == $set['pop3_user']      &&
			$set['tmp_pop3_password']  == $set['pop3_password']
		)
		{
			return true;
		}
	}

	// Initiate POP3 class and set parameters
	require_once(HESK_PATH . 'inc/mail/pop3.php');
	$pop3 = new pop3_class;
	$pop3->hostname	= $set['pop3_host_name'];
	$pop3->port		= $set['pop3_host_port'];
	$pop3->tls		= $set['pop3_tls'];
	$pop3->debug	= 1;

	$connection_OK = false;

	ob_start();

	// Connect to POP3
	if(($error=$pop3->Open())=="")
	{
		// Authenticate
		if(($error=$pop3->Login($set['pop3_user'], hesk_htmlspecialchars_decode(stripslashes($set['pop3_password']))))=="")
		{
			if(($error=$pop3->Close()) == "")
			{
				// Connection OK
				$connection_OK = true;
			}
		}
	}

	if($error != '')
	{
    	global $pop3_error, $pop3_log;
        $pop3_error = $error;
		$pop3_log   = ob_get_contents();
	}

	ob_end_clean();

    return $connection_OK;
} // END hesk_testPOP3()


function hesk_testSMTP($check_old_settings=false)
{
	global $hesk_settings, $hesklang, $set;

	// Get variables
	$set['smtp_host_name']	= hesk_input( hesk_POST('s_smtp_host_name', 'localhost') );
	$set['smtp_host_port']	= intval( hesk_POST('s_smtp_host_port', 25) );
	$set['smtp_timeout']	= intval( hesk_POST('s_smtp_timeout', 10) );
	$set['smtp_ssl']		= empty($_POST['s_smtp_ssl']) ? 0 : 1;
	$set['smtp_tls']		= empty($_POST['s_smtp_tls']) ? 0 : 1;
	$set['smtp_user']		= hesk_input( hesk_POST('s_smtp_user') );
	$set['smtp_password']	= hesk_input( hesk_POST('s_smtp_password') );

	// Are new settings the same as old? If yes, skip testing connection, assume it works
	if ($check_old_settings)
	{
		$set['tmp_smtp_host_name']	= hesk_input( hesk_POST('tmp_smtp_host_name', 'localhost') );
		$set['tmp_smtp_host_port']	= intval( hesk_POST('tmp_smtp_host_port', 25) );
		$set['tmp_smtp_timeout']	= intval( hesk_POST('tmp_smtp_timeout', 10) );
		$set['tmp_smtp_ssl']		= empty($_POST['tmp_smtp_ssl']) ? 0 : 1;
		$set['tmp_smtp_tls']		= empty($_POST['tmp_smtp_tls']) ? 0 : 1;
		$set['tmp_smtp_user']		= hesk_input( hesk_POST('tmp_smtp_user') );
		$set['tmp_smtp_password']	= hesk_input( hesk_POST('tmp_smtp_password') );

		if (
			$set['tmp_smtp_host_name'] != 'mail.domain.com'      && // Default setting
			$set['tmp_smtp_host_name'] == $set['smtp_host_name'] &&
			$set['tmp_smtp_host_port'] == $set['smtp_host_port'] &&
			$set['tmp_smtp_timeout']   == $set['smtp_timeout']   &&
			$set['tmp_smtp_ssl']       == $set['smtp_ssl']       &&
			$set['tmp_smtp_tls']       == $set['smtp_tls']       &&
			$set['tmp_smtp_user']      == $set['smtp_user']      &&
			$set['tmp_smtp_password']  == $set['smtp_password']
		)
		{
			return true;
		}
	}    

	// Initiate SMTP class and set parameters
	require_once(HESK_PATH . 'inc/mail/smtp.php');
	$smtp = new smtp_class;
	$smtp->host_name	= $set['smtp_host_name'];
	$smtp->host_port	= $set['smtp_host_port'];
	$smtp->timeout		= $set['smtp_timeout'];
	$smtp->ssl			= $set['smtp_ssl'];
	$smtp->start_tls	= $set['smtp_tls'];
	$smtp->user			= $set['smtp_user'];
	$smtp->password		= hesk_htmlspecialchars_decode(stripslashes($set['smtp_password']));
	$smtp->debug		= 1;

	if (strlen($set['smtp_user']) || strlen($set['smtp_password']))
	{
		require_once(HESK_PATH . 'inc/mail/sasl/sasl.php');
	}

	$connection_OK = false;

	ob_start();

	// Test connection
	if ($smtp->Connect())
	{
		// SMTP connect successful
	    $connection_OK = true;
		$smtp->Disconnect();
	}
    else
    {
    	global $smtp_error, $smtp_log;
        $smtp_error = ucfirst($smtp->error);
		$smtp_log   = ob_get_contents();
    }

    $smtp_log   = ob_get_contents();
	ob_end_clean();

    return $connection_OK;
} // END hesk_testSMTP()


function hesk_generate_SPAM_question()
{
	$useChars = 'AEUYBDGHJLMNPRSTVWXZ23456789';
	$ac = $useChars{mt_rand(0,27)};
	for($i=1;$i<5;$i++)
	{
	    $ac .= $useChars{mt_rand(0,27)};
	}

    $animals = array('dog','cat','cow','pig','elephant','tiger','chicken','bird','fish','alligator','monkey','mouse','lion','turtle','crocodile','duck','gorilla','horse','penguin','dolphin','rabbit','sheep','snake','spider');
    $not_animals = array('ball','window','house','tree','earth','money','rocket','sun','star','shirt','snow','rain','air','candle','computer','desk','coin','TV','paper','bell','car','baloon','airplane','phone','water','space');

    $keys = array_rand($animals,2);
    $my_animals[] = $animals[$keys[0]];
    $my_animals[] = $animals[$keys[1]];

    $keys = array_rand($not_animals,2);
    $my_not_animals[] = $not_animals[$keys[0]];
    $my_not_animals[] = $not_animals[$keys[1]];

	$my_animals[] = $my_not_animals[0];
    $my_not_animals[] = $my_animals[0];

    $e = mt_rand(1,9);
    $f = $e + 1;
    $d = mt_rand(1,9);
    $s = intval($e + $d);

    if ($e == $d)
    {
    	$d ++;
    	$h = $d;
        $l = $e;
    }
    elseif ($e < $d)
    {
    	$h = $d;
        $l = $e;
    }
    else
    {
    	$h = $e;
        $l = $d;
    }

    $spam_questions = array(
    	$f => 'What is the next number after '.$e.'? (Use only digits to answer)',
    	'white' => 'What color is snow? (give a 1 word answer to show you are a human)',
    	'green' => 'What color is grass? (give a 1 word answer to show you are a human)',
    	'blue' => 'What color is water? (give a 1 word answer to show you are a human)',
    	$ac => 'Access code (type <b>'.$ac.'</b> here):',
    	$ac => 'Type <i>'.$ac.'</i> here to fight SPAM:',
    	$s => 'Solve this equation to show you are human: '.$e.' + '.$d.' = ',
    	$my_animals[2] => 'Which of these is not an animal: ' . implode(', ',hesk_randomize_array($my_animals)),
    	$my_not_animals[2] => 'Which of these is an animal: ' . implode(', ',hesk_randomize_array($my_not_animals)),
    	$h => 'Which number is higher <b>'.$e.'</b> or <b>'.$d.'</b>:',
    	$l => 'Which number is lower <b>'.$e.'</b> or <b>'.$d.'</b>:',
        'no' => 'Are you a robot? (yes or no)',
        'yes' => 'Are you a human? (yes or no)'
    );

    $r = array_rand($spam_questions);
	$ask = $spam_questions[$r];
    $ans = $r;

    return array($ask,$ans);
} // END hesk_generate_SPAM_question()


function hesk_randomize_array($array)
{
	$rand_items = array_rand($array, count($array));
	$new_array = array();
	foreach($rand_items as $value)
	{
	    $new_array[$value] = $array[$value];
	}

	return $new_array;
} // END hesk_randomize_array()
