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
	$project = getProjectInfo($id);
	if( !$project){
		header("Location: projects.php");
	}
	if( count($project) != 1){
		header("Location: projects.php");
	}
	$curProject = $project[0];
	$addContacts = getProjectCientAddContact($id);
	$questions = getProjectQuestions($id);
?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/mainProjects.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/newProject.css?<?= time();?>">

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

<div class="mainProjects col-lg-12">
	<div class="topnav row mainSearch">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-6">
					<h2>Edit Project</h2>
				</div>
				<div class="col-lg-6">
					<button class="floatRight btn btn-primary saveProject">Save</button>
					<a href="projectDetails.php?project=<?=$id?>"><button class="floatRight btn btn-primary">Exit</button></a>
				</div>
			</div>
		</div>
	</div>
	<form>
		<div class="row">
			<div class="col-lg-6">
				<h5 class="required">Client Firm</h5>
				<input type="text" name="clientName" class="form-control" value="<?=$curProject['clientFirm']?>">
			</div>
			<div class="col-lg-6">
				<h5 class="required">Client Contact</h5>
				<input type="text" name="clientMainContact" class="form-control" value="<?=$curProject['clientContacts']?>">
				<p>Additional Contacts<span id="addContacts" class="smallButton">+</span></p>
				<div id="clientAddContact" class="row">
					<?php
					foreach ($addContacts as $value) {
					?>
					<div class='col-lg-12'>
						<input class='addContactField form-control' value="<?=$value['contactName']?>">
						<div class='btn btn-danger delContact'>-</div>
					</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<h5>Project Information</h5>
				<div class="row">
					<div class="col-lg-6">
						<p class="titleP required">Title</p>
						<input type="text" name="projectTitle" class="form-control" value="<?=$curProject['projectTitle']?>">
					</div>
					<div class="col-lg-6">
						<p class="titleP">Type</p>
						<select class="form-control" name="projectType">
							<option <?php if($curProject['projectType'] == 'Phone Consultation') echo "selected";?> value="Phone Consultation">Phone Consultation</option>
							<option <?php if($curProject['projectType'] == 'Written Report') echo "selected";?> value="Written Report">Written Report</option>
							<option <?php if($curProject['projectType'] == 'Private Visit') echo "selected";?> value="Private Visit">Private Visit</option>
							<option <?php if($curProject['projectType'] == 'Talent on Demand') echo "selected";?> value="Talent on Demand">Talent on Demand</option>
							<option <?php if($curProject['projectType'] == 'Strategic Project') echo "selected";?> value="Strategic Project">Strategic Project</option>
							<option <?php if($curProject['projectType'] == 'Partner Support Call') echo "selected";?> value="Partner Support Call">Partner Support Call</option>
							<option <?php if($curProject['projectType'] == 'Expert Witness') echo "selected";?> value="Expert Witness">Expert Witness</option>
							<option <?php if($curProject['projectType'] == 'BOE') echo "selected";?> value="BOE">BOE</option>
						</select>
					</div>
					<div class="col-lg-12">
						<p class="titleP">Description</p>
						<textarea name="projectDesc" rows="5" class="form-control"><?=$curProject['projectDescription']?></textarea>
					</div>
					<div class="col-lg-12">
						<p class="titleP">Practice Area</p>
						<select class="form-control" name="practiceArea">
							<option <?php if($curProject['projectPracticeArea'] == 'Healthcare & Biomedical') echo "selected";?> value="Healthcare & Biomedical">Healthcare & Biomedical</option>
							<option <?php if($curProject['projectPracticeArea'] == 'Tech, Media & Telecom') echo "selected";?> value="Tech, Media & Telecom">Tech, Media & Telecom</option>
							<option <?php if($curProject['projectPracticeArea'] == 'Energy & Industrials') echo "selected";?> value="Energy & Industrials">Energy & Industrials</option>
							<option <?php if($curProject['projectPracticeArea'] == 'Legal & Regulatory Affairs') echo "selected";?> value="Legal & Regulatory Affairs">Legal & Regulatory Affairs</option>
							<option <?php if($curProject['projectPracticeArea'] == 'Consumer Goods & Services') echo "selected";?> value="Consumer Goods & Services">Consumer Goods & Services</option>
							<option <?php if($curProject['projectPracticeArea'] == 'Accounting & Financial Analysis') echo "selected";?> value="Accounting & Financial Analysis">Accounting & Financial Analysis</option>
							<option <?php if($curProject['projectPracticeArea'] == 'Financial & Business Services') echo "selected";?> value="Financial & Business Services">Financial & Business Services</option>
							<option <?php if($curProject['projectPracticeArea'] == 'Real Estate') echo "selected";?> value="Real Estate">Real Estate</option>
							<option <?php if($curProject['projectPracticeArea'] == 'Education') echo "selected";?> value="Education">Education</option>
							<option <?php if($curProject['projectPracticeArea'] == 'Hospitality') echo "selected";?> value="Hospitality">Hospitality</option>
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
							<?php
							foreach ($questions as $question) {
							?>
							<div class='col-lg-12'>
								<input class='question form-control' value='<?=$question["question"]?>'>
								<div class='delQuestion btn btn-danger'>-</div>
							</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript">
	$(".delQuestion").click(function(){
		$(this).parent().remove();
	});
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
		$("#profileQuestions").val("");
		$("#profileQuestions").focus();
		addQuestion(txtQuestion);
	});
	$(".manProject").click(function(){
		window.location.href = "projects.php";
	});
	$(".saveProject").click(function(){
		var data = {};
		data.projectId = "<?=$id?>";
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

		$.post("saveProject.php",{action: "updateProject", data: JSON.stringify(data)}, function (data){
			if(data == "yes"){
				alert("Updated.");
				window.location.href = "projects.php";
			} else{
				alert("Can't save project.");
			}
		});

	});
	$(".delContact").click(function(){
		$(this).parent().remove();
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
			$(this).parent().remove();
		})
		return;
	});
</script>