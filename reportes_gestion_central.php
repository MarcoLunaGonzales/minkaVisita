<?php
require("conexion.inc");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
	echo "<br>";
	echo "<center><table border='0' class='textotit'><tr><th>Reportes Módulo de Gestión Estrategica</th></tr></table></center><br>";
}
else
{	require("estilos_inicio_adm.inc");
	echo "<center><table border='0' class='textotit'><tr><th>Reportes Módulo de Gestión Central</th></tr></table></center><br>";
}
echo "<center><table border='1' class='texto' cellspacing='0' width='100%'>";
//echo "<tr><td><ul><li><a href='rpt_op_central_grilla.php'>Grillas</a></ul></li></td></tr>";
//<li><a href='rpt_op_central_prod_med.php'>Productos Objetivos y Filtrados</a></li>

echo "<tr><td><ul><li>Medicos</li><br>
			<ul>
			<li><a href='rpt_op_central_frecuencia_visita.php'>Frecuencias de Visita</a></li>
			<li><a href='rpt_op_central_grupos_especiales.php'>Grupos Especiales</a></li>
			<li><a href='rpt_op_central_medicos_base_general.php'>Medicos listado general</a></li>
			<li><a href='rpt_op_central_medicos_linea.php'>Medicos x Línea</a></li>
			<li><a href='rpt_op_central_cant_med_territorio.php'>Cantidad de Medicos por Territorio</a></li>
			</ul>
			</ul>
	  </td>";
echo "<td><ul><li>Parrillas</li><br>
			<ul>
			<li><a href='rpt_op_parrilla_central.php'>Parrillas de productos</a></li>
			<li><a href='rpt_op_parrilla_centralresumen.php'>Parrillas de productos Resumido</a></li>
			<li><a href='rpt_op_parrilla_productoespecialidad.php'>Productos de parrilla por Especialidad</a></li>
			<li><a href='rpt_op_parrilla_materialespecialidad.php'>Material de Apoyo por Especialidad</a></li>
			</ul></ul></td>";
echo "<td><ul><li>Productos</li><br>
			<ul>
			<li><a href='rpt_op_central_prod_lineas_visita.php'>x Líneas de Visita</a></li>
			<li><a href='rpt_op_central_prod_especialidad.php'>x Especialidad</a></li>
			<li>x Visitador</li>
			<ul>
			<li><a href='rpt_op_central_prod_visitador.php'>Cantidad de muestras planificadas</a></li>
			<li><a href='rpt_op_central_prod_ent_visitador.php'>Cantidad de muestras entregadas</a></li>
			<li><a href='rpt_op_central_saldo_muestras_visitador.php'>Saldo muestras a fin de ciclo</a></li>
			</ul>
			</ul>
			</ul>
	  </td>";
echo "<td><ul><li>Visitadores</li><br>
			<ul>
			<li><a href='rpt_op_central_cobertura.php'>Cobertura</a></li>
			<li><a href='rpt_op_central_contactos_rezagados.php'>Contactos Rezagados</a></li>
			<li><a href='rpt_op_central_maestro_vs_ejecutado.php'>Rutero Maestro Vs. Ejecutado</a></li>
			<li><a href='rpt_op_central_memorando_llamadaatencion.php'>Memorando de Llamada de Atención</a></li>
			<li><a href='rpt_op_central_detallemedicos.php'>Detalle de Medicos en Rutero Maestro</a></li>
			</ul>
			</ul>
	  </td>";
echo "<td><ul><li>Distribución de Material Promocional</li><br>
			<ul>
			<li><a href='rpt_op_central_distribucion.php'>Distribución x Ciclo</a></li>
			<li><a href='rpt_op_central_distribucionfaltante.php'>Material Promocional No Distribuido</a></li>
			<li><a href='rpt_op_central_distribucionfaltanteenvio.php'>Material Promocional Faltante de envío x Ciclo.</a></li>
			</ul>
			</ul>
	  </td>";
echo "</table>";
echo "<br>";
if($global_usuario==1052)
{	//require('home_central.inc');
}
else
{	require('home_central.inc');
}
?>