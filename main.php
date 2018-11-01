<?php
	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userEmail = $_SESSION['userEmail'];
	if( $userEmail == "")
		header("Location: login.php");
	require_once 'library/UserManager.php';
	$genderFilter = '';
	if( isset($_GET['genderFilter'])) $genderFilter = $_GET['genderFilter'];
	if( isset($_POST['genderFilter'])) $genderFilter = $_POST['genderFilter'];
	// echo $genderFilter;
	$recordCountFilter = 10;
	if( isset($_GET['recordCountFilter'])) $recordCountFilter = $_GET['recordCountFilter'];
	if( isset($_POST['recordCountFilter'])) $recordCountFilter = $_POST['recordCountFilter'];
	$pageNumFilter = 1;
	if( isset($_GET['pageNumFilter'])) $pageNumFilter = $_GET['pageNumFilter'];
	if( isset($_POST['pageNumFilter'])) $pageNumFilter = $_POST['pageNumFilter'];
	$countryFilter = '';
	if( isset($_GET['countryFilter'])) $countryFilter = $_GET['countryFilter'];
	if( isset($_POST['countryFilter'])) $countryFilter = $_POST['countryFilter'];
	// echo($countryFilter);
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

	// getProfileInfos($_email, $_gender, $_recCount, $_pageNum, $_country, $_company);
	$_filter = new \stdClass;
	$_filter->gender = $genderFilter;
	$_filter->recCount = $recordCountFilter;
	$_filter->pageNum = $pageNumFilter;
	$_filter->country = $countryFilter;
	$_filter->company = $companyNameFilter;
	$_filter->jobsFunction = $jobsFunctinoFilter;
	$_filter->industry = $industryFilter;
	$_filter->geography = $geographyFilter;
	$preTime = microtime();
	$profileInfos = getProfileInfos( $userEmail, $_filter);
	// print_r($profileInfos);
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
<!-- 	<div class="topNavMenu">
		<div class="dropdown">
			<a href="javascript:;">Menu <span><i class="fa fa-bars"></i></span></a>
			<ul class="dropdown-content">
		<li><a href="account.php">Account</a></li><li><a href="dashboard.php">Dashboard</a></li><li><a href="invite.php">Invite</a></li><li><a href="userman.php">UserManagement</a></li><li><a href="dictionary.php">Dictionary</a></li>			</ul>
		</div>
	</div> -->
