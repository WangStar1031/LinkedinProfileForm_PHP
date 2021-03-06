<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php?from=main.php");
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

	$preTime = (int)microtime();
	$profileInfos = getProfileInfos( $userEmail, $_filter);
	$profiles = $profileInfos->profiles;
	$allCount = $profileInfos->count;
	$maxPageNum = ($allCount % $recordCountFilter == 0 ? intval($allCount / $recordCountFilter) : intval($allCount / $recordCountFilter) + 1);
	$maxPageNum = ($maxPageNum < 1 ? 1 : $maxPageNum);
	$pageNum = $pageNumFilter;
	$firstNumber = ($pageNum - 1) * $recordCountFilter + 1;
	$endNumber = ( $allCount > $pageNum * $recordCountFilter ? $pageNum * $recordCountFilter : $allCount);

	$curTime = (int)microtime();
	$processTime = round(($curTime - $preTime) * 1000) / 1000;
	
?>

<?php
include("assets/components/header.php");
?>
<link rel="stylesheet" type="text/css" href="assets/css/dashboard.css?<?= time();?>">
<link rel="stylesheet" type="text/css" href="assets/css/topbar.css?<?= time();?>">

<div class="topBar col-lg-12">
	<a href="main.php">
		<img src="assets/imgs/vision-logo-1.png">
		<span class="topTitle"><strong>Nodes</strong></span>
	</a>
	<div class="topUserInfo">
		<a href="projects.php">Projects &nbsp;&nbsp;<span><i class="fa fa-angle-double-right"></i></span></a>&nbsp;&nbsp;
		<a href="logout.php">Log Out &nbsp;&nbsp;<span><i class="fa fa-sign-out"></i></span></a>
	</div>
</div>
<div class="mainProfiles col-lg-12">
	<div class="row">
		<div class="mainTitle col-lg-12">
			<h2>Profiles<a href="createProfile.php" style="float: right;"><button class="btn btn-primary"> + New Profile</button></a></h2>

		</div>
		<div class="searchOptions col-lg-12">
			<form class="searchForm" method="POST">
				<div class="searchTable col-lg-12">
					<div class="intelSearchDiv">
						<button class="btn-success" style="float: right; position: absolute; right: 7px;">Search</button>
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
					<span class="HideItem">
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
				<b>Page : </b> <span class="pagesDiv"></span>
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
			<tr profileId="<?=$profileId?>">
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
					<div onclick="BiographyClicked(this)" style="min-height: 50px;"><span class="biography"><?=$biography?></span><textarea class="HideItem"></textarea></div>
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
					<!-- <a href="editProfile.php?profile=<?=$profileId?>"><button class="btn btn-success">Edit</button></a> -->
					<a href="profile.php?profile=<?=$profileId?>"><button class="btn btn-success">View</button></a>
					<button class="btn btn-danger" onclick="removeProfile('<?=$profileId?>')">Remove</button>
				</td>
			</tr>

			<?php
				}
			?>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	function pageClicked(_this){
		var pageNum = $(_this).text();
		$("input[name=pageNumFilter]").val(pageNum);
		$(".searchForm").submit();
	}
	function pageFirst(){
		$("input[name=pageNumFilter]").val(1);
		$(".searchForm").submit();
	}
	function pagePrev(){
		var curPageNum = $("input[name=pageNumFilter]").val() * 1;
		if( curPageNum == 1){
			pageFirst();
			return;
		}
		$("input[name=pageNumFilter]").val(curPageNum-1);
		$(".searchForm").submit();
	}
	function pageLast(){
		var maxPageNum = $("input[name=pageNumFilter]").attr("max");
		$("input[name=pageNumFilter]").val(maxPageNum);
		$(".searchForm").submit();
	}
	function pageNext(){
		console.log($("input[name=pageNumFilter]").attr("max"));
		var curPageNum = $("input[name=pageNumFilter]").val() * 1;
		if( curPageNum == $("input[name=pageNumFilter]").attr("max")){
			pageLast();
			return;
		}
		$("input[name=pageNumFilter]").val($("input[name=pageNumFilter]").val()*1+1);
		$(".searchForm").submit();
	}
	$(document).ready(function(){
		var maxPageNum = $("input[name=pageNumFilter]").attr("max") * 1;
		var curPageNum = $("input[name=pageNumFilter]").val() * 1;
		if( maxPageNum <= 3){
			for( var i = 0; i < maxPageNum; i++){
				var strHtml = "<button class='btn' onclick='pageClicked(this)'>" + ( i * 1 + 1) + "</button>";
				if( curPageNum - 1 == i){
					strHtml = "<button class='btn btn-primary' onclick='pageClicked(this)'>" + ( i * 1 + 1) + "</button>";
				}
				$(".pagesDiv").append( strHtml);
				$(".pagesDiv").append(" ");
			}
		} else {
			$(".pagesDiv").append("<button class='btn' onclick='pageFirst()'><<</button>");
			$(".pagesDiv").append(" ");
			$(".pagesDiv").append("<button class='btn' onclick='pagePrev()'><</button>");
			$(".pagesDiv").append(" ");
			if( curPageNum == 1){
				for( var i = 0; i < 3; i++){
					strHtml = "<button class='btn' onclick='pageClicked(this)'>"+(i*1+1)+"</button>";
					if( curPageNum - 1 == i){
						strHtml = "<button class='btn btn-primary' onclick='pageClicked(this)'>"+(i*1+1)+"</button>";
					}
					$(".pagesDiv").append(strHtml);
					$(".pagesDiv").append(" ");
				}
			} else if( curPageNum == maxPageNum){
				for( var i = curPageNum - 3; i < curPageNum; i++){
					strHtml = "<button class='btn' onclick='pageClicked(this)'>"+(i*1+1)+"</button>";
					if( curPageNum - 1 == i){
						strHtml = "<button class='btn btn-primary' onclick='pageClicked(this)'>"+(i*1+1)+"</button>";
					}
					$(".pagesDiv").append(strHtml);
					$(".pagesDiv").append(" ");
				}
			} else{
				for( var i = curPageNum - 1; i < curPageNum + 2; i++){
					strHtml = "<button class='btn' onclick='pageClicked(this)'>"+i+"</button>";
					if( curPageNum == i){
						strHtml = "<button class='btn btn-primary' onclick='pageClicked(this)'>"+i+"</button>";
					}
					$(".pagesDiv").append(strHtml);
					$(".pagesDiv").append(" ");
				}

			}
			$(".pagesDiv").append("<button class='btn' onclick='pageNext()'>></button>");
			$(".pagesDiv").append(" ");
			$(".pagesDiv").append("<button class='btn' onclick='pageLast()'>>></button>");
		}
	});
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
		$(_this).find("textarea").width($(_this).parent().width());
		// $(_this).find("textarea").width($(_this).find("span").eq(0).width());
		$(_this).find("textarea").height($(_this).find("span").eq(0).height() + 5);
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
	function removeProfile(_id){
		var txt;
		var r = confirm("Are you sure remove current profile?");
		if (r != true) return;
		$.post("api_getProfiles.php", {case: 'removeProfile', profileId: _id}, function (data){
			document.location.reload();
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