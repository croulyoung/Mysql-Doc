<?php
class MSDB {

	/**
     * Show all databases.
     *
     * @access public
     * @todo return a array
     */
	function list_databases()
	{
		//$sql = "SELECT * FROM sys.databases where name not in ('master', 'tempdb', 'model', 'msdb')";
		$sql = "SELECT * FROM sys.databases";
		$rs = mssql_query($sql);
		return $rs;
	}
	
	/**
     * Show all tables of database.
     *
	 * Name | Engine | Version | Row_format | Rows | Avg_row_length | Data_length | Max_data_length | Index_length | 
	 * Data_free | Auto_increment | Create_time | Update_time | Check_time | Collation | Checksum | Create_options | Comment
	 * 
     * @access public
     * @param database name
     * @todo return a array
     */
	function list_tables($database="")
	{
		$sql = "SHOW TABLE STATUS FROM $database";
		$rs = mssql_query($sql);
		return $rs;
	}
	
	/**
     * Show columns infomation of table.
	 * 
     * Field | Type | Collation | Null | Key | Default | Extra | Privileges | Comment
	 *
     * @access public
     * @param table name
     * @todo return a array
     */
	function list_columns($table="")
	{
		$sql = "SHOW FULL FIELDS FROM $table";
		$rs = mssql_query($sql);
		return $rs;
	}
}
?>