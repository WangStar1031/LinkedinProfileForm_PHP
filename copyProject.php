<?php
$id = "";
if( isset($_GET['project'])) $id = $_GET['project'];

if( $id == ""){
	header("Location: projects.php");
}
sleep(3);
header("Location: projectDetails.php?project=$id");
?>