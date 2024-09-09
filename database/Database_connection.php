<?php
// Database_connection.php

class Database_connection
{
	function connect()
	{
		$connect = new PDO("mysql:host=localhost; dbname=user_auth", "root", "");

		return $connect;
	}
}

?>