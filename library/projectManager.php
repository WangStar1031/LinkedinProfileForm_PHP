<?php

	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	ini_set('implicit_flush', 1);
	ob_implicit_flush(true);
	set_time_limit(0);

	if(!defined("DB_TYPE")) define("DB_TYPE", "mysql");
	if(!defined("DB_HOST")) define("DB_HOST", "localhost");

	if(@file_get_contents(__DIR__."/localhost")){
		if(!defined("DB_NAME")) define("DB_NAME", "linkedin_profiles");
		if(!defined("DB_USER")) define("DB_USER", "root");
		if(!defined("DB_PASSWORD")) define("DB_PASSWORD", "");
	} else if( @file_get_contents(__DIR__ . "/nodelbma")){
		if(!defined("DB_NAME")) define("DB_NAME", "nodelbma_linkedin_profiles");
		if(!defined("DB_USER")) define("DB_USER", "nodelbma_user1");
		if(!defined("DB_PASSWORD")) define("DB_PASSWORD", "123guraud!");
	}
	else{
		if(!defined("DB_NAME")) define("DB_NAME", "linkedin_profiles");
		if(!defined("DB_USER")) define("DB_USER", "root");
		if(!defined("DB_PASSWORD")) define("DB_PASSWORD", "123guraud!");
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
		$sql = "INSERT INTO projects(clientFirm, clientContacts, projectTitle, projectType, projectDescription, projectPracticeArea, startedDate) VALUES ( ?, ?, ?, ?, ?, ?, NOW())";
		$stmt = $db->prepare($sql);
		$stmt->execute([$clientFirm, $clientContacts, $projectTitle, $projectType, $projectDescription, $projectPracticeArea]);
		$id = $db->lastInsertId();
		saveProfileQuestions($id, $lstProfileQuestions);
		saveAddContacts($id, $lstAddContacts);
		return true;
	}
	function getAllProjects($_search = ""){
		global $db;
		if( $_search == ""){
			$sql = "SELECT * FROM projects";
		} else{
			$sql = "SELECT * FROM projects WHERE clientFirm LIKE '%$_search%' OR clientContacts LIKE '%$_search%' OR projectTitle LIKE '%$_search%' OR projectType LIKE '%$_search%' OR projectPracticeArea LIKE '%$_search%' OR projectDescription LIKE '%$_search%'";
		}
		$result = $db->select($sql);
		return $result;
	}
	function getProjectInfo($_id){
		global $db;
		$sql = "SELECT * FROM projects WHERE Id = '$_id'";
		$result = $db->select($sql);
		return $result;
	}
	function getExperts4Project($_projectID){
		global $db;
		$sql = "SELECT * FROM experts_projects WHERE projectId = '$_projectID'";
		$result = $db->select($sql);
		return $result;
	}
?>