<?php

	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	error_reporting(E_ALL);

	require_once __DIR__ . "/library/userManager.php";
	require_once __DIR__ . "/library/projectManager.php";

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
		case 'removeProfile':
			$profileId = '';
			if( isset($_GET['profileId'])) $profileId = $_GET['profileId'];
			if( isset($_POST['profileId'])) $profileId = $_POST['profileId'];
			removeProfileWithId($profileId);
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
			// file_put_contents("filename.txt", $data . PHP_EOL, FILE_APPEND);
			$profile = json_decode($data);
			$ret = saveProfile($email, $profile);
			if( $ret == true){
				echo "Inserted.";
			} else{
				echo "No.";
			}
		break;
		case 'search':
			$name = '';
			if( isset($_GET['name'])) $name = trim($_GET['name']);
			if( isset($_POST['name'])) $name = trim($_POST['name']);
			$location = '';
			if( isset($_GET['location'])) $location = trim($_GET['location']);
			if( isset($_POST['location'])) $location = trim($_POST['location']);
			$jobsFunction = '';
			if( isset($_GET['jobsFunction'])) $jobsFunction = trim($_GET['jobsFunction']);
			if( isset($_POST['jobsFunction'])) $jobsFunction = trim($_POST['jobsFunction']);
			$industry = '';
			if( isset($_GET['industry'])) $industry = trim($_GET['industry']);
			if( isset($_POST['industry'])) $industry = trim($_POST['industry']);

			echo SearchProfiles($name, $location, $jobsFunction, $industry);
		break;
		case 'addExperts':
			$projectId = '';
			if( isset($_GET['projectId'])) $projectId = trim($_GET['projectId']);
			if( isset($_POST['projectId'])) $projectId = trim($_POST['projectId']);
			$ids = '';
			if( isset($_GET['ids'])) $ids = trim($_GET['ids']);
			if( isset($_POST['ids'])) $ids = trim($_POST['ids']);
			echo addExperts($projectId, $ids);
		break;
		case 'modifyExperts':
			$projectId = '';
			if( isset($_GET['projectId'])) $projectId = trim($_GET['projectId']);
			if( isset($_POST['projectId'])) $projectId = trim($_POST['projectId']);
			$experts = '';
			if( isset($_GET['experts'])) $experts = trim($_GET['experts']);
			if( isset($_POST['experts'])) $experts = trim($_POST['experts']);
			$arrExperts = json_decode($experts);
			echo modifyExperts($projectId, $arrExperts);
		break;
		case 'removeExpert':
			$projectId = '';
			if( isset($_GET['projectId'])) $projectId = trim($_GET['projectId']);
			if( isset($_POST['projectId'])) $projectId = trim($_POST['projectId']);
			$profileId = '';
			if( isset($_GET['profileId'])) $profileId = trim($_GET['profileId']);
			if( isset($_POST['profileId'])) $profileId = trim($_POST['profileId']);
			echo removeExpert($projectId, $profileId);
		break;
		case 'removeProject':
			$projectId = '';
			if( isset($_GET['projectId'])) $projectId = trim($_GET['projectId']);
			if( isset($_POST['projectId'])) $projectId = trim($_POST['projectId']);
			echo removeProject($projectId);
		break;
		default:
			break;
	}
?>