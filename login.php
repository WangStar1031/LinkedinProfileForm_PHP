<?php
$email_name = "";
$password = "";
$userVeri = true;
if( isset($_POST['email_name'])){
  $email_name = $_POST['email_name'];
  if( isset($_POST['password'])){
    $password = $_POST['password'];
  }
  require_once 'library/userManager.php';
  $userVeri = verifyUser($email_name, $password);
  if( $userVeri != false){
    session_start();
    $_SESSION['userEmail'] = $email_name;
    header("Location: index.php");
  }
}
?>

<html lang="en">
<?php
include("assets/components/header.php");
?>
<script src="assets/js/jquery.min.js"></script>
<!-- <script src="assets/js/bootstrap.min.js"></script> -->

<body>
<main class="auth-main">
  <div class="auth-block">
    <h3>Sign in  to Node.sg<!-- <img src="assets/imgs/vision-logo.png" width="50px;"> --> </h3>
    <form class="form-horizontal" method="POST">
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

        <div class="col-sm-12">
          <input type="text" class="form-control" id="inputEmail3" placeholder="Email" name="email_name">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

        <div class="col-sm-12">
          <input type="password" class="form-control" id="inputPassword3" placeholder="Password" name="password">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button class="btn btn-default btn-auth">Sign in</button>
          <a style="float: right;" href="signup.php">Signup</a>
        </div>
      </div>
    </form>
    </div>
  </div>
</main>
</body>
</html>
