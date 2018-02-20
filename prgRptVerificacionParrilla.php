<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Reporte Verificacion de Parrilla</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
    <link href="stilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php

 require("conexiondb.inc");

 $codges=$_GET["codges"];
 $codcic=$_GET["codcic"];
 $codter=$_GET["codter"];
 $codlin=$_GET["codlin"];
// $codver=$_GET["codver"];

 $vecCodTer= explode(",", $codter);

// $totTer=sizeof($vecCodTer);
// $criterioTer="0";
// for($i=0;$i<$totTer;$i++)
//    {$criterioTer=$criterioTer.",".$vecCodTer[$i];
//    }

 $criterioTer=$codter;

 $sql="SELECT c.cod_ciudad, c.descripcion FROM ciudades c, funcionarios_agencias f WHERE
       f.cod_ciudad=c.cod_ciudad and f.codigo_funcionario=$global_usuario ORDER BY c.descripcion ASC";
 $sql="SELECT c.cod_ciudad, c.descripcion FROM ciudades c WHERE c.cod_ciudad IN ($criterioTer) ORDER BY c.descripcion ASC";
 $rs=$cnnMySql->consultar($sql);
 $nroRegs=$cnnMySql->nroRegsRetornados($rs);
 if($nroRegs>0)
    {$i=0;
     while($reg=$cnnMySql->siguienteRegistro($rs))
        {$codTer=$reg[0]; $vecCodTer[$i]=$codTer;
         $codDes=$reg[1]; $vecNomTer[$i]=$codDes;
         $i++;
        }
    }
 $totTer=$nroRegs;

