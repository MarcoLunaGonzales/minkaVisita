<?php
error_reporting(0);
require("conexion.inc");
?>

<head>
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#continuar").click(function(){
            var url,territorios;
            url = $("#continuar").attr('rel');
            territorios = $("#territorio").val();
            window.location.href = url+"?&territorios="+territorios;
        })  
    });
    </script>
    <style type="text/css">
    table tr th {
        padding: 0 10px    
    }
    </style>
</head>
<body>
        <?php 
		//require("estilos3.inc"); 
		require("estilos_regional_pri.inc"); 
		?>
		
        
		<h1>Aprobar Bajas</h1>

		<center><table class="texto">
			<tr>
				<th>Territorio</th>
				<td>
					<?php $sql_territorio = mysql_query("SELECT c.cod_ciudad, c.descripcion from ciudades c where c.cod_ciudad = $global_agencia order by c.descripcion") ?>
					<select name="territorio" id="territorio" multiple size="12">
						<?php while ($row_territorio = mysql_fetch_array($sql_territorio)) { ?>
						<option value="<?php echo $row_territorio[0]; ?>"><?php echo $row_territorio[1]; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</table></center>
		
		<div class="divBotones">
		<input type="button" onclick="javascript:void(0)" rel="aprobar_baja_medicos_detalle.php" class="boton" id="continuar" value="Continuar">
		</div>
		
		</body>
