<?php

 require("conexion.inc");
 require("estilos_administracion.inc");

 echo "
<script type='text/javascript' language='Javascript'>
function enviar_registro()
   {location.href='registro_medico.php?cod_ciudad=$cod_ciudad&rnd='+(Math.random()*999999999999999999);
   }
function eliminar_medico(f)
   {var i;
    var j=0;
    datos=new Array();
    for(i=0;i<=f.length-1;i++)
       {if(f.elements[i].type=='checkbox')
           {if(f.elements[i].checked==true)
               {datos[j]=f.elements[i].value;
                j=j+1;
               }
           }
       }
    if(j==0)
       {alert('Debe seleccionar al menos un medico para proceder a su eliminaci�n.');
       }
    else
       {	if(confirm('Esta seguro de realizar la transaccion de eliminacion.')){
				location.href='eliminar_medico.php?datos='+datos+'';
			}
       }
   }
function editar_medico(f)
   {var i;
    var j=0;
    var j_cod_med;
    for(i=0;i<=f.length-1;i++)
       {if(f.elements[i].type=='checkbox')
           {if(f.elements[i].checked==true)
               {j_cod_med=f.elements[i].value;
                j=j+1;
               }
           }
       }
    if(j>1)
       {alert('Debe seleccionar solamente un m�dico para editar sus datos.');
       }
    else
       {if(j==0)
           {alert('Debe seleccionar un m�dico para editar sus datos.');
           }
        else
           {location.href='editar_medico2.php?j_cod_med='+j_cod_med+'&rnd='+(Math.random()*999999999999999999);
           }
       }
   }
</script>
 ";
 echo "<form method='post' action='opciones_medico.php'>";
 $tipo_busqueda=$_POST['tipo_busqueda'];
 $parametro=$_POST['parametro'];
 //$cod_ciudad=$_POST['cod_ciudad'];
 $estadoMedico=$_GET['estado'];
 if($estadoMedico==1){
	$estadoMedico="1,2,3";
 }
 
 if($estadoMedico==""){
	$estadoMedico=1;
 }
 
 $sql="select * from medicos where cod_ciudad='$cod_ciudad' and estado_registro in ($estadoMedico) order by ap_pat_med, ap_mat_med, nom_med";
 if($parametro!='')
    {if($tipo_busqueda==1)
        {$sql="select * from medicos where cod_ciudad='$cod_ciudad' and cod_med='$parametro' and estado_registro in ($estadoMedico) order by ap_pat_med, ap_mat_med, nom_med";
        }
     if($tipo_busqueda==2)
        {$sql="select * from medicos where cod_ciudad='$cod_ciudad' and ap_pat_med like '$parametro%' and estado_registro in ($estadoMedico) order by ap_pat_med, ap_mat_med";
        }
     if($tipo_busqueda==3)
        {$sql="select * from medicos where cod_ciudad='$cod_ciudad' and ap_mat_med like '$parametro%' and estado_registro in ($estadoMedico) order by ap_pat_med, ap_mat_med";
        }
     if($tipo_busqueda==4)
        {$sql="select * from medicos m, especialidades_medicos e where m.cod_med=e.cod_med and m.cod_ciudad='$cod_ciudad' and
                 e.cod_especialidad='$parametro' and estado_registro in ($estadoMedico) order by m.ap_pat_med, m.ap_mat_med";
        }
    }
