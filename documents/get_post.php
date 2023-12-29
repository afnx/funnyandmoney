<?php 
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");
session_start();
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/publisherPosts.php";
require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/publishers.php";

if( isset( $_GET['name'] ) ) {
	if (isset ( $_SESSION['userID'] )) {
		$postDocument = $_GET['name'];
		$publisher = publishers::checkPublisherWithUserID($_SESSION["userID"]);
		if($publisher->ID > 0) {
			$contP = $publisher->ID;
		} else {
			$contP = 0;
		}
			$cont = publisherPosts::cehckPostDocument($_SESSION["userID"], $contP, $postDocument);
		if($cont->ID > 0) {
			$post_file = "{$_SERVER['DOCUMENT_ROOT']}/Uploads/publisherPosts/{$postDocument}";
			if( file_exists( $post_file ) ) {
				header( 'Cache-Control: public' );
				header( 'Content-Description: File Transfer' );
				header( "Content-Disposition: attachment; filename={$cont->document}" );  
				header( 'Content-Transfer-Encoding: binary' );
				readfile( $post_file );
				exit;
			} else {$error = "No such document!";}
		} else {$error = "Unauthorized access!";}
	} else {$error = "Log in pls.";}
} else {$error = "No such document!";}
die( "ERROR: " . $error);
?>