<?php
class MySQL extends mysqli
{
	public function __construct($db_host, $db_name, $db_username, $db_password)
   	{
        // call parent constructor
		@parent::__construct($db_host, $db_username, $db_password, $db_name);
		// was there a connection error?
		if (mysqli_connect_errno())
		{
		 	// safely die with the error template
			throw new Exception(sprintf("Can't connect to database. Error: %s", mysqli_connect_error()));
		}
   	}

   	public function __destruct()
   	{
       	if (!mysqli_connect_errno())
       	{
           	$this->close();
    	}
   	}
	
	function insert_or_update($table, $data)
	{
		foreach($data as $key => $value)
		{
			$value = mysql_escape_string($value);
			$keys[] = $key;
			$values[] = $value;
			$update[] = "`".$key."` = '".$value."'";
		}
		return $this->query("INSERT INTO `".$table."` (`".implode("`, `", $keys)."`) VALUES ('".implode("', '", $values)."') ON DUPLICATE KEY UPDATE ".implode(", ", $update));
	}
	
	function insert($table, $data)
	{
		foreach($data as $key => $value)
		{
			$value = mysql_escape_string($value);
			$keys[] = $key;
			$values[] = $value;
		}
		return $this->query("INSERT INTO `".$table."` (`".implode("`, `", $keys)."`) VALUES ('".implode("', '", $values)."')");
	}
	
	function update($table, $data, $primary)
	{
		$primary_value = mysql_escape_string($data[$primary]);
		unset($data[$primary]);
		foreach($data as $key => $value)
		{
			$update[] = "`".$key."` = '".mysql_escape_string($value)."'";
		}
		return $this->query("UPDATE `".$table."` SET ".implode(", ", $update)." WHERE `".$primary."` = '".$primary_value."'");
	}
	
   	public function query($sql)
   	{ 
       	if (!($result = parent::query($sql)))
       	{
           	throw new Exception(sprintf("Can't perform query on database. Error: %s", $this->error));
       	}
       	return $result;
   	}
}
?>