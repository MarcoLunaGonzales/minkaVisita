<?php
require("conexion.inc");
require("estilos_visitador.inc");
require('comprimir.php');
require('sendmsg.php');
if ($archivo != "none" AND $archivo_size != 0)
{	if ($archivo=="none") 
	{	
	}
	else
	{	copy ($archivo, "archivos_mail//".$archivo_name);
		$archivo_zip=comprimir($archivo_name);
	}	
} 
$sql_dirmail="select email from funcionarios where codigo_funcionario='$global_visitador'";
$resp_dirmail=mysql_query($sql_dirmail);
$dat_dirmail=mysql_fetch_array($resp_dirmail);
$email_funcionario=$dat_dirmail[0];
$from="$email_funcionario";
$asunto="$asunto - $nombre_completo";
if($archivo!="none")
{	sendmsg($destinatario,$asunto, $mensaje,$from,$archivo_zip,'application/x-zip-compressed');
}
else
{	sendmsg($destinatario,$asunto, $mensaje,$from,'','');
}
echo "<script language='Javascript'>
		alert('El mensaje se envio correctamente.');
		location.href='principal_visitador.php';
		</script>";
?>