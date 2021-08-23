<?php
//$command = escapeshellcmd('api-football.py');
//$output = shell_exec($command);
//echo $output;
include_once 'config.php';

$lat = file("text-files/lat.txt");
$lon = file("text-files/lon.txt");
$scores = file("text-files/scores.txt");
$venues = file("text-files/venues.txt");
$competitions = file("text-files/competitions.txt")
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Match Tracker | All Live Football Matches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel="stylesheet" type="text/css" href="./style.css" />
    <link rel="shortcut icon" type="image/jpg" href="favicon/favicon.png"/>
  </head>
    <body> 

    <div class="header">
    <a href="index" id="title">Football Match Tracker</a>
    <p id="subtitle">All Live Football Matches</p>
    </div>

    <div id="map"></div>

    <script>
    let map;

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 30, lng: 10 },
        zoom: 2.5,
        minZoom: 2.5,
        streetViewControl: false,
    });

    var lat = <?php echo json_encode($lat); ?>;
    var lon = <?php echo json_encode($lon); ?>;
    var scores = <?php echo json_encode($scores, JSON_INVALID_UTF8_SUBSTITUTE); ?>;
    var venues = <?php echo json_encode($venues, JSON_INVALID_UTF8_SUBSTITUTE); ?>;
    var competitions = <?php echo json_encode($competitions, JSON_INVALID_UTF8_SUBSTITUTE); ?>;

    for (i = 0; i < lat.length; i++) {
      if (scores[i] !== "None\r\n") {
        addMarker({coordinates:{lat: parseFloat(lat[i]), lng: parseFloat(lon[i])}},
        "<h6><strong>" + scores[i] + "</strong><br><br>Venue: " + venues[i] + "<br>Competition: " + competitions[i] + "</h6>");
    }
    }

    function addMarker(props, content) {
        var marker = new google.maps.Marker({
            position:props.coordinates,
            map:map,
            icon:"http://maps.google.com/mapfiles/kml/pal2/icon49.png",
        })
        //Create new infowindow and add content
        var infowindow = new google.maps.InfoWindow({
        content:content
        });
        //Listener for mouseover marker, prompting infowindow to be displayed
        google.maps.event.addListener(marker, 'mouseover', function() {
        infowindow.open(map, marker);
        });
        //Listener for mouseout marker, prompting infowindow to be hidden
        google.maps.event.addListener(marker, 'mouseout', function() {
        infowindow.close(map, marker);
        });
    }
    }

    </script>

<div class="footer">
  <ul>
  <h5>&copy; Copyright 2021 Football Match Tracker</h5>
  <h5>Powered by:</h5>
  <a href="https://rapidapi.com/api-sports/api/api-football/" target=”_blank” class="api-links">API-FOOTBALL</a><br>
  <a href="https://developers.google.com/maps" target=”_blank” class="api-links">Google Maps</a><br>
  <a href="https://www.geoapify.com/" target=”_blank” class="api-links">Geoapify</a>
  </ul>
  </div>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAP_API_KEY; ?>&callback=initMap&libraries=&v=weekly"
      async
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
  </body>
</html>