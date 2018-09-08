<style type="text/css">
BODY {
	width: 100%;
	height: 500px;
}

#chart-container {
    width: 90%;
}

<?php

session_start();
$codRegional=$_SESSION['codRegionalTrabajo'];
$codMes=$_SESSION['mesTrabajo'];
$codAnio=$_SESSION['anioTrabajo'];
$codPromotor=$_SESSION['promotorTrabajo'];

?>
</style>
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--script type="text/javascript" src="js/Chart.min.js"></script-->
<script type="text/javascript" src="js/Chart.bundle.js"></script>
<script type="text/javascript" src="js/utils.js"></script>


</head>

    <div id="chart-container" style="margin-left:auto; margin-right:auto">
        <canvas id="graphCanvas"></canvas>
    </div>

    <script>
        $(document).ready(function () {
            showGraph();
        });


        function showGraph()
        {
            {
                $.get("dataPresPersonal.php",
				{codRegional:<?php echo $codRegional;?>,
				codMes:<?php echo $codMes;?>,
				codAnio:<?php echo $codAnio;?>,
				codPromotor:<?php echo $codPromotor;?>},
                function (data)
                {
                    console.log(data);
                    var lineas = [];
                    var presupuesto = [];
					var ventas = [];					

                    for (var i in data) {
						lineas.push(data[i].nombreLinea);
                        presupuesto.push(data[i].montoPresupuesto);
                        ventas.push(data[i].montoVenta);
                    }
					//alert(labs);
                    var chartdata = {
                        labels: lineas,
                        datasets: [
                            {
                                label: 'Presupuesto',
                                backgroundColor: '#8E24AA',
                                borderColor: '#46d5f1',
                                data: presupuesto
                            },
							{
                                label: 'Ventas',
                                backgroundColor: '#FDD835',
                                borderColor: '#46d5f1',
                                data: ventas
                            }
                        ]
                    };

                    var graphTarget = $("#graphCanvas");

                    var barGraph = new Chart(graphTarget, {
                        type: 'bar',
                        data: chartdata,
						options: {
							responsive: true,
							legend: {
								position: 'top',
							},
							title: {
								display: true,
								text: 'Avance de Presupuestos Regional'
							}
						}
                    });
                });
            }
        }
        </script>
