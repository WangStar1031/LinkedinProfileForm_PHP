<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userEmail = $_SESSION['userEmail'];
	if( $userEmail == "")
		header("Location: login.php?from=projects.php");
	require_once __DIR__ . '/library/userManager.php';
	require_once __DIR__ . '/library/projectManager.php';

	include("assets/components/header.php");
	$search = "";
	if( isset($_POST['search'])) $search = $_POST['search'];
?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/mainProjects.css?<?= time();?>">
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
			<h2>Projects</h2>
		</div>
	</div>
	<div class="topnav row mainSearch">
		<div class="col-lg-12">
			<div class="row">
				<form class="searchForm search-container col-lg-6" method="POST">
						<i class="fa fa-search searchIcon" aria-hidden="true"></i>
						<input class="form-control" type="text" placeholder="Search" aria-label="Search" name="search" value="<?=$search?>">
				</form>
				<div class="col-lg-6">
					<button class="btn btn-success newProject" style="float: right;">+ NEW </button>
				</div>
			</div>
		</div>
	</div>
	<div class="mainContents">
		<table class="col-lg-12">
			<tr>
				<th>Project Info</th>
				<th>Client</th>
				<th>Start Date</th>
				<th>Experts Attached</th>
				<th>Calls</th>
			</tr>
			<?php
			$projects = getAllProjects($search);
			if( $projects){
				foreach ($projects as $project) {
				?>
				<tr>
					<td><a href="projectDetails.php?project=<?=$project['Id']?>"><?=$project["projectTitle"]?></a></td>
					<td><?=$project["clientFirm"]?></td>
					<td><?=$project["startedDate"]?></td>
				</tr>
				<?php
				}
			}
			?>
		</table>
	</div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript">
	$(".searchForm .searchIcon").click(function(){
		console.log("icon clicked.");
	})
	$(".newProject").click(function(){
		window.location.href = "newProject.php";
	})
</script>