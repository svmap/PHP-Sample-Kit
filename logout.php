<?php
   	include("function/Database.php");
   	$DB = new Database();
   	$DB->logout();
    header('Location:index.php');
?>
