<?php
	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	// require_once 'library/db_user_man.php';
	$userName = $_SESSION['userEmail'];
	if( $userName == "")
		header("Location: login.php");
	// $userInfo = getUserInfoFromName( $userName);
?>

<?php
include("assets/components/header.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<title>Node.sg</title>

<?php
// print_r($_SESSION['userEmail']);
?>
<div class="topBar col-lg-12">
	<a href="main.php">
		<img src="assets/imgs/vision-logo.png">
		<span class="topTitle"><strong>Node.sg</strong> Linkedin Profiles</span>
	</a>
	<div class="topUserInfo">
		<a href="logout.php">Log Out &nbsp;&nbsp;<span><i class="fa fa-sign-out"></i></span></a>
	</div>
<!-- 	<div class="topNavMenu">
		<div class="dropdown">
			<a href="javascript:;">Menu <span><i class="fa fa-bars"></i></span></a>
			<ul class="dropdown-content">
		<li><a href="account.php">Account</a></li><li><a href="dashboard.php">Dashboard</a></li><li><a href="invite.php">Invite</a></li><li><a href="userman.php">UserManagement</a></li><li><a href="dictionary.php">Dictionary</a></li>			</ul>
		</div>
	</div> -->
</div>
<div class="mainProfiles col-lg-12">
	<div class="row">
		<div class="mainTitle col-lg-12">
			<h2>Profiles</h2>
		</div>
	</div>
</div>
