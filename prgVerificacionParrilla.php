<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Reporte Verificacion de Parrilla</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
    <link rel="stylesheet" type="text/css" href="stilos.css" />
    <script type="text/javascript" src="jquery-1.4.4.min.js"></script>
</head>
<body>
<?php

 require("conexiondb.inc");

 echo "<center>";
 echo "<table class='textotit'>";
 echo "<tbody><tr><th>Verificacion de parrillas y medicos</th></tr></tbody>";
 echo "</table>";
 echo "<br>";

 echo "<form id='frmrptverpar' method='get'>";
 echo "<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>";
 //gestiones
 echo "<tr><th align='left'>Gesti&#243;n</th>";
 $sql_gestion="SELECT distinct(codigo_gestion), nombre_gestion, estado FROM gestiones ORDER BY nombre_gestion ASC";
 $rs_gestion=$cnnMySql->consultar($sql_gestion);
 echo "<td><select id='codges' name='codges' class='texto' onChange=''>";
 echo "<option value='0'>( ninguno )</option>";
 $nroRegs=$cnnMySql->nroRegsRetornados($rs_gestion);
 if($nroRegs>0)
    {while($reg=$cnnMySql->siguienteRegistro($rs_gestion))
        {$cod_gestion_rpt=$reg[0];
         $nom_gestion_rpt=$reg[1];
         $estado_gestion_rpt=$reg[2];
         echo "<option value='$cod_gestion_rpt'>$nom_gestion_rpt</option>";
        }
    }
 echo "</select></td></tr>";
 //ciclos
 echo "<tr><th align='left'>Ciclo</th>";
 $sql="SELECT distinct(c.cod_ciclo) FROM ciclos c where codigo_gestion=1007 ORDER BY c.cod_ciclo DESC";
 $rs=$cnnMySql->consultar($sql);
 echo "<td><select id='codcic' name='codcic' class='texto'>";
 echo "<option value='0'>( ninguno )</option>";
 $nroRegs=$cnnMySql->nroRegsRetornados($rs);
 if($nroRegs>0)
    {while($reg=$cnnMySql->siguienteRegistro($rs))
        {$codCiclo=$reg[0];
         echo "<option value='$codCiclo'> $codCiclo </option>";
        }
    }
 echo "</td></tr>";
 //territorios
 echo "<tr><th align='left'>Territorio</th>";
 $sql="SELECT c.cod_ciudad, c.descripcion FROM ciudades c, funcionarios_agencias f WHERE
       f.cod_ciudad=c.cod_ciudad and f.codigo_funcionario=$global_usuario ORDER BY c.descripcion ASC";
 $sql="SELECT c.cod_ciudad, c.descripcion FROM ciudades c ORDER BY c.descripcion ASC";
 $rs=$cnnMySql->consultar($sql);
 $nroRegs=$cnnMySql->nroRegsRetornados($rs);
 echo "<td><select id='codter' name='codter' class='texto' size='12' multiple>";
 //echo "<option value='0'>( ninguno )</option>";
 if($nroRegs>0)
    {while($reg=$cnnMySql->siguienteRegistro($rs))
        {$codigo_ciudad=$reg[0];
         $nombre_ciudad=$reg[1];
         echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
        }
    }
 echo "</select></td></tr>";
 //lienas
 echo "<tr><th align='left'>L&#237;nea</th>";
 $sql_linea="SELECT codigo_linea, nombre_linea FROM lineas WHERE linea_promocion=1 and estado=1 ORDER BY nombre_linea ASC";
 $rs=$cnnMySql->consultar($sql_linea);
 echo "<td><select id='codlin' name='codlin' class='texto'>";
 echo "<option value='0'>( ninguno )</option>";
 $nroRegs=$cnnMySql->nroRegsRetornados($rs);
 if($nroRegs>0)
    {while($rs_linea=$cnnMySql->siguienteRegistro($rs))
        {$cod_linea_rpt=$rs_linea[0];
         $nom_linea_rpt=$rs_linea[1];
         echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
        }
    }
 echo "</select></td></tr>";
 //ver
// echo "<tr><th align='left'>Ver:</th>";
// echo "<td><select id='codver' name='codver' class='texto'>";
// echo "<option value='0'>MM</option>";
// echo "<option value='1'>MA</option>";
// echo "</select></td></tr>";
 //
 echo "</table><br>";
 echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='javascript:mostrarRpt();' class='boton'></center><br>";

 echo "</form>";

 echo "</center>";

?>
<script type="text/javascript" language="Javascript">
  function mostrarRpt(){
    cg=cc=ct=cl=cv=0; sel="form#frmrptverpar";
    cg=$(sel+" #codges").val();
    cc=$(sel+" #codcic").val();
    ct=$(sel+" #codter").val();
    cl=$(sel+" #codlin").val();
    //cv=$(sel+" #codver").val();
    if(cg<=0 || cc<=0 || ct==null || cl<=0) {
      alert("Seleccione todos los campos, son obligatorios.");
    }else{
      par="codges="+cg+"&codcic="+cc+"&codter="+ct+"&codlin="+cl;//+"&codver="+cv;
      window.open('prgRptVerificacionParrilla.php?'+par+'&rnd='+(Math.random()*999999999999999999),'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
    }
  }
</script>
</body>
</html>
