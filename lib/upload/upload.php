<?php
error_reporting(E_ALL);
foreach ($_FILES["images"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $name = $_FILES["images"]["name"][$key];
        $mover = move_uploaded_file($_FILES["images"]["tmp_name"][$key], "../../baco/uploads/" . $_FILES['images']['name'][$key]);
    }
}

if ($mover == true) {
    echo "
    <div id='header'>
        <center><h2>Archivo Cargado satisfactoriamente</h2></center>
    </div>";
}else{
    echo "
    <div id='header'>
        <center><h2>Archivo No Cargado Intentelo de nuevo (Compruebe la extension .xls del archivo)</h2></center>
    </div>";
}
?>