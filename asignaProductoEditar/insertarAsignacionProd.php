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

function ajaxAgencias(codigo, codLinea){
	var codProducto=codigo.value;
	
	var contenedor;
	contenedor = document.getElementById('divAgencias');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxAgenciasTodo.php?codProducto='+codProducto+'&codLineaMkt='+codLinea+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			ajaxEspecialidades(codigo, codLinea);
		}
	}
	ajax.send(null)
}
function ajaxEspecialidades(codigo, codLinea){
	var codProducto=codigo.value;	
	var contenedor;
	contenedor = document.getElementById('divEspecialidades');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxEspecialidadesTodo.php?codProducto='+codProducto+'&codLineaMkt='+codLinea+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null)
}

function envia_formulario(f)
{	var rptProducto;
	rptProducto=f.codProducto.value;
	var rptAgencia=new Array();
	var rptEspecialidades=new Array();
	var rptContactos=new Array();
	var j=0;
	for(i=0;i<=f.rptAgencia.options.length-1;i++)
	{	if(f.rptAgencia.options[i].selected)
		{	rptAgencia[j]=f.rptAgencia.options[i].value;
			j++;
		}
	}
	j=0;
	for(i=0;i<=f.rptEspecialidad.options.length-1;i++)
	{	if(f.rptEspecialidad.options[i].selected)
		{	rptEspecialidades[j]=f.rptEspecialidad.options[i].value;
			j++;
		}
	}
	j=0;
	for(i=0;i<=f.rptContacto.options.length-1;i++)
	{	if(f.rptContacto.options[i].selected)
		{	rptContactos[j]=f.rptContacto.options[i].value;
			j++;
		}
	}
	f.fAgencia.value=rptAgencia;
	f.fEspecialidad.value=rptEspecialidades;
	f.fContacto.value=rptContactos;
	//window.open('guardaEliminarAsignacionProd.php?rptProducto='+rptProducto+'&rptAgencia='+rptAgencia+'&rptEspecialidades='+rptEspecialidades+'&rptContacto='+rptContacto,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	//return(true);
	f.submit();
	return(true);
}
</script>
<?php
	require("../funcion_nombres.php");
	require("../conexion.inc");
	
	require("../conexioni.inc");
	
	//$link = mysqli_connect('localhost', 'root', '', 'visita20140527');
	
	$codLineaMkt=$_GET['lineaMkt'];	
	$nombreLinea=nombreLinea($codLineaMkt);
?>
<!DOCTYPE html>
<html>
<body>
	<div id="wrapper">
		<h2 align="center">Adicionar Producto Linea: <?php echo $nombreLinea;?>
			<?php echo $espe; ?> <?php echo $contacto; ?> <?php echo $nombreCiudad;?> 
		</h2>

		<div id="form">
			<form id="formulario" method="get" action="guardaInsertarAsignacionProd.php" name="formulario">
			<table border="1" cellpadding="0" cellspacing="0" align="center" width="40%">
				<?php 
				$sqlProd="select distinct(m.codigo), concat(m.descripcion, ' ', m.presentacion) from 
				muestras_medicas m where estado=1 order by 2;";
				$respProd=mysql_query($sqlProd);
				echo "<tr><td>Producto</td> 
					<td><select name='codProducto' id='codProducto' onChange='ajaxAgencias(this, $codLineaMkt)'>
					<option value='0'>---</option>";
				while($datProd=mysql_fetch_array($respProd)){
					$codigo=$datProd[0];
					$nombre=$datProd[1];
					echo "<option value='$codigo'>$nombre</option>";
				}
				echo "</select></td></tr>";
				echo "<tr><td>Posicion</td>
				<td>
					<select name='rptPosicion' id='rptPosicion' size='1'>
						<option value='1'>- 1 -</option>
						<option value='2'>- 2 -</option>
						<option value='3'>- 3 -</option>
						<option value='4'>- 4 -</option>
						<option value='5'>- 5 -</option>
						<option value='6'>- 6 -</option>
						<option value='7'>- 7 -</option>
						<option value='8'>- 8 -</option>
					</select>					
				</td>
				</tr>";
				echo "<tr><td>Agencia</td><td>
				<div id='divAgencias'>
					<select><option>Seleccione una opcion</option></select>
				</div></td>
				</tr>";
				echo "<tr><td>Especialidades</td><td>
				<div id='divEspecialidades'>
					<select><option>Seleccione una opcion</option></select>					
				</div></td>
				</tr>";
				
				echo "<tr><td>Nro. Contacto</td>
				<td>
					<select name='rptContacto' id='rptContacto' size='8' multiple>
						<option value='1'>- 1 -</option>
						<option value='2'>- 2 -</option>
						<option value='3'>- 3 -</option>
						<option value='4'>- 4 -</option>
						<option value='5'>- 5 -</option>
						<option value='6'>- 6 -</option>
						<option value='7'>- 7 -</option>
						<option value='8'>- 8 -</option>
					</select>					
				</td>
				</tr>";
			
				?>
			</table>
			<br><br>
			<input type="hidden" name="fAgencia" id="fAgencia">
			<input type="hidden" name="fEspecialidad" id="fEspecialidad">
			<input type="hidden" name="fContacto" id="fContacto">
			<input type="hidden" name="fLineaMkt" id="fLineaMkt" value="<?php echo $codLineaMkt;?>">
			
			
			<center><input type="button" value="Insertar" onClick="envia_formulario(formulario);" name="boton"></center>
		</form>			
		</div>
		
		
	</div>
</body>
</html>