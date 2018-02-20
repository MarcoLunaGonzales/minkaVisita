
<script language='JavaScript'>
		function nuevoAjax()
		{	var xmlhttp=false;
 			try {
 				xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	 		} catch (e) {
 				try {
 					xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
 				} catch (E) {
 					xmlhttp = false;
 				}
  			}
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 			xmlhttp = new XMLHttpRequest();
			}
			return xmlhttp;
		}
		function cambiaProducto(f, id, parrilla, prioridad, linea, codigo){
			var contenedor;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxCambiaProducto.php?parrilla='+parrilla+'&prioridad='+prioridad+'&linea='+linea+'&id='+id+'&codigo='+codigo+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}
		function cambiaMaterial(f, id, parrilla, prioridad, linea, codigo){
			var contenedor;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxCambiaMaterial.php?parrilla='+parrilla+'&prioridad='+prioridad+'&linea='+linea+'&id='+id+'&codigo='+codigo+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}

		function cambiaCantidadProducto(f, id, parrilla, prioridad, linea, cantidad){
			var contenedor;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxCambiaCantidadProducto.php?parrilla='+parrilla+'&prioridad='+prioridad+'&linea='+linea+'&id='+id+'&cantidad='+cantidad+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}

		function cambiaCantidadMaterial(f, id, parrilla, prioridad, linea, cantidad){
			var contenedor;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxCambiaCantidadMaterial.php?parrilla='+parrilla+'&prioridad='+prioridad+'&linea='+linea+'&id='+id+'&cantidad='+cantidad+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}

		function guardaSelect(combo, parrilla, prioridad, id){
			var contenedor;
			var codigo=combo.value;
			var producto=combo.options[combo.selectedIndex].text;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxGuardaProducto.php?parrilla='+parrilla+'&prioridad='+prioridad+'&codigo='+codigo+'&producto='+producto+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}
		function guardaMaterial(combo, parrilla, prioridad, id){
			var contenedor;
			var codigo=combo.value;
			var producto=combo.options[combo.selectedIndex].text;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxGuardaMaterial.php?parrilla='+parrilla+'&prioridad='+prioridad+'&codigo='+codigo+'&producto='+producto+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}
		function guardaAjaxCambiaCantidad(combo, parrilla, prioridad, id){
			var contenedor;
			var cantidad=combo.value;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxGuardaCantidadProducto.php?parrilla='+parrilla+'&prioridad='+prioridad+'&cantidad='+cantidad+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}
		function guardaAjaxCambiaCantidadMaterial(combo, parrilla, prioridad, id){
			var contenedor;
			var cantidad=combo.value;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxGuardaCantidadMaterial.php?parrilla='+parrilla+'&prioridad='+prioridad+'&cantidad='+cantidad+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}

</script>
<?php
require("conexion.inc");
require("estilos.inc");
$gestion_rpt = $codigo_gestion;
$global_linea = $global_linea;
$ciclo_rpt = $_GET['ciclo_trabajo'];
echo "<form method='post' action='opciones_medico.php'>";
echo "<center><table border='0' class='textotit'><tr><th>Editar Parrillas en Conjunto<br>Ciclo Programado: $ciclo_rpt</th></tr></table></center><br>";
echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";

if ($rpt_territorio == 0) {
    // //////////
    $sql = "select * from parrilla where codigo_linea=$global_linea and cod_ciclo='$ciclo_rpt' and 
    codigo_gestion='$gestion_rpt' order by cod_ciclo, agencia, cod_especialidad,categoria_med,numero_visita";
    $resp = mysql_query($sql);
    $filas = mysql_num_rows($resp);
    if ($filas > 0) {
        echo "<table align='center' class='texto'>
		<tr><th>Para editar un producto, material o cantidades haga doble click sobre el item que desee modificar.</th></tr></tr></table>";
        echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
        echo "<tr><th>Especialidad</th><th>Territorio</th><th>Línea de Visita</th><th>Categoria</th><th>Visita</th><th>Parrilla Promocional</th></tr>";
        while ($dat = mysql_fetch_array($resp)) {
            $cod_parrilla = $dat[0];
            $cod_ciclo = $dat[1];
            $cod_espe = $dat[2];
            $cod_cat = $dat[3];
            $fecha_creacion = $dat[5];
            $fecha_modi = $dat[6];
            $numero_de_visita = $dat[7];
            $cod_agencia=$dat[8];
            	$sqlAgencia="select descripcion from ciudades where cod_ciudad=$cod_agencia";
            	$respAgencia=mysql_query($sqlAgencia);
            	$datAgencia=mysql_fetch_array($respAgencia);
            	$nombreAgencia=$datAgencia[0];
            $codigo_lineavisita = $dat[9];
            $sql_nombre_lineavisita = "select nombre_l_visita from lineas_visita where codigo_l_visita='$codigo_lineavisita'";
            $resp_lineavisita = mysql_query($sql_nombre_lineavisita);
            $dat_lineavisita = mysql_fetch_array($resp_lineavisita);
            $nombre_lineavisita = $dat_lineavisita[0];

            $sql1 = "select m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra, m.codigo, mm.codigo_material
				from muestras_medicas m, parrilla_detalle p, material_apoyo mm
      				where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
            $resp1 = mysql_query($sql1);
            $parrilla_medica = "<table class='textomini' width='100%' border='0'>";
            $parrilla_medica = $parrilla_medica . "<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
            while ($dat1 = mysql_fetch_array($resp1)) {
                $muestra = $dat1[0];
                $presentacion = $dat1[1];
                $cant_muestra = $dat1[2];
                $material = $dat1[3];
                $cant_material = $dat1[4];
                $obs = $dat1[5];
                $prioridad = $dat1[6];
                $extra = $dat1[7];
				$codigoMuestra=$dat1[8];
				$codigoMaterial=$dat1[9];
                if ($extra == 1) {
                    $fondo_extra = "<tr bgcolor='#66CCFF'>";
                } else {
                    $fondo_extra = "<tr>";
                } 
                if ($obs != "") {
                    $observaciones = "<img src='imagenes/informacion.gif' alt='$obs'>";
                } else {
                    $observaciones = "&nbsp;";
                } 
                $parrilla_medica = $parrilla_medica . "$fondo_extra<td align='center'>$prioridad</td>
									<td align='left' width='35%'><div id='1$cod_parrilla$prioridad' onDblClick='cambiaProducto(this.form, this.id, $cod_parrilla, $prioridad, $global_linea, \"$codigoMuestra\")';>$muestra $presentacion</div></td>
									<td align='center' width='10%'><div id='2$cod_parrilla$prioridad' onDblClick='cambiaCantidadProducto(this.form, this.id, $cod_parrilla, $prioridad, $global_linea, $cant_muestra)';>$cant_muestra</div></td>
									<td align='left' width='35%'><div id='3$cod_parrilla$prioridad' onDblClick='cambiaMaterial(this.form, this.id, $cod_parrilla, $prioridad, $global_linea, \"$codigoMaterial\")';>$material</div></td>
									<td align='center' width='10%'><div id='4$cod_parrilla$prioridad' onDblClick='cambiaCantidadMaterial(this.form, this.id, $cod_parrilla, $prioridad, $global_linea, $cant_material)';>$cant_material</div></td>
									<td align='center' width='10%'><div id='5$cod_parrilla$prioridad'>$observaciones</td></tr>";
            } 
            $parrilla_medica = $parrilla_medica . "</table>";
            echo "<tr><td align='center'>$cod_espe</td><td>$nombreAgencia&nbsp;</td><td align='center'>$nombre_lineavisita&nbsp;</td><td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
        } 
        echo "</table></center><br>";
    } 
} 

?>