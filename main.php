<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userEmail = $_SESSION['userEmail'];
	if( $userEmail == "")
		header("Location: login.php");
	require_once __DIR__ . '/library/userManager.php';
	$genderFilter = '';
	if( isset($_GET['genderFilter'])) $genderFilter = $_GET['genderFilter'];
	if( isset($_POST['genderFilter'])) $genderFilter = $_POST['genderFilter'];

	$recordCountFilter = 10;
	if( isset($_GET['recordCountFilter'])) $recordCountFilter = $_GET['recordCountFilter'];
	if( isset($_POST['recordCountFilter'])) $recordCountFilter = $_POST['recordCountFilter'];
	$pageNumFilter = 1;
	if( isset($_GET['pageNumFilter'])) $pageNumFilter = $_GET['pageNumFilter'];
	if( isset($_POST['pageNumFilter'])) $pageNumFilter = $_POST['pageNumFilter'];
	$countryFilter = '';
	if( isset($_GET['countryFilter'])) $countryFilter = $_GET['countryFilter'];
	if( isset($_POST['countryFilter'])) $countryFilter = $_POST['countryFilter'];

	$companyNameFilter = '';
	if( isset($_GET['companyNameFilter'])) $companyNameFilter = $_GET['companyNameFilter'];
	if( isset($_POST['companyNameFilter'])) $companyNameFilter = $_POST['companyNameFilter'];

	$jobsFunctinoFilter = '';
	if( isset($_GET['jobsFunctinoFilter'])) $jobsFunctinoFilter = $_GET['jobsFunctinoFilter'];
	if( isset($_POST['jobsFunctinoFilter'])) $jobsFunctinoFilter = $_POST['jobsFunctinoFilter'];
	$industryFilter = '';
	if( isset($_GET['industryFilter'])) $industryFilter = $_GET['industryFilter'];
	if( isset($_POST['industryFilter'])) $industryFilter = $_POST['industryFilter'];
	$geographyFilter = '';
	if( isset($_GET['geographyFilter'])) $geographyFilter = $_GET['geographyFilter'];
	if( isset($_POST['geographyFilter'])) $geographyFilter = $_POST['geographyFilter'];
	$intelSearchFilter = '';
	if( isset($_GET['intelSearchFilter'])) $intelSearchFilter = $_GET['intelSearchFilter'];
	if( isset($_POST['intelSearchFilter'])) $intelSearchFilter = $_POST['intelSearchFilter'];
	$_filter = new \stdClass;
	$_filter->gender = $genderFilter;
	$_filter->recCount = $recordCountFilter;
	$_filter->pageNum = $pageNumFilter;
	$_filter->country = $countryFilter;
	$_filter->company = $companyNameFilter;
	$_filter->jobsFunction = $jobsFunctinoFilter;
	$_filter->industry = $industryFilter;
	$_filter->geography = $geographyFilter;
	$_filter->intelSearch = $intelSearchFilter;

	$preTime = microtime();
	$profileInfos = getProfileInfos( $userEmail, $_filter);
	$profiles = $profileInfos->profiles;
	$allCount = $profileInfos->count;
	$maxPageNum = ($allCount % $recordCountFilter == 0 ? intval($allCount / $recordCountFilter) : intval($allCount / $recordCountFilter) + 1);
	$maxPageNum = ($maxPageNum < 1 ? 1 : $maxPageNum);
	$pageNum = $pageNumFilter;
	$firstNumber = ($pageNum - 1) * $recordCountFilter + 1;
	$endNumber = ( $allCount > $pageNum * $recordCountFilter ? $pageNum * $recordCountFilter : $allCount);

	$curTime = microtime();
	$processTime = round(($curTime - $preTime) * 1000) / 1000;
	
?>

