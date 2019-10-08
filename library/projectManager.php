<?php

	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	ini_set('implicit_flush', 1);
	ob_implicit_flush(true);
	set_time_limit(0);

	define("DB_TYPE", "mysql");
	define("DB_HOST", "localhost");

	if(@file_get_contents(__DIR__."/localhost")){
		define("DB_NAME", "linkedin_profiles");
		define("DB_USER", "root");
		define("DB_PASSWORD", "");
	} else if( @file_get_contents(__DIR__ . "/nodelbma")){
		define("DB_NAME", "nodelbma_linkedin_profiles");
		define("DB_USER", "nodelbma_user1");
		define("DB_PASSWORD", "123guraud!");
	}
	else{
		define("DB_NAME", "linkedin_profiles");
		define("DB_USER", "root");
		define("DB_PASSWORD", "123guraud!");
	}
	require_once __DIR__ . "/Mysql.php";

	$db = new Mysql();
	$db->exec("set names utf8");
	function saveProfileQuestions($id, $lstProfileQuestions){
		global $db;
		foreach ($lstProfileQuestions as $value) {
			$sql = "INSERT INTO questions(projectId, question) VALUES(?,?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$id, $value]);
		}
	}
	function saveAddContacts($id, $lstAddContacts){
		global $db;
		foreach ($lstAddContacts as $value) {
			$sql = "INSERT INTO clientaddcontact(projectId, contactName) VALUES(?,?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$id, $value]);
		}
	}
	function saveProject($_data){
		$clientFirm = $_data->clientFirm;
		$clientContacts = $_data->clientContact;
		$projectTitle = $_data->projectTitle;
		$projectType = $_data->projectType;
		$projectDescription = $_data->projectDesc;
		$projectPracticeArea = $_data->practiceArea;

		$lstProfileQuestions = $_data->lstProfileQuestions;
		$lstAddContacts = $_data->lstAddContacts;

		global $db;
		$sql = "INSERT INTO projects(clientFirm, clientContacts, projectTitle, projectType, projectDescription, projectPracticeArea) VALUES ( ?, ?, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);
		$stmt->execute([$clientFirm, $clientContacts, $projectTitle, $projectType, $projectDescription, $projectPracticeArea]);
		$id = $db->lastInsertId();
		saveProfileQuestions($id, $lstProfileQuestions);
		saveAddContacts($id, $lstAddContacts);
		return true;
	}

?>