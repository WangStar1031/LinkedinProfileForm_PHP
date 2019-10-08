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
	$edit = "";
	if( isset($_GET['edit'])) $edit = $_GET['edit'];
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
<?php
	print_r($curProject);
?>

<div class="mainProjects col-lg-12">
	<div class="row">
		<div class="mainTitle col-lg-12">
			<h2><?=$curProject['projectTitle']?></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 projectInfos">
			<div>
				<!-- <label>Project Number : </label> <?=$curProject['Id']?> -->
			</div>
			<div style="float: left;"><span>Loreal</span>, <span><?=$curProject['clientFirm']?></span></div>
			<div style="float: right;">
				<?php
				if( $edit == 1){
				?>
				<button class="btn btn-primary">Save</button>
				<a href="projectDetails.php?project=<?=$id?>"><button class="btn btn-primary">Exit</button></a>
				<?php
				} else{
				?>
				<a href="projectDetails.php?project=<?=$id?>&edit=1"><button class="btn btn-primary">Edit</button></a>
				<a href="copyProject.php?project=<?=$id?>"><button class="btn btn-primary">Copy</button></a>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-lg-12">
			<h3 style="float: left;">Experts</h3>
			<button class="btn btn-success" style="margin-left: 30px;">Add Expert</button>
		</div>
		<?php
		$experts = getExperts4Project($id);
		if( $experts){
			$i = 0;
			foreach ($experts as $curExpert) {
				$i++;
				$expertProfile = getProfileFromId($curExpert['profileId'])[0];
			?>
				<div class="col-lg-4 col-md-4">
					<h5><?=$i?>) <?= $expertProfile['FirstName'] . $expertProfile['LastName']?></h5>
					<div>
						<?= $expertProfile['ProfileTitle']?>
					</div>
					<div>
						<?= $expertProfile['Biograyphy']?>
					</div>
				</div>
				<div class="col-lg-4 col-md-4">
					<div>
						<label>Project Status</label>
						<?php
						if( $edit == 1){
						?>
						<select id="projectStatus" class="form-control">
							<option value="Invited" <?php if($curExpert['projectStatus'] == 'Invited') echo "selected";?>>Invited</option>
							<option value="Accepted" <?php if($curExpert['projectStatus'] == 'Accepted') echo "selected";?>>Accepted</option>
							<option value="Declined" <?php if($curExpert['projectStatus'] == 'Declined') echo "selected";?>>Declined</option>
							<option value="Call scheduled" <?php if($curExpert['projectStatus'] == 'Call scheduled') echo "selected";?>>Call scheduled</option>
							<option value="Call occurred" <?php if($curExpert['projectStatus'] == 'Call occurred') echo "selected";?>>Call occurred</option>
						</select>
						<?php
						} else{
							echo $curExpert['projectStatus'];
						}
						?>
					</div>
				</div>
				<div class="col-lg-4 col-md-4">
					<label>Basic Info</label>
					<?php
					if( $edit == 1){
					?>
					<div><b>Sale:</b> <input type="number" name="sale" class="form-control" value="<?=$curExpert['sale']?>"></div>
					<div><b>Phone:</b> <input type="text" name="PhneNumber" class="form-control" value="<?=$expertProfile['PhneNumber']?>"></div>
					<div><b>Phone2:</b> <input type="text" name="phone2" class="form-control" value="<?=$curExpert['phone2']?>"></div>
					<div><b>Email:</b> <input type="text" name="Email" class="form-control" value="<?=$expertProfile['Email']?>"></div>
					<div><b>Linkedin:</b> <input type="text" name="ProfileUrl" class="form-control" value="<?=$expertProfile['ProfileUrl']?>"></div>
					<div><b>Location:</b> <input type="text" name="Country" class="form-control" value="<?=$expertProfile['Country']?>"></div>
					<?php
					} else{
					?>
					<div><b>Sale:</b> <?=$curExpert['sale']?></div>
					<div><b>Phone:</b> <?=$expertProfile['PhneNumber']?></div>
					<div><b>Phone2:</b> <?=$curExpert['phone2']?></div>
					<div><b>Email:</b> <?=$expertProfile['Email']?></div>
					<div><b>Linkedin:</b> <?=$expertProfile['ProfileUrl']?></div>
					<div><b>Location:</b> <?=$expertProfile['Country']?></div>
					<?php
					}
					?>
				</div>
			<?php
			}
		}
		?>
	</div>
</div>