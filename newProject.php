<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userEmail = $_SESSION['userEmail'];
	if( $userEmail == "")
		header("Location: login.php?from=newProject.php");
	require_once __DIR__ . '/library/userManager.php';

	include("assets/components/header.php");

?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/mainProjects.css?<?= time();?>">

<div class="topBar col-lg-12">
	<a href="main.php">
		<img src="assets/imgs/vision-logo.png">
		<span class="topTitle"><strong>Nodes</strong></span>
	</a>
	<div class="topUserInfo">
		<a href="logout.php">Log Out &nbsp;&nbsp;<span><i class="fa fa-sign-out"></i></span></a>
	</div>
</div>

<div class="mainProjects col-lg-12">
	<div class="topnav row mainSearch">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-6">
					<h2>New Project</h2>
				</div>
				<div class="col-lg-6">
					<button class="floatRight btn btn-primary saveProject">Save Project</button>
					<button class="floatRight btn btn-primary copyProject">Copy</button>
					<button class="floatRight btn btn-primary manProject">Manage</button>
				</div>
			</div>
		</div>
	</div>
	<form>
		<div class="row">
			<div class="col-lg-6">
				<h5>Client Name</h5>
				<input type="text" name="clientName" class="form-control">
			</div>
			<div class="col-lg-6">
				<h5>Client Contact</h5>
				<input type="text" name="clientMainContact" class="form-control">
				<p>Additional Contacts</p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<h5>Project Information</h5>
				<div class="row">
					<div class="col-lg-6">
						<p class="titleP">Title</p>
						<input type="text" name="projectTitle" class="form-control">
					</div>
					<div class="col-lg-6">
						<p class="titleP">Type</p>
						<select class="form-control" name="projectType">
							<option value="Phone Consultation" selected>Phone Consultation</option>
							<option value="Written Report">Written Report</option>
							<option value="Private Visit">Private Visit</option>
							<option value="Talent on Demand">Talent on Demand</option>
							<option value="Strategic Project">Strategic Project</option>
							<option value="Partner Support Call">Partner Support Call</option>
							<option value="Expert Witness">Expert Witness</option>
							<option value="BOE">BOE</option>
						</select>
					</div>
					<div class="col-lg-12">
						<p class="titleP">Description</p>
						<textarea name="projectDesc" rows="5" class="form-control"></textarea>
					</div>
					<div class="col-lg-12">
						<p class="titleP">Practice Area</p>
						<select class="form-control" name="practiceArea">
							<option value="Healthcare & Biomedical">Healthcare & Biomedical</option>
							<option value="Tech, Media & Telecom">Tech, Media & Telecom</option>
							<option value="Energy & Industrials">Energy & Industrials</option>
							<option value="Legal & Regulatory Affairs">Legal & Regulatory Affairs</option>
							<option value="Consumer Goods & Services">Consumer Goods & Services</option>
							<option value="Accounting & Financial Analysis">Accounting & Financial Analysis</option>
							<option value="Financial & Business Services">Financial & Business Services</option>
							<option value="Real Estate">Real Estate</option>
							<option value="Education">Education</option>
							<option value="Hospitality">Hospitality</option>
						</select>
					</div>
					<div class="col-lg-12">
						<p class="titleP">Profile Questions</p>
						<input type="text" class="form-control" style="width: calc(100% - 5rem - 10px);float: left;" id="profileQuestions">
						<div class="btn btn-primary addQuestion" style="float: right;width: 5rem;">Add</div>
						<input type="hidden" name="profileQuestions">
					</div>
					<div class="col-lg-12" id="lstQuestions">
						
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript">
	function addQuestion(txtQuestion){
		var lstQuestions = $("#lstQuestions p");
		var strHtml = "<p>" + (lstQuestions.length + 1) + ". " + txtQuestion + "</p>";
		$("#lstQuestions").append(strHtml);
	}
	$(".addQuestion").click(function(){
		var txtQuestion = $("#profileQuestions").val();
		if( txtQuestion == ""){
			alert("No entered question.\n Please enter the question.");
			$("#profileQuestions").focus();
			return;
		}
		console.log(txtQuestion);
		$("#profileQuestions").val("");
		$("#profileQuestions").focus();
		addQuestion(txtQuestion);
	});
	$(".manProject").click(function(){
		window.location.href = "projects.php";
	});
	$(".copyProject").click(function(){
		console.log('copyProject');
	});
	$(".saveProject").click(function(){
		console.log('saveProject');
	})
</script>