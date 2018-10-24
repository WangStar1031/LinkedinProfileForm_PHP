<?php
session_start();
$_SESSION['userEmail'] = "";
header('Location: login.php');
?>