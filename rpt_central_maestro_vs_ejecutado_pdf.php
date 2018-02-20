<?php
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\'primer.pdf\'");
passthru("htmldoc --format pdf --left 2.5cm --right 1.5cm --top 1.5cm --bottom 1.5cm " . "--headfootsize 9 --header 't l' --footer '/' "."--size 'a4' --fontsize 10 --charset 8859-15 "."--webpage http://200.105.203.2/visita_medica/rpt_central_maestro_vs_ejecutado_xls.php?variables='$variables'");
?>
