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
define('HESK_PATH','./');

/* Get all the required files and functions */
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');

// Are we in maintenance mode?
hesk_check_maintenance();

// Load Knowledgebase-related functions
require(HESK_PATH . 'inc/knowledgebase_functions.inc.php');
hesk_load_database_functions();

/* Is Knowledgebase enabled? */
if (!$hesk_settings['kb_enable'])
{
	hesk_error($hesklang['kbdis']);
}

/* Connect to database */
hesk_dbConnect();

/* Rating? */
if (isset($_GET['rating']))
{
	// Detect and block robots
    if (hesk_detect_bots())
    {
		?>
		<html>
		<head>
		<meta name="robots" content="noindex, nofollow">
		</head>
		<body>
		</body>
		</html>
		<?php
    }

	// Rating
	$rating = intval( hesk_GET('rating') );

	// Rating value may only be 1 or 5
	if ($rating != 1 && $rating != 5)
	{
		die($hesklang['attempt']);
	}

	// Article ID
    $artid = intval( hesk_GET('id', 0) ) or die($hesklang['kb_art_id']);

    // Check cookies for already rated, rate and set cookie if not already
    $_COOKIE['hesk_kb_rate'] = hesk_COOKIE('hesk_kb_rate');

    if (strpos($_COOKIE['hesk_kb_rate'],'a'.$artid.'%')===false)
    {
		// Update rating, make sure it's a public article in a public category
		hesk_dbQuery("UPDATE `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_articles` AS `t1`
					LEFT JOIN `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_categories` AS `t2` ON t1.`catid` = t2.`id`
					SET `rating`=((`rating`*`votes`)+{$rating})/(`votes`+1), t1.`votes`=t1.`votes`+1
					WHERE t1.`id`='{$artid}' AND t1.`type`='0' AND t2.`type`='0'
					");
    }

    setcookie('hesk_kb_rate', $_COOKIE['hesk_kb_rate'].'a'.$artid.'%', time()+2592000);
    header('Location: knowledgebase.php?article='.$artid.'&rated=1');
    exit();
}

/* Any category ID set? */
$catid = intval( hesk_GET('category', 1) );
$artid = intval( hesk_GET('article', 0) );

if (isset($_GET['search']))
{
	$query = hesk_input( hesk_GET('search') );
}
else
{
	$query = 0;
}

$hesk_settings['kb_link'] = ($artid || $catid != 1 || $query) ? '<a href="knowledgebase.php" class="smaller">'.$hesklang['kb_text'].'</a>' : $hesklang['kb_text'];

if ($hesk_settings['kb_search'] && $query)
{
    hesk_kb_search($query);
}
elseif ($artid)
{
	// Get article from DB, make sure that article and category are public
	$result  = hesk_dbQuery("SELECT t1.*, t2.`name` AS `cat_name`
							FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_articles` AS `t1`
							LEFT JOIN `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_categories` AS `t2` ON `t1`.`catid` = `t2`.`id`
							WHERE `t1`.`id` = '{$artid}'
							AND `t1`.`type` = '0'
							AND `t2`.`type` = '0'
                            ");

    $article = hesk_dbFetchAssoc($result) or hesk_error($hesklang['kb_art_id']);
    hesk_show_kb_article($artid);
}
else
{
	hesk_show_kb_category($catid);
}
echo "</table>";
echo '<footer style="margin-top: 100px;">
<div class="footer-bottom">
			<div class="container">	
			

			<div class="container">

							<ul class="list-inline">
								<li><a href="../about">About</a></li>
								<li><a href="../help">Help</a></li>
								<li><a href="../contact">Contact</a></li>
								<li><a href="../privacy">Privacy</a></li>
								<li><a href="../terms">Terms</a></li>
							</ul>
				</div>
			
				<ul class="list-inline">
					<li><a href="http://twitter.com/funnynmoney" class="btn btn-circle btn-social-icon" data-toggle="tooltip" title="Follow us on Twitter"><i class="fa fa-twitter"></i></a></li>
					<li><a href="http://facebook.com/funnynmoney" class="btn btn-circle btn-social-icon" data-toggle="tooltip" title="Follow us on Facebook"><i class="fa fa-facebook"></i></a></li>
					<li><a href="#" class="btn btn-circle btn-social-icon" data-toggle="tooltip" title="Follow us on Google"><i class="fa fa-google-plus"></i></a></li>
				</ul>
				
					<span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=Rp18Tyb5wZzBGhzVLdVVaFhdwCsq4goJt4TRfpljAxVBq4Wkvh2QijFGWV81"></script></span>
				<br/>
				<br/>
				<p>Powered by Help Desk Ticket Software<br/>
				© 2016 Funny&Money. All rights reserved.</p>
				
			</div>
		</div>
</footer>';
exit();


/*** START FUNCTIONS ***/

function hesk_kb_header($kb_link) {
	global $hesk_settings, $hesklang;
	?>


	</td>
	</tr>
	<tr>
	<td>

    <?php
    /* Print large search box */
    hesk_kbSearchLarge();

} // END hesk_kb_header()


function hesk_kb_search($query) {
	global $hesk_settings, $hesklang;

    define('HESK_NO_ROBOTS',1);

	/* Print header */
    $hesk_settings['tmp_title'] = $hesklang['sr'] . ': ' . substr(hesk_htmlspecialchars(stripslashes($query)),0,20);
	require_once(HESK_PATH . 'inc/header.inc.php');
	hesk_kb_header($hesk_settings['kb_link']);

	$res = hesk_dbQuery('SELECT t1.`id`, t1.`subject`, LEFT(`t1`.`content`, '.max(200, $hesk_settings['kb_substrart'] * 2).') AS `content`, t1.`rating` FROM `'.hesk_dbEscape($hesk_settings['db_pfix']).'kb_articles` AS t1
    					LEFT JOIN `'.hesk_dbEscape($hesk_settings['db_pfix'])."kb_categories` AS t2 ON t1.`catid` = t2.`id`
						WHERE t1.`type`='0' AND t2.`type`='0' AND  MATCH(`subject`,`content`,`keywords`) AGAINST ('".hesk_dbEscape($query)."') LIMIT " . intval($hesk_settings['kb_search_limit']));
    $num = hesk_dbNumRows($res);

    ?>
	<p>&raquo; <b><?php echo $hesklang['sr']; ?> (<?php echo $num; ?>)</b></p>

	<?php
	if ($num == 0)
	{
		echo '<p><i>'.$hesklang['nosr'].'</i></p>
        <p>&nbsp;</p>
        ';
        hesk_show_kb_category(1,1);
	}
    else
    {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="7" height="7"><img src="img/roundcornerslt.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornerstop"></td>
	<td><img src="img/roundcornersrt.jpg" width="7" height="7" alt="" /></td>
</tr>
<tr>
	<td class="roundcornersleft">&nbsp;</td>
	<td>
		<div align="center">
        <table border="0" cellspacing="1" cellpadding="3" width="100%">
        <?php
			while ($article = hesk_dbFetchAssoc($res))
			{
	            $txt = hesk_kbArticleContentPreview($article['content']);

	            if ($hesk_settings['kb_rating'])
	            {
	            	$alt = $article['rating'] ? sprintf($hesklang['kb_rated'], sprintf("%01.1f", $article['rating'])) : $hesklang['kb_not_rated'];
	                $rat = '<td width="1" valign="top"><img src="img/star_'.(hesk_round_to_half($article['rating'])*10).'.png" width="85" height="16" alt="'.$alt.'" border="0" style="vertical-align:text-bottom" /></td>';
	            }
	            else
	            {
	            	$rat = '';
	            }

				echo '
				<tr>
				<td>
	                <table border="0" width="100%" cellspacing="0" cellpadding="1">
	                <tr>
	                <td width="1" valign="top"><img src="img/article_text.png" width="16" height="16" border="0" alt="" style="vertical-align:middle" /></td>
	                <td valign="top"><a href="knowledgebase.php?article='.$article['id'].'">'.$article['subject'].'</a></td>
	                '.$rat.'
                    </tr>
	                </table>
	                <table border="0" width="100%" cellspacing="0" cellpadding="1">
	                <tr>
	                <td width="1" valign="top"><img src="img/blank.gif" width="16" height="10" style="vertical-align:middle" alt="" /></td>
	                <td><span class="article_list">'.$txt.'</span></td>
                    </tr>
	                </table>

	            </td>
				</tr>';
			}
	?>
    	</table>
        </div>
	</td>
	<td class="roundcornersright">&nbsp;</td>
</tr>
<tr>
	<td><img src="img/roundcornerslb.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornersbottom"></td>
	<td width="7" height="7"><img src="img/roundcornersrb.jpg" width="7" height="7" alt="" /></td>
</tr>
</table>

    <p>&nbsp;<br />&laquo; <a href="javascript:history.go(-1)"><?php echo $hesklang['back']; ?></a></p>
    <?php
    } // END else

} // END hesk_kb_search()


function hesk_show_kb_article($artid)
{
	global $hesk_settings, $hesklang, $article;

	// Print header
    $hesk_settings['tmp_title'] = $article['subject'];
	require_once(HESK_PATH . 'inc/header.inc.php');
	hesk_kb_header($hesk_settings['kb_link']);

    // Update views by 1 - exclude known bots and reloads because of ratings
    if ( ! isset($_GET['rated']) && ! hesk_detect_bots())
    {
		hesk_dbQuery("UPDATE `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_articles` SET `views`=`views`+1 WHERE `id`={$artid} LIMIT 1");
    }

    echo '

 
		 <div class="panel panel-default">
 

					<div class="panel-heading">
						<h2 class="panel-title">'.$article['subject'].'</h2>
					</div>

 
					<div class="panel-body">
		
		
		
    '. $article['content'];

    if ( ! empty($article['attachments']))
    {
		echo '<p><b>'.$hesklang['attachments'].':</b><br />';
		$att=explode(',',substr($article['attachments'], 0, -1));
		foreach ($att as $myatt)
        {
			list($att_id, $att_name) = explode('#', $myatt);
			echo '<img src="img/clip.png" width="16" height="16" alt="'.$att_name.'" style="align:text-bottom" /> <a href="download_attachment.php?kb_att='.$att_id.'" rel="nofollow">'.$att_name.'</a><br />';
		}
		echo '</p>';
    }

	// Article rating
	if ($hesk_settings['kb_rating'] && strpos( hesk_COOKIE('hesk_kb_rate'),'a'.$artid.'%')===false )
	{
		echo '
	    <div id="rating" class="rate" align="right">&nbsp;<br />'.$hesklang['rart'].'
			<a href="Javascript:void(0)" onclick="Javascript:window.location=\'knowledgebase.php?rating=5&amp;id='.$article['id'].'\'" rel="nofollow">'.strtolower($hesklang['yes']).'</a> /
	        <a href="Javascript:void(0)" onclick="Javascript:window.location=\'knowledgebase.php?rating=1&amp;id='.$article['id'].'\'" rel="nofollow">'.strtolower($hesklang['no']).'</a>
	    </div>
        ';
	}
    echo '</div></div>';

	// Related articles
	if ($hesk_settings['kb_related'])
	{
		require(HESK_PATH . 'inc/mail/email_parser.php');

		$query = hesk_dbEscape( $article['subject'] . ' ' . convert_html_to_text($article['content']) );

		// Get relevant articles from the database
		$res = hesk_dbQuery("SELECT t1.`id`, t1.`subject`, MATCH(`subject`,`content`,`keywords`) AGAINST ('{$query}') AS `score` FROM `".hesk_dbEscape($hesk_settings['db_pfix']).'kb_articles` AS t1 LEFT JOIN `'.hesk_dbEscape($hesk_settings['db_pfix'])."kb_categories` AS t2 ON t1.`catid` = t2.`id` WHERE t1.`type`='0' AND t2.`type`='0' AND MATCH(`subject`,`content`,`keywords`) AGAINST ('{$query}') LIMIT ".intval($hesk_settings['kb_related']+1));

		// Array with related articles
		$related_articles = array();

		while ($related = hesk_dbFetchAssoc($res))
		{
			// Get base match score from the first article
			if ( ! isset($base_score) )
			{
				$base_score = $related['score'];
			}

			// Ignore this article
			if ( $related['id'] == $artid )
			{
				continue;
			}

			// Stop when articles reach less than 10% of base score
			if ($related['score'] / $base_score < 0.10)
			{
				break;
			}

			// This is a valid related article
			$related_articles[$related['id']] = $related['subject'];
		}

		// Print related articles if we have any valid matches
		if ( count($related_articles) )
		{
			echo ' <div class="panel panel-default" style="margin-top: 30px;">
 

					<div class="panel-heading">
						<h3 class="panel-title">'.$hesklang['relart'].'</h3>
					</div>

 
					<div class="panel-body">';
			foreach ($related_articles as $id => $subject)
			{
				echo '<img src="img/article_text.png" width="16" height="16" border="0" alt="" style="vertical-align:middle;padding:2px;" /> <a href="knowledgebase.php?article='.$id.'">'.$subject.'</a><br />';
			}
			echo '</div></div>';
		}
	}

    if ($article['catid']==1)
    {
    	$link = 'knowledgebase.php';
    }
    else
    {
    	$link = 'knowledgebase.php?category='.$article['catid'];
    }
    ?>

 <div class="panel panel-default" style="margin-top: 30px;">
 

					<div class="panel-heading">
						<h3 class="panel-title"><?php echo $hesklang['ad']; ?></h3>
					</div>

 
					<div class="panel-body">

	<table border="0">
    <tr>
    <td><?php echo $hesklang['aid']; ?>: </td>
    <td><?php echo $article['id']; ?></td>
    </tr>
    <tr>
    <td><?php echo $hesklang['category']; ?>: </td>
    <td><a href="<?php echo $link; ?>"><?php echo $article['cat_name']; ?></a></td>
    </tr>

    <?php
    if ($hesk_settings['kb_date'])
    {
    ?>
    <tr>
    <td><?php echo $hesklang['dta']; ?>: </td>
    <td><?php echo hesk_date($article['dt'], true); ?></td>
    </tr>
    <?php
    }

    if ($hesk_settings['kb_views'])
    {
    ?>
    <tr>
    <td><?php echo $hesklang['views']; ?>: </td>
    <td><?php echo (isset($_GET['rated']) ? $article['views'] : $article['views']+1); ?></td>
    </tr>
    <?php
    }

	if ($hesk_settings['kb_rating'])
	{
		$alt = $article['rating'] ? sprintf($hesklang['kb_rated'], sprintf("%01.1f", $article['rating'])) : $hesklang['kb_not_rated'];
		echo '
        <tr>
        <td>'.$hesklang['rating'].' ('.$hesklang['votes'].'):</td>
        <td><img src="img/star_'.(hesk_round_to_half($article['rating'])*10).'.png" width="85" height="16" alt="'.$alt.'" title="'.$alt.'" border="0" style="vertical-align:text-bottom" /> ('.$article['votes'].')</td>
        </tr>
        ';
	}
	?>
    </table>
    </div>
	</div>

    <?php
    if (!isset($_GET['suggest']))
    {
    	?>
		<p>&nbsp;<br />&laquo; <a href="javascript:history.go(<?php echo isset($_GET['rated']) ? '-2' : '-1'; ?>)"><?php echo $hesklang['back']; ?></a></p>
        <?php
    }
    else
    {
    	?>
        <p>&nbsp;</p>
        <?php
    }

} // END hesk_show_kb_article()


function hesk_show_kb_category($catid, $is_search = 0) {
	global $hesk_settings, $hesklang;

	$res = hesk_dbQuery("SELECT `name`,`parent` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_categories` WHERE `id`='{$catid}' AND `type`='0' LIMIT 1");
    $thiscat = hesk_dbFetchAssoc($res) or hesk_error($hesklang['kb_cat_inv']);

    if ($is_search == 0)
    {
		/* Print header */
		$hesk_settings['tmp_title'] = $hesk_settings['hesk_title'] . ' - ' . hesk_htmlspecialchars($thiscat['name']);
		require_once(HESK_PATH . 'inc/header.inc.php');
		hesk_kb_header($hesk_settings['kb_link']);

		// If we are in "Knowledgebase only" mode show system messages
		if ($catid == 1 && hesk_check_kb_only(false) )
		{
			// Service messages
			$res = hesk_dbQuery('SELECT `title`, `message`, `style` FROM `'.hesk_dbEscape($hesk_settings['db_pfix'])."service_messages` WHERE `type`='0' ORDER BY `order` ASC");
			while ($sm=hesk_dbFetchAssoc($res))
			{
				hesk_service_message($sm);
			}
		}
    }

	if ($thiscat['parent'])
	{
		$link = ($thiscat['parent'] == 1) ? 'knowledgebase.php' : 'knowledgebase.php?category='.$thiscat['parent'];
		echo '<span class="homepageh3">&raquo; '.$hesklang['kb_cat'].': '.$thiscat['name'].'</span>
        &nbsp;(<a href="javascript:history.go(-1)">'.$hesklang['back'].'</a>)
		';
	}

	$result = hesk_dbQuery("SELECT `id`,`name`,`articles` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_categories` WHERE `parent`='{$catid}' AND `type`='0' ORDER BY `cat_order` ASC");
	if (hesk_dbNumRows($result) > 0)
	{
        ?>

		<p>&raquo; <b><?php echo $hesklang['kb_cat_sub']; ?>:</b></p>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="7" height="7"><img src="img/roundcornerslt.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornerstop"></td>
	<td><img src="img/roundcornersrt.jpg" width="7" height="7" alt="" /></td>
</tr>
<tr>
	<td class="roundcornersleft">&nbsp;</td>
	<td>

		<table border="0" cellspacing="1" cellpadding="3" width="100%">

		<?php
		$per_col = $hesk_settings['kb_cols'];
		$i = 1;

		while ($cat = hesk_dbFetchAssoc($result))
		{

			if ($i == 1)
		    {
				echo '<tr>';
		    }

			echo '
		    <td width="50%" valign="top">
			<table border="0">
			<tr><td><img src="img/folder.gif" width="20" height="20" alt="" style="vertical-align:middle" /><a href="knowledgebase.php?category='.$cat['id'].'">'.$cat['name'].'</a></td></tr>
			';

			/* Print most popular/sticky articles */
			if ($hesk_settings['kb_numshow'] && $cat['articles'])
		    {
		        $res = hesk_dbQuery("SELECT `id`,`subject` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_articles` WHERE `catid`='{$cat['id']}' AND `type`='0' ORDER BY `sticky` DESC, `views` DESC, `art_order` ASC LIMIT " . (intval($hesk_settings['kb_numshow']) + 1) );
		        $num = 1;
				while ($art = hesk_dbFetchAssoc($res))
				{
					echo '
		            <tr>
		            <td><img src="img/article_text.png" width="16" height="16" border="0" alt="" style="vertical-align:middle" />
		            <a href="knowledgebase.php?article='.$art['id'].'" class="article">'.$art['subject'].'</a></td>
		            </tr>';

		            if ($num == $hesk_settings['kb_numshow'])
		            {
		            	break;
		            }
		            else
		            {
		            	$num++;
		            }
				}
		        if (hesk_dbNumRows($res) > $hesk_settings['kb_numshow'])
		        {
		        	echo '<tr><td>&raquo; <a href="knowledgebase.php?category='.$cat['id'].'"><i>'.$hesklang['m'].'</i></a></td></tr>';
		        }
		    }

			echo '
			</table>
		    </td>
			';

			if ($i == $per_col)
		    {
				echo '</tr>';
		        $i = 0;
		    }
			$i++;
		}
		/* Finish the table if needed */
		if ($i != 1)
		{
			for ($j=1;$j<=$per_col;$j++)
		    {
				echo '<td width="50%">&nbsp;</td>';
				if ($i == $per_col)
			    {
					echo '</tr>';
			        break;
			    }
		        $i++;
		    }
		}

		?>
		</table>

	</td>
	<td class="roundcornersright">&nbsp;</td>
</tr>
<tr>
	<td><img src="img/roundcornerslb.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornersbottom"></td>
	<td width="7" height="7"><img src="img/roundcornersrb.jpg" width="7" height="7" alt="" /></td>
</tr>
</table>

	<?php
	} // END if NumRows > 0
	?>

	<p>&raquo; <b><?php echo $hesklang['ac']; ?></b></p>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="7" height="7"><img src="img/roundcornerslt.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornerstop"></td>
	<td><img src="img/roundcornersrt.jpg" width="7" height="7" alt="" /></td>
</tr>
<tr>
	<td class="roundcornersleft">&nbsp;</td>
	<td>

	<?php
	$res = hesk_dbQuery("SELECT `id`, `subject`, LEFT(`content`, ".max(200, $hesk_settings['kb_substrart'] * 2).") AS `content`, `rating` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_articles` WHERE `catid`='{$catid}' AND `type`='0' ORDER BY `sticky` DESC, `art_order` ASC");
	if (hesk_dbNumRows($res) == 0)
	{
		echo '<p><i>'.$hesklang['noac'].'</i></p>';
	}
	else
	{
			echo '<div align="center"><table border="0" cellspacing="1" cellpadding="3" width="100%">';
			while ($article = hesk_dbFetchAssoc($res))
			{
	            $txt = hesk_kbArticleContentPreview($article['content']);

	            if ($hesk_settings['kb_rating'])
	            {
	            	$alt = $article['rating'] ? sprintf($hesklang['kb_rated'], sprintf("%01.1f", $article['rating'])) : $hesklang['kb_not_rated'];
	                $rat = '<td width="1" valign="top"><img src="img/star_'.(hesk_round_to_half($article['rating'])*10).'.png" width="85" height="16" alt="'.$alt.'" title="'.$alt.'" border="0" style="vertical-align:text-bottom" /></td>';
	            }
	            else
	            {
	            	$rat = '';
	            }

				echo '
				<tr>
				<td>
	                <table border="0" width="100%" cellspacing="0" cellpadding="1">
	                <tr>
	                <td width="1" valign="top"><img src="img/article_text.png" width="16" height="16" border="0" alt="" style="vertical-align:middle" /></td>
	                <td valign="top"><a href="knowledgebase.php?article='.$article['id'].'">'.$article['subject'].'</a></td>
	                '.$rat.'
                    </tr>
	                </table>
	                <table border="0" width="100%" cellspacing="0" cellpadding="1">
	                <tr>
	                <td width="1" valign="top"><img src="img/blank.gif" width="16" height="10" style="vertical-align:middle" alt="" /></td>
	                <td><span class="article_list">'.$txt.'</span></td>
                    </tr>
	                </table>
	            </td>
				</tr>';
			}
		    echo '</table></div>';
	}
	?>

	</td>
	<td class="roundcornersright">&nbsp;</td>
</tr>
<tr>
	<td><img src="img/roundcornerslb.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornersbottom"></td>
	<td width="7" height="7"><img src="img/roundcornersrb.jpg" width="7" height="7" alt="" /></td>
</tr>
</table>

	<?php
    /* On the main KB page print out top and latest articles if needed */
    if ($catid == 1)
	{
        /* Get list of top articles */
        hesk_kbTopArticles($hesk_settings['kb_popart'], 0);

        /* Get list of latest articles */
        hesk_kbLatestArticles($hesk_settings['kb_latest'], 0);
	}

} // END hesk_show_kb_category()
?>
