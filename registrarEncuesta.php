<script language='JavaScript'>
function validar(current_form){
// guardamos el nombre de grupo de radios o el primer radio de un grupo sin seleccionar
// para devolverle el foco
var radio_group = ""
var ok_form = 1
    for(var ctr = 0 ; ctr < current_form.length-15; ctr++){
    // comprobamos que el campo es radio y que nombre de grupo de radio
     if(current_form[ctr].type == "radio" && current_form[ctr].name != radio_group){
            //comprobamos si tiene marcado un radio en el grupo
            if(check_radio(eval("current_form." + current_form[ctr].name))){
              // Si no tiene selecionado ningun radio rompemos el bucle
              // y asignamos 0 a ok_form
              ok_form--
              // guardamos el radio para asignar el foco
              radio_group = current_form[ctr]
              break
            }
            // Si hemos llegado aquí asignamos el nombre del grupo 
            // para buscar en el siguiente grupo
            radio_group = current_form[ctr].name
        }
    }
 if(ok_form){
   
     current_form.submit()
 }
 else{
   alert("Por favor, complete el cuestionario.")
   radio_group.focus()
 }
}
// esta funcion comprueba el grupo  de radio
function check_radio(radio_group){
  // comprobamos que en el grupo de radio haiga uno seleccionado
  for(var ctr = 0 ; ctr < radio_group.length; ctr++){
    if(radio_group[ctr].checked){
     // Si hay uno selecionado
         return false
    }
  }
        // Si no hay ninguno seleccionado
      return true
}
//-->
</script>
<?php
require("conexion.inc");
require("estilos_visitador.inc");
$cod_med=$cod_med;
$nombre_med=$nombre_med;
$cod_espe=$cod_espe;

	echo "<form method='post' action='guardaEncuesta.php'>";
	echo "<table border='0' class='textotit' align='center'><tr><th>Frecuencia de Uso de Medicamentos<br>Medico: $nombre_med</th></tr></table>";
	
	echo "<table border='1' class='texto' cellspacing='0' width='60%' align='center'>";
	echo "<tr><th>Principio Activo</th><th>Producto</th><th>Frecuencia de Uso</th></tr>";
	
	$sql="select m.codigo, principio_activo, concat(descripcion,' ',presentacion) from `producto_especialidad` p, `muestras_medicas` m
	where p.`codigo_mm`=m.`codigo` and p.`codigo_linea`=1021 and p.`cod_especialidad`='$cod_espe' and m.estado=1 order by 2";
	$resp=mysql_query($sql);
	echo "<input type='hidden' name='cod_med' value='$cod_med'>";
	echo "<input type='hidden' name='cod_espe' value='$cod_espe'>";
	
	$i=1;
	while($dat=mysql_fetch_array($resp)){
		$codigo=$dat[0];
		$principioActivo=$dat[1];
		$descripcion=$dat[2];
		echo "<input type='hidden' name='producto$i' value='$codigo'>";
		echo "<tr><td>$principioActivo</td><td>$descripcion</td><td align='center'>
		<input type='radio' name='frecuencia$i' value='1'>Alta
		<input type='radio' name='frecuencia$i' value='2'>Media
		<input type='radio' name='frecuencia$i' value='3'>Baja
		<input type='radio' name='frecuencia$i' value='4'>No Utiliza
		</td></tr>";
		$i++;
	}
	echo "</table><br><br>";
	
	echo "<input type='hidden' name='numero_productos' value='$i'>";
	
	echo "<table border='1' class='texto' cellspacing='0' width='60%' align='center'>";
	echo "<tr><th colspan='4'>Incluir Producto COFAR que no se encuentra en el formulario de Encuesta</th></tr>";
	echo "<tr><th>&nbsp;</th><th>Producto</th><th>Frecuencia de Uso</th></tr>";
	for($j=1;$j<=3;$j++){
		echo "<tr><td>&nbsp;</td><td><select name='productoExtra$j' class='texto'>";
		$sqlProductos="select codigo, concat(descripcion,' ',presentacion) from muestras_medicas where estado=1";
		$respProductos=mysql_query($sqlProductos);
		echo "<option value='0'></option>";
		while($datProductos=mysql_fetch_array($respProductos)){
			$codProd=$datProductos[0];
			$nombreProd=$datProductos[1];
			echo "<option value='$codProd'>$nombreProd</option>";
		}
		echo"</select></td>";
		echo "<td align='center'>
		<input type='radio' name='frecuenciaExtra$j' value='1'>Alta
		<input type='radio' name='frecuenciaExtra$j' value='2'>Media
		<input type='radio' name='frecuenciaExtra$j' value='3'>Baja
		<input type='radio' name='frecuenciaExtra$j' value='4'>No Utiliza
		</td></tr>";
		
	}
	echo "</table>";
	echo "<br>";

	echo "<center><input type='button' value='Guardar' class='boton' onClick='validar(this.form);'></center>";
	echo "</form>";
?>