<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps Directions - Cebu Technological University Danao Campus</title>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
        body {
            font-family: Arial, sans-serif;
        }
        #controls {
            margin: 10px;
        }
        input[type="text"] {
            padding: 8px;
            margin: 5px 0;
            width: 200px;
        }
        select {
            padding: 8px;
            margin: 5px 0;
            width: 210px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h2>Google Maps Directions - Cebu Technological University Danao Campus</h2>

    <!-- Map container -->
    <div id="map"></div>

    <!-- Controls for entering start and end points with autocomplete and travel modes -->
    <div id="controls">
        <input id="start" type="text" placeholder="Enter start location">
        <input id="end" type="text" placeholder="Enter end location">
        <select id="travelMode">
            <option value="DRIVING">Driving</option>
            <option value="WALKING">Walking</option>
            <option value="BICYCLING">Bicycling</option>
            <option value="TRANSIT">Transit</option>
        </select>
        <button onclick="calculateRoute()">Get Directions</button>
    </div>

    <!-- Error handling -->
    <div id="error-message" style="color: red; margin-top: 10px;"></div>

    <script>
        let map;
        let directionsService;
        let directionsRenderer;
        let startAutocomplete;
        let endAutocomplete;

        // Initialize the map and set location to Cebu Technological University - Danao Campus
        function initMap() {
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();

            // Center the map at Cebu Technological University - Danao Campus
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 10.5223, lng: 124.0286}, // Coordinates for Cebu Technological University - Danao Campus
                zoom: 15
            });
            
            directionsRenderer.setMap(map);

            // Initialize autocomplete
            const startInput = document.getElementById('start');
            const endInput = document.getElementById('end');
            startAutocomplete = new google.maps.places.Autocomplete(startInput);
            endAutocomplete = new google.maps.places.Autocomplete(endInput);
        }

        function calculateRoute() {
            const start = document.getElementById('start').value;
            const end = document.getElementById('end').value;
            const travelMode = document.getElementById('travelMode').value;

            // Create a directions request
            const request = {
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode[travelMode]
            };

            // Calculate the route
            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                } else {
                    document.getElementById('error-message').innerText = 'Directions request failed due to ' + status;
                }
            });
        }

        // Load Google Maps API with error handling
        function loadScript() {
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyBDtc2Hnee_1qlV102sYcAE0Yyic050HoU&libraries=places&callback=initMap`;
            script.async = true;
            script.onerror = function() {
                document.getElementById('error-message').innerText = 'Failed to load Google Maps API. Please check your API key and internet connection.';
            };
            document.head.appendChild(script);
        }

        // Load the script on window load
        window.onload = loadScript;
    </script>

</body>
</html>
