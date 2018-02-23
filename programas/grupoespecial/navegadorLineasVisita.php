<html>
    <head>
        <title>Lineas de visita</title>
        <link type="text/css" rel="stylesheet" href="../../stilos.css">
        <script type='text/javascript' language='javascript'>
            function adiLineaVisita(codGrupo,codLinea,codCiudad)
            {   location.href='lineaVisitaAdicionar.php?cod_grupo='+codGrupo+'&codigo_linea='+codLinea+'&cod_ciudad='+codCiudad+'';
            }
            function modLineaVisita(codGrupo,codLinea,codCiudad)
            {   var i;
                var tag=document.getElementById("nroregs");
                var codlinvis,c=0,nroregs=tag.value;
                for(i=1;i<=nroregs;i++) {
                    tag=document.getElementById("codlinvis"+i);
                    if(tag.checked==true) {
                        c++; codlinvis=tag.value;
                    }
                }
                if(c>1)
                {   alert('Debe seleccionar solamente un Grupo Especial para editar sus datos.');
                }
                else
                {   if(c==0)
                    {   alert('Debe seleccionar un Grupo Especial para editar sus datos.');
                    }
                    else
                    {   location.href='lineaVisitaEditar.php?cod_grupo='+codGrupo+'&codigo_linea='+codLinea+'&cod_ciudad='+codCiudad+'&codlinvis='+codlinvis+'';
                    }
                }
            }
            function eliLineaVisita(codGrupo,codLinea,codCiudad)
            {   var i;
                var tag=document.getElementById("nroregs");
                var lstcodlinvis="0",c=0,nroregs=tag.value;
                for(i=1;i<=nroregs;i++) {
                    tag=document.getElementById("codlinvis"+i);
                    if(tag.checked==true) {
                        c++; lstcodlinvis=lstcodlinvis+","+tag.value;
                    }
                }
                if(c==0)
                {   alert('Debe seleccionar al menos un Grupo Especial para proceder a su eliminacion.');
                }
                else
                {   if(confirm('Esta seguro de eliminar los datos.'))
                    {   location.href='lineaVisitaEliminar.php?cod_grupo='+codGrupo+'&codigo_linea='+codLinea+'&cod_ciudad='+codCiudad+'&lstcodlinvis='+lstcodlinvis+'';
                    }
                    else
                    {   return(false);
                    }
                }
            }
        </script>
    </head>
    <body background="../../imagenes/fondo_pagina.jpg" >
    <center>
        <?php
        require("../../conexion.inc");

        $cod_grupo = $_GET["cod_grupo"];
        $codigo_linea = $_GET["codigo_linea"];
        $cod_ciudad = $_GET["cod_ciudad"];

        $consulta = "
    SELECT gelv.cod_l_visita, lv.nombre_l_visita FROM grupoespecial_lineavisita AS gelv, lineas_visita AS lv WHERE gelv.cod_l_visita = lv.codigo_l_visita AND lv.codigo_linea = $codigo_linea
    AND gelv.cod_grupo = $cod_grupo ORDER BY lv.nombre_l_visita ASC ";
        $rs1 = mysql_query($consulta);
        echo "<br>";
        echo "<div class='textotit'><b>Lineas visita</b></div><br>";
        $contador = 0;
        echo "<table border='1' class='texto' cellspacing='0' >"; //width='70%'
        echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Lineas de Visita</th><th>Visitadores</th></tr>"; //<th>&nbsp;</th>
        while ($reg = mysql_fetch_array($rs1)) {
            $contador++;
            $codLineaVisita = $reg[0];
            $nomLineaVisita = $reg[1]; //$nomLineaVisita = str_replace("   ", " ", str_replace("  ", " ", trim($nomLineaVisita)));
            $sql_funcionarios = mysql_query("select concat(f.paterno,' ',f.materno,' ',f.nombres) from lineas_visita_visitadores a, funcionarios f where a.codigo_l_visita = $codLineaVisita  and f.codigo_funcionario = a.codigo_funcionario and f.cod_ciudad = $cod_ciudad");
            $num = mysql_num_rows($sql_funcionarios);
            if ($num == 0) {
                $funcionarios = "---";
            } else {
                while ($row_funcionarios = mysql_fetch_array($sql_funcionarios)) {
                    $funcionarios .= $row_funcionarios[0] . "<br />";
                }
            }
            echo "<tr>";
            echo "<td align='right'>$contador</td>";
            echo "<td align='center'><input type='checkbox' id='codlinvis$contador' value='$codLineaVisita'></td>";
            echo "<td align='left'>$nomLineaVisita</td>";
            echo "<td align='left'>$funcionarios</td>";
            echo "</tr>";
            $funcionarios = "";
        }
        echo "</table><br>";
        echo "<input type='hidden' id='nroregs' value='$contador'>";

        echo "<table border='0' class='texto'>";
        echo "<tr>";
        echo "<td><a href='javascript:history.back(1);'><img  border='0'src='../../imagenes/volver.gif' width='15' height='8'></a></td>";
        echo "</tr>";
        echo "</table>";

        echo "<table border='0' class='texto'>";
        echo "<tr>";
        echo "<td><input type='button' value='Adicionar' class='boton' onclick='javascript:adiLineaVisita($cod_grupo,$codigo_linea,$cod_ciudad)'></td>";
//echo "<td><input type='button' value='Editar' class='boton' onclick='javascript:modLineaVisita($cod_grupo,$codigo_linea,$cod_ciudad)'></td>";
        echo "<td><input type='button' value='Eliminar' class='boton' onclick='javascript:eliLineaVisita($cod_grupo,$codigo_linea,$cod_ciudad)'></td>";
        echo "</tr>";
        echo "</table>";
        ?>
    </center>
</body>
</html>
