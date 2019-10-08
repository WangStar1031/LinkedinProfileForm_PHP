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
<style type="text/css">
	.smallButton{
		margin-left: 20px;
		cursor: pointer;
		border-radius: 100%;
		background-color: green;
		color: white;
		padding: 0px 4px;
	}
	.required:after{
		content: "*";
		color: red;
		margin-left: 10px;
	}
	.addContactField{
		width: calc(100% - 50px);
		float: left;
	}
	.delContact, .delQuestion{
		float: right;
	}
	#clientAddContact > div{
		margin-bottom: 10px;
	}
	.question{
		width: calc(100% - 50px);
		float: left;
	}
	#lstQuestions{
		margin-top: 10px;
	}
	#lstQuestions > div{
		margin-bottom: 10px;
	}
</style>
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
				<h5 class="required">Client Firm</h5>
				<input type="text" name="clientName" class="form-control">
			</div>
			<div class="col-lg-6">
				<h5 class="required">Client Contact</h5>
				<input type="text" name="clientMainContact" class="form-control">
				<p>Additional Contacts<span id="addContacts" class="smallButton">+</span></p>
				<div id="clientAddContact" class="row">
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<h5>Project Information</h5>
				<div class="row">
					<div class="col-lg-6">
						<p class="titleP required">Title</p>
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
					<div class="col-lg-12">
						<div class="row" id="lstQuestions">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript">
	function addQuestion(txtQuestion){
		var lstQuestions = $("#lstQuestions > div");
		var strHtml = "";
		strHtml += "<div class='col-lg-12'>";
		strHtml += "<input class='question form-control' value='" + txtQuestion + "'>";
		strHtml += "<div class='delQuestion btn btn-danger'>-</div>";
		strHtml += "</div>";
		$("#lstQuestions").append(strHtml);
		$(".delQuestion").unbind("click");
		$(".delQuestion").click(function(){
			$(this).parent().remove();
		});
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
		var data = {};
		var clientFirm = $("input[name=clientName]").val();
		if( !clientFirm) return;
		data.clientFirm = clientFirm;

		var clientContact = $("input[name=clientMainContact]").val();
		if( !clientContact) return;
		data.clientContact = clientContact;

		var lstAdditionals = $("input.addContactField");
		var lstAddContacts = [];
		for( var i = 0; i < lstAdditionals.length; i++){
			var curConName = lstAdditionals.eq(i).val();
			if( !curConName) continue;
			lstAddContacts.push(curConName);
		}
		data.lstAddContacts = lstAddContacts;

		var projectTitle = $("input[name=projectTitle]").val();
		if( !projectTitle)return;
		data.projectTitle = projectTitle;

		var projectType = $("select[name=projectType]").val();
		if( !projectType) return;
		data.projectType = projectType;

		var projectDesc = $("textarea[name=projectDesc]").val();
		if( !projectDesc) return;
		data.projectDesc = projectDesc;

		var practiceArea = $("select[name=practiceArea]").val();
		if( !practiceArea) return;
		data.practiceArea = practiceArea;

		var lstQuestions = $("input.question");
		var lstProfileQuestions = [];
		for( var i = 0; i < lstQuestions.length; i++){
			var curQuestion = lstQuestions.eq(i).val();
			if( !curQuestion)continue;
			lstProfileQuestions.push(curQuestion);
		}
		data.lstProfileQuestions = lstProfileQuestions;

		$.post("saveProject.php",{action: "saveProject", data: JSON.stringify(data)}, function (data){
			console.log(data);
			if(data == "yes"){
				alert("Inserted.");
				window.location.href = "projects.php";
			} else{
				alert("Can't save project.");
			}
		});

	});
	$("#addContacts").click(function(){
		var strHtml = "";
		strHtml += "<div class='col-lg-12'>";
			strHtml += "<input class='addContactField form-control'>";
			strHtml += "<div class='btn btn-danger delContact'>-</div>";
		strHtml += "</div>";
		$("#clientAddContact").append(strHtml);
		$(".delContact").unbind("click");
		$(".delContact").click(function(){
			console.log($(".delContact").length);
			console.log(this);
			console.log($(this).index());
			$(this).parent().remove();
		})
		return;
		var newContact = prompt("Please enter new contact name");
		if( newContact != null){
			console.log( newContact);

		}
	});
</script>