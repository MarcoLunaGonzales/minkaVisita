<?php

require("conexion.inc");

echo "<script language='Javascript'>
                function enviar_nav()
                {       location.href='registro_funcionarios.php?cod_ciudad=$cod_ciudad';
                }
                function eliminar_nav(f)
                {
                        var i;
                        var j=0;
                        datos=new Array();
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       datos[j]=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }
                        if(j==0)
                        {       alert('Debe seleccionar al menos un funcionario para proceder a su eliminación.');
                        }
                        else
                        {
                                if(confirm('Esta seguro de eliminar los datos ya que con ello se perdera toda la información historica del funcionario.'))
                                {
                                        location.href='eliminar_funcionario.php?datos='+datos+'&cod_ciudad=$cod_ciudad';
                                }
                                else
                                {
                                        return(false);
                                }
                        }
                }
				function editar_nav(f)
                {
                        var i;
                        var j=0;
                        var j_contacto;
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       j_contacto=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }
                        if(j>1)
                        {       alert('Debe seleccionar solamente un funcionario para editar sus datos.');
                        }
                        else
                        {
                                if(j==0)
                                {
                                        alert('Debe seleccionar un funcionario para editar sus datos.');
                                }
                                else
                                {
                                        location.href='editar_funcionarios.php?j_funcionario='+j_contacto+'&cod_ciudad=$cod_ciudad';
                                }
                        }
                }
                function cambiar_vista(sel_vista, f)
                {
                        var modo_vista;
                        modo_vista=sel_vista.value;
                        location.href='navegador_funcionarios.php?cod_ciudad=$cod_ciudad&vista='+modo_vista+'';
                }
                </script>";
        if($usuario_rrhh!="")
		{	require("estilos_rrhh.php");
		}
		else
		{	require("estilos_administracion.inc");
		}
		$sql_cab="select descripcion from ciudades where cod_ciudad=$cod_ciudad";
                $resp_cab=mysql_query($sql_cab);
                $dat_cab=mysql_fetch_array($resp_cab);
                $nombre_ciudad=$dat_cab[0];
        echo "<form method='post' action=''>";
        //esta parte saca el ciclo activo
        $sql="select f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres,f.fecha_nac,f.direccion,f.telefono, f.celular,f.email,
		ci.descripcion,f.estado, f.codigo_lineaclave,
		(select l.nombre_linea as linea_clave from lineas l where l.codigo_linea=f.codigo_lineaclave)
        from funcionarios f, cargos c, ciudades ci
        where f.cod_cargo=c.cod_cargo and f.cod_ciudad=ci.cod_ciudad and f.cod_ciudad='$cod_ciudad' and f.estado='1' order by c.cargo,f.paterno";
        if($vista==1)
        {
                $sql="select f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres,f.fecha_nac,
				f.direccion,f.telefono, f.celular,f.email,ci.descripcion,f.estado, f.codigo_lineaclave,
				(select l.nombre_linea as linea_clave from lineas l where l.codigo_linea=f.codigo_lineaclave)
        from funcionarios f, cargos c, ciudades ci
        where f.cod_cargo=c.cod_cargo and f.cod_ciudad=ci.cod_ciudad and f.cod_ciudad='$cod_ciudad' and 
		f.estado='0' order by c.cargo,f.paterno";
        }
        if($vista==2)
        {
                $sql="select f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres,f.fecha_nac,f.direccion,f.telefono, 
				f.celular,f.email,ci.descripcion,f.estado, f.codigo_lineaclave,
				(select l.nombre_linea as linea_clave from lineas l where l.codigo_linea=f.codigo_lineaclave)
        from funcionarios f, cargos c, ciudades ci
        where f.cod_cargo=c.cod_cargo and f.cod_ciudad=ci.cod_ciudad and f.cod_ciudad='$cod_ciudad' 
		order by c.cargo,f.paterno";
        }
		$resp=mysql_query($sql);
        echo "<center><table border='0' class='textotit'><tr><th>Registro de Funcionarios<br>Territorio $nombre_ciudad</th></tr></table></center><br>";
        echo "<table align='center' class='texto'><tr><th>Ver funcionarios: </th><td><select name='vista' class='texto' onChange='cambiar_vista(this, this.form)'>";
        if($vista==0)   echo "<option value='0' selected>Activos</option><option value='1'>Retirados</option><option value='2'>Todo</option>";
        if($vista==1)   echo "<option value='0'>Activos</option><option value='1' selected>Retirados</option><option value='2'>Todo</option>";
        if($vista==2)   echo "<option value='0'>Activos</option><option value='1'>Retirados</option><option value='2' selected>Todo</option>";
        echo "</select>";
        echo "</td></tr></table><br>";
		 echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Funcionarios Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";
        echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Cargo</th><th>Nombre</th>
				<th>Correo Electrónico</th><th>Línea Clave</th><th>Alta en sistema</th>
				<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
        $indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
        {
                $codigo=$dat[0];
                $cargo=$dat[1];
                $paterno=$dat[2];
                $materno=$dat[3];
                $nombre=$dat[4];
                $nombre_f="$paterno $materno $nombre";
                $fecha_nac=$dat[5];
                $direccion=$dat[6];
                $telf=$dat[7];
                $cel=$dat[8];
                $email=$dat[9];
                $ciudad=$dat[10];
                $estado=$dat[11];
								$linea_clave=$dat[13];
                if($estado==0)
                {
                        $fondo_fila="#ff6666";
                        $ver_lineas="Ver Líneas >>";
                        $restablecer="Restablecer contraseña";
                        $agenciasFuncionario="Agencias";
                }
                else
                {
                        $fondo_fila="";
                        $ver_lineas="<a href='anadir_funcionario_linea.php?j_funcionario=$codigo&cod_territorio=$cod_ciudad'>Ver Líneas >></a>";
                        $restablecer="<a href='restablecer_contrasena.php?codigo_funcionario=$codigo&cod_territorio=$cod_ciudad'>Restablecer contraseña</a>";
                        $agenciasFuncionario="<a href='navegadorFuncionariosAgencias.php?codigo_funcionario=$codigo'>Agencias</a>";
                }
                $sql_alta_sistema="select * from usuarios_sistema where codigo_funcionario='$codigo'";
                $resp_alta_sistema=mysql_query($sql_alta_sistema);
                $filas_alta=mysql_num_rows($resp_alta_sistema);
                if($estado==0)
                {	$alta_sistema="<img src='imagenes/no.gif'>";
							  	$dar_alta="Dar alta en Sistema >>";
							  	$restablecer="Restablecer contraseña";
				  				$agenciasFuncionario="Agencias";
								}
								if($estado==1)
                	{		if($filas_alta==0)
                			{
												$alta_sistema="<img src='imagenes/no.gif'>";  
												$dar_alta="<a href='alta_funcionario_sistema.php?codigo_funcionario=$codigo&cod_territorio=$cod_ciudad'>Dar alta en Sistema >></a>";
											}
											else
											{
						  					$alta_sistema="<img src='imagenes/si.gif'>";
												$dar_alta="Dar alta en Sistema >>";
											}
                	}
									echo "<tr bgcolor='$fondo_fila'><td align='center'>$indice_tabla</td>
										<td align='center'><input type='checkbox' name='cod_contacto' value='$codigo'></td>
											<td>&nbsp;$cargo</td><td>$nombre_f</td>
										<td align='left'>&nbsp;$email</td>
										<td align='left'>&nbsp;$linea_clave</td>
										<td align='center'>$alta_sistema</td>
										<td align='center'>$ver_lineas</td>
										<td align='center'>$dar_alta</td>
										<td align='center'>$restablecer</td>
										<td align='center'>$agenciasFuncionario</td></tr>";
        						$indice_tabla++;
					}
        echo "</table></center><br>";
        echo"\n<table align='center'><tr><td><a href='navegador_funcionarios1.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
        echo "<center><table border='0' class='texto'>";
        echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td>
		<td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)' disabled='true'></td>
		</tr></table></center>";
        echo "</form>";
?>

