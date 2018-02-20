<html>
<body>

<h1>My First Google Map</h1>

<div id="map" style="width:100%;height:500px"></div>

<script>
function myMap() {
  var myCenter = new google.maps.LatLng(-16.503991, -68.121299);
  var mapCanvas = document.getElementById("map");
  var mapOptions = {center: myCenter, zoom: 15};
  var map = new google.maps.Map(mapCanvas, mapOptions);
  var marker = new google.maps.Marker({position:myCenter});
  marker.setMap(map);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjzv1PIy1PfjeSxl8-KNNfwtpCdpA45Xo&callback=myMap"></script>

</body>
</html>
