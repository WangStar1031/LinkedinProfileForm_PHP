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

	require_once __DIR__ . "/Mysql.php";

	$db = new Mysql();
	$db->exec("set names utf8");

	function registerUser($_Name, $_SurName, $_email, $_pass) {
		global $db;
		$data = md5($_pass, "65416");
		$sql = "INSERT INTO users (Name, SurName, Email, Password) VALUES ( ?, ?, ?, ?)";
		$stmt= $db->prepare($sql);
		$stmt->execute([$_Name, $_SurName, $_email, $data]);
	}

	function verifyUser($_email, $_pass) {
		global $db;
		$data = md5($_pass, "65416");

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

	function saveProfile($_email, $_profile){

	}
?>