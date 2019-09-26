<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userEmail = $_SESSION['userEmail'];
	if( $userEmail == "")
		header("Location: login.php");
	require_once __DIR__ . '/library/userManager.php';

	include("assets/components/header.php");

?>
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<div class="topBar col-lg-12">
	<a href="main.php">
		<img src="assets/imgs/vision-logo.png">
		<span class="topTitle"><strong>Node.sg</strong></span>
	</a>
	<div class="topUserInfo">
		<a href="logout.php">Log Out &nbsp;&nbsp;<span><i class="fa fa-sign-out"></i></span></a>
	</div>
</div>