</div>
<div class="mainProfiles col-lg-12">
	<div class="row">
		<div class="mainTitle col-lg-12">
			<h2>Profiles</h2>
		</div>
		<div class="searchOptions col-lg-12">
			<form class="searchForm" method="POST">
				<div class="searchTable col-lg-12">
					<!-- <input type="checkbox" onclick="checkAll()">
					<div class="btn" onclick="btnDeleteClicked()"><i class="fa fa-trash-o"></i></div> -->
					<div class="intelSearchDiv">
						<h4>Search</h4>
						<input type="text" name="intelSearch" style="width: 100%;" placeholder="Keyword Search">
					</div>
					<h4>Filter</h4>
					<span>
						Company Name:
						<input type="text" name="companyNameFilter" value="<?=$companyNameFilter?>">
						<!-- From Date:
						<input type="date" name="fromCompanyDate" value="1900-01-01">
						To Date:
						<input type="date" name="toCompanyDate" value="<?php echo date('Y-m-d');?>"> -->
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
					<!-- <span class="genderFilterDiv">
						Gender: 
						<select name="genderFilter">
							<option <?php if($genderFilter == "All") echo"selected";?>>All</option>
							<option <?php if($genderFilter == "Male") echo"selected";?>>Male</option>
							<option <?php if($genderFilter == "Female") echo"selected";?>>Female</option>
						</select> -->
					</span>
					<span class="recordCountFilterDiv">
						Record count per page:
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
<!-- 					<span>
						Country:
						<input type="text" name="countryFilter" value="<?=$countryFilter?>">
					</span> -->
					<button>Search</button>
				</div>
			</form>
		</div>
		<div class="profileList col-lg-12">
			<div>
				<b> Result : </b><span><?=$processTime?>seconds </span>
				<b> Total : </b><span><?=$allCount?></span>
				<b> From : </b><span><?=$firstNumber?></span>
				<b> To : </b><span><?=$endNumber?></span>
			</div>
			<table>
				<tr>
					<th class="number"></th>
					<!-- <th class="check"></th> -->
					<th class="img"></th>
					<th class="prefix"></th>
					<th>FirstName</th>
					<th>LastName</th>
					<th>Country</th>
					<th>Email</th>
					<th>PhoneNumber</th>
					<th>Industry</th>
					<th>ProfileUrl</th>
					<th>ProfileTitle</th>
					<!-- <th>FirstName</th> -->
				</tr>
			<?php
			// echo "<div>";
			// print_r($profiles);
			// echo "</div>";
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
				$profileUrl = $profile["ProfileUrl"];
				$imageUrl = $profile["ImageUrl"];
				$profileTitle = $profile["ProfileTitle"];
				$biography = $profile["Biography"];
				$employHistory = $profile["employHistory"];
				$educationHistory = $profile["educationHistory"];
			?>
			<tr
			<?php 
			if($rowIndex % 2 == 0){
				echo "class='grayRow'";
			} else{
				echo "class='whiteRow'";
			}
			?>>
				<td><?=$rowIndex?></td>
				<td style="display: none;" class="profileId" rowIndex="<?=$rowIndex?>"><?=$profileId?></td>
				<!-- <td><input type="checkbox"></td> -->
				<td><img class="profileImg" src="<?=$imageUrl?>" onclick="imgClicked(this)"></td>
				<td><?=$prefix?></td>
				<td><input type="text" title="<?=$firstName?>" value="<?=$firstName?>"></td>
				<td><input type="text" title="<?=$lastName?>" value="<?=$lastName?>"></td>
				<td><input type="text" title="<?=$country?>" value="<?=$country?>"></td>
				<td><a href="mailto:<?=$email.'?Subject=Hi, '.$firstName?>"><input type="text" title="<?=$email?>" value="<?=$email?>"></a></td>
				<td><input type="text" title="<?=$phoneNumber?>" value="<?=$phoneNumber?>"></td>
				<td><input type="text" title="<?=$industry?>" value="<?=$industry?>"></td>
				<td><a href="<?=$profileUrl?>" target="_blank"><input type="text" title="<?=$profileUrl?>" value="<?=$profileUrl?>"></a></td>
				<td><input type="text" title="<?=$profileTitle?>" value="<?=$profileTitle?>"></td>
			</tr>
			<tr class="profileDetails HideItem <?php echo($rowIndex %2 == 0 ? 'grayRow' : 'whiteRow')?>" id="detail<?=$rowIndex?>">
				<td colspan="11">
					<div class="row">
						<div class="col-lg-6">
							<h5>Biography</h5>
							<?=$biography?>
						</div>
						<div class="col-lg-3">
							<h5>Employment History</h5>
							<?php
							foreach ($employHistory as $value) {
								$comName = $value['CompanyName'];
								$roleTitle = $value['RoleTitle'];
								$fromDate = $value['FromDate'];
								$toDate = $value['ToDate'];
							?>
							<div><strong><?=$roleTitle?></strong> at <strong><?=$comName?></strong></div>
							<?=$fromDate?> - <?=$toDate?>
							<?php
							}
							?>
						</div>
						<div class="col-lg-3">
							<h5>Education History</h5>
							<?php
							foreach ($educationHistory as $value) {
								$schoolName = $value['SchoolName'];
								$degreeName = $value['DegreeName'];
								$areaName = $value['AreaName'];
								$startYear = $value['StartYear'];
								$endYear = $value['EndYear'];
							?>
							<div><strong><?=$schoolName?></strong></div>
							<div><?=$degreeName?> <?=$areaName?></div>
							<div><?=$startYear?> - <?=$endYear?></div>
							<?php
							}
							?>
						</div>
					</div>
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