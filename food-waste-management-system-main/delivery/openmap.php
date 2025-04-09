<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Current Location on Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.min.js"></script>
    <link rel="stylesheet" href="../home.css">
    <style>
      @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
      #map-container { width: auto; height: 300px; margin: 10px 20px; z-index: 2; }
      #contain { font-family: 'Poppins', sans-serif; text-align: center; }
      .nav-bar a { background:#06C167; }
      @media screen and (max-width: 600px) { #map-container { height: 200px; } }
    </style>
</head>
<body>
<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <nav class="nav-bar">
        <ul>
            <li><a href="delivery.php">Home</a></li>
            <li><a href="#" class="active">Map</a></li>
            <li><a href="deliverymyord.php">My Orders</a></li>
        </ul>
    </nav>
</header>
<br>

<div id="contain">
    <h3> Current Location </h3>
    <div id="map-container"></div>
    <div id="city-name"></div>
    <div id="address"></div>
</div>

<script>
function initMap() {
    var mapContainer = document.getElementById("map-container");
    
    // Default location: Mumbai (if geolocation fails)
    var defaultLocation = { lat: 19.0760, lng: 72.8777 };

    navigator.geolocation.getCurrentPosition(function(position) {
        var userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
        loadMap(userLocation);
    }, function() {
        alert("Geolocation failed. Defaulting to Mumbai.");
        loadMap(defaultLocation);
    });
}

function loadMap(location) {
    var map = L.map("map-container").setView(location, 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data © OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);
    
    var marker = L.marker(location).addTo(map);
    marker.bindPopup("<b>You are here!</b>").openPopup();

    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${location.lat}&lon=${location.lng}`)
    .then(response => response.json())
    .then(data => {
        var cityName = data.address.city || data.address.town || "Mumbai";
        document.getElementById("city-name").innerHTML = `You are in ${cityName}`;
        document.getElementById("address").innerHTML = `You are at ${data.display_name}`;
    });
}

initMap();
</script>

</body>
</html>
