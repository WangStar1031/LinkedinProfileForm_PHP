<?php
	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userEmail = $_SESSION['userEmail'];
	if( $userEmail == "")
		header("Location: login.php?from=projects.php");
	require_once __DIR__ . '/library/userManager.php';
	require_once __DIR__ . '/library/projectManager.php';

	$id = "";
	if( isset($_GET['project'])) $id = $_GET['project'];
	if( $id == ""){
		header("Location: projects.php");
	}
	$project = getProjectInfo($id);
	if( !$project){
		header("Location: projects.php");
	}
	if( count($project) != 1){
		header("Location: projects.php");
	}
	$curProject = $project[0];
	include("assets/components/header.php");
	include("library/countries.php");
	$profiles = [];
	if( isset($_POST['project'])){
		$strSearch = "";
		if( isset($_POST['strSearch'])) $strSearch = $_POST['strSearch'];
		$hasGmail = "";
		if( isset($_POST['hasGmail'])) $hasGmail = $_POST['hasGmail'] == "on" ? "gmail.com" : "";
		$hasPhone = false;
		if( isset($_POST['hasPhone'])) $hasPhone = $_POST['hasPhone'] == "on" ? true : false;
		$rate = false;
		$fromSale = 0;
		$toSale = -1;
		if( isset($_POST['rate'])) {
			$rate = $_POST['rate'] == "on" ? true : false;
			if( isset($_POST['fromSale'])) $fromSale = $_POST['fromSale'];
			if( isset($_POST['toSale'])) $toSale = $_POST['toSale'];
		}
		$signedTC = false;
		if( isset($_POST['signedTC'])) $signedTC = $_POST['signedTC'] == "on" ? true : false;
		$chkCompany = false;
		if( isset($_POST['chkCompany'])) $chkCompany = $_POST['chkCompany'] == "on" ? true : false;
		$strCompanies = "";
		if( isset($_POST['strCompanies'])) $strCompanies = $_POST['strCompanies'];
		$chkGeograpy = false;
		if( isset($_POST['chkGeograpy'])) $chkGeograpy = $_POST['chkGeograpy'] == "on" ? true : false;
		$strCountries = "";
		if( isset($_POST['strCountries'])) $strCountries = $_POST['strCountries'];
		$projectHistory = false;
		$strProjectHistories = "";
		if( isset($_POST['projectHistory'])) {
			$projectHistory = $_POST['projectHistory'] == "on" ? true : false;
			if( isset($_POST['strProjectHistories'])) $strProjectHistories = $_POST['strProjectHistories'];
		}
		$profiles = SearchProfiles4Project( $id, $strSearch, $hasGmail, $hasPhone, $rate, $fromSale, $toSale, $signedTC, $chkCompany, $strCompanies, $chkGeograpy, $strCountries, $projectHistory, $strProjectHistories);
		// print_r(count($profiles));
		// echo "<br>";
		// print_r($profiles);
	}
?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/mainProjects.css?<?= time();?>">

<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<style type="text/css">
	.subSection{
		margin-left: 30px;
	}
	.hideItem{
		display: none;
	}
</style>
<div class="topBar col-lg-12">
	<a href="projects.php">
		<img src="assets/imgs/vision-logo.png">
		<span class="topTitle"><strong>Nodes</strong></span>
	</a>
	<div class="topUserInfo">
		<a href="logout.php">Log Out &nbsp;&nbsp;<span><i class="fa fa-sign-out"></i></span></a>
	</div>
