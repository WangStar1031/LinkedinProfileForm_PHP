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

<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>

<div class="topBar col-lg-12">
	<a href="projects.php">
		<img src="assets/imgs/vision-logo-1.png">
		<span class="topTitle"><strong>Nodes</strong></span>
	</a>
	<div class="topUserInfo">
		<a href="logout.php">Log Out &nbsp;&nbsp;<span><i class="fa fa-sign-out"></i></span></a>
	</div>
</div>
<?php
	// print_r($curProject);
?>
<style type="text/css">
	#expertSearchResults img{
		width: 30px;
		border-radius: 100%;
		margin-top: 5px;
		margin-bottom: 5px;
	}
	#expertSearchResults{
		max-height: 300px;
		overflow: auto;
	}
	.profileSection.odd{
		background-color: #eee;
	}
</style>
<div class="mainProjects col-lg-12">
	<div class="row">
		<div class="mainTitle col-lg-12">
			<?php
			if( $edit == 1){
			?>
			<h2><?=$curProject['projectTitle']?></h2>
			<!-- <h2>Title : <span><input type="text" name="projectTitle" value="<?=$curProject['projectTitle']?>"></span></h2> -->
			<?php
			} else{
			?>
			<h2><?=$curProject['projectTitle']?></h2>
			<?php
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 projectInfos">
			<div>
				<!-- <label>Project Number : </label> <?=$curProject['Id']?> -->
			</div>
			<div style="float: left;"><span>Loreal</span>, <span><?=$curProject['clientFirm']?></span></div>
			<div style="float: right;">
				<button onclick="deleteProject('<?=$id?>')" class="btn btn-danger">Delete</button>
				<?php
				if( $edit == 1){
				?>
				<button class="btn btn-primary" onclick="onSave()">Save</button>
				<a href="projectDetails.php?project=<?=$id?>"><button class="btn btn-primary">Exit</button></a>
				<?php
				} else{
				?>
				<a href="editProject.php?project=<?=$id?>"><button class="btn btn-primary">Edit</button></a>
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
			<div class="row">
				<h3 style="float: left; margin-left: 30px;">Experts
					<a href="addExperts.php?project=<?=$id?>"><button class="btn btn-success" style="margin-left: 30px;">Add Expert</button></a>
					<!-- <button class="btn btn-success" style="margin-left: 30px;" data-toggle="modal" data-target="#myModal">Add Expert</button> -->
				</h3>
			</div>
			
		</div>
		<?php
		$experts = getExperts4Project($id);
		if( $experts){
			$i = 0;
			foreach ($experts as $curExpert) {
				$i++;
				$profileId = $curExpert['profileId'];
				$expertProfile = getProfileFromId($profileId);
			?>
			<div profileId="<?=$profileId?>" class="col-lg-12 profileSection <?= $i%2?'odd':''?>">
				<div class="row">
					<div class="col-lg-12">
						<h4><?=$i?>) <?= $expertProfile['FirstName'] . ' ' . $expertProfile['LastName']?> <span><button class="btn btn-danger" onclick="onDelete('<?=$profileId?>')">Delete</button></span></h4>
					</div>
					<div class="col-lg-6 col-md-6">
						<div>
							<?= $expertProfile['ProfileTitle']?>
						</div>
						<div>
							<?= $expertProfile['Biography']?>
						</div>
					</div>
					<div class="col-lg-3 col-md-3">
						<div>
							<label>Project Status</label><br>
							<?php
							if( $edit == 1){
							?>
							<select class="projectStatus form-control">
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
					<div class="col-lg-3 col-md-3">
						<label>Basic Info</label>
						<?php
						if( $edit == 1){
						?>
						<table style="width: 100%;">
							<tr>
								<td><b>Sale:</b></td>
								<td><input type="number" name="sale" class="form-control" value="<?=$curExpert['sale']?>"></td>
							</tr>
							<tr>
								<td><b>Phone:</b></td>
								<td><input type="text" name="PhoneNumber" class="form-control" value="<?=$expertProfile['PhoneNumber']?>"></td>
							</tr>
							<tr>
								<td><b>Phone2:</b></td>
								<td><input type="text" name="phone2" class="form-control" value="<?=$expertProfile['PhoneNumber2']?>"></td>
							</tr>
							<tr>	
								<td><b>Email:</b></td>
								<td><input type="text" name="Email" class="form-control" value="<?=$expertProfile['Email']?>"></td>
							</tr>
							<tr>
								<td><b>Linkedin:</b></td>
								<td><input type="text" name="ProfileUrl" class="form-control" value="<?=$expertProfile['ProfileUrl']?>"></td>
							</tr>
							<tr>
								<td><b>Location:</b></td>
								<td><input type="text" name="Country" class="form-control" value="<?=$expertProfile['Country']?>"></td>
							</tr>
						</table>
						<?php
						} else{
						?>
						<div><b>Sale:</b> <?=$curExpert['sale']?></div>
						<div><b>Phone:</b> <?=$expertProfile['PhoneNumber']?></div>
						<div><b>Phone2:</b> <?=$expertProfile['PhoneNumber2']?></div>
						<div><b>Email:</b> <?=$expertProfile['Email']?></div>
						<div><b>Linkedin:</b> <a href="<?=$expertProfile['ProfileUrl']?>" target="_blank"><?=$expertProfile['ProfileUrl']?></a></div>
						<div><b>Location:</b> <?=$expertProfile['Country']?></div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
			<br>
			<?php
			}
		}
		?>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Experts</h4>
      </div>
      <div class="modal-body">
      	<h5>Search Options <span><button class="btn btn-success" style="float: right;" onclick="expertSearch()">Search</button></span></h5>
      	<div class="row" style="height: 20px;"></div>
      	<div class="row">
			<div class="col-lg-6 col-md-6">
				<div class="row">
					<div class="col-lg-4 col-md-4"><label>Name:</label></div>
					<div class="col-lg-8 col-md-8"><input type="text" name="Name" class="form-control"></div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6">
				<div class="row">
					<div class="col-lg-4 col-md-4"><label>Location:</label></div>
					<div class="col-lg-8 col-md-8"><input type="text" name="Location" class="form-control"></div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6">
				<div class="row">
					<div class="col-lg-4 col-md-4"><label>Jobs Function:</label></div>
					<div class="col-lg-8 col-md-8"><input type="text" name="JobFunction" class="form-control"></div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6">
				<div class="row">
					<div class="col-lg-4 col-md-4"><label>Industry:</label></div>
					<div class="col-lg-8 col-md-8"><input type="text" name="Industry" class="form-control"></div>
				</div>
			</div>
      	</div>
      	<hr>
      	<div id="expertSearchResults">
      		
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="AddExperts()">Add</button>
      </div>
    </div>
  </div>
</div>	
<script type="text/javascript">
	function expertSearch(){
		var name = $("input[name=Name]").val();
		var location = $("input[name=Location]").val();
		var jobsFunction = $("input[name=JobFunction]").val();
		var industry = $("input[name=Industry]").val();

		$.post("api_getProfiles.php", {case: "search", name: name, location: location, jobsFunction: jobsFunction, industry: industry}, function(data){
			$("#expertSearchResults").html(data);
		})
	}
	function AddExperts(){
		var experts = $("#expertSearchResults input[type=checkbox]").filter( function(){
			return $(this).prop("checked") == true;
		});
		console.log( experts);
		if( experts.length != 0){
			var arrIds = [];
			for( var i = 0; i < experts.length; i++){
				arrIds.push( experts.eq(i).parent().parent().attr("id"));
			}
			$.post("api_getProfiles.php", {case: "addExperts", projectId: "<?=$id?>", ids: arrIds.join(",")}, function(data){
				console.log( data);
				if( data == "yes"){
					window.location.reload();
				}
			})
		} else{
			$("#myModal").modal('hide');
		}
	}
	function onSave(){
		var profileDivs = $(".profileSection");
		var projectTitle = $("input[name=projectTitle]").val();

		var arrExperts = [];
		for( var i = 0; i < profileDivs.length; i++){
			var curExpertDiv = profileDivs.eq(i);
			var profileId = curExpertDiv.attr("profileId");
			var projectStatus = curExpertDiv.find(".projectStatus").val();
			var sale = curExpertDiv.find("input[name=sale]").val();
			var PhoneNumber = curExpertDiv.find("input[name=PhoneNumber]").val();
			var phone2 = curExpertDiv.find("input[name=phone2]").val();
			var Email = curExpertDiv.find("input[name=Email]").val();
			var ProfileUrl = curExpertDiv.find("input[name=ProfileUrl]").val();
			var Country = curExpertDiv.find("input[name=Country]").val();
			var curExpert = { profileId: profileId, projectStatus : projectStatus, sale : sale, PhoneNumber : PhoneNumber, phone2 : phone2, Email : Email, ProfileUrl : ProfileUrl, Country : Country};
			arrExperts.push(curExpert);
		}
		console.log( arrExperts);
		$.post("api_getProfiles.php", {case: "modifyExperts", projectId: "<?=$id?>", experts: JSON.stringify(arrExperts)}, function(data){
			if( data == "yes"){
				window.location.href = "projectDetails.php?project=<?=$id?>";
			}
		})
	}
	function onDelete(_profileId){
		if( confirm("Are you sure delete this expert?") == true){
			$.post("api_getProfiles.php", {case: "removeExpert", projectId: "<?=$id?>", profileId: _profileId}, function(data){
				if( data == "yes"){
					window.location.reload();
				}
			});
		}
	}
	function deleteProject(_projectId){
		if( confirm("Are you sure delete current project?") == true){
			$.post("api_getProfiles.php", {case: "removeProject", projectId: _projectId}, function(data){
				if( data == "yes"){
					window.location.reload();
				}
			});
		}
	}
</script>