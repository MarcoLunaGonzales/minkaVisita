<?php require("conexion.inc"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="stylesheet" href="stilos.css" type="text/css" />
        <link rel="stylesheet" media="screen" type="text/css" href="css/datepicker.css" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="ajax/reportecategorizacion/send.data.js"></script>
        <!--<script type="text/javascript" src="lib/datepicker.js"></script>-->
        <script type="text/javascript">
            var windowSizeArray = [ "width=1000,height=800,scrollbars=yes" ];
            $(function() {
//                $('.datepicker').DatePicker({
//                    format:'Y-m-d',
//                    date:$('.datepicker').val(),
//                    current: $('.datepicker').val(),
//                    calendars: 3,
//                    starts: 1,
//                    position: 'r',
//                    onBeforeShow: function(){
//                        $('.datepicker').DatePickerSetDate($('.datepicker').val(), true);
//                    },
//                    onChange: function(formated, dates){
//                        $('.datepicker').val(formated);
//                        $('.datepicker').DatePickerHide();
//                    }
//                });
//                $('.datepicker2').DatePicker({
//                    format:'Y-m-d',
//                    date: $('.datepicker2').val(),
//                    current: $('.datepicker2').val(),
//                    calendars: 3,
//                    starts: 1,
//                    position: 'r',
//                    onBeforeShow: function(){
//                        $('.datepicker2').DatePickerSetDate($('.datepicker2').val(), true);
//                    },
//                    onChange: function(formated, dates){
//                        $('.datepicker2').val(formated);
//                        $('.datepicker2').DatePickerHide();
//                    }
//                });
                $("#enviar").click(function(event){
                    var tipo = $(".tipo:checked").val();
                    var fecha_inicio = $(".inicio").val();
                    var fecha_final = $(".final").val();
//                    if(tipo == '' || tipo == null){
//                        alert("Debe seleccionar un El tipo de datos del reporte (Entrada o salida)")
//                    }else{
                        var url = "rpt_material_apoyo.php?tipo="+tipo+"&f_inicio="+fecha_inicio+"&f_final="+fecha_final;
                        var windowName = "Reporte_MaterialApoyo";//$(this).attr("name");
                        var windowSize = windowSizeArray[$(this).attr("rel")];
                    
                        window.open(url, windowName, windowSize);
 
                        event.preventDefault();
//                    }
                })
            })
        </script>
        <style type="text/css">
            #header h2 { color: #5F7BA9; font-size: 11pt; font-family: Verdana}
            body { background: #fff url('imagenes/fondo_pagina.jpg') no-repeat right; }
            #filtro th { text-align: left;; width: 30%; font-size: 12px }
        </style>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <center>
                    <h2>Reporte Ingreso Salida Material de Apoyo</h2>
                </center>
            </div>
            <div id="content">
                <center>
                    <table border="1" width="30%" cellpadding="5" id="filtro">
<!--                        <tr>
                            <th>Entrada / Salida</th>
                            <td>
                                <input type="radio" value="1" name="tipo" class="tipo" />Entrada de Material Apoyo <br />
                                <input type="radio" value="2" name="tipo" class="tipo" />Salida de Material Apoyo
                            </td>
                        </tr>-->
                        <tr>
                            <th>Fecha Inicio</th>
                            <td>
                                <input type="text" class="datepicker inicio" placeholder="2010-01-01" value="2010-01-01" />
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha final</th>
                            <td>
                                <input type="text" class="datepicker2 final" placeholder="2010-01-01" value="2010-01-01" />
                            </td>
                        </tr>
                    </table>
                </center>
                <center style="padding:20px 0">
                    <input type='button' name='reporte' rel="0" value='Ver Reporte' class='boton' id="enviar" />
                </center>
            </div>
        </div><!-- container -->
    </body>
</html>