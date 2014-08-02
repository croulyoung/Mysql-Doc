<?php
	require("db.php");

	$dbserver = $_POST["dbserver"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$database = $_POST["database"];

	//connect database server
	$dbCon = @mysql_connect($dbserver, $username, $password) or die("connect error! check your username and password.");
	mysql_query("set names 'utf8'");
	
	//select a database
	@mysql_select_db($database, $dbCon) or die("database name error!");
	
	$db = new DB;
	$table_array = $db->list_tables($database);
	while ($row = mysql_fetch_array($table_array))
	{
		$table_name = $row["Name"];
		echo "<p style='font-size:24px; font-weight:bold; color:red;'>" . $table_name . "</p>";
		
		$table_info = "<table border='1'><tr>" .
						"<th>Engine</th>" .
						"<th>Version</th>" .
						"<th>Rows</th>" .
						"<th>Data Length</th>" .
						"<th>Auto Increment</th>" .
						"<th>Create Time</th>" .
						"<th>Update Time</th>" .
						"<th>Comment</th></tr>";
		$table_info .= "<tr><td>" . $row["Engine"] . "</td>";
		$table_info .= "<td>" . $row["Version"] . "</td>";
		$table_info .= "<td>" . $row["Rows"] . "</td>";
		$table_info .= "<td>" . $row["Data_length"] . "</td>";
		$table_info .= "<td>" . $row["Auto_increment"] . "</td>";
		$table_info .= "<td>" . $row["Create_time"] . "</td>";
		$table_info .= "<td>" . $row["Update_time"] . "</td>";
		$table_info .= "<td>" . $row["Comment"] . "</td></tr></table><br/>";
		echo $table_info;
		
		$column_info = "<table border='1'><tr><th>Field Name</th>" .
						"<th>Primary Key</th>" .
						"<th>Field Type</th>" .
						"<th>Default Value</th>" .
						"<th>Not NULL</th>" .
						"<th>Comment</th>" .
						"<th>Extra</th></tr>";
		
		$column_array = $db->list_columns($table_name);
		while ($property = mysql_fetch_array($column_array))
		{
			$column_info .= "<tr>";
			$column_info .= "<td>" . $property["Field"] . "</td>";
			$column_info .= "<td>" . $property["Key"] . "</td>";
			$column_info .= "<td>" . $property["Type"] . "</td>";
			$column_info .= "<td>" . $property["Default"] . "</td>";
			$column_info .= "<td>" . $property["Null"] . "</td>";
			$column_info .= "<td>" . $property["Comment"] . "</td>";
			$column_info .= "<td>" . $property["Extra"] . "</td>";
			$column_info .= "</tr>";
		}
		$column_info .= "</table><br/><br/>";
		echo $column_info;
	}
?>