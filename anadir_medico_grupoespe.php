<?php
 require("conexion.inc");
 require("estilos_gerencia.inc");

 $cod_grupo=$_GET['cod_grupo'];

 echo "
<script type='text/javascript' language='javascript'>
function adicionar_medico(f) {
    var i=0,c=0;
    var cadresp,cadAux=\"true\";
    var vecDatos=new Array();
    var vecLinVis=new Array();
    var tag,tagLv;
    var totregs=parseInt(document.getElementById('tmptotalregs').value);
    for(i=1;i<=totregs;i++) {
        tag=document.getElementById('tmpchk'+i+'');
        if(tag!=null) {
            if(tag.type=='checkbox') {
                cadresp=''+tag.checked+'';
                if(cadresp==cadAux) {
                    //tagLv=document.getElementById('tmpcmb'+i+'');
                    vecDatos[c]=parseInt(tag.value);
                    //vecLinVis[c]=parseInt(tagLv.value);
                    c=c+1;
                }
            }
        }
    }
    if(c==0) {
        alert('Debe seleccionar al menos un m&eacute;dico para adicionarlo al Grupo.');
    } else {
        location.href='guardar_medico_grupoespe.php?vecdatos='+vecDatos+'&veclinvis='+vecLinVis+'&cod_grupo=$cod_grupo&codigo_linea=$codigo_linea&cod_ciudad=$cod_ciudad';
    }
}
</script>
";
 echo "<form method='post' action='opciones_medico.php'>";

 //formamos la cabecera
 $sql_cab="select nombre_grupo_especial, cod_muestra, agencia, codigo_linea, codigo_linea1, codigo_linea2, codigo_linea3 from grupo_especial where codigo_grupo_especial='$cod_grupo'";
 $resp_cab=mysql_query($sql_cab);
 //echo "$sql_cab <br>";
 $dat_cab=mysql_fetch_array($resp_cab);
 $nombre_grupo_espe=$dat_cab[0];
 $cod_espe=$dat_cab[1];
 $codCiudad=$dat_cab[2];
 $codLinea1=$dat_cab[3];
 $codLinea2=$dat_cab[4];
 $codLinea3=$dat_cab[5];
 $codLinea4=$dat_cab[6];
 
 //fin formar cabecera
 echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Grupos Especiales<br>Grupo Especial:<strong>$nombre_grupo_espe</strong></td></tr></table></center><br>";
 echo "<center><table border='0' class='textotit'><tr><td>Adicionar M&eacute;dicos al Grupo</td></tr></table></center><br>";
 $sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med
     from medicos m, categorias_lineas c
     where m.cod_ciudad='$codCiudad' and m.cod_med=c.cod_med and 
	 (c.codigo_linea='$codLinea1' or c.codigo_linea='$codLinea2' or c.codigo_linea='$codLinea3' or c.codigo_linea='$codLinea4')
	 order by m.ap_pat_med";
	 
	
//echo "$sql <br>";

 $resp=mysql_query($sql);
 echo "<center><table border='1' class='textomini' cellspacing='0' width='50%'>";
 echo "<tr>";
 echo "<th>&nbsp;</th>";
 echo "<th>RUC</th>";
 echo "<th>Nombre</th>";
 echo "<th>Especialidades</th>";
 //echo "<th>Linea Visita</th>";
 echo "</tr>";
 $cont=0;
 while($dat=mysql_fetch_array($resp)) {
     $cod=$dat[0];
     $pat=$dat[1];
     $mat=$dat[2];
     $nom=$dat[3];
     $cont++;
     //revisamos si el medico ya pertenece al grupo especial
     $sql_auxiliar="select gg.cod_med from grupo_especial_detalle gg,grupo_especial g where g.codigo_grupo_especial=gg.codigo_grupo_especial and 
	 gg.cod_med='$cod' and g.agencia='$codCiudad' and gg.codigo_grupo_especial='$cod_grupo'";
     $resp_auxiliar=mysql_query($sql_auxiliar);
     $num_filas_auxiliar=mysql_num_rows($resp_auxiliar);
     //si existe pondremos una condicional para que no se muestren sus datos
     $nombre_completo="$pat $mat $nom";
     $sql2="select c.cod_especialidad, c.categoria_med
            from especialidades_medicos e, categorias_lineas c
            where c.cod_med=e.cod_med and c.cod_med=$cod and c.cod_especialidad=e.cod_especialidad and 
			(c.codigo_linea='$codLinea1' or c.codigo_linea='$codLinea2' or c.codigo_linea='$codLinea3' or c.codigo_linea='$codLinea4') 
			order by c.categoria_med limit 0,1";
     $resp2=mysql_query($sql2);
     $especialidad="";
     while($dat2=mysql_fetch_array($resp2)) {
         $espe=$dat2[0];
         $cat=$dat2[1];
         $especialidad="$especialidad<br>$espe $cat";
     }
     $especialidad="$especialidad<br><br>";
     //si existe el medico en el grupo o sea $num_filas_auxiliar es 1 entonces no lo mostramos
     if($num_filas_auxiliar==0) {
        
		/*	//---- ---- ---- ----
         $cadLineaVisita="";
         $cadLineaVisita=$cadLineaVisita."<select id='tmpcmb$cont' name='cmblineavisita' class='texto clscmblstvst'>";
         $cadLineaVisita=$cadLineaVisita."<option value='0'>( ninguno )</option>";
         $consulta="SELECT l.codigo_l_visita, l.nombre_l_visita FROM lineas_visita l, lineas_visita_especialidad le WHERE l.codigo_l_visita=le.codigo_l_visita AND le.cod_especialidad='$espe' ORDER BY l.nombre_l_visita ASC ";
         $rs=mysql_query($consulta);
         //echo "-- $consulta --";
         while($reg=mysql_fetch_array($rs)) {
             $codLineaVisita=$reg[0];
             $desLineaVisita=$reg[1];
             $cadLineaVisita=$cadLineaVisita."<option value='$codLineaVisita'>$desLineaVisita</option>";
         }
         $cadLineaVisita=$cadLineaVisita."</select>";
         $cadLineaVisita=$cadLineaVisita."</td>";
         //---- ---- ---- ----
		 */
         echo "<tr>";
         echo "<td align='center'><input id='tmpchk$cont' type='checkbox' name='codigos_ciclos' value=$cod></td>";
         echo "<td align='center'>$cod</td>";
         echo "<td align='left' class='texto'>$nombre_completo</td>";
         echo "<td align='center'>&nbsp;$especialidad</td>";
         //echo "<td>$cadLineaVisita</td>";
         echo "</tr>";
     }
 }
 echo "</table>";
 echo "<input id='tmptotalregs' type='hidden' value='$cont'/>";
 //echo "<input id='tmptotalregs' type='hidden' value='$cont'/>";
 echo "</center><br>";
 echo "<table align='center'>";
 echo "<tr>";
 echo "<td><a href='javascript:history.go(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td>";
 echo "</tr>";
 echo "</table>";
 echo "<center>";
 echo "<table border='0' class='texto'>";
 echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='adicionar_medico(this.form)'></td></tr>";
 echo "</table>";
 echo "</center>";
 echo "</form>";

?>
