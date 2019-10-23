<?php
	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userEmail = $_SESSION['userEmail'];
	if( $userEmail == "")
		header("Location: login.php?from=main.php");
	require_once __DIR__ . '/library/userManager.php';
	require_once __DIR__ . '/library/projectManager.php';
	require_once __DIR__ . '/library/countries.php';
	require_once __DIR__ . '/library/timezone.php';

	include("assets/components/header.php");



?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/mainProjects.css?<?= time();?>">

<style type="text/css">
	.requiredField:after{
		content: "*";
		color: red;
		padding-left: 5px;
	}
	.EmploymentSection, .EducationSection{
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
		<img src="assets/imgs/vision-logo-1.png">
		<span class="topTitle"><strong>Nodes</strong></span>
	</a>
	<div class="topUserInfo">
		<a href="logout.php">Log Out &nbsp;&nbsp;<span><i class="fa fa-sign-out"></i></span></a>
	</div>
</div>
<!-- 
<input list="languages">
<datalist id="languages">
	<option value="En">En</option>
	<option value="Du">Du</option>
</datalist> -->

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
					<!-- <input type="hidden" name="prefix"> -->
					<div class="col-md-4 col-xs-4">
						<input type="radio" id="Mr" class="prefix"> <label for="Mr">Mr.</label>
					</div>
					<div class="col-md-4 col-xs-4">
						<input type="radio" id="Ms" class="prefix"> <label for="Ms">Ms.</label>
					</div>
					<div class="col-md-4 col-xs-4">
						<input type="radio" id="Dr" class="prefix"> <label for="Dr">Dr.</label>
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
				<select class="form-control" id="Country" name="Country">
					<option value=""></option>
					<?php
					foreach ($countries as $key => $value) {
						$curCountryName = $value['country'];
					?>
					<option value="<?=$curCountryName?>"><?=$curCountryName?></option>
					<?php
					}
					?>
				</select>
				<!-- <input type="text" name="Country" class="form-control"> -->
			</div>
			<div class="col-lg-4 col-md-4">
				<label class="">Time Zone</label>
				<select class="form-control" name="TimeZone" id="TimeZone">
					<option value=""></option>
					<?php
					foreach ($timezones as $timezone) {
					?>
					<option value="<?=$timezone?>"><?=$timezone?></option>
					<?php
					}
					?>
				</select>
				<!-- <input type="text" name="Country" class="form-control"> -->
			</div>
			<div class="col-lg-4 col-md-4">
				<label class="">Reffered By</label>
				<input type="text" name="RefferedBy" class="form-control">
			</div>

			<div class="col-lg-6 col-md-6">
				<label class="">Linedin Profile URL</label>
				<input type="text" name="LinedinUrl" class="form-control">
			</div>
			<div class="col-lg-6 col-md-6">
				<label class="">Job Profile URL</label>
				<input type="text" name="JobProfileUrl" class="form-control">
			</div>
			<!-- <div class="col-lg-4 col-md-4">
				<label class="">Source</label>
				<input type="text" name="Country" class="form-control">
			</div> -->

			<div class="col-lg-4 col-md-4">
				<label class="requiredField">Practice Area</label>
				<select class="form-control" name="JobFunction" id="JobFunction">
					<option value=""></option>
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
				<!-- <input type="text" name="Country" class="form-control"> -->
			</div>
			<div class="col-lg-4 col-md-4">
				<label class="">Email address</label>
				<input type="text" name="Email" class="form-control">
			</div>
			<div class="col-lg-4 col-md-4">
				<label class="">Mobile Number</label>
				<input type="text" name="PhoneNumber" class="form-control">
			</div>

		</div>
		<div class="row">
			<div class="col-lg-12">
				<label class="">English Biography</label>
				<!-- <button class="btn btn-success" style="margin: 5px;" onclick="makeBio()">Make Automatically</button> -->
				<textarea class="form-control" rows="5" name="Biography"></textarea>
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
		<br>
		<div class="row">
			<div class="col-lg-12">
				<h4>Education History</h4>
			</div>
			<div class="col-lg-12 EducationHistory">
			</div>
			<div class="col-lg-12">
				<div class="row">
					<div class="col-lg-12">
						<div class="btn btn-success" style="float: right; margin-top: 10px;" onclick="AddEducation()">Add Education</div>
					</div>
				</div>
			</div>
		</div>
		<br>
	</div>
</div>
<div style="display: none;" id="EmploymentSection">
				<div class="EmploymentSection" style="margin-top: 10px;">
					<div class="row">
						<div class="col-lg-12">
							<div class="closeX" style="">
								<a href="javascript:void(0)" onclick="removeEmpSection(this)"><i class="fa fa-times"></i></a>
							</div>
						</div>
					</div>
					<div class="row" style="padding: 10px;"></div>
					<div class="col-lg-12">
							<div class="col-lg-6 col-md-6">
								<label class="requiredField">Title</label>
								<input type="text" name="Title" class="form-control">
							</div>
							<div class="col-lg-6 col-md-6">
								<label class="requiredField">Company</label>
								<input list="Companies" name="Company" class="form-control">
								<datalist id="Companies">
								<?php
								$lstCompanies = getAllCompanyNames();
								foreach ($lstCompanies as $company) {
								?>
									<option value="<?=$company?>"><?=$company?></option>
								<?php
								}
								?>
								</datalist>

								<!-- <input type="text" name="Company" class="form-control"> -->
							</div>
							<div class="col-lg-6 col-md-6">
								<label class="requiredField">Start Date</label>
								<input type="date" name="StartDate" class="form-control">
							</div>
							<div class="col-lg-6 col-md-6">
								<label class="">End Date</label> 
								&nbsp;&nbsp;<input type="checkbox" name="isCurrent"> <label>Present/Current Working</label>
								<input type="date" name="EndDate" class="form-control">
							</div>
					</div>
					<div class="row" style="padding: 20px;">
					</div>
				</div>
</div>

<div style="display: none;" id="EducationSection">
				<div class="EducationSection" style="margin-top: 10px;">
					<div class="row">
						<div class="col-lg-12">
							<div class="closeX" style="">
								<a href="javascript:void(0)" onclick="removeEmpSection(this)"><i class="fa fa-times"></i></a>
							</div>
						</div>
					</div>
					<div class="row" style="padding: 10px;"></div>
					<div class="col-lg-12">
							<div class="col-lg-6 col-md-6">
								<label class="requiredField">School Name</label>
								<input type="text" name="SchoolName" class="form-control">
							</div>
							<div class="col-lg-6 col-md-6">
								<label class="requiredField">Degree Name</label>
								<input type="text" name="DegreeName" class="form-control">
							</div>
							<div class="col-lg-4 col-md-4">
								<label class="requiredField">Area Name</label>
								<input type="text" name="AreaName" class="form-control">
							</div>
							<div class="col-lg-4 col-md-4">
								<label class="requiredField">Start Year</label>
								<input type="number" name="StartYear" class="form-control">
							</div>
							<div class="col-lg-4 col-md-4">
								<label class="requiredField">End Year</label>
								<input type="number" name="EndYear" class="form-control">
							</div>
					</div>
					<div class="row" style="padding: 20px;">
					</div>
				</div>
</div>


<script type="text/javascript">
	var userEmail = "<?=$userEmail?>";
	AddEducation();
	function AddEducation(){
		$("#EducationSection .EducationSection").clone().appendTo(".EducationHistory");
	}
	AddPosition();
	function AddPosition(){
		$("#EmploymentSection .EmploymentSection").clone().appendTo(".EmploymentHistory");
	}
	function setNormal(){
		$(".prefix").parent().css("border", "none");
		$("input[name=FirstName]").css("border", "1px solid #ccc");
		$("input[name=LastName]").css("border", "1px solid #ccc");
		$("#Country").css("border", "1px solid #ccc");
		$("#JobFunction").css("border", "1px solid #ccc");
		var Employments = $(".EmploymentHistory .EmploymentSection");
		for( var i = 0; i < Employments.length; i++){
			var curEmp = Employments.eq(i);
			curEmp.find("input[name=Title]").css("border", "1px solid #ccc");
			curEmp.find("input[name=Company]").css("border", "1px solid #ccc");
			curEmp.find("input[name=StartDate]").css("border", "1px solid #ccc");
		}
		var Educations = $(".EducationHistory .EducationSection");
		for( var i = 0; i < Educations.length; i++){
			var curEmp = Educations.eq(i);
			curEmp.find("input[name=SchoolName]").css("border", "1px solid #ccc");
			curEmp.find("input[name=DegreeName]").css("border", "1px solid #ccc");
			curEmp.find("input[name=AreaName]").css("border", "1px solid #ccc");
			curEmp.find("input[name=StartYear]").css("border", "1px solid #ccc");
			curEmp.find("input[name=EndYear]").css("border", "1px solid #ccc");
		}
	}
	function saveProject(){
		setNormal();
		var canBeSubmit = true;
		var prefix = "";
		var prefixRadios = $(".prefix");
		for( var i = 0; i < prefixRadios.length; i++){
			if( prefixRadios.eq(i).prop("checked")){
				prefix = prefixRadios.eq(i).attr("id");
			}
		}
		if( prefix == ""){
			canBeSubmit = false;
			$(".prefix").parent().css("border", "1px solid red");
		}
		var FirstName = $("input[name=FirstName]").val();
		if( !FirstName){
			canBeSubmit = false;
			$("input[name=FirstName]").css("border", "1px solid red");
		}
		var LastName = $("input[name=LastName]").val();
		if( !LastName){
			canBeSubmit = false;
			$("input[name=LastName]").css("border", "1px solid red");
		}
		var Suffix = $("input[name=Suffix]").val();
		var Country = $("#Country").val();
		if( !Country){
			canBeSubmit = false;
			$("#Country").css("border", "1px solid red");
		}
		var TimeZone = $("#TimeZone").val();
		var RefferedBy = $("input[name=RefferedBy]").val();
		var LinedinUrl = $("input[name=LinedinUrl]").val();
		var JobProfileUrl = $("input[name=JobProfileUrl]").val();
		var JobFunction = $("#JobFunction").val();
		if( !JobFunction){
			canBeSubmit = false;
			$("#JobFunction").css("border", "1px solid red");
		}
		var Email = $("input[name=Email]").val();
		var PhoneNumber = $("input[name=PhoneNumber]").val();
		var Biography = $("textarea[name=Biography]").val();

		var Employments = $(".EmploymentHistory .EmploymentSection");
		var arrEmploys = [];
		for( var i = 0; i < Employments.length; i++){
			var curEmp = Employments.eq(i);
			var curTitle = curEmp.find("input[name=Title]").val();
			if( !curTitle){
				canBeSubmit = false;
				curEmp.find("input[name=Title]").css("border", "1px solid red");
			}
			var curCompany = curEmp.find("input[name=Company]").val();
			if( !curCompany){
				canBeSubmit = false;
				curEmp.find("input[name=Company]").css("border", "1px solid red");
			}
			var StartDate = curEmp.find("input[name=StartDate]").val();
			if( !StartDate){
				canBeSubmit = false;
				curEmp.find("input[name=StartDate]").css("border", "1px solid red");
			}
			var EndDate = curEmp.find("input[name=EndDate]").val();
			if( curEmp.find("input[name=isCurrent]").prop("checked")){
				EndDate = "Present";
			}
			arrEmploys.push({CompanyName: curCompany, RoleTitle: curTitle, FromDate: StartDate, ToDate: EndDate});
		}

		var Educations = $(".EducationHistory .EducationSection");
		var arrEducations = [];
		for( var i = 0; i < Educations.length; i++){
			var curEdu = Educations.eq(i);
			var curSchoolName = curEdu.find("input[name=SchoolName]").val();
			if( !curSchoolName){
				canBeSubmit = false;
				curEdu.find("input[name=SchoolName]").css("border", "1px solid red");
			}
			var curDegreeName = curEdu.find("input[name=DegreeName]").val();
			if( !curDegreeName){
				canBeSubmit = false;
				curEdu.find("input[name=DegreeName]").css("border", "1px solid red");
			}
			var curAreaName = curEdu.find("input[name=AreaName]").val();
			if( !curAreaName){
				canBeSubmit = false;
				curEdu.find("input[name=AreaName]").css("border", "1px solid red");
			}
			var curStartYear = curEdu.find("input[name=StartYear]").val();
			if( !curStartYear){
				canBeSubmit = false;
				curEdu.find("input[name=StartYear]").css("border", "1px solid red");
			}
			var curEndYear = curEdu.find("input[name=EndYear]").val();
			if( !curEndYear){
				canBeSubmit = false;
				curEdu.find("input[name=EndYear]").css("border", "1px solid red");
			}
			arrEducations.push({SchoolName: curSchoolName, DegreeName: curDegreeName, AreaName: curAreaName, StartYear: curStartYear, EndYear: curEndYear});
		}

		if( canBeSubmit == false){
			return false;
		}

		var data = {prefix: prefix, FirstName: FirstName, LastName: LastName, Suffix: Suffix, Country: Country, TimeZone: TimeZone, RefferedBy: RefferedBy, LinedinUrl: LinedinUrl, JobProfileUrl: JobProfileUrl, JobFunction: JobFunction, Email: Email, PhoneNumber: PhoneNumber, Biography: Biography, arrEmploys: arrEmploys, arrEducations: arrEducations};
		$.post("api_getProfiles.php", {case:"manProfile", email: userEmail, data: JSON.stringify(data)}, function( data){
			if( data = "Inserted"){
				alert("Successfully created.");
				window.location.href = "main.php";
			} else{
				alert("Creating profile failed.");
			}
		});
	}
	function checkValid(){

	}
	function makeBio(){
		var Employments = $(".EmploymentHistory .EmploymentSection");
		var arrEmploys = [];
		for( var i = 0; i < Employments.length; i++){
			var curEmp = Employments.eq(i);
			var curTitle = curEmp.find("input[name=Title]").val();
			var curCompany = curEmp.find("input[name=Company]").val();
			var StartDate = curEmp.find("input[name=StartDate]").val();
			var EndDate = curEmp.find("input[name=EndDate]").val();
			if( !curTitle || !curCompany || !StartDate){
				alert("Please insert correct Title, Company Name and Start Date");
				return;
			}
			arrEmploys.push({CompanyName: curCompany, RoleTitle: curTitle, FromDate: StartDate, ToDate: EndDate});
		}
		var FirstName = $("input[name=FirstName]").val();
		var LastName = $("input[name=LastName]").val();
		if( !FirstName || !LastName){
			alert("Please insert First Name and Last Name.");
			return;
		}

		var strBio = "";
		var fullName = FirstName + " " + LastName;
		var prevCount = false;
		var isCurrentJob = false;
		if( arrEmploys.length == 0){
			strBio += fullName + " is currently in-between jobs.";
		}
		for( var i = 0; i < arrEmploys.length; i++){
			var curExp = arrEmploys[i];
			// if( curExp.workingHistory.length == 1){
				var curWorking = curExp.workingHistory[0];
				var strDuration = curWorking.duration;
				var lstDuration = strDuration.split(" - ");
				if( curWorking.duration.indexOf("Present") != -1){
					isCurrentJob = true;
					strBio += fullName + " is currently employed at " + curExp.companyName + ", since " + lstDuration[0] + ", holding  the title of " + curWorking.title + ".";
				} else{
					if( isCurrentJob == false){
						strBio += fullName + " is currently in-between jobs.";
					}
					prevCount = !prevCount;
					if( prevCount ){
						strBio += " Previously, ";
					} else{
						strBio += " Before this, ";
					}
					// console.log(prevCount );
					// console.log( strBio);
					strBio += FirstName + " held the position of " + curWorking.title + ", while working at " + curExp.companyName + ".";
					if( lstDuration != ""){
						strBio += " " + FirstName + " held this role for " + convertDuration(lstDuration) + "(" + strDuration + ")."
					}
				}
			// } else{

			// }
		}
		$("textarea[name=Biography]").val(strBio);
	}
	function removeEmpSection(_this){
		$(_this).parent().parent().parent().parent().remove();
	}
</script>