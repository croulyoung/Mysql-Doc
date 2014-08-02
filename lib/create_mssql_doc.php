<?php
	require("msdb.php");
	
	$dbserver = $_POST["dbserver"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$database = $_POST["database"];

	//connect database server
	$dbCon = @mssql_connect($dbserver, $username, $password) or die("Connect error! Check your username and password please.");
	//mssql_query("set names 'utf8'");
	
	//select a database
	@mssql_select_db($database, $dbCon) or die("Database name error!");
	
	$folderName = $database . "-" . date("Ymd") . "-" . date("His");
	$dir = realpath(dirname(__FILE__).'/../') . '\\mssql_doc\\' . $folderName;
	create_folder($dir);
	
	$db = new MSDB;
	
	$fp = create_html($dir . "\\index.html");
	$str_head = "<!DOCTYPE html>\n";
	$str_head .= "<html>\n";
	$str_head .= "<head>\n";
	$str_head .= "<meta charset='utf-8'>\n";
	$str_head .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
	$str_head .= "<link href='main.css' rel='stylesheet'>";
	$str_head .= "</head>\n";
	$str_head .= "<body>\n";
	$str_head .= "<div class='container'>\n";
	$str_head .= "<h3><a href='index.html'>Database Name: " . $database . "</a></h3>\n";
	
	$str_foot = "\n</div>\n</body>\n";
	$str_foot .= "</html>\n";
	
	fwrite($fp, $str_head);
	
	$str_info = "<table class='table table-bordered table-hover'><tr>\n" .
						"<th>Table Name</th>\n" .
						"<th>Engine</th>\n" .
						"<th>Version</th>\n" .
						"<th>Rows</th>\n" .
						"<th>Data Length</th>\n" .
						"<th>Auto Increment</th>\n" .
						"<th>Create Time</th>\n" .
						"<th>Update Time</th>\n" .
						"<th>Comment</th></tr>\n";
	$tables_info = $str_info;
	$table_array = $db->list_tables($database);
	
	$rows = array();
	$tables_info_list = "<div class='col-xs-6 col-sm-3 sidebar-offcanvas'><div class='list-group'>\n";
	while ($r = mssql_fetch_array($table_array))
	{
		$rows[] = $r;
		$tables_info_list .= "  <a href='" . $r["Name"] . ".html' class='list-group-item' title='" . $r["Name"] . "'>" . $r["Name"] . "</a>\n";
	}
	$tables_info_list .= "</div></div>\n";
	
	foreach ($rows as $row)
	{
		$table_name = $row["Name"];
		$tables_row = "<tr><td><a href='" . $table_name . ".html'>" . $table_name . "</a></td>\n";
		$tables_row .= "<td>" . $row["Engine"] . "</td>\n";
		$tables_row .= "<td>" . $row["Version"] . "</td>\n";
		$tables_row .= "<td>" . $row["Rows"] . "</td>\n";
		$tables_row .= "<td>" . $row["Data_length"] . "</td>\n";
		$tables_row .= "<td>" . $row["Auto_increment"] . "</td>\n";
		$tables_row .= "<td>" . $row["Create_time"] . "</td>\n";
		$tables_row .= "<td>" . $row["Update_time"] . "</td>\n";
		$tables_row .= "<td>" . $row["Comment"] . "</td></tr>\n";
		$tables_info .= $tables_row;
		
		$table_dir = $dir . "\\" . $table_name . ".html";
		$fp_table = create_html($table_dir);
		$table_info = $str_info . $tables_row . "</table><br/>\n";
		fwrite($fp_table, $str_head);
		fwrite($fp_table, $tables_info_list);
		fwrite($fp_table, "<div class='col-xs-12 col-sm-9'>");
		fwrite($fp_table, $table_info);
		$column_info = get_column_info($db, $table_name);
		fwrite($fp_table, $column_info);
		fwrite($fp_table, "<a href='index.html'>Back to Index</a>\n</div>");
		fwrite($fp_table, $str_foot);
		echo $table_dir . "\n<br/>";
	}
	$tables_info .= "</table><br/>";
	fwrite($fp, $tables_info);
	
	fwrite($fp, $str_foot);
	fclose($fp);
	echo $dir . "\\index.html\n<br/>";
	
	copy(realpath(dirname(__FILE__).'/../') . '\\css\\main.css', $dir . '\\main.css');
	echo $dir . "\\main.css<br/>Complete!<br/><br/><br/>";
	
	function create_folder($dir="")
	{
		if (!file_exists($dir))
		{
			mkdir($dir, 0777);
		}
	}
	
	function create_html($table_name="")
	{
		$fp = fopen($table_name , "a+");
		return $fp;
	}
	
	function get_column_info($db, $table_name="")
	{
		$column_info = "<table class='table table-bordered table-hover'><tr><th>Field Name</th>\n" .
						"<th>Primary Key</th>\n" .
						"<th>Field Type</th>\n" .
						"<th>Default Value</th>\n" .
						"<th>Not NULL</th>\n" .
						"<th>Comment</th>\n" .
						"<th>Extra</th></tr>\n";
		
		$column_array = $db->list_columns($table_name);
		while ($property = mysql_fetch_array($column_array))
		{
			$column_info .= "<tr>";
			$column_info .= "<td>" . $property["Field"] . "</td>\n";
			$column_info .= "<td>" . $property["Key"] . "</td>\n";
			$column_info .= "<td>" . $property["Type"] . "</td>\n";
			$column_info .= "<td>" . $property["Default"] . "</td>\n";
			$column_info .= "<td>" . $property["Null"] . "</td>\n";
			$column_info .= "<td>" . $property["Comment"] . "</td>\n";
			$column_info .= "<td>" . $property["Extra"] . "</td>\n";
			$column_info .= "</tr>";
		}
		$column_info .= "</table><br/>\n";
		return $column_info;
	}
?>