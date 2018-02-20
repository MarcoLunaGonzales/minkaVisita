<script language='JavaScript'>
	function activarCampos(f, chk, i){
		var indice=parseInt(i);
		indice=indice*8;
		if(chk.checked){
			f.elements[indice-5].disabled=false;				
			f.elements[indice-4].disabled=false;				
			if(f.elements[indice-6].value!=0){
				f.elements[indice-3].disabled=false;				
				f.elements[indice-2].disabled=false;								
				f.elements[indice-1].disabled=false;	
			}					
		}else{
			f.elements[indice-5].disabled=true;				
			f.elements[indice-4].disabled=true;				
			f.elements[indice-3].disabled=true;				
			f.elements[indice-2].disabled=true;				
			f.elements[indice-1].disabled=true;				

		}
	}	
function validar(){
	alert("hola como vamos");
	alert(form9.length);
}
</script>

<?php
require("conexion.inc");
require("estilos_visitador_sincab.inc");
require("funcion_nombres.php");
require("functionMostrarParrilla.php");

$codMed=$codMed;
$codEspe=$codEspe;
$codCat=$codCat;
$codContacto=$codContacto;
$nombreMed=$nombreMed;
$codLinea=$global_linea;
$codTerritorio=$global_agencia;
$codLineaVisita=$codLineaVisita;
$numVisita=$numVisita;
$codCiclo=$ciclo_global;
$codGestion=$codigo_gestion;
$diaContacto=$diaContacto;
$codMedFrec=$codMedFrec;

$nombreMed=nombreMedico($codMed);

echo "<table align='center' class='textotit'><tr><th>Registro de Visita Medica<br>
			Medico: $nombreMed Especialidad: $codEspe Categoria: $codCat<br>
			</table><br>";

$fechaVisita=date("d/m/Y");

echo "<form name='form1' method='post' action='guardaRegistrarVisitaParrilla.php'>";
echo "<table border='0' cellspacing='0' class='texto' align='center'><tr><th>Fecha Real de Visita</th><th>
		<INPUT  type='text' class='texto' id='fechaVisitaReal' size='10' name='fechaVisitaReal' value='$fechaVisita'>
		<IMG id='imagenFecha' src='imagenes/fecha.bmp'>
		<DLCALENDAR tool_tip='Seleccione la Fecha'
		daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' 
		navbar_style='background-color: 7992B7; color:ffffff;' 
		input_element_id='fechaVisitaReal' 
		click_element_id='imagenFecha'></DLCALENDAR></th>
		</tr></table><br>";


//echo $numVisita;

$vectorNumVisita=explode("-",$numVisita);
$numReg=1;
for($i=0;$i<=sizeof($vectorNumVisita)-1;$i++){
	$numVisitaNuevo=$vectorNumVisita[$i];
	$numReg=mostrarParrilla($codGestion, $codCiclo, $codTerritorio, $codLineaVisita, $codEspe, $codCat, $codLinea, $numVisitaNuevo, $diaContacto,$codMedFrec,$numReg);
}
echo "<input type='hidden' name='cod_contacto' value='$codContacto'>";
echo "<input type='hidden' name='cod_med' value='$codMed'>";
echo "<input type='hidden' name='cod_espe' value='$codEspe'>";
echo "<input type='hidden' name='cod_cat' value='$codCat'>";
echo "<input type='hidden' name='cod_dia' value='$diaContacto'>";


echo "<center><input type='button' class='boton' value='Guardar' onClick='validar();'></center>";
echo "</form>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>