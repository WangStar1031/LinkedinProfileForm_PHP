<?php

	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	ini_set('implicit_flush', 1);
	ob_implicit_flush(true);
	set_time_limit(0);

	define("DB_TYPE", "mysql");
	define("DB_HOST", "127.0.0.1");
	define("DB_NAME", "linkedin_profiles");
	define("DB_USER", "root");

	if(@file_get_contents(__DIR__."/localhost")){
		define("DB_PASSWORD", "");
	}
	else{
		define("DB_PASSWORD", "123guraud!");
	}

	require_once __DIR__ . "/library/userManager.php";

	$db = new Mysql();
	$db->exec("set names utf8");

	$case = '';
	if( isset($_GET['case'])) $case = $_GET['case'];
	if( isset($_POST['case'])) $case = $_POST['case'];
	$email = '';
	if( isset($_GET['email'])) $case = $_GET['email'];
	if( isset($_POST['email'])) $case = $_POST['email'];
	switch ($case) {
		case 'verify':
			$pass = '';
			if( isset($_GET['pass'])) $case = $_GET['pass'];
			if( isset($_POST['pass'])) $case = $_POST['pass'];
			$verifyResult = verifyUser( $email, $pass);
			echo $verifyResult;
		break;
		case 'profiles':
			$data = '';
			if( isset($_GET['data'])) $case = $_GET['data'];
			if( isset($_POST['data'])) $case = $_POST['data'];
			$profile = json_decode($data);
		break;
		default:
			break;
	}
?>