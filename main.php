<?php
	session_start();
	if( !isset( $_SESSION['userEmail']))
		header("Location: login.php");
	$userName = $_SESSION['userEmail'];
	if( $userName == "")
		header("Location: login.php");
	require_once 'library/UserManager.php';
	$profiles = getProfiles( $userName);

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
		<span class="topTitle"><strong>Node.sg</strong> Linkedin Profiles</span>
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
		<div class="profileList col-lg-12">
			<table>
				<tr>
					<th class="check"></th>
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
			foreach ($profiles as $profile) {
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
			?>
			<tr>
				<td><input type="checkbox"></td>
				<td><img style="width: 50px; border-radius: 100%" src="<?=$imageUrl?>"></td>
				<td><?=$prefix?></td>
				<td><?=$firstName?></td>
				<td><?=$lastName?></td>
				<td><?=$country?></td>
				<td><?=$email?></td>
				<td><?=$phoneNumber?></td>
				<td><?=$industry?></td>
				<td><?=$profileUrl?></td>
				<td><?=$profileTitle?></td>
			</tr>
			<?php
				$employHistory = getEmployHistory($profileId);
			}
			?>
			</table>
		</div>
	</div>
</div>
