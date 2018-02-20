<?php
	require("../funcion_nombres.php");
	require("../conexion.inc");
	
	require("../conexioni.inc");
	
	//$link = mysqli_connect('localhost', 'root', '', 'visita20140527');
	
	$id=$_GET['id'];
	$espe=$_GET['espe'];
	$contacto=$_GET['contacto'];
	$codCiudad=$_GET['codCiudad'];
	$lineaMkt=$_GET['lineaMkt'];
	$lineaVis=$_GET['lineaVis'];

	$claveInsert=$id."|".$espe."|".$contacto."|".$codCiudad."|".$lineaMkt."|".$lineaVis;
	
	$nombreLinea=nombreLinea($lineaMkt);
	$nombreCiudad=nombreTerritorio($codCiudad);
	
	$consulta = "select id, especialidad, ciudad, linea_mkt, concat(m.descripcion, ' ', m.presentacion)producto, 
	m.codigo, contacto, linea from asignacion_productos_excel_detalle a, muestras_medicas m 
	where a.id='$id' and linea_mkt='$lineaMkt' and especialidad='$espe' 
	and ciudad='$codCiudad' and contacto='$contacto' and a.producto=m.codigo order by posicion";
	$resultado = mysqli_query($link, $consulta);
	$elementos = null;
	while ($datos = mysqli_fetch_assoc($resultado)){
		$clave=$datos['id']."|".$datos['especialidad']."|".$datos['ciudad']."|".$datos['linea_mkt']."|".$datos['codigo']."|".$datos['contacto']."|".$datos['linea'];
		//echo $clave;
		//$elementos[$datos['codigo']] = $datos['producto'];
		$elementos[$clave] = $datos['producto'];
	}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lista editable y ordenable</title>
    <meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Lilita+One' rel='stylesheet' type='text/css'>
    <link href="pagina.css" rel="stylesheet" type="text/css" media="all">    
</head>
<body>
	<div id="wrapper">
		<h1>Edicion de productos Asignados <?php echo $nombreLinea;?>
			<?php echo $espe; ?> <?php echo $contacto; ?> <?php echo $nombreCiudad;?> 
		</h1>
		<ul id="lista">
			<?php 
				foreach ($elementos as $id => $nombre)
					echo '<li id="'.$id.'" contenteditable="true">'.$nombre.'</li>';
			?>
		</ul>
		<div id="form">
			<input type="radio" name="editar-ordenar" id="quitar" value="quitar" checked="checked"/>
            <label for="editar1">Quitar</label>
            <input type="radio" name="editar-ordenar" value="ordenar" id="ordenar1"/>
            <label for="ordenar1">Ordenar</label>
			<form id="formulario" method="post">
				<?php 
				$sqlProd="select codigo, concat(descripcion, ' ',presentacion) from muestras_medicas where estado=1 order by 2";
				$respProd=mysql_query($sqlProd);
				echo "<select name='campo-nombre' id='campo-nombre' >";
				while($datProd=mysql_fetch_array($respProd)){
					$codigo=$datProd[0];
					$nombre=$datProd[1];
					echo "<option value='$codigo'>$nombre</option>";
				}
				echo "</select>";
				echo "<input type='hidden' id='idClave' name='idClave' value='$claveInsert'>";
				?>
				<!--input type="text" id="campo-nombre" name="nombre" placeholder="Nuevo elemento"-->
				<input type="submit" value="Añadir">
			</form>			
		</div>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
	<script>
		$(function(){
			var formulario = $('#formulario'), ordenando = false, lista = $('#lista'),
                    elementos = lista.find('li');
			lista.sortable({
                update: function(event,ui){
                    var ordenPuntos = $(this).sortable('toArray').toString();
                    $.ajax({
                        type: 'POST',
                        url: 'controlador.php',
                        dataType: 'json',
                        data: {
                            accion: 'ordenar',
                            puntos: ordenPuntos
                        }
                    });
                }
            });
            lista.sortable('disable');
            $('input[name="editar-ordenar"]').on('change', function(){
                if ($(this).val() == 'ordenar'){
                    lista.sortable('enable');
                    elementos.attr('contenteditable',false);
                    ordenando = true;
                }
                else{
                    lista.sortable('disable');
                    elementos.attr('contenteditable',false);
					//elementos.attr('contenteditable',true);
                    ordenando = false;
                }
            });


			formulario.on('submit',function(evento){ //Cuando el formulario se envía, vamos a insertar
				evento.preventDefault();
				var nombre = $('#campo-nombre').val();
				$('#campo-nombre').val('');
				
				var claveInsert = $('#idClave').val(); 
				
				$.ajax({
                    type: 'POST',
                    url: 'controlador.php',
                    dataType: 'json',
                    data: {
                        accion: 'insertar',
                        nombre: nombre,
						clave: claveInsert,
                        orden: elementos.length + 1 // El orden es el número de elementos + 1
                    },
                    success: function (devolver){
                    	//if (devolver.valor){
                    	if(1==1){
							$('<li>',{
                    			id : devolver.valor,
                    			'class': ordenando ? 'ordenable' : '',
                    			text: devolver.valor1,
                    			'contenteditable' : !ordenando
                    		}).hide().appendTo($('#lista')).fadeIn('slow');
                    	}
                    }
                });
            });
            lista.on('keydown', 'li', function(evento){
                var punto = $(this);
                //var idPunto = punto.attr('id').split('-');
				var idPunto = punto.attr('id');
                //idPunto = idPunto[1];

                switch(evento.keyCode){
                    case 27:{ //Escape
                        document.execCommand('undo');
                        punto.blur();
                        break;
                    }
                    case 46:{ //Suprimir
                        if (confirm('¿Seguro que quiere eliminar este elemento?')){
                            $.ajax({
                                type: 'POST',
                                data: {
                                    accion: 'eliminar',
                                    orden: punto.index(),
                                    id: idPunto
                                },
                                url: 'controlador.php',
                                success: function(e){
                                    punto.fadeOut('slow').remove();
                                }
                            });
                        }
                        break;
                    }
                    case 13:{ //Enter
                        evento.preventDefault();
                        var texto = punto.text();
                        punto.blur();
                        $.ajax({
                            type: 'POST',
                            data: {
                                accion: 'editar',
                                id: idPunto,
                                nombre: texto
                            },
                            url: 'controlador.php'
                        });
                        break;
                    }
                }
            });
		});
	</script>
</body>
</html>