<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjzv1PIy1PfjeSxl8-KNNfwtpCdpA45Xo"></script>
  <link href="stylesMapa.css" rel="stylesheet">
  <script type="text/javascript">
  function load(longitud, latitud, idBoleta) {
    var map = new google.maps.Map(document.getElementById("map"), {
      center: new google.maps.LatLng(latitud,longitud),
      zoom: 17,
      mapTypeId: 'roadmap'
    });
    var infoWindow = new google.maps.InfoWindow;
    /*downloadUrl("markers.php?idBoleta=14", function(data) {
      var xml = data.responseXML;
      var markers = xml.documentElement.getElementsByTagName("marker");
      for (var i = 0; i < markers.length; i++) {
        var point = new google.maps.LatLng(
          parseFloat(markers[i].getAttribute("lat")),
          parseFloat(markers[i].getAttribute("lng")));
        var icon = 'marker.png';
        var marker = new google.maps.Marker({
          map: map,
          position: point,
          icon: icon,
		  label: 'xxx'
        });
      }
    });*/
	var point = new google.maps.LatLng(latitud,longitud);
        var icon = 'marker.png';
        var marker = new google.maps.Marker({
          map: map,
          position: point,
          icon: icon,
		  label: ''
        });
  }
  function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
    new ActiveXObject('Microsoft.XMLHTTP') :
    new XMLHttpRequest;
    request.onreadystatechange = function() {
      if (request.readyState == 4) {
        request.onreadystatechange = doNothing;
        callback(request, request.status);
      }
    };
    request.open('GET', url, true);
    request.send(null);
  }
  function doNothing() {}
  </script>
</head>
<div class='titular'>Visualización de Punto de Visita <br> <?php  echo $_GET['medico']; ?></div>
	<?php
	$idBoleta=$_GET['id'];
	$longitud=$_GET['longitud'];
	$latitud=$_GET['latitud'];
	$medico=$_GET['medico'];
	
	echo "<body onload='load($longitud, $latitud, $idBoleta);'>";
	?>

	<div id="map"></div>
</body>
</html>