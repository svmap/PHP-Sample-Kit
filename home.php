<?php
   	include("function/Database.php");
   	$DB = new Database();
   	if(!$DB->is_logged_in())
   	{
       	header('Location:index.php');
   	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Site Name - Home</title>
	<?php include("function/Header.php"); ?>
</head>
<body>
	<?php include("function/Navbar.php"); ?>
	<div class="container">
		<!-- CONTENT START --->
		<h2 class="text-center" style="margin-top:5%;">Welcome <?php echo $_SESSION['name']; ?></h2> 
		<!-- CONTENT END --->
	</div>
	<?php include("function/Footer.php"); ?>
	<script type="text/javascript">
		$(document).ready(function()
		{
			
		});
	</script>
</body>
</html>
