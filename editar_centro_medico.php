<?php
$codigo = $_REQUEST["cod"];
?>
<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="lib/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="lib/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="ajax/centro_medico/send.data.js"></script>
<script type="text/javascript" language="Javascript">
    $(document).ready(function(){
        $("#guardar").click(function(){
            var flag = 0;
            if($("#name").val() == ''){
                alert("Debe ingresar un nombre para el centro medico")
                flag = 0
            }else{
                flag=1;
            }
            if($("#direccion").val() == ''){
                alert("Debe ingresar una direccion para el centro medico")
                flag = 0;
            }else{
                flag = 1;
            }
            editar(<?php echo $codigo ?>);
        })
       
    })
</script>
<?php
require("estilos_administracion.inc");
require("conexion.inc");

$sql = mysql_query(" select * from centros_medicos where cod_centro_medico = $codigo ");
?>
<table width="100%"  border="0" cellspacing="0" class="textotit">
    <tr><td align='center'><div align="center"><h2>REGISTRO DE CENTROS M&Eacute;DICOS</h2></div><br></td></tr>
</table>
<center>
    <form action="registro_medico.php" method="post" name="form1">
        <table width="400" border="1" cellpadding="5">
            <?php while ($row = mysql_fetch_array($sql)) { ?>
                <tr>
                    <th scope="row">Nombre</th>
                    <td><input type="text" size="50" id="name" class="name" name="name" placeholder="Nombre del centro m&eacute;dico" value="<?php echo $row[1] ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Direccion</th>
                    <td><input type="text" size="50" id="direccion" class="direccion" name="direccion" placeholder="Direccion del centro m&eacute;dico" value="<?php echo $row[2] ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Ciudad</th>
                    <td>
                        <select name="ciudad" id="ciudad">
                            <?php $sql_ciudad = mysql_query(" select * from ciudades where cod_ciudad != 115"); ?>
                            <?php while ($row_ciudad = mysql_fetch_array($sql_ciudad)) { ?>
                                <option value="<?php echo $row_ciudad[0] ?>" <?php if($row[3] == $row_ciudad[0] ):  ?> selected <?php endif; ?>><?php echo $row_ciudad[1] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <table align='center'><tr><td><a href='centro_medico_lista.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>
        <table class="texto" border="0" align="center"><tr><td align='center'><input type="button" class="boton" id="guardar" value="Enviar"></td></tr></table>
    </form>
</center>
</body>
</html>
