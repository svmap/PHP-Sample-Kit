<?php
   	include("function/Database.php");
   	$DB = new Database();
   	if($DB->is_logged_in())
   	{
       	header('Location:home.php');
   	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Site Name</title>
	<?php include("function/Header.php"); ?>
	<link rel="stylesheet" type="text/css" href="assets/css/login.css">
</head>
<body>
	<?php include("function/Navbar.php"); ?>
	<div class="container">
		<!-- CONTENT START --->
		<div class="d-flex justify-content-center h-100">
			<div class="card">
				<div class="card-header">
					<h3>Sign In</h3>
					<div class="d-flex justify-content-end social_icon">
						<span><i class="fab fa-facebook-square"></i></span>
						<span><i class="fab fa-google-plus-square"></i></span>
						<span><i class="fab fa-twitter-square"></i></span>
					</div>
				</div>
				<div class="card-body">
					<form class="login_form">
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" class="form-control input_username" placeholder="username">
						</div>
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" class="form-control input_password" placeholder="password">
						</div>
						<div class="row align-items-center remember">
							<input type="checkbox">Remember Me
						</div>
						<div class="form-group">
							<a class="btn float-right login_btn submit_login_form">Login</a>
						</div>
					</form>
				</div>
				<div class="card-footer">
					<div class="d-flex justify-content-center links">
						Don't have an account?<a href="#">Sign Up</a>
					</div>
					<div class="d-flex justify-content-center">
						<a href="#">Forgot your password?</a>
					</div>
				</div>
			</div>
		</div>
		<!-- CONTENT END --->
	</div>
	<?php include("function/Footer.php"); ?>
	<script type="text/javascript">
		$(document).ready(function()
		{
			$(".submit_login_form").click(function()
			{

				if($(".input_username").val()!="" || $(".input_password").val()!="")
				{
					var data_array = {'username':$(".input_username").val(),'password':$(".input_password").val()};
		        	var result = submit_form('initialize.php',data_array);
		        	try
			        {
			        	var json = JSON.parse(result.responseText);
			        	if(json['Status'] == "Success")
                  		{
                  			window.location.href="home.php";
                  		}
                  		else
                  		{
                  			alert(json['Message'] | 'Failed To Login');
                  		}
			        }
			        catch(Exp)
			        {
			        	console.log(Exp);
			        }
				}
				else
				{
					alert("Please Provide Credentials !");
				}
			});
		});
	</script>
</body>
</html>
