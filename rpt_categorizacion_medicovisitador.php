<?php
header("Content-Type: text/html; charset=UTF-8");
require("conexion.inc");
require('fpdf.php');

class PDF extends FPDF {

	function Footer() {
		//Go to 1.5 cm from bottom
		$this->SetY( 0 );
		$this->SetFont( 'Arial', 'B', 7 );
		//Print current and total page numbers
		$this->Cell( 0, 10, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C' );
	}

}

$rpt_ciclo = $ciclo_rpt;
$rpt_gestion = $gestion_rpt;

$pdf = new FPDF( 'P', 'mm', 'A4' );
$pdf->AliasNbPages();
$pdf->SetMargins( 0, 0 );

$sqlMedicos = "select distinct(rmd.`cod_med`), concat(m.`nom_med`,' ',m.`ap_pat_med`) as medico , espemed.desc_especialidad, rmd.cod_visitador,
	rmd.categoria_med, dm.direccion
	from `rutero_maestro_cab_aprobado` rmc, `rutero_maestro_aprobado` rm, especialidades espemed,
	`rutero_maestro_detalle_aprobado` rmd, `medicos` m, direcciones_medicos dm where rmc.`cod_rutero` = rm.`cod_rutero` and
	 dm.cod_med = m.cod_med and m.`cod_med`=rmd.`cod_med` and rmd.cod_especialidad = espemed.cod_especialidad and rm.`cod_contacto` = rmd.`cod_contacto` and
	 rmc.`cod_visitador` in (select codigo_funcionario from funcionarios where
       cod_ciudad = '$rpt_territorio' and cod_cargo = 1011 and estado in (0,1)) and rmc.`codigo_ciclo` = '$rpt_ciclo' and
       rmc.`codigo_gestion` = '$rpt_gestion' and rmd.cod_visitador = $visitador order by cod_visitador, medico";
//echo $sqlMedicos;
$respMedicos = mysql_query( $sqlMedicos );
$hojas = 1;
while ( $datMedicos = mysql_fetch_array( $respMedicos ) ) {


	$codMed = $datMedicos[ 0 ];
	$nombreMed = $datMedicos[ 1 ];
	$codEspecialidad = $datMedicos[ 2 ];
	$codVisitador = $datMedicos[ 3 ];
	$codCat = $datMedicos[ 4 ];
	$dirMed = $datMedicos[ 5 ];

	$sqlInsert = "insert into medico_a_categorizar  values($codMed, $codVisitador, '$codEspecialidad', '$codCat',$rpt_territorio,'$dirMed')";
	mysql_query( $sqlInsert );

	if ( $hojas % 2 != 0 ) {
		$pdf->AddPage();
	}
	if ( $hojas % 2 != 0 ) {
		$pdf->SetFont( 'Arial', 'B', 15 );
		$txt1 = "Boleta de Categorizacion de Medicos";
		$pdf->SetXY( 10, 15 );
		$pdf->Cell( 200, 10, $txt1, 0, 2, "C" );

		$pdf->SetFont( 'Arial', 'B', 9 );
		$txt1 = "Dr(a).: $nombreMed";
		$pdf->SetXY( 15, 25 );
		$pdf->Cell( 200, 10, $txt1, 0, 2 );
		$txt1 = "Especialidad: $codEspecialidad";
		$pdf->SetXY( 15, 29 );
		$pdf->Cell( 200, 10, $txt1, 0, 2 );
		$txt1 = "Direccion: $dirMed";
		$pdf->SetXY( 15, 33 );
		$pdf->Cell( 200, 10, $txt1, 0, 2 );


		$txt1 = "DATOS COMPLEMENTARIOS";
		$pdf->SetXY( 10, 39 );
		$pdf->Cell( 200, 10, $txt1, 0, 2, "C" );

		$pdf->SetXY( 10, 50 );
		$pdf->Cell( 5, 5, "", 1, 2, "C" );
		$pdf->SetXY( 20, 50 );
		$pdf->Cell( 90, 5, "Farmacias de Referencia:", 1, 2, "C" );
		$pdf->SetXY( 115, 50 );
		$pdf->Cell( 90, 5, "Direccion", 1, 2, "C" );
		$farm = 1;
		$altura = 58;
		for ( $farm; $farm <= 3; $farm ++  ) {
			$pdf->SetXY( 10, $altura );
			$pdf->Cell( 5, 5, "$farm", 1, 2, "C" );
			$pdf->SetXY( 20, $altura );
			$pdf->SetFillColor( 248, 232, 219 );
			$pdf->Cell( 90, 5, "", 1, 2, "C", true );
			$pdf->SetXY( 115, $altura );
			$pdf->SetFillColor( 248, 232, 219 );
			$pdf->Cell( 90, 5, "", 1, 2, "C" ,true);
			$altura = $altura + 7;
		}

		$pdf->SetXY( 10, 80 );
		$pdf->Cell( 80, 5, "Sexo", 0, 2, "L" );
		$pdf->SetXY( 115, 80 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 30, 5, "", 1, 2, "C", true );


		$pdf->SetXY( 10, 87 );
		$pdf->Cell( 80, 5, "Nro. de pacientes dia en el lugar de visita", 0, 2, "L" );
		$pdf->SetXY( 115, 87 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 30, 5, "", 1, 2, "C", true );

		$pdf->SetXY( 115, 94 );
		$pdf->Cell( 20, 5, "Alto", 1, 2, "C" );
		$pdf->SetXY( 140, 94 );
		$pdf->Cell( 20, 5, "Medio", 1, 2, "C" );
		$pdf->SetXY( 165, 94 );
		$pdf->Cell( 20, 5, "Bajo", 1, 2, "C" );

		$pdf->SetXY( 10, 101 );
		$pdf->Cell( 80, 5, "Tiene preferencia prescriptiva por productos de marca", 0, 2, "L" );
		$pdf->SetXY( 115, 101 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );
		$pdf->SetXY( 140, 101 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );
		$pdf->SetXY( 165, 101 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );

		$pdf->SetXY( 10, 108 );
		$pdf->Cell( 80, 5, "Nivel Socieconomico de los Pacientes", 0, 2, "L" );
		$pdf->SetXY( 115, 108 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );
		$pdf->SetXY( 140, 108 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );
		$pdf->SetXY( 165, 108 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );

		$pdf->SetXY( 10, 115 );
		$pdf->Cell( 80, 5, "Costo consulta (Bs.)", 0, 2, "L" );
		$pdf->SetXY( 115, 115 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 30, 5, "", 1, 2, "C", true );

		$pdf->SetFont( 'Arial', '', 8 );
		$pdf->SetXY( 170, 120 );
		$pdf->Cell( 30, 5, "($codVisitador)", 0, 2, "R" );
	} else {
		$pdf->SetFont( 'Arial', 'B', 15 );
		$txt1 = "Boleta de Categorizacion de Medicos";
		$pdf->SetXY( 10, 145 );
		$pdf->Cell( 200, 10, $txt1, 0, 2, "C" );

		$pdf->SetFont( 'Arial', 'B', 9 );
		$txt1 = "Dr(a).: $nombreMed";
		$pdf->SetXY( 15, 155 );
		$pdf->Cell( 200, 10, $txt1, 0, 2 );
		$txt1 = "Especialidad: $codEspecialidad";
		$pdf->SetXY( 15, 159 );
		$pdf->Cell( 200, 10, $txt1, 0, 2 );
		$txt1 = "Direccion: $dirMed";
		$pdf->SetXY( 15, 163 );
		$pdf->Cell( 200, 10, $txt1, 0, 2 );


		$txt1 = "DATOS COMPLEMENTARIOS";
		$pdf->SetXY( 10, 170 );
		$pdf->Cell( 200, 10, $txt1, 0, 2, "C" );

		$pdf->SetXY( 10, 180 );
		$pdf->Cell( 5, 5, "", 1, 2, "C" );
		$pdf->SetXY( 20, 180 );
		$pdf->Cell( 90, 5, "Farmacias de Referencia:", 1, 2, "C" );
		$pdf->SetXY( 115, 180 );
		$pdf->Cell( 90, 5, "Direccion", 1, 2, "C" );
		$farm = 1;
		$altura = 188;
		for ( $farm; $farm <= 3; $farm ++  ) {
			$pdf->SetXY( 10, $altura );
			$pdf->Cell( 5, 5, "$farm", 1, 2, "C" );
			$pdf->SetXY( 20, $altura );
			$pdf->SetFillColor( 248, 232, 219 );
			$pdf->Cell( 90, 5, "", 1, 2, "C", true );
			$pdf->SetXY( 115, $altura );
			$pdf->SetFillColor( 248, 232, 219 );
			$pdf->Cell( 90, 5, "", 1, 2, "C",true );
			$altura = $altura +7;
		}
		$pdf->SetXY( 10, 210 );
		$pdf->Cell( 80, 5, "Sexo", 0, 2, "L" );
		$pdf->SetXY( 115, 210 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 30, 5, "", 1, 2, "C", true );


		$pdf->SetXY( 10, 217 );
		$pdf->Cell( 80, 5, "Nro. de pacientes dia en el lugar de visita", 0, 2, "L" );
		$pdf->SetXY( 115, 217 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 30, 5, "", 1, 2, "C", true );

		$pdf->SetXY( 115, 224 );
		$pdf->Cell( 20, 5, "Alto", 1, 2, "C" );
		$pdf->SetXY( 140, 224 );
		$pdf->Cell( 20, 5, "Medio", 1, 2, "C" );
		$pdf->SetXY( 165, 224 );
		$pdf->Cell( 20, 5, "Bajo", 1, 2, "C" );

		$pdf->SetXY( 10, 231 );
		$pdf->Cell( 80, 5, "Tiene preferencia prescriptiva por productos de marca", 0, 2, "L");
		$pdf->SetXY( 115, 231 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );
		$pdf->SetXY( 140, 231 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );
		$pdf->SetXY( 165, 231 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );

		$pdf->SetXY( 10, 239 );
		$pdf->Cell( 80, 5, "Nivel Socieconomico de los Pacientes", 0, 2, "L" );
		$pdf->SetXY( 115, 239 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );
		$pdf->SetXY( 140, 239 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );
		$pdf->SetXY( 165, 239 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 20, 5, "", 1, 2, "C", true );

		$pdf->SetXY( 10, 246 );
		$pdf->Cell( 80, 5, "Costo consulta (Bs.)", 0, 2, "L" );
		$pdf->SetXY( 115, 246 );
		$pdf->SetFillColor( 248, 232, 219 );
		$pdf->Cell( 30, 5, "", 1, 2, "C", true );

		$pdf->SetFont( 'Arial', '', 8 );
		$pdf->SetXY( 170, 251 );
		$pdf->Cell( 30, 5, "($codVisitador)", 0, 2, "R" );
	}

	$hojas ++;
}



$pdf->Output();
?>