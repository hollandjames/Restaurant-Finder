<?php
require_once("connection.php");




if(isset($_POST["locationJson"])){

    $locationJson = $_POST['locationJson'];
    $event = $_POST['event'];
    $datetime = $_POST['datetime'];

    $query ="INSERT INTO Locations (json,Event,Time) VALUES (:param, :event,:datetime)";
    $statement = $pdo ->prepare($query);
    $statement->bindParam(':param', $locationJson);
    $statement->bindParam(':event', $event);
    $statement->bindParam(':datetime', $datetime);
    $statement->execute();



}

?>
