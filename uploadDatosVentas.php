<?php
require("conexion.inc");
require("estilos_regional_pri.inc");
require("funciones.php");



error_reporting(-1);
set_time_limit(0);

$fechahora=date("dmy.Hi");

$archivoName=$fechahora.$_FILES['archivo']['name'];

echo "<h1>Cargado de Datos de Venta</h1>";


echo "<h3>Hora inicio: " . date("Y-m-d H:i:s")."</h3>";

$fechaHoraIni=date("Y-m-d H:i:s");

/*INSERTAMOS EN LOG CUP*/
$sqlCodCup="select IFNULL(max(c.id_cargado)+1,1) from datos_logs c;";
$respCodCup=mysql_query($sqlCodCup);
$codLogCup=mysql_result($respCodCup,0,0);

$sqlInsertLog="insert into datos_logs (id_cargado, fecha_inicial) values
('$codLogCup','$fechaHoraIni')";
$respInsertLog=mysql_query($sqlInsertLog);


if ($_FILES['archivo']["error"] > 0){
	echo "Error: " . $_FILES['archivo']['error'] . "<br>";
}
else{
	if($_FILES['archivo']['type']=="application/x-zip-compressed"){
		//echo "Nombre: " . $_FILES['archivo']['name'] . "<br>";
		//echo "Tipo: " . $_FILES['archivo']['type'] . "<br>";
		//echo "Tamaño: " . (($_FILES["archivo"]["size"])/1024)/1024 . " MB<br>";
		//echo "Carpeta temporal: " . $_FILES['archivo']['tmp_name'];
		move_uploaded_file($_FILES['archivo']['tmp_name'], "datoscsv/".$archivoName);	
		
		echo "<h3>1. El archivo de Ventas fue movido correctamente!</h3>";
		
		//ACA DESCOMPRIMIMOS EL ARCHIVO
		$zip = new ZipArchive;
		if ($zip->open('datoscsv/'.$archivoName) === TRUE) {
			$zip->extractTo('datoscsv/procesar/');
			$zip->close();
			echo "<h3>2. El archivo tiene el formato correcto!</h3>";
		} else {
			echo "<h3>2. El archivo NO tiene el formato correcto, contacte con el administrador!</h3>";
		}
		
		/*REALIZAMOS EL BORRADO DE LAS TABLAS*/
		
		$sqlDelete="TRUNCATE TABLE datosventatmp";
		$respDelete=mysql_query($sqlDelete);
				
		
		/*DATOS*/		
		
		$file = fopen("datoscsv/procesar/datosventas.csv", "r") or exit("No se puede abrir el archivo DatosVentas!");
		//Output a line of the file until the end is reached
		$indice=1;
		$insert_str="";
		while(!feof($file)){
			$varDatos=fgets($file);
			if($varDatos!=""){
				list($codProducto, $codAgencia, $codCliente, $codVendedor, $descodigo, $fechaVenta, $cantidadVenta, $vcaja, $montoVentaBrutoBs, $totalventa_us, $totalcosto_bs, $totalcosto_us, $totalutilidad_bs, $totalutilidad_us, $producto, $productounidad, $productonivel1, $productonivel2, $productonivel3, $cliente, $clientenivel1, $clientenivel2, $clientenivel3, $vendedor, $vendedornivel1, $vendedornivel2, $puntoventa, $puntoventanivel1, $lineaVisita, $almacen, $almacennivel1, $anio, $mes, $clifecha_aper, $gestion, $lineaProducto, $nombreAgencia, $montoNetoBs, $nombreVendedor, $precio, $nombreProducto, $nombreCliente, $nombreCanal)=explode(";", $varDatos);				

				$codProducto=trim($codProducto);
				$codProducto=str_replace('"', '', $codProducto);
				
				$codAgencia=trim($codAgencia);
				$codAgencia=str_replace('"', '', $codAgencia);
				
				$codCliente=trim($codCliente);
				$codCliente=str_replace('"', '', $codCliente);
				
				$codVendedor=trim($codVendedor);
				$codVendedor=str_replace('"', '', $codVendedor);
								
				if($fechaVenta!='vfecha'){
					$fechaVenta=trim($fechaVenta);
					$fechaVenta=str_replace('"', '', $fechaVenta);
						
					list($diaDato, $mesDato, $anioDato)=explode("/",$fechaVenta);
					$fechaVenta=$anioDato."-".$mesDato."-".$diaDato;
				

					$insert_str .= "('$codProducto','$codAgencia','$codCliente','$codVendedor','$fechaVenta','$cantidadVenta','$montoVentaBrutoBs','$lineaProducto','$nombreAgencia','$montoNetoBs','$nombreVendedor','$nombreProducto','$nombreCliente','$nombreCanal'),";	
				}
				
			}
			
			if($indice%20000==0){
				$insert_str = substr_replace($insert_str, '', -1, 1);
				$sqlInserta="insert into datosventatmp (cod_producto, cod_agencia, cod_cliente, cod_vendedor, fecha, cantidad, monto, nombre_linea, nombre_agencia, monto_neto, nombre_vendedor, nombre_producto, nombre_cliente, nombre_canal) 
					values ".$insert_str.";";
				
				//echo $sqlInserta."<br>";
				$respInserta=mysql_query($sqlInserta) or die(mysql_error());
				$insert_str="";
			}
			$indice++;
		}
		$insert_str = substr_replace($insert_str, '', -1, 1);
		$sqlInserta="insert into datosventatmp (cod_producto, cod_agencia, cod_cliente, cod_vendedor, fecha, cantidad, monto, nombre_linea, nombre_agencia, monto_neto, nombre_vendedor, nombre_producto, nombre_cliente, nombre_canal) 
			values ".$insert_str.";";
		//echo $sqlInserta;
		$respInserta=mysql_query($sqlInserta) or die(mysql_error());
		fclose($file);
		
		
		
		
	}else{
		echo "<h3>Error con el tipo de archivo. No es un archivo .zip</h3>";
	}
}

