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
		file_put_contents($_email . ".log", json_encode($_profile));
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

	function getProfiles($_email, $_pageNum = 0, $_offset = 20){
		global $db;
		$record = $db->select('select * from users where Email="' . $_email . '"');
		if( $record ){
			$userId = $record[0]['Id'];
			return $db->select('select * from users where UserId="' . $userId . '" limit ' . $_pageNum * $_offset . "," . $_offset);
		}
		return $record;
	}
?>