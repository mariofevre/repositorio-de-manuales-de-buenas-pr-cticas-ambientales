<?php
include ('./includes/encabezado.php');	
$Base=$_SESSION['AppSettings']->DATABASE_NAME;

$Log=array();
function terminar($Log){
	echo json_encode($Log);
	exit;
}

if(!isset($_POST['tabla'])){
	$Log['res']='err';
	$Log['tx'][]='No fue enviada la variable tabla';
	terminar($Log);
}
if(!isset($_POST['nombre'])){
	$Log['res']='err';
	$Log['tx'][]='No fue enviada la variable nobmre';	
	terminar($Log);
}

$n1=str_replace(" ","", $_POST['nombre']);

if(strlen($n1)<4){
	$Log['res']='err';
	$Log['alerta']='El nombre es muy corto';
	$Log['tx'][]='longitud de nombre muy corta';	
	terminar($Log);
}

$query="SELECT * FROM ".$_POST['tabla']." WHERE nombre like '%".$_POST['nombre']."%'";
$cons=mysql_query($query,$Conec1);
while($row=mysql_fetch_assoc($cons)){
	$n1=str_replace(" ","", $_POST['nombre']);
	$n1=strtolower($n1);
	$n2=str_replace(" ","", $row['nombre']);
	$n2=strtolower($n2);
	
	if($n1==$n2){
		$Log['res']='exito';
		$Log['alerta']='El nombre ya estaba utilizado, se asigna a la clase existente: '.$row['nombre'];
		$Log['data']['nid']=0;
		$Log['data']['eid']=$row['id'];
		terminar($Log);			
	}
}
if(mysql_error($Conec1)!=''){
	$Log['res']='err';
	$Log['tx'][]='Error al insertar en base de datos';
	$Log['tx'][]=mysql_error($Conec1);
	$Log['tx'][]=$query;
	terminar($Log);	
}

$query="INSERT INTO ".$_POST['tabla']." SET nombre='".$_POST['nombre']."'";
mysql_query($query,$Conec1);
if(mysql_error($Conec1)!=''){
	$Log['res']='err';
	$Log['tx'][]='Error al insertar en base de datos';
	$Log['tx'][]=mysql_error($Conec1);
	$Log['tx'][]=$query;
	terminar($Log);	
}

if(mysql_insert_id($Conec1)<1){
	$Log['res']='err';
	$Log['tx'][]='No se generó un id postivo wen la base';
	terminar($Log);	
}

$Log['res']='exito';
$Log['data']['nid']=mysql_insert_id($Conec1);
terminar($Log);	