</div>
<div class="mainProjects col-lg-12">
	<div class="row">
		<div class="mainTitle col-lg-12">
			<h2><?=$curProject['projectTitle']?> <span><a href="projectDetails.php?project=<?=$id?>"><button class="btn btn-normal">Back to project</button></a></span></h2>
		</div>
	</div>
	<form class="row searchOptions" action="?project=<?=$id?>" method="POST" onsubmit="return checkForms()">
		<input type="hidden" name="project" value="<?=$id?>">
		<div class="col-lg-12">
			<!-- <div class="row"> -->
				<input type="text" name="strSearch" placeholder="Type here to search" class="form-control" style="float: left;">
				<button class="btn btn-primary" style="float: right; margin-top: 5px;">Search</button>
			<!-- </div> -->
		</div>
		<div class="row"></div>
		<div class="col-lg-12">
			<a href="javascript:void(0)" onclick="ShowHideFilters(this)" style="text-decoration: none;">Show filters</a>
			<br>
			<div class="row filterOptions">
				<div class="col-lg-12">
					<h4>Filters</h4>
				</div>
				<div class="col-lg-12">
					<input type="checkbox" name="hasGmail" id="hasGmail"> <label for="hasGmail"> Has Gmail Address</label><br>
					<input type="checkbox" name="hasPhone" id="hasPhone"> <label for="hasPhone"> Has Phone Number</label><br>
					<input type="checkbox" name="rate" id="rate"> <label for="rate">Rate</label><span> $ <input type="number" name="fromSale"> to $ <input type="number" name="toSale"></span><br>
					<input type="checkbox" name="signedTC" id="signedTC"> <label for="signedTC">Signed T&C</label><br>
					<input type="checkbox" name="chkCompany"> <label> Company </label> <span><input type="text" id="strCompany"></span> <span><div class="btn-primary btn" onclick="addCompany()">Add</div></span>
					<input type="hidden" name="strCompanies">
					<div id="companies" class="subSection"></div>

					<input type="checkbox" name="chkGeograpy" id="chkGeograpy"> <label for="chkGeograpy">Geography </label> <span class="glyphicon glyphicon-menu-down" onclick="geoClicked(this)"></span>
					<input type="hidden" name="strCountries">
					<div id="GeographySection">
						<?php
						foreach ($continents as $continent) {
						?>
						<div class="subSection hideItem">
							
						<input type="checkbox" id="<?=$continent?>" onchange="continentChanged(this)"> <label for="<?=$continent?>"><?=$continent?></label> <span class="glyphicon glyphicon-menu-down" onclick="continentClicked(this)"></span><br>
						<?php
							foreach ($countries as $key => $country) {
								if( $country['continent'] != $continent) continue;
						?>
							<div class="subSection hideItem country">
								<input type="checkbox" id="<?=$country['country']?>"> <label for="<?=$country['country']?>"><?=$country['country']?></label>
							</div>
						<?php
							}
							?>
						</div>
						<?php
						}
						?>
					</div>

					<input type="checkbox" name="projectHistory" id="projectHistory"> <label for="projectHistory">Project History </label> <span><input type="text" id="strHistory"></span> <span><div class="btn-primary btn" onclick="addProjectHistory()">Add</div></span>
					<input type="hidden" name="strProjectHistories">
					<div id="histories" class="subSection"></div>
					
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(".filterOptions").hide();
	function ShowHideFilters(_this){
		if( $(".filterOptions").is(":visible")){
			$(".filterOptions").hide();
			$(_this).html("Show filters");
		} else{
			$(".filterOptions").show();
			$(_this).html("Hide filters");
		}
	}
	function geoClicked(_this){
		if( $(_this).hasClass("glyphicon-menu-down")){
			$(_this).removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
			$("#GeographySection > div.subSection").removeClass("hideItem");
		} else{
			$(_this).removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
			$("#GeographySection > div.subSection").addClass("hideItem");
		}
	}
	function continentChanged(_this){
		$(_this).parent().find("div.subSection input[type=checkbox]").prop("checked", $(_this).prop("checked"));
	}
	function continentClicked(_this){
		if( $(_this).hasClass("glyphicon-menu-down")){
			$(_this).removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
			$(_this).parent().find("div.subSection").removeClass("hideItem");
			// $("#GeographySection > div.subSection").removeClass("hideItem");
		} else{
			$(_this).removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
			// $("#GeographySection > div.subSection").addClass("hideItem");
			$(_this).parent().find("div.subSection").addClass("hideItem");
		}
	}
	function addCompany(){
		var lstCompanies = [];
		var strBuf = $("#companies").html();
		if( strBuf)
			lstCompanies = strBuf.split(", ");
		var curCompany = $("#strCompany").val();
		if( !curCompany)return;
		$("#strCompany").val("");
		$("#strCompany").focus();
		if( lstCompanies.indexOf(curCompany) != -1){
			return false;
		}
		lstCompanies.push( curCompany);
		$("#companies").html(lstCompanies.join(", "));
		return false;
	}
	function addProjectHistory(){
		var lstHistories = [];
		var strBuf = $("#histories").html();
		if( strBuf)
			lstHistories = strBuf.split(", ");
		var curProject = $("#strHistory").val();
		if( !curProject)return;
		$("#strHistory").val("");
		$("#strHistory").focus();
		if( lstHistories.indexOf(curProject) != -1){
			return false;
		}
		lstHistories.push( curProject);
		$("#histories").html(lstHistories.join(", "));
		return false;
	}
	function checkForms(){
		$("input[name=strCompanies]").val($("#companies").html());
		$("input[name=strProjectHistories]").val($("#histories").html());
		var chkedCountries = $(".country input:checked");
		var arrBuff = [];
		for( var i = 0; i < chkedCountries.length; i++){
			arrBuff.push(chkedCountries.eq(i).attr("id"));
		}
		$("input[name=strCountries]").val(arrBuff.join(","));
		
		return true;
	}
</script>

