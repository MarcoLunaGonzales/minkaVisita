<?php
ini_set('memory_limit','512M');

require("conexion.inc");

$sqlProductos="select concat(p.id_raiz,p.id_concentracion,p.id_presentaciones,p.id_formaf)codconjuncion, p.presentacion, m.id_mercado, 
p.id_laboratorio, (select l.id from cup_laboratorio l where l.abreviatura=p.id_laboratorio)as idlaboratorio from 
cup_presentacion p, cup_mercados_productos m, cup_mercado me 
where m.id_raiz=p.id_raiz and p.id_concentracion=m.id_concentracion and 
p.id_presentaciones=m.id_presentacion and p.id_formaf=m.id_formaf and me.id_mercado=m.id_mercado order by 2 asc";
$respProductos=mysql_query($sqlProductos);
$indiceProducto=1;
$insert_str="";
while($datProductos=mysql_fetch_array($respProductos)){
	$codConjuncion=$datProductos[0];
	$nombreProducto=$datProductos[1];
	$nombreProducto=str_replace("'","",$nombreProducto);
	$idMercado=$datProductos[2];
	$idlaboratorio=$datProductos[4];
		
	$idProducto=$indiceProducto."".$idMercado;
	
	$insert_str .= "('$idProducto','$nombreProducto','$idMercado','$idlaboratorio','9999','$codConjuncion'),";
	if($indiceProducto%10000==0){
		$insert_str = substr_replace($insert_str, '', -1, 1);
		$sqlInserta="insert into cup_productos (id, nombre, Mercado_id, Laboratorio_id, Molecula_id, id_auxiliar) 
			values ".$insert_str;
		//echo $sqlInserta;
		$respInserta=mysql_query($sqlInserta);
		$insert_str="";
	}
	$indiceProducto++;	
}
$insert_str = substr_replace($insert_str, '', -1, 1);
$sqlInserta="insert into cup_productos (id, nombre, Mercado_id, Laboratorio_id, Molecula_id, id_auxiliar) 
	values ".$insert_str;
$respInserta=mysql_query($sqlInserta);


$sqlPxs="select concat(d.id_raiz,d.id_concentracion,d.id_presentacion,d.id_formaf)idConjuncionado, 
d.mes, m.id_mercado, d.id_medico, sum(d.cant_pxs1), 
d.id_raiz, d.id_concentracion, d.id_presentacion, d.id_formaf 
from cup_datos d, cup_mercados_productos m, cup_mercado me where me.id_mercado=m.id_mercado 
and d.id_raiz=m.id_raiz and d.id_concentracion=m.id_concentracion and 
d.id_presentacion=m.id_presentacion 
and d.id_formaf=m.id_formaf
group by d.id_raiz, d.id_presentacion, d.id_formaf, 
d.id_concentracion, d.mes, m.id_mercado, d.id_medico having sum(d.cant_pxs1)>0 order by d.mes";
$respPxs=mysql_query($sqlPxs);
$indice=1;
$insert_str="";

while($datPxs=mysql_fetch_array($respPxs) && $indice<=4000){
	$idConjuncionado=$datPxs[0];
	$fechaPx=$datPxs[1];
	list($anioPx,$mesPx)=explode("/",$fechaPx);
	$fechaPx=$anioPx."-".$mesPx."-01";
	$idMercado=$datPxs[2];
	$idMedico=$datPxs[3];
	$cantidadPxs=$datPxs[4];
	
	$sqlCodProd="select id from cup_productos where id_auxiliar='$idConjuncionado'";
	$respCodProd=mysql_query($sqlCodProd);
	$numFilasResp=mysql_num_rows($respCodProd);
	$idProducto=0;
	if($numFilasResp>0){
		$idProducto=mysql_result($respCodProd,0,0);
	}
	
	$insert_str .= "('$indice','0','$cantidadPxs','$fechaPx','9999','$idProducto','$idMercado','$idMedico'),";
	if($indice%2000==0){
		$insert_str = substr_replace($insert_str, '', -1, 1);
		$sqlInserta="insert into cup_pxs (id, categoria, cantidad, fecha, Producto_id, mercado_id, cod_closeup) 
			values ".$insert_str;
		//echo $sqlInserta;
		$respInserta=mysql_query($sqlInserta);
		$insert_str="";
	}
	$indice++;	
}
$insert_str = substr_replace($insert_str, '', -1, 1);
$sqlInserta="insert into cup_pxs (id, categoria, cantidad, fecha, Producto_id, mercado_id, cod_closeup) 
	values ".$insert_str;
$respInserta=mysql_query($sqlInserta);

?>