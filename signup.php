<?php
$email_name = "";
$password = "";
$_firstName = "";
$_lastName = "";

require_once 'library/userManager.php';
if( isset($_POST['email_name'])){
	$email_name = $_POST['email_name'];
	$password = $_POST['password'];
	$_firstName = $_POST['first_name'];
	$_lastName = $_POST['first_name'];
	$ret = registerUser($_firstName, $_lastName, $email_name, $password);
	if( $ret != false){
		header("Location: index.php");
	}
}
?>

<html lang="en">
<head>
<?php
include("assets/components/header.php");
?>
<script src="assets/js/jquery.min.js"></script>
<!-- <script src="assets/js/bootstrap.min.js"></script> -->

<body>
<main class="auth-main">
  <div class="auth-block">
    <h3>Sign up to Nodes<!-- <img src="assets/imgs/vision-logo.png" width="50px;"> --> </h3>
    <form class="form-horizontal" method="POST" onsubmit="return validate()">
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label" required>FirstName</label>

        <div class="col-sm-12">
          <input type="text" class="form-control" placeholder="First Name" name="first_name">
        </div>
      </div>


      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label" required>LastName</label>

        <div class="col-sm-12">
          <input type="text" class="form-control" placeholder="Last Name" name="last_name">
        </div>
      </div>


      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label" required>Email</label>

        <div class="col-sm-12">
          <input type="text" class="form-control" placeholder="Email" name="email_name">
        </div>
      </div>

      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label" required>Password</label>

        <div class="col-sm-12">
          <input type="password" class="form-control" placeholder="Password" name="password">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button class="btn btn-default btn-auth">Sign up</button>
          <a style="float: right;" href="login.php">Signin</a>
        </div>
      </div>
    </form>
    </div>
  </div>
</main>
</body>
</html>
<script type="text/javascript">
	function validate(){
		if( $("input[name=first_name]").val() == "" || $("input[name=last_name]").val() == "" || $("input[name=email_name]").val() == "" || $("input[name=password]").val() == "")
			return false;
		return true;
	}
</script>