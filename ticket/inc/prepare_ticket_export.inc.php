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

/* Acceptable $sort values and default asc(1)/desc(0) setting */
$sort_possible = array(
'trackid' 		=> 1,
'lastchange' 	=> 0,
'name' 			=> 1,
'subject' 		=> 1,
'status' 		=> 1,
'lastreplier' 	=> 1,
'priority' 		=> 1,
'category' 		=> 1,
'dt' 			=> 0,
'id' 			=> 1,
);

// These values should have collate appended in SQL
$sort_collation = array(
'name',
'subject',
);

// DATE
$sql .= " AND DATE(`dt`) BETWEEN '" . hesk_dbEscape($date_from) . "' AND '" . hesk_dbEscape($date_to) . "' ";


// Start the order by part of the SQL query
$sql .= " ORDER BY ";

/* Sort by which field? */
if (isset($_GET['sort']) && ! is_array($_GET['sort']) && isset($sort_possible[$_GET['sort']]))
{
	$sort = hesk_input($_GET['sort']);

    $sql .= ' `'.hesk_dbEscape($sort).'` ';

    // Need to set MySQL collation?
    if ( in_array($_GET['sort'], $sort_collation) )
    {
    	$sql .= " COLLATE '" . hesk_dbEscape($hesklang['_COLLATE']) . "' ";
    }
}
else
{
	/* Default sorting by ticket status */
    $sql .= ' `id` ';
    $sort = 'id';
}

/* Ascending or Descending? */
if (isset($_GET['asc']) && intval($_GET['asc'])==0)
{
    $sql .= ' DESC ';
    $asc = 0;
    $asc_rev = 1;

    $sort_possible[$sort] = 1;
}
else
{
    $sql .= ' ASC ';
    $asc = 1;
    $asc_rev = 0;
    if (!isset($_GET['asc']))
    {
    	$is_default = 1;
    }

    $sort_possible[$sort] = 0;
}

# Uncomment for debugging purposes
# echo "SQL: $sql<br>";
