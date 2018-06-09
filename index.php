<?php
require_once('getMarkers.php');

$markerArray = getMarkers();
$firstLocation = $markerArray[0]['json'];
?>


<html>
<title>Life Planner</title>
<head>

 <link rel="stylesheet" type="text/css" href="style.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>




    <input id="pac-input" class="controls" type="text" placeholder="Search Box">

<button id ="saveToDatabase"> Save to events </button>

<div id = "map">

</div>


<script>
var MarkerObject = <?php echo $firstLocation ?>;


</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLA4DFC6I-78xDP1MR4HZptnhttW02otU&libraries=places&callback=initAutocomplete"
async defer></script>
<script type="text/javascript" src="map.js"></script>

</body>

</html>





