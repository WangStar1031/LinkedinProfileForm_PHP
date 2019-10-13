<?php
$id = "";
if( isset($_GET['project'])) $id = $_GET['project'];

if( $id == ""){
	header("Location: projects.php");
}
require_once __DIR__ . '/library/userManager.php';
require_once __DIR__ . '/library/projectManager.php';
$newId = copyProject($id);
if( $newId == false){
	header("Location: projects.php");
}
header("Location: projects.php");
?>