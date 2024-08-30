<?php
session_start();
error_reporting(0);
include('include/dbcon.php');

if (isset($_SESSION['id'])) {
  header('location:dashboard.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>BuzzOffs | Homepage </title>
	<link rel="icon" href="dist/img/logo.png" type="image/x-icon"/>
	<link rel="stylesheet" href="dist/css/home.css">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body style="background-color: #071228;">
<div id="container" class="container">
		<!-- FORM SECTION -->
		<div class="row">
			<!-- SIGN UP -->
			<div class="col align-items-center flex-col sign-up">
				<div class="form-wrapper align-items-center">
				  <form method="post" onsubmit="return validateForm()">
					<div class="form sign-up">
						<div class="input-group">
							<i class='bx bxs-user'></i>
							<input type="text" placeholder="Username" name="username" id="username" required>
						</div>
						<div class="input-group">
							<i class='bx bx-mail-send'></i>
							<input type="email" placeholder="Email" name="email" id="email" required>
						</div>
						<div class="input-group">
							<i class='bx bxs-lock-open-alt'></i>
							<input type="password" placeholder="Password" name="password" id="password" required>
						</div>
						<div class="input-group">
							<i class='bx bxs-lock-alt'></i>
							<input type="password" placeholder="Confirm password" id="confirm-pass" name="confirm-pass" required>
						</div>
						<input type="submit" name="signup_submit" class="button2" value="Sign Up">
						<p>
							<span>
								Already have an account?
							</span>
							<b onclick="toggle()" class="pointer">
								Sign in here
							</b>
						</p>
					</div>
				  </form>
				</div>
			</div>
			<!-- END SIGN UP -->
			<!-- SIGN IN -->
			<div class="col align-items-center flex-col sign-in">
				<div class="form-wrapper align-items-center">
				  <form method="post">
					<div class="form sign-in">
						<div class="input-group">
							<i class='bx bx-mail-send'></i>
							<input type="text" placeholder="Email" name="sign_email" required>
						</div>
						<div class="input-group">
							<i class='bx bxs-lock-alt'></i>
							<input type="password" placeholder="Password" name="sign_password" required>
						</div>
						<input type="submit" name="signin_submit" class="button" value="Sign In">
						<p>
							<b>
								<a href="forgotpass.php" style="text-decoration: none; color: black;">Forgot password?</a>
							</b>
						</p>
						<p>
							<span>
								Don't have an account?
							</span>
							<b onclick="toggle()" class="pointer">
								Sign up here
							</b>
						</p>
					</div>
				  </form>
				</div>
				<div class="form-wrapper">
		
				</div>
			</div>
			<!-- END SIGN IN -->
		</div>
		<!-- END FORM SECTION -->
		<!-- CONTENT SECTION -->
		<div class="row content-row">
			<!-- SIGN IN CONTENT -->
			<div class="col align-items-center flex-col">
				<div class="text sign-in">
					<h2>
						Welcome back
					</h2>
				</div>
			</div>
			<!-- END SIGN IN CONTENT -->
			<!-- SIGN UP CONTENT -->
			<div class="col align-items-center flex-col">
				<div class="img sign-up">
				
				</div>
				<div class="text sign-up">
					<h2>
						Join us now
					</h2>
	
				</div>
			</div>
			<!-- END SIGN UP CONTENT -->
		</div>
		<!-- END CONTENT SECTION -->
	</div>
</body>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
let container = document.getElementById('container')

toggle = () => {
	container.classList.toggle('sign-in')
	container.classList.toggle('sign-up')
}

setTimeout(() => {
	container.classList.add('sign-in')
}, 200)

function validateForm() {
  var username = document.getElementById('username').value;
  if (!/^[a-zA-Z0-9_]+$/.test(username)) {
      showError("Username can only contain letters, _, and no spaces.");
      return false;
  }

  // Email validation
  var email = document.getElementById('email').value;
  if (!/\S+@\S+\.\S+/.test(email)) {
      showError("Invalid Email Address.");
      return false;
  }

  // Password validation
  var password = document.getElementById('password').value;
  var confirmPassword = document.getElementById('confirm-pass').value;
  if (password.length < 8) {
      showError("Password must be at least 8 characters long.");
      event.preventDefault();
  }
  if (password !== confirmPassword) {
      showError("Passwords do not match.");
      return false;
  }
  return true;
}

function showError(message) {
  swal({
    icon: "error",
    title: "Error!",
    text: message,
    buttons: false,
    timer: 1500
  });
}
</script>
</html>

<?php  
if(isset($_POST['signup_submit']))
{
  $username=$_POST['username'];
  $email=$_POST['email'];
  $password=md5($_POST['password']);

  $query=mysqli_query($con,"INSERT INTO users(username, email, password) VALUES('$username','$email','$password')");

  if($query){
      echo '
            <script type="text/javascript">
            $(document).ready(function(){
              swal({
                title: "Your account is now registered",
                icon: "success",
                timer: 2000,
                buttons: false,
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
              icon: "error",
              title: "Something went wrong!",
              text: "Please try again",
              buttons: false,
              timer: 1500
           });
          });
          </script>
          ';
  }
 }

if(isset($_POST['signin_submit']))
{
    $email=$_POST['sign_email'];
    $password=md5($_POST['sign_password']);
    $query=mysqli_query($con,"SELECT id FROM users WHERE email='$email' && password='$password' ");
    $ret=mysqli_fetch_array($query);
    if($ret>0){
      $_SESSION['id']=$ret['id'];
        echo '
            <script type="text/javascript">
            $(document).ready(function(){
               window.location.href="dashboard.php";
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
                timer: 1500,
                buttons: false,
              });
            });
            </script>
        ';
    }
}
 ?>
