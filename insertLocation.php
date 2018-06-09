<?php
require_once("connection.php");



if(isset($_POST["locationJson"])){

    $locationJson = $_POST['locationJson'];

    $query ="INSERT INTO Locations (json) VALUES (:json)";
    $statement = $pdo ->prepare($query);
    $statement->bindParam(':json', $locationJson);
    $statement->execute();

}

?>
