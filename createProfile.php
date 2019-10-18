<?php
	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userEmail = $_SESSION['userEmail'];
	if( $userEmail == "")
		header("Location: login.php?from=main.php");
	require_once __DIR__ . '/library/userManager.php';
	require_once __DIR__ . '/library/projectManager.php';

	include("assets/components/header.php");



?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/mainProjects.css?<?= time();?>">

<script src="assets/js/jquery.min.js"></script>

<style type="text/css">
	.requiredField:after{
		content: "*";
		color: red;
		padding-left: 5px;
	}
	.EmploymentSection{
		border: 1px solid gray;
	}
	.closeX{
		text-align: right;
		background-color: #ddd;
		width: 100%;
		padding: 5px;
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
	<div>
		<div class="topnav row mainSearch">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-lg-6">
						<h2>Create lead</h2>
					</div>
					<div class="col-lg-6">
						<button class="floatRight btn btn-primary saveProject" onclick="saveProject()">Save</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-3">
				<div class="row">
					<div class="col-lg-12">
						<label class="requiredField">Prefix</label>
					</div>
					<div class="col-md-4 col-xs	-4">
						<input type="radio" name="prefix"> <label>Mr.</label>
					</div>
					<div class="col-md-4 col-xs	-4">
						<input type="radio" name="prefix"> <label>Ms.</label>
					</div>
					<div class="col-md-4 col-xs	-4">
						<input type="radio" name="prefix"> <label>Dr.</label>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3">
				<label class="requiredField">
					First Name
				</label>
				<input type="text" name="FirstName" class="form-control">
			</div>
			<div class="col-lg-3 col-md-3">
				<label class="requiredField">
					Last Name
				</label>
				<input type="text" name="LastName" class="form-control">
			</div>
			<div class="col-lg-3 col-md-3">
				<label class="">
					Suffix
				</label>
				<input type="text" name="Suffix" class="form-control">
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 col-md-4">
				<label class="requiredField">Country</label>
				<input type="text" name="Country" class="form-control">
			</div>
			<div class="col-lg-4 col-md-4">
				<label class="">Time Zone</label>
				<input type="text" name="Country" class="form-control">
			</div>
			<div class="col-lg-4 col-md-4">
				<label class="">Reffered By</label>
				<input type="text" name="Country" class="form-control">
			</div>

			<div class="col-lg-6 col-md-6">
				<label class="">Linedin Profile URL</label>
				<input type="text" name="Country" class="form-control">
			</div>
			<div class="col-lg-6 col-md-6">
				<label class="">Job Profile URL</label>
				<input type="text" name="Country" class="form-control">
			</div>
			<!-- <div class="col-lg-4 col-md-4">
				<label class="">Source</label>
				<input type="text" name="Country" class="form-control">
			</div> -->

			<div class="col-lg-4 col-md-4">
				<label class="requiredField">Practice Area</label>
				<input type="text" name="Country" class="form-control">
			</div>
			<div class="col-lg-4 col-md-4">
				<label class="">Email address</label>
				<input type="text" name="Country" class="form-control">
			</div>
			<div class="col-lg-4 col-md-4">
				<label class="">Mobile Number</label>
				<input type="text" name="Country" class="form-control">
			</div>

		</div>
		<div class="row">
			<div class="col-lg-12">
				<label class="">English Biography</label>
				<textarea class="form-control" rows="5"></textarea>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-lg-12">
				<h4>Employment History</h4>
			</div>
			<div class="col-lg-12 EmploymentHistory">
			</div>
			<div class="col-lg-12">
				<div class="row">
					<div class="col-lg-12">
						<div class="btn btn-success" style="float: right; margin-top: 10px;" onclick="AddPosition()">Add Position</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="display: none;" id="EmploymentSection">
				<div class="EmploymentSection" style="margin-top: 10px;">
					<div class="row">
						<div class="col-lg-12">
							<div class="closeX" style="">
								<a href="javascript:void(0)"><i class="fa fa-times"></i></a>
							</div>
						</div>
					</div>
					<div class="row" style="padding: 10px;"></div>
					<div class="col-lg-12">
							<div class="col-lg-6 col-md-6">
								<label class="requiredField">Title</label>
								<input type="text" name="" class="form-control">
							</div>
							<div class="col-lg-6 col-md-6">
								<label class="requiredField">Company</label>
								<input type="text" name="" class="form-control">
							</div>
							<div class="col-lg-6 col-md-6">
								<label class="requiredField">Start Date</label>
								<input type="date" name="" class="form-control">
							</div>
							<div class="col-lg-6 col-md-6">
								<label class="">End Date</label>
								<input type="date" name="" class="form-control">
							</div>
					</div>
					<div class="row" style="padding: 20px;">
					</div>
				</div>
</div>


<script type="text/javascript">
	AddPosition();
	function AddPosition(){
		$("#EmploymentSection .EmploymentSection").clone().appendTo(".EmploymentHistory");
	}
	function saveProject(){

	}
</script>