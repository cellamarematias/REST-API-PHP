<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/customer.class.php';


if($_SERVER['REQUEST_METHOD'] == "GET"){
    echo "Es un GET"

}else{
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}


?>