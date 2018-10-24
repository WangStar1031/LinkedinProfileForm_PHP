<?php
	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	header("Location: main.php");
?>