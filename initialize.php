<?php
	require("function/Database.php");
	$DB = new Database();
	if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']))
	{
		$result = $DB->login($_POST['username'],$_POST['password']);
		if($result['Status'])
		{
			echo json_encode(array("Status"=>"Success","Message"=>$result['Message']));
		}
		else
		{
			echo json_encode(array("Status"=>"Failed","Message"=>$result['Message']));
		}
	}