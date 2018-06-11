<?php
require_once("connection.php");


function getMarkers(){
    global $pdo;

$query ="SELECT json FROM Locations";
$statement = $pdo ->prepare($query);
$statement->execute();
$markers = $statement->fetchall();
return $markers;
}




?>