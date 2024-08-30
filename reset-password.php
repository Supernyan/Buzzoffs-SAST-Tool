<?php 
session_start();
include('include/dbcon.php');

$user_id=$_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BuzzOffs | Reset Password</title>
  <link rel="icon" href="dist/img/logo.png" type="image/x-icon"/>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body class="hold-transition login-page" style="background-color: #010a4b !important;">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index.php" class="h1"><b>Buzz</b>Offs</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg"><b>Reset your password</b></p>
      <form method="post">
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="confirm_pass" class="form-control" placeholder="Please re-enter same password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8"></div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="submit" class="btn btn-primary btn-block" style="background-color: #010a4b;">Reset</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<?php include("include/scripts.php") ?>
</body>
</html>

<?php
if(isset($_POST['submit']))
{
    $password=$_POST['password'];
    $confirm_pass=$_POST['confirm_pass'];
    if($password==$confirm_pass){
    	$password=md5($password);
	    $query=mysqli_query($con,"UPDATE users SET password='$password' WHERE id=$user_id");
	    if($query){
	       unset($_SESSION['user_id']);
	       echo '
            <script type="text/javascript">
            $(document).ready(function(){
              swal({
                title: "Password Reset Successful",
                icon: "success",
                timer: 1000,
                buttons: false,
              }).then(function() {
                window.location.href="login.php";
              });
            });
            </script>
            ';
	    }
	    else{
	      echo '
            <script type="text/javascript">
            $(document).ready(function(){
              swal({
                title: "There is error please try again",
                icon: "error",
                timer: 1000,
                buttons: false,
              });
            });
            </script>
            ';
	    }
   }
   else{
    echo '
            <script type="text/javascript">
            $(document).ready(function(){
              swal({
                title: "Password does not match",
                icon: "error",
                timer: 1000,
                buttons: false,
              });
            });
            </script>
            ';
   }
}
?>