echo "<h3>Hora Fin Cargado Archivo: " . date("Y-m-d H:i:s")."</h3>";
$fechaHoraFin=date("Y-m-d H:i:s");

$sqlModiLog="update datos_logs set fecha_final='$fechaHoraFin', glosa='FinalizadoArchivo'
where id_cargado='$codLogCup'";
$respModiLog=mysql_query($sqlModiLog);

//EMPEZAMOS LA SEGUNDA PARTE CARGADO DE DATOS EN LA TABLA OFICIAL
$sqlMeses="select year(d.fecha), month(d.fecha), count(*) from datosventatmp d group by year(d.fecha), month(d.fecha)";
$respMeses=mysql_query($sqlMeses);
while($datMeses=mysql_fetch_array($respMeses)){
	$anioBorrar=$datMeses[0];
	$mesBorrar=$datMeses[1];
	
	$sqlBorrarOficial="delete from datosventa where year(fecha)='$anioBorrar' and month(fecha)='$mesBorrar'";
	$respBorrarOficial=mysql_query($sqlBorrarOficial);
	
	$sqlInsertTabla="INSERT INTO datosventa(cod_producto, cod_agencia, cod_cliente, cod_vendedor, fecha, cantidad, monto, nombre_linea, nombre_agencia, monto_neto, nombre_vendedor, nombre_producto, nombre_cliente, nombre_canal) 
		SELECT cod_producto, cod_agencia, cod_cliente, cod_vendedor, fecha, cantidad, monto, nombre_linea, nombre_agencia, monto_neto, nombre_vendedor, nombre_producto, nombre_cliente, nombre_canal FROM datosventatmp where year(fecha)='$anioBorrar' and month(fecha)='$mesBorrar'";
	$respInsertTabla=mysql_query($sqlInsertTabla);
}

echo "<h3>Hora Fin Proceso de Archivo: " . date("Y-m-d H:i:s")."</h3>";
$fechaHoraFin=date("Y-m-d H:i:s");

$sqlModiLog="update datos_logs set fecha_final2='$fechaHoraFin', glosa='FinalizadoProceso'
where id_cargado='$codLogCup'";
$respModiLog=mysql_query($sqlModiLog);


//EMPEZAMOS LA TERCERA PARTE CARGADO DE DATOS EN LA TABLA OFICIAL
$sqlTruncate="TRUNCATE TABLE ciudades";
$respTruncate=mysql_query($sqlTruncate);

$sqlTruncate="TRUNCATE TABLE clientes";
$respTruncate=mysql_query($sqlTruncate);

$sqlTruncate="TRUNCATE TABLE productos";
$respTruncate=mysql_query($sqlTruncate);

$sqlTruncate="TRUNCATE TABLE ventas";
$respTruncate=mysql_query($sqlTruncate);

$sqlInsertNew="INSERT INTO ciudades(cod_ciudad, nombre_ciudad) 
				SELECT d.cod_agencia, d.nombre_agencia from datosventa d GROUP BY d.cod_agencia";
$respInsertNew=mysql_query($sqlInsertNew);

$sqlInsertNew="INSERT INTO productos(cod_producto, nombre_producto, linea) 
				SELECT d.cod_producto, d.nombre_producto, d.nombre_linea from datosventa d GROUP BY d.cod_producto";
$respInsertNew=mysql_query($sqlInsertNew);

$sqlInsertNew="INSERT INTO clientes(cod_cliente, nombre_cliente, cod_ciudad) 
				SELECT d.cod_cliente, d.nombre_cliente, d.cod_agencia  from datosventa d GROUP BY d.cod_cliente";
$respInsertNew=mysql_query($sqlInsertNew);

$sqlInsertNew="INSERT INTO ventas(cod_cliente, cod_producto, cod_funcionario, cod_ciudad, fecha_venta, cantidad, monto_venta, canal) 
				SELECT cod_cliente, cod_producto, cod_vendedor, cod_agencia, fecha, cantidad, monto_neto, nombre_canal from datosventa d";
$respInsertNew=mysql_query($sqlInsertNew);


echo "<h3>Hora Fin Copiado de Datos Oficiales: " . date("Y-m-d H:i:s")."</h3>";
$fechaHoraFin=date("Y-m-d H:i:s");

$sqlModiLog="update datos_logs set fecha_final3='$fechaHoraFin', glosa='FinalizadoCopiadoDatosOficial'
where id_cargado='$codLogCup'";
$respModiLog=mysql_query($sqlModiLog);

?>