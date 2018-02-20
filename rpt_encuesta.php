<?php
require("conexion.inc");
require('fpdf.php');

class PDF extends FPDF
{
	function Footer()
	{
    //Go to 1.5 cm from bottom
    $this->SetY(0);
    $this->SetFont('Arial','B',7);
    //Print current and total page numbers
    $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

$rpt_ciclo=$ciclo_rpt;
$rpt_gestion=$gestion_rpt;

$pdf=new FPDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->SetMargins(0,0);

$sqlMedicos="select distinct(rmd.`cod_med`), concat(m.`nom_med`,' ',m.`ap_pat_med`) as medico , rmd.cod_especialidad, rmd.cod_visitador, 
	rmd.categoria_med
	from `rutero_maestro_cab_aprobado` rmc, `rutero_maestro_aprobado` rm, 
	`rutero_maestro_detalle_aprobado` rmd, `medicos` m where rmc.`cod_rutero` = rm.`cod_rutero` and 
	 m.`cod_med`=rmd.`cod_med` and rm.`cod_contacto` = rmd.`cod_contacto` and 
	 rmc.`cod_visitador` in (select codigo_funcionario from funcionarios where 
       cod_ciudad = '$rpt_territorio' and cod_cargo = 1011 and estado in (0,1)) and rmc.`codigo_ciclo` = '$rpt_ciclo' and 
       rmc.`codigo_gestion` = '$rpt_gestion' order by cod_visitador, medico";
$respMedicos=mysql_query($sqlMedicos);
while($datMedicos=mysql_fetch_array($respMedicos)){

	
	$codMed=$datMedicos[0];
	$nombreMed=$datMedicos[1];
	$codEspecialidad=$datMedicos[2];
	$codVisitador=$datMedicos[3];
	$codCat=$datMedicos[4];
	
	$sqlInsert="insert into medicos_a_encuestar2 values($codMed, $codVisitador, '$codEspecialidad', '$codCat')";
	$respInsert=mysql_query($sqlInsert);
	
	$pdf->AddPage(); 
	$pdf->SetFont('Arial','B',7);

	$pdf->SetXY(170,5);		$pdf->Cell(50,10,"$codEspecialidad/$codVisitador",0,2,"C");
												$pdf->Cell(50,10,'Pag. '.$pdf->PageNo().'/{nb}',0,0,'C');

	$pdf->SetXY(20,5);		
	//$pdf->Image("imagenes\breskot.png",10,5,30,15);
	$pdf->SetFont('Arial','B',15);
	$txt1="FRECUENCIA DE USO DE MEDICAMENTOS";	
	$pdf->SetXY(10,15);		$pdf->Cell(200,10,$txt1,0,2,"C");

	$pdf->SetFont('Arial','B',9);
	$txt1="Dr(a).: $nombreMed";
	$pdf->SetXY(15,25);		$pdf->Cell(200,10,$txt1,0,2);
	$txt1="Distinguido Dr(a).:";
	$pdf->SetXY(15,33);		$pdf->Cell(200,10,$txt1,0,2);
	$txt1="Le solicitamos tenga la gentileza de seleccionar la frecuencia de uso de los fármacos que se detallan a continuación de";
	$pdf->SetXY(15,37);		$pdf->Cell(200,10,$txt1,0,2);
	$txt1="acuerdo a su práctica médica diaria:";
	$pdf->SetXY(15,41);		$pdf->Cell(200,10,$txt1,0,2);
	
	$pdf->SetXY(10,50);		$pdf->Cell(156,10,"FARMACO",1,2,"C");
	$pdf->SetXY(166,50);	$pdf->Cell(40,5,"FRECUENCIA DE USO",1,2,"C");

	$pdf->SetFont('Arial','B',5);
	$pdf->SetXY(166,55);	$pdf->Cell(10,5,"ALTA",1,2,"C");
	$pdf->SetXY(176,55);	$pdf->Cell(10,5,"MEDIA",1,2,"C");
	$pdf->SetXY(186,55);	$pdf->Cell(10,5,"BAJA",1,2,"C");
	$pdf->SetXY(196,55);	$pdf->Cell(10,5,"NO UTILIZA",1,2,"C");

	$codEspe=$codEspecialidad;
	$sql="select m.`descripcion`, m.principio_activo from `producto_especialidad` p, `muestras_medicas` m
	where p.`codigo_mm`=m.`codigo` and p.`codigo_linea`=1021 and p.`cod_especialidad`='$codEspe' and m.estado=1 order by 2";

	//$sql="select m.`descripcion`, m.principio_activo from `muestras_medicas` m where estado=1 order by 1";
	$resp=mysql_query($sql);
	$y=60;
	$contador=1;
	while($dat=mysql_fetch_array($resp)){
		$marca=$dat[0];
		$tam=strlen($marca);
		$cadena1=substr($marca,$tam-2, $tam);	
		if($cadena1=="MM"){
			$marcaCorrecta=substr($marca,0,$tam-2);
		}	

		$principio=$dat[1];
	
		$pdf->SetFont('Arial','B',7);	
		$pdf->SetXY(10,$y);		$pdf->Cell(156,3.5,$principio,1,2,"L");

		$pdf->SetXY(166,$y);	$pdf->Cell(10,3.5,"",1,2,"C");
		$pdf->SetXY(176,$y);	$pdf->Cell(10,3.5,"",1,2,"C");
		$pdf->SetXY(186,$y);	$pdf->Cell(10,3.5,"",1,2,"C");
		$pdf->SetXY(196,$y);	$pdf->Cell(10,3.5,"",1,2,"C");

		$y=$y+3.5;
		$contador++;
	}
	
	for($i=$contador;$i<=53;$i++){
		$pdf->SetFont('Arial','B',7);	
		$pdf->SetXY(10,$y);		$pdf->Cell(156,3.5,"",1,2,"L");
		
		$pdf->SetXY(166,$y);	$pdf->Cell(10,3.5,"",1,2,"C");
		$pdf->SetXY(176,$y);	$pdf->Cell(10,3.5,"",1,2,"C");
		$pdf->SetXY(186,$y);	$pdf->Cell(10,3.5,"",1,2,"C");
		$pdf->SetXY(196,$y);	$pdf->Cell(10,3.5,"",1,2,"C");

		$y=$y+3.5;
	}

	
	$pdf->SetFont('Arial','B',8);
	$txt1="Si Ud. Utiliza otro fármaco que no se encuentre en este listado y le gustase contar con él, favor señalar el nombre del mismo para que sea";
	$pdf->SetXY(15,250);		$pdf->Cell(200,10,$txt1,0,2);
	$txt1="tomado en cuenta por nuestro Departamento Comercial.";
	$pdf->SetXY(15,254);		$pdf->Cell(200,10,$txt1,0,2);

	$y=262;
	for($i=1;$i<=1;$i++){
		$pdf->SetFont('Arial','B',7);	
		$pdf->SetXY(10,$y);		$pdf->Cell(156,3.5,"",1,2,"L");
		
		$pdf->SetXY(166,$y);	$pdf->Cell(10,3.5,"",1,2,"C");
		$pdf->SetXY(176,$y);	$pdf->Cell(10,3.5,"",1,2,"C");
		$pdf->SetXY(186,$y);	$pdf->Cell(10,3.5,"",1,2,"C");
		$pdf->SetXY(196,$y);	$pdf->Cell(10,3.5,"",1,2,"C");

		$y=$y+3.5;
	}

	$pdf->SetXY(70,273);	$pdf->Cell(20,3.5,"FIRMA","T",1,"C");
	$pdf->SetXY(130,273);	$pdf->Cell(20,3.5,"SELLO","T",1,"C");
}



$pdf->Output();

?>