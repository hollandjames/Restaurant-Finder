<?php

/*
header('Pragma: no-cache');
header('cache-Control: no-cache, must-revalidate'); // HTTP/1.1
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
*/
require_once('getMarkers.php');
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

//var_dump($markerArray);
//$newVar = stripcslashes($allMarkersJson[0]);
?>


<html>
<title>Life Planner</title>
<head>

 <link rel="stylesheet" type="text/css" href="style.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <script src="rome.js"></script>



</head>

<body>

    <input id="eventName" class="controls" type="text" placeholder="Enter Event Name">
    <input id="pac-input" class="controls" type="text" placeholder="Enter Event Location">
    <input id='input' class='input'>



<button id ="saveToDatabase"> Save to events </button>


<div id = "map">


</div>

<div id ="tableData">
</div>

<button onclick ="displayMyJourney()">Show overall route</button><br>





Where you at:
<select id="currentLocationDropdown"></select>
Wheres do you wanna be:
<select id="destinationLocationDropdown"></select>

<button id ="findPath">Get the route</button>





<div id="directionsPanel" style="width:24%;height: 100%;"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLA4DFC6I-78xDP1MR4HZptnhttW02otU&libraries=places&callback=initAutocomplete"
async defer></script>


</body>

</html>


<script>

var map;
rome(input);


var markerJson = <?php echo $allMarkersJson ?>;
var eventName = <?php echo $eventMarkersJson ?>;
var eventTime = <?php echo $TimeMarkersJson ?>;

//var firstJson = allMarkers[0];
//markers stored in json need to be converted to correct format
		var markerMap = markerJson.map(function(e) {
			return JSON.parse(e);
		});

var eventPlace = [];

for (let x=0;x<markerMap.length;++x){
  let currMarker = markerMap[x];
  eventPlace.push(currMarker.name);
}

var allInfo =[eventPlace,eventName,eventTime];

function initAutocomplete() {
    map = new google.maps.Map(document.getElementById('map'), {
    center: {lat:51.507351 , lng: -0.127758},
    zoom: 13,
    mapTypeId: 'roadmap',
    gestureHandling: 'greedy'
  });
displayMyJourney();

var input = document.getElementById('pac-input');
var searchBox = new google.maps.places.SearchBox(input);


$("#saveToDatabase").click(function(){
//gets place

var bounds = new google.maps.LatLngBounds();
var place = searchBox.getPlaces();

//gets the first selected place in array
var locationJson = JSON.stringify(place[0]);
var event = firstLetterCaps((document.getElementById('eventName').value));
var datetime =(document.getElementById('input').value).toString();

$.ajax({
 type: "POST",
 url: "insertLocation.php",
 dataType:"json",
 data:  {
   locationJson : locationJson, event:event,datetime
},
cache: false,
 success: function(result){
   //if successful pop up notification using notify js

 console.log("success");

 },
 error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus); alert("Error: " + errorThrown);
                }
 });
});
}


var myTableDiv = document.getElementById("tableData");
var table = document.createElement('TABLE');
table.border='1';
var tableBody = document.createElement('TBODY');
table.appendChild(tableBody);
for (var i=0; i<allInfo[0].length; i++){
   var tr = document.createElement('TR');
   tableBody.appendChild(tr);

   for (var j=0; j<3; j++){
       var td = document.createElement('TD');
       td.width='150';
       td.appendChild(document.createTextNode(allInfo[j][i]));
       tr.appendChild(td);
   }
}
myTableDiv.appendChild(table);

function firstLetterCaps(s){
 var capitalised=  s.charAt(0).toUpperCase() + s.slice(1);
 return capitalised;
}

$("#findPath").click(function(){
   let fromJourney = document.getElementById('currentLocationDropdown').value;
   let toJourney = document.getElementById('destinationLocationDropdown').value;
   var fromJourneyObj;
   var toJourneyObj;

   for (let i = 0; i<markerMap.length;i++){
     if (markerMap[i].name ==fromJourney){

    fromJourneyObj = new google.maps.LatLng(markerMap[i].geometry.location);
}
if (markerMap[i].name ==toJourney){
toJourneyObj = new google.maps.LatLng(markerMap[i].geometry.location);
}
}

  var searchRequest = {
  origin: fromJourneyObj,
  destination: toJourneyObj,
  travelMode:"WALKING"
};
var searchService = new google.maps.DistanceMatrixService();
var searchDirectionsService = new google.maps.DirectionsService();

var searchDirectionsDisplay = new google.maps.DirectionsRenderer({
  polylineOptions:{
    strokeColor: 'red'},
    //suppressMarkers: true // removes the default markers
});

searchService.getDistanceMatrix(
    {
      origins: [fromJourneyObj],
      destinations: [toJourneyObj],
      travelMode: "WALKING"
   }, displayTitle);

   function displayTitle(response, status) {

//edit html element to display  names of route
}

  searchDirectionsService.route(searchRequest, function(response, status) {
    if (status == "OK") {

      searchDirectionsDisplay.setDirections(response);
      searchDirectionsDisplay.setMap(map);
      //clear the current route

      $("#directionsPanel").empty();

      searchDirectionsDisplay.setPanel(document.getElementById('directionsPanel'));
    } else {
      window.alert("Directions request failed due to " + status);
    }

  });

  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat:51.507351 , lng: -0.127758},
    zoom: 13,
    mapTypeId: 'roadmap',
    gestureHandling: 'greedy'
  });



});

function displayMyJourney(){

var waypts =[]
for (let y=1;y<markerMap.length-1;y++){
waypts[y-1]=new Object();
waypts[y-1].location=new google.maps.LatLng(markerMap[y].geometry.location);
waypts[y-1].stopover =true;
}
  var request = {
  origin: new google.maps.LatLng(markerMap[0].geometry.location),
  destination: new google.maps.LatLng(markerMap[(markerMap.length-1)].geometry.location),
  waypoints:waypts,
  optimizeWaypoints: true,
  travelMode:"WALKING"
};

var directionsDisplay = new google.maps.DirectionsRenderer({
  polylineOptions:{
    strokeColor: 'blue'},
    //suppressMarkers: true // removes the default markers
})



var directionsService = new google.maps.DirectionsService();
var service = new google.maps.DistanceMatrixService();

  service.getDistanceMatrix(
    {
      origins: [request.origin],
      destinations: [request.destination],
      travelMode: request.travelMode
   }, callback);

function callback(response, status) {


   var results = response.rows[0].elements;
   var element = results[0];
   var duration = element.duration.text;

}


  directionsService.route(request, function(response, status) {
    if (status == "OK") {
      directionsDisplay.setDirections(response);
      directionsDisplay.setMap(map);
    //  directionsDisplay.setPanel(document.getElementById('directionsPanel'));
    } else {
      window.alert("Directions request failed due to " + status);
    }
  });

  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat:51.507351 , lng: -0.127758},
    zoom: 13,
    mapTypeId: 'roadmap',
    gestureHandling: 'greedy'
  });

}


var selectCurr = document.getElementById("currentLocationDropdown");

for (let g = 0;g<eventPlace.length;g++){
  var opt = document.createElement("option");
  opt.text = eventPlace[g];
  selectCurr.appendChild(opt);
}

var selectDest = document.getElementById("destinationLocationDropdown");
for (let g = 0;g<eventPlace.length;g++){
  var opt = document.createElement("option");
  opt.text = eventPlace[g];
 // opt.value = puzzleArray[g];
  selectDest.appendChild(opt);
}








    </script>





