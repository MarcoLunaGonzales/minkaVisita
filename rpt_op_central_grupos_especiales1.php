<script type='text/javascript' language='Javascript'>
    function envia_select(form) {
        form.submit();
        return(true);
    }
    function envia_formulario(f) {
        var rpt_territorio=f.rpt_territorio.value;
        window.open('rpt_central_grupos_especiales1.php?rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
        return(true);
    }
</script>
<?php
 require("conexion.inc");
 require("estilos_cuerpo.inc");

 $rpt_territorio=$_GET["rpt_territorio"];

 echo "<center>";
 echo "<table class='textotit'><tr><th>M&#233;dicos en Grupos Especiales</th></tr></table><br>";
 echo "<form metodh='GET'>";
 echo "<table class='texto' border='1' align='center' cellSpacing='0'>";

// echo "<tr><th align='left'>L&#237;nea</th>";
// $sql_linea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 order by nombre_linea";
// $resp_linea=mysql_query($sql_linea);
// echo "<td><select name='linea_rpt' class='texto' onChange='envia_select(this.form)'>";
// $bandera=0;
// while($datos_linea=mysql_fetch_array($resp_linea))
//    {$cod_linea_rpt=$datos_linea[0];$nom_linea_rpt=$datos_linea[1];
//     if($linea_rpt==$cod_linea_rpt)
//        {echo "<option value='$cod_linea_rpt' selected>$nom_linea_rpt</option>";
//        }
//     else
//        {echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
//        }
//    }
// echo "</select></td>";

 //echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
 $consultaSqlql="SELECT cod_ciudad, descripcion FROM ciudades ORDER BY descripcion ASC";
 $rs=mysql_query($consultaSqlql);
 echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto'>";
 while($reg=mysql_fetch_array($rs))
    {$codigo_ciudad=$reg[0];
     $nombre_ciudad=$reg[1];
     if($rpt_territorio==$codigo_ciudad)
        {echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
        }
     else
        {echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
        }
    }
 echo "</select></td></tr>";

// echo "<tr><th align='left'>Grupo Especial</th><td><select name='grupo_especial' class='texto' size='10' multiple>";
// $sql_grupos="
//     select codigo_grupo_especial, nombre_grupo_especial from grupo_especial
//     where agencia='$rpt_territorio' and codigo_linea='$linea_rpt' order by nombre_grupo_especial";
// $resp_grupos=mysql_query($sql_grupos);
// while($dat_grupos=mysql_fetch_array($resp_grupos))
//    {$codigo_grupo=$dat_grupos[0];
//     $nombre_grupo=$dat_grupos[1];
//     echo "<option value='$codigo_grupo'>$nombre_grupo</option>";
//    }
// echo "</select>";
// echo "</td></tr>";

 echo "</table><br>";
 if($global_usuario==1032)
    {require('home_gerencia.inc');
    }
 else
    {require('home_central.inc');
    }
 echo "<input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>";
 echo "</form>";
 echo "</center>";
?>
