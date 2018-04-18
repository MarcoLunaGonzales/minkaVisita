<?php

require("../lib/phpmailer/class.phpmailer.php");
require("../conexion.inc");


$visitador2 =  $_GET['visitador'];
$cod_motivo =  $_GET['motivo'];

$sql_visitador = mysql_query("SELECT DISTINCT r.cod_visitador, CONCAT(f.nombres,' ',f.paterno,' ',f.materno) FROM funcionarios f, rutero_detalle r WHERE r.cod_visitador = f.codigo_funcionario AND r.cod_contacto = $visitador2 ");
$cod_visitador = mysql_result($sql_visitador,0,0);
$nom_visitador = mysql_result($sql_visitador,0,1);

$sql_motivo = mysql_query("SELECT descripcion_motivo  from motivos_baja where codigo_motivo = $cod_motivo");
$motivo = mysql_result($sql_motivo, 0, 0);

$date = date('d/m/Y');

// $mail = new PHPMailer();


// $mail->AddReplyTo("solicitud_baja_visitador@cofar.com.bo","Solicitud Baja Visitador");

// $mail->SetFrom("solicitud_baja_visitador@cofar.com.bo","Solicitud Baja Visitador");

// $mail->AddReplyTo("solicitud_baja_visitador@cofar.com.bo","Solicitud Baja Visitador");

// $address = "mpacheco@cofar.com.bo";
// // $address2 = "jarze@cofar.com.bo";
// $address3 = "gtancara@cofar.com.bo";
// //$mail->AddAddress($address, "Dr. Miguel A. Pacheco");
// // $mail->AddAddress($address2, "Jorge Arze");
// //$mail->AddAddress($address3, "German Tancara");

// $mail->Subject    = "Solicitud Baja Visitador '$nom_visitador' ";

// $mail->AltBody    = "Se solicit&oacute; la baja para el funcionario '$nom_visitador' con el motivo de: '$motivo' el d&iacute;a de hoy($date)"; 

// $mail->MsgHTML("Estimados Se&ntilde;or@s: <br /> <br />Se solicit&oacute; la baja del visitador <strong>$nom_visitador</strong> por el motivo: <strong>$motivo</strong> el d&iacute;a de hoy<strong>($date)</strong>. <br /> <br /> Por favor tomar en cuenta para la aprobaci&oacute;n o no en la aprobaci&oacute;n de bajas.<br /> <br /><i>/* Este es un mensaje autom&aacute;tico. Por favor no responda a este correo. */</i>");


if(!$mail->Send()) {
	echo "<script language='Javascript'>
	window.close()
	</script>";
} else {
	echo "<script language='Javascript'>
	window.close()
	</script>";
}

?>
