<?php

	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	error_reporting(E_ALL);

	require_once __DIR__ . "/library/userManager.php";

	$case = '';
	if( isset($_GET['case'])) $case = $_GET['case'];
	if( isset($_POST['case'])) $case = $_POST['case'];
	$email = '';
	if( isset($_GET['email'])) $email = $_GET['email'];
	if( isset($_POST['email'])) $email = $_POST['email'];
	switch ($case) {
		case 'modify':
			$profileUrl = '';
			if( isset($_GET['profileUrl'])) $profileUrl = $_GET['profileUrl'];
			if( isset($_POST['profileUrl'])) $profileUrl = $_POST['profileUrl'];
			$profile = '';
			if( isset($_GET['profile'])) $profile = $_GET['profile'];
			if( isset($_POST['profile'])) $profile = $_POST['profile'];
			if( $profileUrl != ''){
				modifyProfile($profileUrl, $profile);
			}
			break;
		case 'remove':
			$profileUrl = '';
			if( isset($_GET['profileUrl'])) $profileUrl = $_GET['profileUrl'];
			if( isset($_POST['profileUrl'])) $profileUrl = $_POST['profileUrl'];
			if( $profileUrl != '') removeProfile($profileUrl);
			break;
		case 'verify':
			$pass = '';
			if( isset($_GET['pass'])) $pass = $_GET['pass'];
			if( isset($_POST['pass'])) $pass = $_POST['pass'];
			$verifyResult = verifyUser( $email, $pass);
			switch ($verifyResult) {
				case 1:echo "Success";
					break;
				case -1:echo "Wrong Password";
					break;
				case 0: echo "No email";;
					break;
			}
			// echo $verifyResult;
		break;
		case 'profiles':
			$data = '';
			if( isset($_GET['data'])) $data = $_GET['data'];
			if( isset($_POST['data'])) $data = $_POST['data'];
			$profile = json_decode($data);
			$ret = saveProfile($email, $profile);
			if( $ret == true){
				echo "Inserted.";
			} else{
				echo "No.";
			}
		break;
		default:
			break;
	}
?>