<?php
include("assets/components/header.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">
<title>Node.sg</title>

<div class="topBar col-lg-12">
	<a href="main.php">
		<img src="assets/imgs/vision-logo.png">
		<span class="topTitle"><strong>Node.sg</strong></span>
	</a>
	<div class="topUserInfo">
		<a href="logout.php">Log Out &nbsp;&nbsp;<span><i class="fa fa-sign-out"></i></span></a>
	</div>
</div>
<div class="mainProfiles col-lg-12">
	<div class="row">
		<div class="mainTitle col-lg-12">
			<h2>Profiles</h2>
		</div>
		<div class="searchOptions col-lg-12">
			<form class="searchForm" method="POST">
				<div class="searchTable col-lg-12">
					<div class="intelSearchDiv">
						<button style="float: right; position: absolute; right: 7px;">Search</button>
						<h4>Search</h4>
						<input type="text" name="intelSearchFilter" style="width: 100%;" placeholder="Keyword Search" value="<?=$intelSearchFilter?>">
					</div>
					<h4>Filter</h4>
					<span>
						Company Name:
						<input type="text" name="companyNameFilter" value="<?=$companyNameFilter?>">
					</span>
					<span>
						Jobs Function:
						<input type="text" name="jobsFunctionFilter" value="<?=$jobsFunctinoFilter?>">
					</span>
					<span>
						Industry:
						<input type="text" name="industryFilter" value="<?=$industryFilter?>">
					</span>
					<span>
						Geography:
						<input type="text" name="geographyFilter" value="<?=$geographyFilter?>">
					</span>
					<span class="recordCountFilterDiv">
						Views per page:
						<select name="recordCountFilter">
							<option <?php if($recordCountFilter == "10") echo "selected";?>>10</option>
							<option <?php if($recordCountFilter == "20") echo "selected";?>>20</option>
							<option <?php if($recordCountFilter == "50") echo "selected";?>>50</option>
							<option <?php if($recordCountFilter == "100") echo "selected";?>>100</option>
						</select>
					</span>
					<span>
						Page Number:
						<input type="number" name="pageNumFilter" min="1" max="<?=$maxPageNum?>" value="<?=$pageNum?>" style="width: 50px;">
					</span>
				</div>
			</form>
		</div>
		<div class="profileList col-lg-12">
			<div>
				<b> Result : </b><span><?=$processTime?> secs </span> &nbsp&nbsp&nbsp
				<b> Number of matches : </b><span><?=$allCount?></span> &nbsp&nbsp&nbsp
				<b>Page : </b>
			</div>
			<br>
			<table>
			<?php
			$rowIndex = 0;
			foreach ($profiles as $profile) {
				$rowIndex++;
				$profileId = $profile["Id"];
				$prefix = $profile["Prefix"];
				$firstName = $profile["FirstName"];
				$lastName = $profile["LastName"];
				$country = $profile["Country"];
				$email = $profile["Email"];
				$phoneNumber = $profile["PhoneNumber"];
				$industry = $profile["Industry"];
				$jobfunction = $profile["JobFunction"];
				$profileUrl = $profile["ProfileUrl"];
				$imageUrl = $profile["ImageUrl"];
				$profileTitle = $profile["ProfileTitle"];
				$biography = $profile["Biography"];
				$employHistory = $profile["employHistory"];
				$educationHistory = $profile["educationHistory"];
			?>
			<tr>
				<td><?=$rowIndex?><span class="profileUrl HideItem"><?=$profileUrl?></span></td>
				<td style="width: 40%;">
					<div>
						<span><img src="<?=$imageUrl?>" style="width: 50px;border-radius: 100%;"></span>
					<span>
						<table>
							<tr>
								<td style="width: 25%;"><b>First Name:</b></td>
								<td style="width: 25%;" onclick="onEditableCellClicked(this)"><span class="firstName"><?=$firstName?></span><input type="text" class="edit HideItem"></td>
								<td style="width: 25%;"><b>Last Name:</b></td>
								<td style="width: 25%;" onclick="onEditableCellClicked(this)"><span class="lastName"><?=$lastName?></span><input type="text" class="edit HideItem"></td>
							</tr>
							<tr>
								<td style="width: 25%;"><b>Email Address:</b></td>
								<td style="width: 25%;" onclick="onEditableCellClicked(this)"><span class="email"><?=$email?></span><input type="text" class="edit HideItem"></td>
								<td style="width: 25%;"><b>Phone Number:</b></td>
								<td style="width: 25%;" onclick="onEditableCellClicked(this)"><span class="phoneNumber"><?=$phoneNumber?></span><input type="text" class="edit HideItem"></td>
							</tr>
						</table>
					</span>
					</div>
					<h5>Biography</h5>
					<div onclick="BiographyClicked(this)"><span class="biography"><?=$biography?></span><textarea class="HideItem"></textarea></div>
				</td>
				<td>
					<h5>Job Experience</h5>
					<?php
					$number = 0;
					$strNumber = "";
					foreach ($employHistory as $emps) {
						$number++;
						$strNumber = $number;
						if( $number <= 9) $strNumber = "&nbsp&nbsp" . $number;
					?>
					<p><b><span><?=$strNumber?>)</span> <span><?=$emps["CompanyName"]?></span></b> <?=$emps["RoleTitle"]?>(<?=$emps["FromDate"]?> - <?=$emps["ToDate"]?>)</p>
					<?php
					}
					?>
				</td>
				<td>
					<p><b>Jobs Function : </b><?=$jobfunction?></p>
					<p><b>Industry : </b><?=$industry?></p>
					<p><b>Geography : </b><?=$country?></p>
					<button class="btn btn-disable" onclick="btnSaveClicked(this)">Save</button>
					<button class="btn btn-danger" onclick="btnRemoveClicked(this)">Remove</button>
				</td>
			</tr>

			<?php
				}
			?>
			</table>
		</div>
	</div>
</div>


