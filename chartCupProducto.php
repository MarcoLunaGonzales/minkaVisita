<style type="text/css">
BODY {
	width: 100%;
	height: 600px;
}

#chart-container {
    width: 70%;
}

<?php

session_start();
$codCUP=$_SESSION['codCUP'];
$idMercado=$_SESSION['idMercado'];

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
                $.get("dataCupProducto.php",
				{codCUP:<?php echo $codCUP;?>,
				idMercado:<?php echo $idMercado;?>},
                function (data)
                {
                    console.log(data);
                    var productos = [];
                    var trim1 = [];
					var trim2 = [];
					var trim3 = [];
					var trim4 = [];
					

                    for (var i in data) {
                        //alert(data[i]);
						productos.push(data[i].nombreProducto.substr(0,10));
                        trim1.push(data[i].trim1);
                        trim2.push(data[i].trim2);
                        trim3.push(data[i].trim3);
                        trim4.push(data[i].trim4);
                    }
					//alert(labs);
                    var chartdata = {
                        labels: productos,
                        datasets: [
                            {
                                label: 'Trim I',
                                backgroundColor: '#ff6384',
                                borderColor: '#46d5f1',
                                data: trim1
                            },
							{
                                label: 'Trim II',
                                backgroundColor: '#49e2ff',
                                borderColor: '#46d5f1',
                                data: trim2
                            },
							{
                                label: 'Trim III',
                                backgroundColor: '#cc65fe',
                                borderColor: '#46d5f1',
                                data: trim3
                            },
							{
                                label: 'Trim IV',
                                backgroundColor: '#ffce56',
                                borderColor: '#46d5f1',
                                data: trim4
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
								text: 'Grafico por Productos'
							}
						}
                    });
                });
            }
        }
        </script>
