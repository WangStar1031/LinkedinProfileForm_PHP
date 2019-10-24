<?php
	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userEmail = $_SESSION['userEmail'];
	if( $userEmail == "")
		header("Location: login.php?from=main.php");
	$id = "";
	if( isset($_GET['profile'])) $id = $_GET['profile'];
	if( isset($_POST['profile'])) $id = $_POST['profile'];

	if( $id == ""){
		header("Location: main.php");
	}

	require_once __DIR__ . '/library/userManager.php';
	require_once __DIR__ . '/library/projectManager.php';
	require_once __DIR__ . '/library/countries.php';
	require_once __DIR__ . '/library/timezone.php';

	$profile = getProfileFromId($id);
	include("assets/components/header.php");
	if( !$profile)
		header("Location: main.php");


?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/mainProjects.css?<?= time();?>">

<div class="topBar col-lg-12">
	<a href="main.php">
		<img src="assets/imgs/vision-logo-1.png">
		<span class="topTitle"><strong>Nodes</strong></span>
	</a>
	<div class="topUserInfo">
		<a href="logout.php">Log Out &nbsp;&nbsp;<span><i class="fa fa-sign-out"></i></span></a>
	</div>
</div>
<?php
// print_r($profile);
	$arrParticipants = ["Invited", "Sent to Client", "Paid", "InvitedAgain", "Available", "Attached", "Declined", "Availability Provided", "Accept Started", "Canceled", "Highlights Added", "Published", "Call Occurred", "Excluded", "Failed OQs", "Scheduling Hold", "Call Cancelled", "Failed Qualifier", "In Progress", "Accepted", "Completed"];
	$arrTypes = ["Consultation", "Qualitrics Survey"];
?>
<style type="text/css">
	.searchBar input{
		height: 32px;
		border-radius: 5px;
	}

	.checkbox-menu li label {
		display: block;
		padding: 3px 10px;
		clear: both;
		font-weight: normal;
		line-height: 1.42857143;
		color: #333;
		white-space: nowrap;
		margin:0;
		transition: background-color .4s ease;
	}
	.checkbox-menu li input {
		margin: 0px 5px;
		top: 2px;
		position: relative;
	}

	.checkbox-menu li.active label {
		background-color: #cbcbff;
		font-weight:bold;
	}

	.checkbox-menu li label:hover,
	.checkbox-menu li label:focus {
		background-color: #f5f5f5;
	}

	.checkbox-menu li.active label:hover,
	.checkbox-menu li.active label:focus {
		background-color: #b8b8ff;
	}

</style>
<div class="col-lg-12">
	<div class="row">
		<div class="col-lg-12">
			<h2><span class="FirstName"><?=$profile['FirstName']?></span> <span class="LastName"><?=$profile['LastName']?></span></h2>
			<h4><?=$profile['ProfileTitle']?></h4>
		</div>
		<div class="col-lg-8 col-md-8">
			<h5>Biography</h5>
			<div><?=$profile['Biography']?></div>
			<div class="projectHistory">
				<div class="row">

					<div class="col-lg-12 searchBar">
						<h5>Project History
							<span style="float: right;">
								<input type="date" name="" value="<?=date('Y-m-d', strtotime('-2 year'))?>">
								<input type="date" name="" value="<?=date('Y-m-d')?>">&nbsp;&nbsp;
								<input type="number" name="" style="max-width: 4rem;" value="5"> per page &nbsp;&nbsp;
								<button class="btn"><</button>
								Page <input type="number" name="" style="max-width: 4rem;" value="1"> of 25 
								<button class="btn">></button>
							</span>
						</h5>
					</div>
					<div class="row"></div>
					<div class="col-lg-12">
						<hr>
					</div>
					<div class="col-lg-12">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<input type="text" name="" placeholder="Search project history..." class="form-control">
							</div>
							<div class="col-lg-6 col-md-6">

