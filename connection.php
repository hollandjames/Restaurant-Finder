<?php
  try
{
	$servername = "localhost";
	$dbusername = "viktorvamos";
	$dbpassword = "admin1";
	$dbname = "LifePlanner";
	$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
}

catch(PDOException $e)
{
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

?>
