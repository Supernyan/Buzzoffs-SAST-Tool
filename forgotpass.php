<?php 
session_start();
include('include/dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BuzzOffs | Forgot Password</title>
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
      <p class="login-box-msg"><b>Verify you account first</b></p>
      <form method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8"></div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="submit" class="btn btn-primary btn-block" style="background-color:#010a4b;">Verify</button>
          </div>
          <!-- /.col -->
        </div>
        
        <div class="login-box-msg">
          <p style="margin-top:10px; margin-bottom: 0;"><b>Contact us for more assistance.</b></p>
          09-5665211/contact@buzzoffs.com
        </div>
      </form>
      <p class="mb-1">
        <a href="login.php">Back</a>
      </p>
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
    $email=$_POST['email'];
    $username=$_POST['username'];
    $query=mysqli_query($con,"SELECT id FROM users WHERE email='$email' && username='$username' ");
    $ret=mysqli_fetch_array($query);
    if($ret>0){
      $userId = $ret['id'];
      $_SESSION['user_id'] = $userId;
       echo '
            <script type="text/javascript">
            $(document).ready(function(){
              swal({
                title: "Verified Successful",
                icon: "success",
                timer: 1000,
                buttons: false,
              }).then(function() {
                window.location.href="reset-password.php";
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
                title: "Your details are incorrect, please try again",
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