<div class="dropdown" style="display: inline-block;">
  <button class="btn btn-primary dropdown-toggle" type="button" 
          id="dropdownMenu1" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="true">
    <!-- <i class="glyphicon glyphicon-cog"></i> -->
    Participant Status
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
  	<li>
  		<label>All: <input type="checkbox" id="chkPartAllOn" style="display: inline-block;"><label for="chkPartAllOn" style="display: inline-block;"> On </label>| <input type="checkbox" id="chkPartAllOff" style="display: inline-block;"> <label for="chkPartAllOff" style="display: inline-block;">Off</label></label>
  	</li>
  	<?php
  	foreach ($arrParticipants as $value) {
  		?>
    <li>
      <label><input type="checkbox" class="chkParticipiant"><?=$value?></label>
    </li>
  		<?php
  	}
  	?>
  </ul>
</div>


<div class="dropdown" style="display: inline-block;">
  <button class="btn btn-primary dropdown-toggle" type="button" 
          id="dropdownMenu1" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="true">
    <!-- <i class="glyphicon glyphicon-cog"></i> -->
    Type
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
  	<li>
  		<label>All: <input type="checkbox" id="chkTypeAllOn" style="display: inline-block;"><label for="chkTypeAllOn" style="display: inline-block;"> On </label>| <input type="checkbox" id="chkTypeAllOff" style="display: inline-block;"> <label for="chkTypeAllOff" style="display: inline-block;">Off</label></label>
  	</li>
  	<?php
  	foreach ($arrTypes as $value) {
  		?>
    <li>
      <label><input type="checkbox" class="chkParticipiant"><?=$value?></label>
    </li>
  		<?php
  	}
  	?>
  </ul>
</div>
								
							</div>
						</div>
						<span style="right: 10px; position: absolute;">
							<!-- <button class="btn btn-success">Participiant Status</button><button class="btn btn-success">Types</button> -->
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4">
			<div class="row">
				<div class="col-lg-5 col-md-5">
					<h5>Rate : </h5>
					<div><?=$profile['Rate'] ? $profile['Rate'] : 0?>$/hr</div>
					<h5>T&C Signed on : </h5>
					<div><?=$profile['TCSigned'] ? $profile['TCSigned'] : 0?></div>
					<h5>Created on : </h5>
					<div><?=$profile['Created'] ? $profile['Created'] : 0?></div>
				</div>
				<div class="col-lg-7 col-md-7">

<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" 
          data-toggle="dropdown">
    <!-- <i class="glyphicon glyphicon-cog"></i> -->
    Tools
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
  	<li><a href="editProfile.php?profile=<?=$id?>">Edit Page</a></li>
  	<li><a href="#">Send PW reset email</a></li>
  	<li><a href="#">Email T&C</a></li>
  	<li><a href="#">Send bio update email</a></li>
  	<li><a href="#">Upload resume</a></li>
  </ul>
</div>
					<!-- <h5>Tools</h5>
					<a href="editProfile.php?profile=<?=$id?>"><button class="btn btn-primary">Edit Page</button></a>
					<a href=""><button class="btn btn-primary">Send PW reset email</button></a>
					<a href=""><button class="btn btn-primary">Email T&C</button></a>
					<a href=""><button class="btn btn-primary">Send bio update email</button></a>
					<a href=""><button class="btn btn-primary">Upload resume</button></a> -->
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<h5>Job History</h5>
					<!-- <?=print_r($profile['employHistory']);?> -->
					<?php
					foreach ($profile['employHistory'] as $employ) {
					?>
					<div class="employSection">
						<h6>- <?=$employ['RoleTitle']?></h6>
						<div><?=$employ['CompanyName']?> (<?=$employ['FromDate']?> - <?=$employ['ToDate']?>)</div>

					</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".checkbox-menu").on("change", "input[type='checkbox'].chkParticipiant", function() {
   $(this).closest("li").toggleClass("active", this.checked);
});

$(document).on('click', '.allow-focus', function (e) {
  e.stopPropagation();
});

</script>