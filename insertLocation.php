<?php
require_once("connection.php");



if(isset($_POST["locationJson"])){

    $locationJson = $_POST['locationJson'];
    $eventName = $_POST['eventName'];
    $datetime = $_POST['datetime'];

    $query ="INSERT INTO Locations (json,Event,Time) VALUES (:param,:param,:param)";
    $statement = $pdo ->prepare($query);
    $statement->bindParam(':param', $locationJson,$eventName,$datetime);
    $statement->execute();

}

?>
