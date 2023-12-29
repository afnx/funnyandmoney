<?php
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/functions.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/users.php";

$loc = new localization($_SESSION['language']);
$user = new users ($_SESSION['userID']);
$fn = new functions();
if(!empty($user->username) AND !is_null($user->username)) {
	$fn->redirect("user/" . $user->username);
} else {
	$fn->redirect("profile?id=" . $_SESSION["userID"]);
}