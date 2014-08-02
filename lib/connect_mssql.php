<?php
	require("msdb.php");
	
	$dbserver = $_POST["dbserver"];
	$username = $_POST["username"];
	$password = $_POST["password"];

	//connect database server
	$dbCon = mssql_connect($dbserver, $username, $password);
	if (!$dbCon)
	{
		echo "";
	}
	else
	{
		$db = new MSDB;
		$db_array = $db->list_databases();
		$db_list = "";
		while ($row = mssql_fetch_array($db_array))
		{
			$db_list .= "<option value='" . $row["name"] . "'>" . $row["name"] . "</option>";
		}
		// echo $db_list;
		echo "Connect error! Check your username and password please.";
	}
?>