// echo "<table class='linea' border='5'>";
// echo "<tbody>";
// echo "<tr><td>GES</td><td>_".$codges."_</td></tr>";
// echo "<tr><td>CIC</td><td>_".$codcic."_</td></tr>";
// echo "<tr><td>TER</td><td>_".$cadTer."_</td></tr>";
// echo "<tr><td>LIN</td><td>_".$codlin."_</td></tr>";
// echo "<tr><td>VER</td><td>_".$codver."_</td></tr>";
// echo "</tbody>";
// echo "</table>";
// echo "<br>";

 echo "<center>";
 echo "<table class='linea' border='0' width='100%'>";
 echo "<tbody><tr><td align='left'><img src='imagenes/logocofar.png'></td></tr></tbody>";
 echo "</table>";
 //echo "<br>";

 $consulta="SELECT distinct(g.nombre_gestion) FROM gestiones g WHERE codigo_gestion=$codges";
 $rs=$cnnMySql->consultar($consulta);
 $nroRegs=$cnnMySql->nroRegsRetornados($rs);
 $nomGes="";
 if($nroRegs>0)
    {$reg=$cnnMySql->siguienteRegistro($rs);
     $nomGes=$reg[0];
    }
 $consulta="SELECT nombre_linea FROM lineas WHERE linea_promocion=1 and estado=1 and codigo_linea=$codlin";
 $rs=$cnnMySql->consultar($consulta);
 $nroRegs=$cnnMySql->nroRegsRetornados($rs);
 $nomLin="";
 if($nroRegs>0)
    {$reg=$cnnMySql->siguienteRegistro($rs);
     $nomLin=$reg[0];
    }
 echo "<div class='textotit'>";
 echo "<b>Verificacion de parrillas y medicos<br>";
 echo "Gesti&#243;n:</b> $nomGes <b>Ciclo:</b> $codcic<br>";
 echo "<b>Linea:</b> $nomLin";
 echo "</div><br>";

 echo "<table class='textomini' border='1' cellspacing='0'>";
 //CABECERA
 echo "<tr>";
 echo "<th rowspan='2'>Esp.</th><th rowspan='2'>Cat.</th>";
 $cadSubCols="";
 for($i=0;$i<$totTer;$i++)
    {echo "<th colspan='3'>$vecNomTer[$i]</th>";
     $cadSubCols=$cadSubCols."<th>N&#176;<br>Meds.</th><th>N&#176;<br>Parrs.</th><th>Cont.<br>Grilla</th>";
    }
 echo "</tr>";
 echo "<tr>$cadSubCols</tr>";
 //CUERPO
 //$consulta="SELECT DISTINCT cl.cod_especialidad,cl.categoria_med FROM categorias_lineas AS cl WHERE cl.codigo_linea = $codlin ORDER BY cl.cod_especialidad,cl.categoria_med ASC";
 $consulta="SELECT e.cod_especialidad,e.desc_especialidad,c.categoria_med FROM especialidades e, categorias_medicos c WHERE c.categoria_med<>'D' ORDER BY e.cod_especialidad,c.categoria_med ASC";
 $rs1=$cnnMySql->consultar($consulta);
 $nroRegs=$cnnMySql->nroRegsRetornados($rs1);
 $cont=0;
 if($nroRegs>0)
    {while($reg1=$cnnMySql->siguienteRegistro($rs1))
        {$codEsp  =$reg1[0];  if($codEsp=="") $codEsp="-";
         $categoria=$reg1[2]; if($categoria=="") $categoria="-";
         $cont++;
         echo "<tr>";
         echo "<td>$codEsp</td><td>$categoria</td>";
         for($i=0;$i<$totTer;$i++)
            {$codTer=$vecCodTer[$i];
             //
             //CALCULAMOS numeroMedicos
             $numeroMedicos=0;
             $consulta="
                 SELECT count(distinct(rd.cod_med)) FROM rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
                 WHERE rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion=$codges and rc.codigo_ciclo=$codcic
                 and rc.codigo_linea=$codlin and
                 rc.cod_visitador in
                     (SELECT codigo_funcionario FROM funcionarios WHERE cod_ciudad=$codTer) and
                 rd.cod_especialidad='$codEsp' and rd.categoria_med='$categoria' ";
             $rs2=$cnnMySql->consultar($consulta);
             $nroRegs=$cnnMySql->nroRegsRetornados($rs2);
             if($nroRegs>0)
                {$reg2=$cnnMySql->siguienteRegistro($rs2);
                 $numeroMedicos=$reg2[0];
                }
             //
             //CALCULAMOS numeroParrillas
             $numeroParrillas=0;
             $consulta="
                 SELECT count(*) FROM parrilla p WHERE
                 p.codigo_gestion=$codges and p.cod_ciclo=$codcic and p.codigo_linea=$codlin and p.agencia=$codTer and p.cod_especialidad='$codEsp' and
                 p.categoria_med='$categoria' ";
             $rs2=$cnnMySql->consultar($consulta);
             $nroRegs=$cnnMySql->nroRegsRetornados($rs2);
             if($nroRegs>0)
                {$reg2=$cnnMySql->siguienteRegistro($rs2);
                 $numeroParrillas=$reg2[0];
                }
             //
             //CALCULAMOS contactosGrilla
             $contactosGrilla=0;
             $consulta="
                 SELECT gd.frecuencia FROM grilla g, grilla_detalle gd WHERE g.codigo_grilla=gd.codigo_grilla and g.codigo_linea=$codlin AND
                 g.agencia=$codTer and gd.cod_especialidad='$codEsp' and gd.cod_categoria='$categoria' ";
             $rs2=$cnnMySql->consultar($consulta);
             $nroRegs=$cnnMySql->nroRegsRetornados($rs2);
             if($nroRegs>0)
                {$reg2=$cnnMySql->siguienteRegistro($rs2);
                 $contactosGrilla=$reg2[0];
                }
             //
             //desplegamos los resultados
             $fondo="";
             if($numeroParrillas!=$contactosGrilla)
                {$fondo1=" style='background: #ffea5c;'";
                 $fondo2=" style='background: #fff089;'";
                 $fondo3=" style='background: #fff4ab;'";
                }
             else
                {$fondo1=" style='background: #eaeaea;'";
                 $fondo2=" style='background: #f5f5f5;'";
                 $fondo3=" style='background: #fdfdfd;'";
                }
             echo "<td$fondo1>$numeroMedicos</td>";
             echo "<td$fondo2>$numeroParrillas</td>";
             echo "<td$fondo3>$contactosGrilla</td>";
            }
         echo "</tr>";
        }
    }
 echo "</table>";
 echo "</center>";
 //echo "<br>N&#250;mero de registros retornados: $cont.<br>";

?>
<script type="text/javascript" language="Javascript">
</script>
</body>
</html>
