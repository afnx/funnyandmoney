<?php
ini_set ( 'display_errors', 'off' );
ini_set ( 'display_startup_errors', 'off' );
error_reporting ( E_ALL );
ini_set("log_errors", 1);
ini_set("error_log", "../error_log");

//Check whether HTTP or CLI for cron
$sapi_type = php_sapi_name();
if(substr($sapi_type, 0, 3) == 'cli' || empty($_SERVER['REMOTE_ADDR'])) {
	
  require_once dirname ( dirname ( __FILE__ ) ) . "/BL/Tables/posts.php";

	$posts = new posts();

	// Update positions

	$sql = "UPDATE posts SET positionID='1' WHERE TIMESTAMPDIFF(DAY, createddate_, CURRENT_TIMESTAMP()) >= 7";

	$posts->executenonquery($sql,NULL,true);



	echo "Completed !";  
	
} else {
	
	die('Access denied!');
	exit;
  
}



?>