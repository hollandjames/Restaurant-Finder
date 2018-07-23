<?php
require_once("connection.php");


function getMarkers(){
    global $pdo;

$query ="SELECT * FROM Locations";
$statement = $pdo ->prepare($query);
$statement->execute();
$markers = $statement->fetchall();
return $markers;
}

$markerArray = getMarkers();
//echo sizeof($markerArray);
$firstLocation = $markerArray[0]['json'];

$allMarkers = array();

for($x=0; $x<sizeof($markerArray); $x++)
{
    array_push($allMarkers,$markerArray[$x]['json']);
}
$justEvent = array();

for($x=0; $x<sizeof($markerArray); $x++)
{
    array_push($justEvent,$markerArray[$x]['Event']);
}
$justTime = array();
for($x=0; $x<sizeof($markerArray); $x++)
{
    array_push($justTime,$markerArray[$x]['Time']);
}

$allMarkersJson = json_encode($allMarkers, JSON_UNESCAPED_SLASHES);
$eventMarkersJson = json_encode($justEvent, JSON_UNESCAPED_SLASHES);
$TimeMarkersJson = json_encode($justTime, JSON_UNESCAPED_SLASHES);



?>