//echo $sql;
 $resp=mysql_query($sql);
 echo "<center><table border='0' class='textotit'><tr><td>M&#233;dicos Listado Madre</td></tr></table></center><br>";
 echo "<table align='center'><tr><td><a href='navegador_medicos1.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
 echo "<center><table border='0' class='texto'>";
 echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_registro()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_medico(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_medico(this.form)'></td></tr></table></center>";

 echo "<center><table border='1' class='textosupermini' cellspacing='0' width='100%'>";
 echo "<tr><td>&nbsp;</td><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidades</th><th>Direcciones</th><th>Estado</th>
 <th>L&#237;neas / Visitadores</th><th>Categorizacion</th></tr>";
 $indice_tabla=1;
 while($dat=mysql_fetch_array($resp))
    {$cod=$dat[0];
     $pat=$dat[1];
     $mat=$dat[2];
     $nom=$dat[3];
     $fecha_nac=$dat[4];
     $telf=$dat[5];
     $cel=$dat[6];
     $email=$dat[7];
     $hobbie =$dat[8];
     $hobbie2=$dat[9];
     $est_civil=$dat[10];
     $secre=$dat[11];
     $perfil=$dat[12];
     $estado_registro=$dat[16];
     $nombre_completo="$pat $mat $nom";
     $sql1="select direccion from direcciones_medicos where cod_med=$cod order by direccion asc";
     $resp1=mysql_query($sql1);
     
     /* Inicio.. para el estado del registro de medicos  */
     $sql_estado =  mysql_query("Select estado from estado_medico_registro where id = $estado_registro ");
     while($row_estado = mysql_fetch_assoc($sql_estado)){
         $estado_registro_medico = $row_estado['estado'];
     }
     
     /* Fin.. para el estado del registro de medicos  */
     
     $direccion_medico="<table border=0 class='textosupermini' width='100%'>";
     while($dat1=mysql_fetch_array($resp1))
        {$dir=$dat1[0];
         $direccion_medico="$direccion_medico<tr><td align='left'>$dir</td></tr>";
        }
     $direccion_medico="$direccion_medico</table>";
     $sql2="select cod_especialidad from especialidades_medicos where cod_med=$cod order by cod_especialidad asc";
     $resp2=mysql_query($sql2);
     $especialidad="<table border=0 class='textomini' width='50%'>";
     while($dat2=mysql_fetch_array($resp2))
        {$espe=$dat2[0];
         $especialidad="$especialidad<tr><td align='left'>$espe</td></tr>";
        }
     $especialidad="$especialidad</table>";
     $sql_auxiliar="select * from categorias_lineas where cod_med='$cod'";
     $resp_auxiliar=mysql_query($sql_auxiliar);
     $registrado_en_linea=mysql_num_rows($resp_auxiliar);
     if($registrado_en_linea==0)
        {$color_reg="";
        }
     else
        {	$color_reg="#00ffff";
        }
     
	 $lineas_medico="select distinct(l.nombre_linea), c.cod_especialidad, c.categoria_med, l.codigo_linea from lineas l, 
	 categorias_lineas c where c.cod_med='$cod' and l.codigo_linea=c.codigo_linea and l.estado=1";
     $resp_lineas=mysql_query($lineas_medico);
     $cad_lineas="<table class='textosupermini' border='0'>";
     while($dat_lineas=mysql_fetch_array($resp_lineas)){
		$nombre_linea="$dat_lineas[0] $dat_lineas[1] $dat_lineas[2]";
		$codLineaAsig=$dat_lineas[3];
		
		$sqlMedAsignado="select f.paterno, f.materno, f.nombres from funcionarios f, medico_asignado_visitador m where
                                     f.codigo_funcionario=m.codigo_visitador and m.cod_med=$cod and f.estado=1 and m.codigo_linea='$codLineaAsig'";
		$respMedAsignado=mysql_query($sqlMedAsignado);
		$visitadoresAsignados="";
		while($datMedAsignado=mysql_fetch_array($respMedAsignado)){
			$visitadoresAsignados=$visitadoresAsignados."- $datMedAsignado[0] $datMedAsignado[2]";
		}
		$cad_lineas="$cad_lineas <tr><td>$nombre_linea</td><td>$visitadoresAsignados</td></tr>";
    }
    $cad_lineas="$cad_lineas</table>";
	 

     //
     $consulta = "select nombre_perfil_psicografico from perfil_psicografico where cod_perfil_psicografico=$perfil";
     $rs = mysql_query($consulta);
     if (mysql_num_rows($rs)==1) {
         $reg = mysql_fetch_array($rs);
         $perfil = $reg[0];
     } else {
         $perfil = "&nbsp;";
     }
     //
     $consulta = "SELECT e.nombre_estadocivil FROM estado_civil e WHERE e.cod_estadocivil=$est_civil";
     $rs = mysql_query($consulta);
     if (mysql_num_rows($rs)==1) {
         $reg = mysql_fetch_array($rs);
         $est_civil = $reg[0];
     } else {
         $est_civil = "&nbsp;";
     }
     //
     echo "<tr bgcolor='$color_reg'>
         <td align='center'>$indice_tabla</td>
         <td align='center'><input type='checkbox' name='codigos_ciclos' value='$cod'></td>
         <td align='center'>$cod</td>
         <td align='left' class='textomini'>$nombre_completo</td>
         <td align='center'>&nbsp;$especialidad</td>
         <td align='center'>&nbsp;$direccion_medico</td>
		 <td align='center'><span style='font-size: 15px;'>$estado_registro_medico</span></td>
         <td align='center'>$cad_lineas</td>
         <td align='center'><a href='formulario_medico.php?cod_medico=$cod'>Categorizacion</td>
        
         </tr>";
     $indice_tabla++;
    }
 echo "</table></center><br>";
 echo "<table align='center'><tr><td><a href='navegador_medicos1.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
 echo "<center><table border='0' class='texto'>";
 echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_registro()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_medico(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_medico(this.form)'></td></tr></table></center>";
 echo "</form>";

?>
