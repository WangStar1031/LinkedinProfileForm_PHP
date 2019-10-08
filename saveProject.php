<?php

	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	error_reporting(E_ALL);

	require_once __DIR__ . "/library/projectManager.php";

	$action = "";
	if( isset($_GET['action'])) $action = $_GET['action'];
	if( isset($_POST['action'])) $action = $_POST['action'];
	$data = "";
	if( isset($_GET['data'])) $data = $_GET['data'];
	if( isset($_POST['data'])) $data = $_POST['data'];
	// echo $action;
	switch ($action) {
		case 'saveProject':
			// echo "string";
			$_data = json_decode($data);
			if( saveProject($_data) == true) echo "yes";
			else echo "no";
			break;
		
		default:
			# code...
			break;
	}
?>