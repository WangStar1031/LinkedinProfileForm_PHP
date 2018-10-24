<?php
	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	// require_once 'library/db_user_man.php';
	$userName = $_SESSION['userEmail'];
	// $userInfo = getUserInfoFromName( $userName);
?>

<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<title>Project Dashboard - Vision A.I.</title>

<?php
?>
