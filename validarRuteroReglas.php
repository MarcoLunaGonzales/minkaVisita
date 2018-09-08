<?php

 function validaRuteroReglas($codVisitador, $codRutero){
     require("conexion.inc");
     require("funcion_nombres.php");

     $sqlNombreDias="select id, dia_contacto from orden_dias order by 1";
     $respNombreDias=mysql_query($sqlNombreDias);
     $vNombres[]=array();
     $j=1;
     while($datNombreDias=mysql_fetch_array($respNombreDias)) {
         $vNombres[$j]=$datNombreDias[1];
         $j++;
     }

     $banderaProc=0;

     $sqlMedicos="
         select distinct (rd.`cod_med`) from `rutero_maestro_cab` rc, `rutero_maestro` r,
         `rutero_maestro_detalle` rd where rc.`cod_rutero` = r.`cod_rutero` and r.`cod_contacto` = rd.`cod_contacto` and
         rc.`cod_visitador` = $codVisitador and rc.`cod_rutero` = $codRutero";
	 $respMedicos=mysql_query($sqlMedicos);
     echo "<table class='texto'>
     <tr><th colspan='3'>Validacion de Secuencia en Rutero</th></tr>
     <tr><th>Medico</th><th>Contactos</th><th>Dias de Contacto</th></tr>";
     while($datMedicos=mysql_fetch_array($respMedicos)){
         $codMed=$datMedicos[0];
         $sqlDias="
             select o.id from `rutero_maestro_cab` rc, `rutero_maestro` r,
             `rutero_maestro_detalle` rd, `orden_dias` o where rc.`cod_rutero` = r.`cod_rutero` and
             r.`cod_contacto` = rd.`cod_contacto` and rc.`cod_visitador` = $codVisitador and rc.cod_rutero='$codRutero'
             and o.`dia_contacto`=r.`dia_contacto` and  rd.`cod_med`=$codMed and rd.cod_visitador = $codVisitador order by o.id";
         $respDias=mysql_query($sqlDias);

         $numContactos=mysql_num_rows($respDias);

         $vDias[]=array();
         $i=0;
         while($datDias=mysql_fetch_array($respDias)){
             $codDia=$datDias[0];
             $vDias[$i]=$codDia;
             $i++;
         }

         //validacion para 8 contactos
         $bandera=0;
         if($numContactos==8){
             if(($vDias[0]>=1 && $vDias[0]<=5) && ($vDias[1]>=1 && $vDias[1]<=5) && ($vDias[2]>=6 && $vDias[2]<=10) && ($vDias[3]>=6 && $vDias[3]<=10) && ($vDias[4]>=11 && $vDias[4]<=15) && ($vDias[5]>=11 && $vDias[5]<=15) && ($vDias[6]>=16 && $vDias[6]<=20) && ($vDias[7]>=16 && $vDias[7]<=20)){

             }else{
                 $bandera=1;
                 $banderaProc=1;
             }
             for($i=0;$i<=6;$i++){
                 if(($vDias[$i+1]-$vDias[$i]) > 1){
                 }
                 else{
                     $bandera=1;
                     $banderaProc=1;
                 }
             }
             if($bandera==1){
                 $diasContacto=$vNombres[$vDias[0]]." ".$vNombres[$vDias[1]]." ".$vNombres[$vDias[2]]." ".$vNombres[$vDias[3]]." ".$vNombres[$vDias[4]]." ".$vNombres[$vDias[5]]." ".$vNombres[$vDias[6]]." ".$vNombres[$vDias[7]];
                 $nombreMedico=nombreMedico($codMed);
                 echo "<tr><td>$nombreMedico</td><td>$numContactos</td><td>$diasContacto</td></tr>";
             }
             else{
             }
         }
         //fin 8 contactos
         //validacion para 6 contactos
         $bandera=0;
         if($numContactos==6){
             if(($vDias[0]>=1 && $vDias[0]<=5) && ($vDias[1]>=1 && $vDias[1]<=5) && ($vDias[2]>=6 && $vDias[2]<=10) && ($vDias[3]>=11 && $vDias[3]<=15) && ($vDias[4]>=11 && $vDias[4]<=15) && ($vDias[5]>=16 && $vDias[5]<=20)){

             }else{
                 if(($vDias[0]>=1 && $vDias[0]<=5) && ($vDias[1]>=6 && $vDias[1]<=10) && ($vDias[2]>=6 && $vDias[2]<=10) && ($vDias[3]>=11 && $vDias[3]<=15) && ($vDias[4]>=16 && $vDias[4]<=20) && ($vDias[5]>=16 && $vDias[5]<=20)){
                 }else{
                     $xx="semana1";
                     $bandera=1;
                     $banderaProc=1;
                 }
             }
             for($i=0;$i<=4;$i++){
                 if(($vDias[$i+1]-$vDias[$i]) > 1){
                 }
                 else{
                     $bandera=1;
                     $banderaProc=1;
                 }
             }
             if($bandera==1){
                 $diasContacto=$vNombres[$vDias[0]]." ".$vNombres[$vDias[1]]." ".$vNombres[$vDias[2]]." ".$vNombres[$vDias[3]]." ".$vNombres[$vDias[4]]." ".$vNombres[$vDias[5]];
                 $nombreMedico=nombreMedico($codMed);
                 echo "<tr><td>$nombreMedico</td><td>$numContactos</td><td>$diasContacto</td></tr>";
             }
             else{
             }
         }
         //fin 6 contactos

         //validacion para 4 contactos
         $bandera=0;
         if($numContactos==4){
             if(($vDias[0]>=1 && $vDias[0]<=5) && ($vDias[1]>=6 && $vDias[1]<=10) && ($vDias[2]>=11 && $vDias[2]<=15) && ($vDias[3]>=16 && $vDias[3]<=20)){

             }else{
                 $bandera=1;
                 $banderaProc=1;
             }
             for($i=0;$i<=2;$i++){
                 if(($vDias[$i+1]-$vDias[$i]) > 1){
                 }
                 else{
                     $bandera=1;
                     $banderaProc=1;
                 }
             }
             if($bandera==1){
                 $diasContacto=$vNombres[$vDias[0]]." ".$vNombres[$vDias[1]]." ".$vNombres[$vDias[2]]." ".$vNombres[$vDias[3]];
                 $nombreMedico=nombreMedico($codMed);
                 echo "<tr><td>$nombreMedico</td><td>$numContactos</td><td>$diasContacto</td></tr>";
             }
             else{
             }
         }
         //fin 4 contactos
         //validacion para 2 contactos
         $bandera=0;
         if($numContactos==2){
             if(($vDias[0]>=1 && $vDias[0]<=10) && ($vDias[1]>=11 && $vDias[1]<=20)){

             }else{
                 $bandera=1;
                 $banderaProc=1;
             }
             if($bandera==1){
                 $diasContacto=$vNombres[$vDias[0]]." ".$vNombres[$vDias[1]];
                 $nombreMedico=nombreMedico($codMed);
                 echo "<tr><td>$nombreMedico</td><td>$numContactos</td><td>$diasContacto</td></tr>";
             }
             else{
             }
         }
         //fin 2 contactos

         unset($vDias);
     }
     echo "</table>";
     return $banderaProc;
 }

?>