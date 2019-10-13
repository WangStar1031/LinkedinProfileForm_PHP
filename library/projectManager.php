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
	function saveProfileExperts($id, $lstExperts){
		global $db;
		foreach ($lstExperts as $value) {
			$sql = "INSERT INTO experts_projects(projectId, profileId) VALUES(?,?)";
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
	function cloneProject($_data){
		$clientFirm = $_data->clientFirm;
		$clientContacts = $_data->clientContact;
		$projectTitle = $_data->projectTitle;
		$projectType = $_data->projectType;
		$projectDescription = $_data->projectDesc;
		$projectPracticeArea = $_data->practiceArea;

		$lstProfileQuestions = $_data->lstProfileQuestions;
		$lstAddContacts = $_data->lstAddContacts;
		$lstExperts = $_data->lstExperts;

		global $db;
		$sql = "INSERT INTO projects(clientFirm, clientContacts, projectTitle, projectType, projectDescription, projectPracticeArea, startedDate) VALUES ( ?, ?, ?, ?, ?, ?, NOW())";
		$stmt = $db->prepare($sql);
		$stmt->execute([$clientFirm, $clientContacts, $projectTitle, $projectType, $projectDescription, $projectPracticeArea]);
		$id = $db->lastInsertId();
		saveProfileQuestions($id, $lstProfileQuestions);
		saveAddContacts($id, $lstAddContacts);
		saveProfileExperts($id, $lstExperts);

		return $id;
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
	function getProjectExperts($_projectID){
		global $db;
		$sql = "SELECT profileId FROM experts_projects WHERE projectId = '$_projectID'";
		$result = $db->select($sql);
		$retVal = [];
		if( !$result)
			return $retVal;
		foreach ($result as $record) {
			$retVal[] = intval($record['profileId']);
		}
		return $retVal;
	}
	function getProjectCientAddContact($_id){
		global $db;
		$sql = "SELECT contactName FROM clientaddcontact WHERE projectId = '$_id'";
		$result = $db->select($sql);
		$retVal = [];
		if( !$result)
			return $retVal;
		foreach ($result as $record) {
			$retVal[] = $record['contactName'];
		}
		return $result;
	}
	function getProjectQuestions($_id){
		global $db;
		$sql = "SELECT question FROM questions WHERE projectId = '$_id'";
		$result = $db->select($sql);
		$retVal = [];
		if( !$result)
			return $retVal;
		foreach ($result as $record) {
			$retVal[] = $record['contactName'];
		}
		return $result;
	}
	function getExperts4Project($_projectID){
		global $db;
		$sql = "SELECT * FROM experts_projects WHERE projectId = '$_projectID'";
		$result = $db->select($sql);
		return $result;
	}
	function copyProject($_projectID){
		global $db;
		$records = getProjectInfo($_projectID);
		if( !$records)return false;
		$record = $records[0];
		$_data = new \stdClass;
		$_data->clientFirm = $record['clientFirm'];
		$_data->clientContact = $record['clientContacts'];
		$_data->projectTitle = $record['projectTitle'];
		$_data->projectType = $record['projectType'];
		$_data->projectDesc = $record['projectDescription'];
		$_data->practiceArea = $record['projectPracticeArea'];

		$_data->lstProfileQuestions = getProjectQuestions($_projectID);
		$_data->lstAddContacts = getProjectCientAddContact($_projectID);
		$_data->lstExperts = getProjectExperts($_projectID);
		cloneProject($_data);
	}
	function addExperts($projectId, $ids){
		global $db;
		$arrIds = explode(",", $ids);
		foreach ($arrIds as $value) {
			if( !$db->select("SELECT * FROM experts_projects WHERE projectId='$projectId' AND profileId='$value'") ){
				$sql = "INSERT INTO experts_projects(projectId, profileId) VALUES (?,?)";
				$stmt = $db->prepare($sql);
				$stmt->execute([$projectId, intval($value)]);
			}
		}
		return "yes";
	}
	function modifyExperts($projectId, $arrExperts){
		global $db;
		foreach ($arrExperts as $curExpert) {
			$profileId = $curExpert->profileId;
			$projectStatus = $curExpert->projectStatus;
			$sale = intval($curExpert->sale);
			$phone2 = $curExpert->phone2;

			$PhoneNumber = $curExpert->PhoneNumber;
			$Email = $curExpert->Email;
			$ProfileUrl = $curExpert->ProfileUrl;
			$Country = $curExpert->Country;
			
			$sql = "UPDATE profiles SET Country=?, Email=?, ProfileUrl=?, PhoneNumber=? WHERE Id=?";
			$stmt= $db->prepare($sql);
			$stmt->execute([$Country, $Email, $ProfileUrl, $PhoneNumber, $profileId]);

			$sql = "UPDATE experts_projects SET projectStatus=?, sale=?, phone2=? WHERE projectId=? AND profileId=?";
			$stmt= $db->prepare($sql);
			$stmt->execute([$projectStatus, $sale, $phone2, $projectId, $profileId]);
		}
		return "yes";
	}
	function removeExpert($projectId, $profileId){
		global $db;
		$strsql = "DELETE FROM experts_projects WHERE projectId='$projectId' AND profileId='$profileId'";
		$db->__exec__($strsql);
		return "yes";
	}
	function removeProject($projectId){
		global $db;
		$db->__exec__("DELETE FROM projects WHERE Id='$projectId'");
		$db->__exec__("DELETE FROM clientaddcontact WHERE projectId='$projectId'");
		$db->__exec__("DELETE FROM experts_projects WHERE projectId='$projectId'");
		$db->__exec__("DELETE FROM questions WHERE projectId='$projectId'");
		return "yes";
	}
?>