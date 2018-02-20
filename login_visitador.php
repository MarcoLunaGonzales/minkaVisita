<?php
	echo'<script language="JavaScript" src="validar_general.js"></script>';
	require("estilos.inc");
	echo "<center><table border=0 class='textotit'>";
	echo "<tr><td>Bienvenido al Sistema de Visita Médica</td></tr></table><br>";
	
	echo "<form method='post' action='cookie_visitador.php' onSubmit='return validar(this)'>";
	echo "<table border=0 class='texto'>";
	echo "<tr><td>Usuario</td><td><input type='text' name='usuario' class='texto'></td></tr>";
	echo "<tr><td>Contraseña</td><td><input type='password' class='texto' name='clave'></td></tr>";
	echo "</table></center>";
	echo "<center><input type='submit' value='Ingresar' class='texto'></center>";
	echo "</form>";
?>