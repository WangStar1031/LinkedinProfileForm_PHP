<?php

	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	ini_set('implicit_flush', 1);
	ob_implicit_flush(true);
	set_time_limit(0);

	define("DB_TYPE", "mysql");
	define("DB_HOST", "localhost");
	define("DB_NAME", "linkedin_profiles");
	define("DB_USER", "root");

	if(@file_get_contents(__DIR__."/localhost")){
		define("DB_PASSWORD", "");
	}
	else{
		define("DB_PASSWORD", "123guraud!");
	}

	require_once __DIR__ . "/Mysql.php";

	$db = new Mysql();
	$db->exec("set names utf8");

	function getUserId($_email){
		global $db;
		$sql = "select Id from users where Email = '$_email'";
		$result = $db->select($sql);
		return $result[0]["Id"];
	}
	
	function registerUser($_Name, $_SurName, $_email, $_pass) {
		if( $_email == "")return false;
		global $db;
		$result = getUserId($_email);
		if( $result == false){
			$data = password_hash($_pass, PASSWORD_DEFAULT);
			$sql = "INSERT INTO users(Name, SureName, Email, Password) VALUES ( ?, ?, ?, ?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$_Name, $_SurName, $_email, $_pass]);
			$result = getUserId($_email);
			return $result[0]["Id"];
		}
		return false;
	}
	
	function verifyUser($_email, $_pass) {
		global $db;

		$record = $db->select('select * from users where Email="' . $_email . '"');
		if( $record){
			$pass = $record[0]["Password"];
			if( $pass == $_pass)
				return 1;
			else
				return -1;
		}
		return 0;
	}

	function getProfileId($_userId, $_email){
		global $db;
		$sql = "select Id from profiles where UserId=" . $_userId . " and Email='" . $_email . "'";
		$record = $db->select($sql);
		if( $record){
			return $record[0]["Id"];
		}
		return false;
	}

	function saveProfile($_email, $_profile){
		$userId = getUserId($_email);
		if( $userId == false)
			return false;
		// file_put_contents($_email . ".log", json_encode($_profile));
		global $db;


		$prefix = $_profile->prefix;
		$firstName = $_profile->firstName;
		$lastName = $_profile->lastName;
		$country = $_profile->strLocation;
		$email = $_profile->strEmail;
		$phoneNumber = $_profile->strPhoneNumber;
		$industry = $_profile->industry;
		$jobFunction = $_profile->jobsFunction;
		$profile = $_profile->strProfile;
		$imgUrl = $_profile->strImgUrl;
		$profileTitle = $_profile->strHeadLine;
		$biography = $_profile->biography;

		$sql = "select Id from profiles where UserId=" . $userId . " and Email='" . $email . "'";
		$record = $db->select($sql);
		if( $record){
			return false;
		}

		$sql = "INSERT INTO profiles(UserId, Prefix, FirstName, LastName, Country, Email, PhoneNumber, Industry, JobFunction, ProfileUrl, ImageUrl, ProfileTitle, Biography) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);
		$stmt->execute([$userId, $prefix, $firstName, $lastName, $country, $email, $phoneNumber, $industry, $jobFunction, $profile, $imgUrl, $profileTitle, $biography]);
		
		$profileId = getProfileId($userId, $email);
		if( !$profileId)
			return false;

		$strExperience = $_profile->strExperience;
		foreach ($strExperience as $experience) {
			$companyName = $experience->companyName;
			$workingHistory = $experience->workingHistory;
			foreach ($workingHistory as $curWorking) {
				$title = $curWorking->title;
				$duration = $curWorking->duration;
				$lstDuration = [];
				if( $duration != "")
					$lstDuration = explode(" - ", $duration);
				$fromDate = "";
				$toDate = "";
				if( count($lstDuration)){
					$fromDate = $lstDuration[0];
					if( count($lstDuration) > 1){
						$toDate = $lstDuration[1];
					}
				}
				$sql = "INSERT INTO employment(ProfileId, CompanyName, RoleTitle, FromDate, ToDate) VALUES(?, ?, ?, ?, ?)";
				$stmt = $db->prepare($sql);
				$stmt->execute([$profileId, $companyName, $title, $fromDate, $toDate]);
			}
		}
		$strEducation = $_profile->strEducation;
		foreach ($strEducation as $education) {
			$schoolName = $education->schoolName;
			$degreeName = $education->degreeName;
			$areaName = $education->areaName;
			$duration = $education->duration;
			$startYear = "";
			$endYear = "";
			$lstDuration = [];
			if( $duration != "")
				$lstDuration = explode(" - ", $duration);
			if( count($lstDuration)){
				$startYear = $lstDuration[0];
				if( count($lstDuration) > 1)
					$endYear = $lstDuration[1];
			}
			$sql = "INSERT INTO education(ProfileId, SchoolName, DegreeName, AreaName, StartYear, EndYear) VALUES( ?, ?, ?, ?, ?, ?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$profileId, $schoolName, $degreeName, $areaName, $startYear, $endYear]);
		}
		return true;
	}
	function getEmployHistory($_profileId){
		global $db;
		$sql = "select * from employment where ProfileId=" . $_profileId;
		$record = $db->select($sql);
		if( $record){
			return $record;
		}
		return [];
	}
	function getEducationHistory($_profileId){
		global $db;
		$sql = "select * from education where ProfileId=" . $_profileId;
		$record = $db->select($sql);
		if( $record){
			return $record;
		}
		return [];
	}
	function getProfileInfos($_email, $_filter){
	// function getProfileInfos($_email, $_gender, $_recCount, $_pageNum, $_country, $_company){
		global $db;
		$_gender = '';
		if(isset($_filter->gender)) $_gender = $_filter->gender;
		$_recCount = '';
		if(isset($_filter->recCount)) $_recCount = $_filter->recCount;
		$_pageNum = '';
		if(isset($_filter->pageNum)) $_pageNum = $_filter->pageNum;
		$_country = '';
		if(isset($_filter->country)) $_country = $_filter->country;
		$_company = '';
		if(isset($_filter->company)) $_company = $_filter->company;

		$_jobsFunction = '';
		if(isset($_filter->jobsFunction)) $_jobsFunction = $_filter->jobsFunction;
		$_industry = '';
		if(isset($_filter->industry)) $_industry = $_filter->industry;
		$_geography = '';
		if(isset($_filter->geography)) $_geography = $_filter->geography;

		$_intelSearch = '';
		if(isset($_filter->intelSearch)) $_intelSearch = $_filter->intelSearch;

		$userId = getUserId($_email);
		$sql = "select * from profiles ";
		// $where = "where UserId=" . $userId;
		$where = "where 1";
		if( $_intelSearch != ""){
			$intelWhere = " and (";
			$intelWhere .= " Prefix like '%" . $_intelSearch . "%'";
			$intelWhere .= " or FirstName like '%" . $_intelSearch . "%'";
			$intelWhere .= " or LastName like '%" . $_intelSearch . "%'";
			$intelWhere .= " or Country like '%" . $_intelSearch . "%'";
			$intelWhere .= " or Email like '%" . $_intelSearch . "%'";
			$intelWhere .= " or PhoneNumber like '%" . $_intelSearch . "%'";
			$intelWhere .= " or Industry like '%" . $_intelSearch . "%'";
			$intelWhere .= " or ProfileTitle like '%" . $_intelSearch . "%'";
			$intelWhere .= " or Biography like '%" . $_intelSearch . "%'";

			$sql_com = "select distinct ProfileId from employment where CompanyName like '%" . $_intelSearch . "%'";
			$comRecords = $db->select($sql_com);
			$profiles = [];
			if( $comRecords){
				foreach ($comRecords as $proId) {
					$profiles[] = $proId["ProfileId"];
				}	
			}
			$sql_edu = "select distinct ProfileId from education where SchoolName like '%" . $_intelSearch . "%'";
			$eduRecords = $db->select($sql_com);
			if( $eduRecords){
				foreach ($eduRecords as $proId) {
					if( array_search( $proId["ProfileId"], $profiles) !== false){
						$profiles[] = $proId["ProfileId"];
					}
				}
			}
			if( count($profiles)){
				$intelWhere .= " and Id in (" . implode(",", $profiles) . ")";
			}

			$intelWhere .= ")";
			$where .= $intelWhere;
		}
		switch ($_gender) {
			// case 'All':
			// 	break;
			case 'Male':
				$where .= " and Prefix='Mr.'";
				break;
			case 'Female':
				$where .= " and Prefix='Ms.'";
				break;
		}
		// if( $_country != ""){
		// 	$where .= " and Country like '%" . $_country . "%'";
		// }
		if( $_jobsFunction != ""){
			$where .= " and JobFunction like '%" . $_jobsFunction . "%'";
		}
		if( $_industry != ""){
			$where .= " and Industry like '%" . $_industry . "%'";
		}
		if( $_geography != ""){
			$where .= " and Country like '%" . $_geography . "%'";
		}
		if( $_company != ""){
			$sql_com = "select distinct ProfileId from employment where CompanyName like '%" . $_company . "%'";
			$comRecords = $db->select($sql_com);
			$profiles = [];
			foreach ($comRecords as $proId) {
				$profiles[] = $proId["ProfileId"];
			}
			$where .= " and Id in (" . implode(",", $profiles) . ")";
		}
		$countRec = $db->select("select count(*) as count from profiles " . $where);
		$count = $countRec[0]['count'];
		$where .= " limit " . (($_pageNum-1) * $_recCount) . "," . $_recCount;
		// echo $sql . $where;
		$record = $db->select($sql . $where);
		// print_r($record);
		$retVal = new \stdClass;
		$retVal->count = $count;
		$retVal->profiles = [];
		if( $record){
			foreach ($record as $value) {
				$profileId = $value['Id'];
				$empHist = getEmployHistory($profileId);
				$value['employHistory'] = $empHist;
				$empHist = getEducationHistory($profileId);
				$value['educationHistory'] = $empHist;
				$retVal->profiles[] = $value;
			}
			// $retVal->profiles = $record;
		}
		return $retVal;
	}
	function getProfiles($_email, $_pageNum = 0, $_offset = 20){
		global $db;
		return $db->select('select * from profiles limit ' . $_pageNum * $_offset . "," . $_offset);

		$record = $db->select('select * from users where Email="' . $_email . '"');
		if( $record ){
			$userId = $record[0]['Id'];
			return $db->select('select * from profiles where UserId="' . $userId . '" limit ' . $_pageNum * $_offset . "," . $_offset);
		}
		return $record;
	}
?>