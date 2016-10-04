<?php
/*
 * dirije una consulta ajax a una función php
 * 
 * 
 */
ini_set('display_errors', '1');
header("Cache-control: private");
include('./includes/encabezado.php');
include('./cons_general.php');


//ini_set('display_errors', '1');
$func = $_POST['funcion'];
$resultado=$func($_POST['id'],$_POST['v1']);
//$res['res']='exito';

//print_r($resultado);
//ini_set('display_errors', '1');
//echo json_encode($resultado);
?>