<?php
require_once('getMarkers.php');

$markerArray = getMarkers();
//echo sizeof($markerArray);
$firstLocation = $markerArray[0]['json'];

$allMarkers = array();

for($x=0; $x<sizeof($markerArray); $x++)
{
    array_push($allMarkers,$markerArray[$x]['json']);
}

$allMarkersJson = json_encode($allMarkers, JSON_UNESCAPED_SLASHES);

//$newVar = stripcslashes($allMarkersJson[0]);
?>


<html>
<title>Life Planner</title>
<head>

 <link rel="stylesheet" type="text/css" href="style.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>

    <input id="eventName" class="controls" type="text" placeholder="Enter Event Name">
    <input id="pac-input" class="controls" type="text" placeholder="Enter Event Location">
    <input id="datetime" type="datetime-local">



<button id ="saveToDatabase" onclick="initAutoComplete()"> Save to events </button>

<div id = "map">


</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLA4DFC6I-78xDP1MR4HZptnhttW02otU&libraries=places&callback=initAutocomplete"
async defer></script>


</body>

</html>


<script>



var allMarkers = <?php echo $allMarkersJson ?>;
//var firstJson = allMarkers[0];

		var markerMap = allMarkers.map(function(e) {
			return JSON.parse(e);
		});




function initAutocomplete() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat:51.507351 , lng: -0.127758},
    zoom: 13,
    mapTypeId: 'roadmap'
  });

for(x=0;x<markerMap.length;x++){
  var thisMarker = markerMap[x];

  var marker = new google.maps.Marker({

    position: thisMarker.geometry.location,
    title:"Hello World!"
  });

marker.setMap(map);
}
var input = document.getElementById('pac-input');
var searchBox = new google.maps.places.SearchBox(input);


$("#saveToDatabase").click(function(){
//gets place

var bounds = new google.maps.LatLngBounds();
var place = searchBox.getPlaces();
//gets event
var event = document.getElementById('eventName');
//gets date time
var datetime =document.getElementById('datetime');


//gets the first selected place in array
var locationJson = JSON.stringify(place[0]);

$.ajax({
 type: "POST",
 url: "insertLocation.php",
 dataType:"json",
 data:  {
   locationJson : locationJson, event:event,datetime:datetime
},

 cache: false,
 success: function(result){
   //if successful pop up notification using notify js

  window.alert("successful upload!");

 }});
});


}

function refreshPage(){

  location.reload();
}

    </script>