<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript">
	function onEditableCellClicked(_this){
		if( !$(_this).find(".edit").hasClass("HideItem"))return;
		var curRow = $(_this).parent();
		var curCell = $(_this);
		var strContents = curCell.text();
		$(_this).find(".edit").removeClass("HideItem").val(strContents);
		curCell.find("span").addClass("HideItem");
		$(_this).find(".edit").focus();
		$(_this).find(".edit").keyup(function(e){
			if( e.keyCode == 13){
				var btnSave = $(this).parent().parent().parent().parent().parent().parent().parent().parent().find("td button").eq(0);
				btnSave.removeClass("btn-disable").addClass("btn-primary");
				$(this).parent().find("span").text($(this).val()).removeClass("HideItem");
				$(this).addClass("HideItem");
			} else if( e.keyCode == 27){
				$(this).parent().find("span").removeClass("HideItem");
				$(this).addClass("HideItem");
			}
		});
		$(_this).find(".edit").focusout(function(e){
			$(this).parent().find("span").removeClass("HideItem");
			$(this).addClass("HideItem");
		});
	}
	function BiographyClicked(_this){
		if( $(_this).find("span").eq(0).hasClass("HideItem")) return;
		var bio = $(_this).find("span").eq(0).text();
		$(_this).find("textarea").val(bio);
		$(_this).find("textarea").width($(_this).find("span").eq(0).width());
		$(_this).find("textarea").height($(_this).find("span").eq(0).height());
		$(_this).find("span").eq(0).addClass("HideItem");
		$(_this).find("textarea").eq(0).removeClass("HideItem");
		$(_this).find("textarea").eq(0).focus();
		$(_this).find("textarea").eq(0).focusout(function(e){
			$(this).parent().find("span").removeClass("HideItem");
			$(this).addClass("HideItem");
		});
		$(_this).find("textarea").eq(0).keyup(function(e){
			if( e.keyCode == 13){
				if( e.shiftKey || e.ctrlKey){
					var btnSave = $(this).parent().parent().parent().parent().parent().parent().parent().parent().find("td button").eq(0);
					btnSave.removeClass("btn-disable").addClass("btn-primary");
					$(this).parent().find("span").text($(this).val());
					$(this).parent().find("span").removeClass("HideItem");
					$(this).addClass("HideItem");
				}
			} else if(e.keyCode == 27){
				$(this).parent().find("span").removeClass("HideItem");
				$(this).addClass("HideItem");
			}
		});
	}
	function btnRemoveClicked(_this){
		var txt;
		var r = confirm("Are you sure remove current profile?");
		if (r != true) return;
		var profileUrl = $(_this).parent().parent().find(".profileUrl").eq(0).html();
		$.post("api_getProfiles.php", {case: 'remove', profileUrl: profileUrl}, function (data){
			document.location.reload();
		});
	}
	function btnSaveClicked(_this){
		if( $(_this).hasClass("btn-disable"))return;
		var curRow = $(_this).parent().parent();
		var profileUrl = curRow.find(".profileUrl").eq(0).html();
		var firstName = curRow.find(".firstName").eq(0).html();
		var lastName = curRow.find(".lastName").eq(0).html();
		var email = curRow.find(".email").eq(0).html();
		var phoneNumber = curRow.find(".phoneNumber").eq(0).html();
		var biography = curRow.find(".biography").eq(0).html();
		var profile = {firstName: firstName, lastName: lastName, email: email, phoneNumber: phoneNumber, biography: biography};
		$.post("api_getProfiles.php", {case: 'modify',profileUrl: profileUrl, profile: profile}, function(data){
			$(_this).removeClass("btn-primary").addClass("btn-disable");
		});
	}
	function setAutoHeight(){
		var arrTexts = $("textarea");
		for( var i = 0; i < arrTexts.length; i ++){
			var curText = arrTexts.eq(i);
			curText.css("height", curText.css("scrollHeight") + "px");
		}
	}
	setAutoHeight();
	function imgClicked(_this){
		var curRow = $(_this).parent().parent();
		var rowId = curRow.find('td').eq(0).text();
		$("#detail" + rowId).toggleClass("HideItem");
	}
	function checkAll(){
		var checked = $(".searchTable input[type=checkbox]").eq(0).prop("checked");
		$(".profileList table input[type=checkbox]").prop("checked", checked);
	}
	function btnDeleteClicked(){
		var lstCheckboxes = $(".profileList table input[type=checkbox]");
		var lstCheckedIds = [];
		for( var i = 0; i < lstCheckboxes.length; i++){
			var curCheck = lstCheckboxes.eq(i);
			if( curCheck.prop("checked")){
				var row = curCheck.parent().parent();
				var id = row.find(".profileId").text();
				lstCheckedIds.push(id);
			}
		}
		console.log("btnDeleteClicked");
	}
</script>