<?php
ini_set('display_errors', '1');
include ('./includes/encabezado.php');	
$Base=$_SESSION['AppSettings']->DATABASE_NAME;



$Log=array();
function terminar($Log){
	$qa=json_encode($Log);
	if($qa==null){print_r($Log);}else{echo $qa;}
	exit;
}

						
if(!isset($_POST['clase'])){
	$Log['res']='err';
	$Log['tx'][]='No fue enviada la variable clase';
	terminar($Log);
}
if(!isset($_POST['idclase'])){
	$Log['res']='err';
	$Log['tx'][]='No fue enviada la variable idclase';	
	terminar($Log);
}
if(!isset($_POST['idBPA'])){
	$Log['res']='err';
	$Log['tx'][]='No fue enviada la variable idBPA';	
	terminar($Log);
}

if(!isset($_POST['accion'])){
	$Log['res']='err';
	$Log['tx'][]='No fue enviada la variable accion';	
	terminar($Log);
}



$arrTC=array(
"escalas" => "CLASescalas",
"fases" => "CLASfases",
"medio" => "CLASmedio",
"tipos" => "CLAStipos"
);

$arrTL=array(
"escalas" => "BPclasifEscala",
"fases" => "BPclasifFases",
"medio" => "BPclasifMedio",
"tipos" => "BPclasifTipo"
);

if(!isset($arrTC[$_POST['clase']])){
	$Log['res']='err';
	$Log['tx'][]='Clase no reconocida para asignacion de tabla';	
	terminar($Log);
}

$tablaC= $arrTC[$_POST['clase']];
$tablaL= $arrTL[$_POST['clase']];



if($_POST['accion']=='agrega'){
	$query ="
	INSERT INTO `sustentabilidad`.`".$tablaL."`
	values(null,".$_POST['idBPA'].",".$_POST['idclase'].")";	
	
	$Log['tx'][]='tageando';
}elseif($_POST['accion']=='borra'){
	$query ="
	DELETE FROM `sustentabilidad`.`".$tablaL."`
	WHERE id_h_BPbuenasprac_id = ".$_POST['idBPA']."
	AND   id_h_".$tablaC."_id = ".$_POST['idclase'];
	
	$Log['tx'][]='destageando';
}else{
	$Log['res']='err';
	$Log['tx'][]='accion no reconocida';	
	terminar($Log);
}


$cons=mysql_query($query,$Conec1);
if(mysql_error($Conec1)!=''){
	$Log['res']='err';
	$Log['tx'][]='Error al insertar en base de datos';
	$Log['tx'][]=mysql_error($Conec1);
	$Log['tx'][]=$query;
	terminar($Log);	
}

$Log['res']='exito';
$Log['data']['nid']=mysql_insert_id($Conec1);
terminar($Log);	

