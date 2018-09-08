<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Reporte Puntos de Contacto</title>
	
	<script type="text/javascript" src="js/jquery.min.js"></script>


    <style>
      #map {
        height: 100%;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>

<?php
	require("conexion.inc");
	error_reporting(0);
	//require("estilos_reportes.inc");
	require("funcion_nombres.php");
	$rpt_ciclo=$rpt_ciclo;
	$rpt_gestion=$rpt_gestion;
	$rpt_territorio=$rpt_territorio;
	$rpt_visitador=$rpt_visitador;
	$nombreVisitadorX=nombreVisitador($rpt_visitador);
	$rpt_linea=$rpt_linea;
	$nombreGestion=nombreGestion($rpt_gestion);
	$rpt_fecha=$rpt_fecha;
	
	//$rpt_fecha=str_replace("-","|",$rpt_fecha);
	
	$sqlAux="select c.estado from ciclos c where c.codigo_gestion=$rpt_gestion and c.cod_ciclo=$rpt_ciclo";
	$respAux=mysql_query($sqlAux);
	$estadoAux=mysql_result($respAux,0,0);

	if($estadoAux=="Activo"){
		$tabla="boletas_visita_cabXXX";
	}else{
		$tabla="boletas_visita_cabanterior";
	}
?>
	
	<center><div class='titular'><h1 style="display: block;
    font-size: 15pt;
    -webkit-margin-before: 0.83em;
    -webkit-margin-after: 0.83em;
    -webkit-margin-start: 0px;
    -webkit-margin-end: 0px;
    font-family: Verdana;
	color: #585858;
	text-align: center;">Reporte Puntos de Contacto</h1></div>
	<div><h2 style="display: block;
    font-size: 12pt;
    -webkit-margin-before: 0.83em;
    -webkit-margin-after: 0.83em;
    -webkit-margin-start: 0px;
    -webkit-margin-end: 0px;
    font-family: Verdana;
	color: #651FFF;
	text-align: center;">Visitador: <?php echo $nombreVisitadorX;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      
	Fecha de Contacto: <?php echo $rpt_fecha;?></h2></div></center>
	
    <div id="map"></div>
    <script>
     function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: -16.5264984, lng: -68.1574491}
        });

        setMarkers(map);
      }
	  
      function setMarkers(map) {
        var image = {
          url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
          size: new google.maps.Size(20, 32),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(0, 32)
        };

        var shape = {
          coords: [1, 1, 1, 20, 18, 20, 18, 1],
          type: 'poly'
        };
		
		function addInfoWindow(marker, message) {
            var infoWindow = new google.maps.InfoWindow({
                content: message
            });
            google.maps.event.addListener(marker, 'click', function () {
                infoWindow.open(map, marker);
            });
        }
		
		function obtenerDireccion(latitud, longitud){
			var direccion="";
			$.get("https://maps.googleapis.com/maps/api/geocode/json?latlng="+latitud+","+longitud+"&location_type=ROOFTOP&result_type=street_address&key=AIzaSyAjzv1PIy1PfjeSxl8-KNNfwtpCdpA45Xo",
			function (data)
			{	console.log(data);
				if(data.status=="OK"){
					direccion=data.results[0].formatted_address;
				}
			});
			console.log("direccion: "+direccion);
			return direccion;
		}

		
		
		$.get("dataJsonMapContactos.php",
		{codVis:<?php echo $rpt_visitador; ?>,
		fechaVis:'<?php echo $rpt_fecha; ?>'},
		function (data)
		{
			console.log(data);
			for (var i in data) {
				//alert(data[i].latitud + " "+data[i].longitud);
				var marker = new google.maps.Marker({
				position: {lat: parseFloat(data[i].latitud), lng: parseFloat(data[i].longitud)},
				map: map,
				title: data[i].nombreMedico
				});

				var direccion="";
				var contenido="<center><b>"+data[i].nombreMedico+"</b><br>"+direccion+"<br>Especialidad: <b>"+data[i].especialidad+"</b><br>Fecha Visita: <b>"+data[i].fechaVisita+"</b></center>";
				/*$.get("https://maps.googleapis.com/maps/api/geocode/json?latlng="+parseFloat(data[i].latitud)+","+parseFloat(data[i].longitud)+"&location_type=ROOFTOP&result_type=street_address&key=AIzaSyAjzv1PIy1PfjeSxl8-KNNfwtpCdpA45Xo",
				function (data)
				{	console.log(data);
					if(data.status=="OK"){
						contenido=contenido+data.results[0].formatted_address;
						console.log(contenido);
					}
				});
				console.log("content "+contenido);*/
				addInfoWindow(marker, contenido);
			}
		});
		
		
		var poly;
		var polyOptions = {
		  strokeColor: '#0000ff',    
		  strokeOpacity: 1.0,   
		  strokeWeight: 2 
		}
		poly = new google.maps.Polyline(polyOptions);
		poly.setMap(map);   

		var path = poly.getPath();    
		$.get("dataJsonMapContactos.php",
		{codVis:<?php echo $rpt_visitador; ?>,
		fechaVis:'<?php echo $rpt_fecha; ?>'},
		function (data)
		{
			for (var i in data) {
				path.push(new google.maps.LatLng(parseFloat(data[i].latitud), parseFloat(data[i].longitud)));
			}
		});
		
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjzv1PIy1PfjeSxl8-KNNfwtpCdpA45Xo&callback=initMap">
    </script>
  </body>
</html>