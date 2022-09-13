<?php
	require_once realpath(__DIR__ . '/vendor/autoload.php');
	
	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();
	
	$dotenv->required(['DB_SERVER', 'DB_DATABASE', 'DB_USER', 'DB_PASS']);
	
	define("SERVER", $_ENV['DB_SERVER']);
	define("DATABASE", $_ENV['DB_DATABASE']);
	define("USERNAME", $_ENV['DB_USER']);
	define("PASSWORD", $_ENV['DB_PASS']);
	class DataProvider
	{
		public static function getConnection(){
			$connection=mysqli_connect(SERVER,USERNAME,PASSWORD)or
			die("Could not connect to ".SERVER.".");
			return $connection;
		}

		public static function execQuery($sql)
		{
			$connection=mysqli_connect(SERVER,USERNAME,PASSWORD)or
			die("Could not connect to ".SERVER.".");
			mysqli_select_db($connection,DATABASE);
			mysqli_query($connection,"set names 'utf8mb4'");
			$result = mysqli_query($connection,$sql);
			if(!$result)die ("Query failed:".mysqli_error($connection));
			mysqli_close($connection);
			return $result;
		}
	}
?>