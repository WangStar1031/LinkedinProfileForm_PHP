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
	function modifyProfile($_profileUrl, $_profile){
		$firstName = $_profile['firstName'];
		$lastName = $_profile['lastName'];
		$email = $_profile['email'];
		$phoneNumber = $_profile['phoneNumber'];
		$biography = $_profile['biography'];
		global $db;
		$sql = "UPDATE profiles SET FirstName=?, LastName=?, Email=?, PhoneNumber=?, Biography=? WHERE ProfileUrl=?";
		$stmt= $db->prepare($sql);
		$stmt->execute([$firstName, $lastName, $email, $phoneNumber, $biography, $_profileUrl]);
	}
	function removeProfileWithId($profileId){
		global $db;
		$strsql = "DELETE FROM profiles WHERE Id='$profileId'";
		$db->__exec__($strsql);
		$db->__exec__("DELETE FROM education WHERE ProfileId = '$profileId'");
		$db->__exec__("DELETE FROM employment WHERE ProfileId = '$profileId'");
		$db->__exec__("DELETE FROM experts_projects WHERE profileId = '$profileId'");
	}
	function removeProfile($_profileUrl){
		global $db;
		$profileId = getProfileId4Url($_profileUrl);
		removeProfileWithId($profileId);
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

	function getProfileId4Url( $profile){
		global $db;
		$sql = "select Id from profiles where ProfileUrl='" . $profile . "'";
		$record = $db->select($sql);
		if( $record){
			return $record[0]["Id"];
		}
		return false;
	}
	function getProfileId($userId, $profile){
		global $db;
		$sql = "select Id from profiles where UserId=" . $userId . " and ProfileUrl='" . $profile . "'";
		$record = $db->select($sql);
		if( $record){
			return $record[0]["Id"];
		}
		return false;
	}
	function saveManProfile($email, $profile, $profileId = 0){
		$prefix = $profile->prefix;
		$FirstName = $profile->FirstName;
		$LastName = $profile->LastName;
		$Suffix = $profile->Suffix;
		$Country = $profile->Country;
		$TimeZone = $profile->TimeZone;
		$RefferedBy = $profile->RefferedBy;
		$Email = $profile->Email;
		$PhoneNumber = $profile->PhoneNumber;
		$JobFunction = $profile->JobFunction;
		$LinedinUrl = $profile->LinedinUrl;
		$JobProfileUrl = $profile->JobProfileUrl;
		$Biography = $profile->Biography;

		$userId = getUserId($email);
		if( $userId == false)
			return false;
		global $db;
		if( $profileId == 0){
			$sql = "INSERT INTO profiles(UserId, Prefix, FirstName, LastName, Suffix, Country, TimeZone, RefferedBy, Email, PhoneNumber, JobFunction, ProfileUrl, JobProfileUrl, Biography, Created) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, Now())";
		} else{
			$sql = "UPDATE profiles SET UserId=?, Prefix=?, FirstName=?, LastName=?, Suffix=?, Country=?, TimeZone=?, RefferedBy=?, Email=?, PhoneNumber=?, JobFunction=?, ProfileUrl=?, JobProfileUrl=?, Biography=? WHERE Id=?";
		}
		$stmt = $db->prepare($sql);
		if( $profileId == 0){
			$stmt-> execute([$userId, $prefix, $FirstName, $LastName, $Suffix, $Country, $TimeZone, $RefferedBy, $Email, $PhoneNumber, $JobFunction, $LinedinUrl, $JobProfileUrl, $Biography]);
			$ProfileId = $db->lastInsertId();
		} else{
			$stmt-> execute([$userId, $prefix, $FirstName, $LastName, $Suffix, $Country, $TimeZone, $RefferedBy, $Email, $PhoneNumber, $JobFunction, $LinedinUrl, $JobProfileUrl, $Biography, $profileId]);
			$strsql = "DELETE FROM employment WHERE ProfileId='$profileId'";
			$db->__exec__($strsql);
			$strsql = "DELETE FROM education WHERE ProfileId='$profileId'";
			$db->__exec__($strsql);
			$ProfileId = $profileId;
		}
		if( !$ProfileId )return false;

		$arrEmploys = $profile->arrEmploys;
		foreach ($arrEmploys as $employment) {
			$CompanyName = $employment->CompanyName;
			$RoleTitle = $employment->RoleTitle;
			$FromDate = $employment->FromDate;
			$ToDate = $employment->ToDate;
			$sql = "INSERT INTO employment(ProfileId, CompanyName, RoleTitle, FromDate, ToDate) VALUES(?, ?, ?, ?, ?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$ProfileId, $CompanyName, $RoleTitle, $FromDate, $ToDate]);
 		}

		$arrEducations = $profile->arrEducations;
		foreach ($arrEducations as $education) {
			$SchoolName = $education->SchoolName;
			$DegreeName = $education->DegreeName;
			$AreaName = $education->AreaName;
			$StartYear = $education->StartYear;
			$EndYear = $education->EndYear;
			$sql = "INSERT INTO education(ProfileId, SchoolName, DegreeName, AreaName, StartYear, EndYear) VALUES(?, ?, ?, ?, ?, ?)";
			$stmt = $db->prepare($sql);
			$stmt->execute([$ProfileId, $SchoolName, $DegreeName, $AreaName, $StartYear, $EndYear]);
 		}
 		return true;
	}
	function saveProfile($_email, $_profile){
		$userId = getUserId($_email);
		if( $userId == false)
			return false;
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

		$sql = "select Id from profiles where UserId=" . $userId . " and ProfileUrl='" . $profile . "'";
		$record = $db->select($sql);
		if( $record){
			$sql = "UPDATE profiles SET Prefix=?, FirstName=?, LastName=?, Country=?, Email=?, PhoneNumber=?, Industry=?, JobFunction=?, ImageUrl=?, ProfileTitle=?, Biography=?) WHERE UserId=? AND ProfileUrl=?";
			$stmt = $db->prepare($sql);
			$stmt->execute([ $prefix, $firstName, $lastName, $country, $email, $phoneNumber, $industry, $jobFunction, $imgUrl, $profileTitle, $biography, $userId, $profile]);
		} else{
			$sql = "INSERT INTO profiles(UserId, Prefix, FirstName, LastName, Country, Email, PhoneNumber, Industry, JobFunction, ProfileUrl, ImageUrl, ProfileTitle, Biography, Created) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, Now())";
			$stmt = $db->prepare($sql);
			$stmt->execute([$userId, $prefix, $firstName, $lastName, $country, $email, $phoneNumber, $industry, $jobFunction, $profile, $imgUrl, $profileTitle, $biography]);
		}

		// sleep(2);
		$profileId = getProfileId($userId, $profile);
		if( !$profileId)
			return false;
		$strsql = "DELETE FROM employment WHERE ProfileId='$profileId'";
		$db->__exec__($strsql);

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
		$strsql = "DELETE FROM education WHERE ProfileId='$profileId'";
		$db->__exec__($strsql);

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
	function getProfileFromId($_id){
		global $db;
		$records = $db->select("SELECT * FROM profiles WHERE Id = '$_id'");
		if( !$records) return false;
		if( count($records) == 0) return false;
		$profile = $records[0];
		$profile['employHistory'] = getEmployHistory($_id);
		$profile['educationHistory'] = getEducationHistory($_id);

		return $profile;
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
	function SearchProfiles($_name, $_location, $_jobsFunction, $_industry){
		global $db;
		$records = $db->select("SELECT * FROM profiles WHERE (FirstName LIKE '%$_name%' OR LastName LIKE '%$_name%') AND Country LIKE '%$_location%' AND JobFunction LIKE '%$_jobsFunction%' AND Industry LIKE '%$_industry%'");
		if( !$records )
			return "";
		$retStr = "";
		$retStr .= "<table style='width:100%;'>";
		$retStr .= "<tr>";
			$retStr .= "<th></th>";
			$retStr .= "<th>Name</th>";
			// $retStr .= "<th>Title</th>";
			$retStr .= "<th>Country</th>";
			$retStr .= "<th>Industry</th>";
			// $retStr .= "<th>Jobs Function</th>";
		$retStr .= "</tr>";
		foreach ($records as $record) {
			$retStr .= "<tr id='" . $record['Id'] . "'>";
				$retStr .= "<td>" . "<input type='checkbox'> " . "<img src='" . $record['ImageUrl'] . "'>" . "</td>";
				$retStr .= "<td>" . $record['FirstName'] . " " . $record['LastName'] . "</td>";
				// $retStr .= "<td>" . $record['ProfileTitle'] . "</td>";
				$retStr .= "<td>" . $record['Country'] . "</td>";
				$retStr .= "<td>" . $record['Industry'] . "</td>";
				// $retStr .= "<td>" . $record['JobFunction'] . "</td>";
			$retStr .= "</tr>";
		}
		$retStr .= "</table>";
		return $retStr;
	}
	function SearchProfiles4Project( $id, $strSearch, $hasEmail, $hasPhone, $rate, $fromSale, $toSale, $signedTC, $chkCompany, $strCompanies, $chkGeograpy, $strCountries, $projectHistory, $strProjectHistories){
		global $db;
		$sql = "select * from profiles ";
		$where = "where 1";
		if( $strSearch != ""){
			$intelWhere = " and (";
			$intelWhere .= " Prefix like '%" . $strSearch . "%'";
			$intelWhere .= " or FirstName like '%" . $strSearch . "%'";
			$intelWhere .= " or LastName like '%" . $strSearch . "%'";
			$intelWhere .= " or Country like '%" . $strSearch . "%'";
			$intelWhere .= " or Email like '%" . $strSearch . "%'";
			$intelWhere .= " or PhoneNumber like '%" . $strSearch . "%'";
			$intelWhere .= " or Industry like '%" . $strSearch . "%'";
			$intelWhere .= " or ProfileTitle like '%" . $strSearch . "%'";
			$intelWhere .= " or Biography like '%" . $strSearch . "%'";

			$sql_com = "select distinct ProfileId from employment where CompanyName like '%" . $strSearch . "%'";
			$comRecords = $db->select($sql_com);
			$profiles = [];
			if( $comRecords){
				foreach ($comRecords as $proId) {
					$profiles[] = $proId["ProfileId"];
				}	
			}
			$sql_edu = "select distinct ProfileId from education where SchoolName like '%" . $strSearch . "%'";
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
		if( $hasEmail == true){
			$where .= " and Email is not null";
		}
		if( $hasPhone == true){
			$where .= " and PhoneNumber is not null";
		}
		if( $rate == true){

		}
		if( $signedTC == true){

		}
		if( $chkGeograpy == true){
			$arrCountries = explode(",", $strCountries);
			if( count($arrCountries) != 0){
				$where .= " and Country in (";
				for( $i = 0; $i < count($arrCountries); $i++){
					if( $i != 0){
						$where .= ",";
					}
					$where .= '"' . $arrCountries[$i] . '"';
				}
				$where .= ")";
			}
		}
		// $countRec = $db->select("select count(*) as count from profiles " . $where);
		// $count = $countRec[0]['count'];
		// $where .= " limit " . (($_pageNum-1) * $_recCount) . "," . $_recCount;
		// print_r($sql . $where);
		$records = $db->select($sql . $where);
		$retVal = [];
		if( !$records)return $retVal;
		// print_r($strCompanies);
		foreach ($records as $record) {
			$profileId = $record['Id'];
			if( $chkCompany == true){
				if( !isWorkedInCompany($profileId, $strCompanies))
					continue;
			}
			$empHist = getEmployHistory($profileId);
			$record['employHistory'] = $empHist;
			// $empHist = getEducationHistory($profileId);
			// $record['educationHistory'] = $empHist;
			$record['projectIds'] = getProjects4Profile($profileId);
			$retVal[] = $record;
		}
		return $retVal;
	}
	function isWorkedInCompany($profileId, $strCompanies){
		global $db;
		$arrCompanies = explode(",", $strCompanies);
		$strBuff = "";
		for($i = 0; $i < count($arrCompanies); $i++) {
			$CompanyName = $arrCompanies[$i];
			if( $i != 0)
				$strBuff .= ",";
			$strBuff .= "'" . $CompanyName . "'";
		}
		$sql = "SELECT count(*) as count FROM employment WHERE ProfileId = '$profileId' AND CompanyName in (" . $strBuff . ")";
		// print_r($sql);
		$ret = $db->select($sql);
		if( $ret[0]['count'] == 0)return false;
		return true;
	}
	function getProjects4Profile($profileId){
		global $db;
		$retVal = [];
		$records = $db->select("SELECT projectId FROM experts_projects WHERE profileId = '$profileId'");
		if( !$records)return $retVal;
		foreach ($records as $record) {
			$retVal[] = $record['projectId'];
		}
		return $retVal;
	}
	function getAllCompanyNames(){
		global $db;
		$records = $db->select("SELECT DISTINCT CompanyName FROM employment ORDER BY CompanyName ASC");
		$retVal = [];
		if( !$records) return $retVal;
		foreach ($records as $record) {
			$retVal[] = $record['CompanyName'];
		}
		return $retVal;
	}
?>