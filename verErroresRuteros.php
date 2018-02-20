<?php
require("conexion.inc");
$lineaTrabajo=1005;
$sqlFuncionario="select codigo_funcionario, paterno from funcionarios where cod_cargo=1011 and estado=1";
$respFuncionario=mysql_query($sqlFuncionario);
while($datFuncionario=mysql_fetch_array($respFuncionario)){
		$codVisitador=$datFuncionario[0];
		$paterno=$datFuncionario[1];

		$sqlRutero="select distinct(rd.cod_med), r.cod_rutero from rutero_maestro_cab rc, rutero_maestro r, rutero_maestro_detalle rd
		where rc.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and rc.codigo_linea=$lineaTrabajo
		and rc.cod_visitador=$codVisitador";
		$respRutero=mysql_query($sqlRutero);
		while($datRutero=mysql_fetch_array($respRutero)){
				$codMed=$datRutero[0];
				$codRutero=$datRutero[1];
				//sacar espe y categoria de la linea
				$sqlLinea="select cod_especialidad, categoria_med from categorias_lineas where cod_med=$codMed 
									and codigo_linea=$lineaTrabajo";
				$respLinea=mysql_query($sqlLinea);
				while($datLinea=mysql_fetch_array($respLinea)){
						$codEspeCorregir=$datLinea[0];
						$categoriaCorregir=$datLinea[1];
						
						$sqlUpdate="update rutero_maestro_detalle set cod_especialidad='$codEspeCorregir', 
						categoria_med='$categoriaCorregir' where cod_med=$codMed and cod_contacto in 
						(select cod_contacto from rutero_maestro where cod_rutero=$codRutero)";

						echo $sqlUpdate;

						$respUpdate=mysql_query($sqlUpdate);

				}	
		}
}

?>