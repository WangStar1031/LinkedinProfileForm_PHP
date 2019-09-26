<?php
	session_start();
	$from = "";
	if( isset($_GET['from'])){
		$from = $_GET['from'];
	}
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	if( isset($_GET['from'])){
		header("Location: " . $_GET['from']);
		exit();
	}
	header("Location: main.php");
?>