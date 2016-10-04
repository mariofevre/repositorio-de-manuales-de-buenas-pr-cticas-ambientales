<?php
include ('./includes/encabezado.php');	
$Base=$_SESSION['AppSettings']->DATABASE_NAME;

$Log=array();
function terminar($Log){
	echo json_encode($Log);
	exit;
}


$r=array("tp","t1","l1","t2","l2","accion");

foreach($r as $var){
	if(!isset($_POST[$var])){	
	$Log['res']='err';
	$Log['tx'][]='No fue enviada la variable '.$var;
	terminar($Log);
	}
}


$query="SHOW COLUMNS FROM ".$_POST['tp'];
$cons=mysql_query($query,$Conec1);
while($row=mysql_fetch_assoc($cons)){
	$e=explode("_",$row['Field']);
	$link=$e[1];
	
	if($link==$_POST['t1']){
		if(isset($C1)){
			$Log['res']='err';
			$Log['tx'][]="Dos columnas en la tabla ".$_POST['tp']." parecen responder al vínculo requerido: ".$C1." y ".$row['Field'];
			terminar($Log);
		}
		$C1=$row['Field'];
	}
	
	if($link==$_POST['t2']){
		if(isset($C2)){
			$Log['res']='err';
			$Log['tx'][]="Dos columnas en la tabla ".$_POST['tp']." parecen responder al vínculo requerido: ".$C2." y ".$row['Field'];
			terminar($Log);
		}
		$C2=$row['Field'];
	}
	
}


$query="SELECT * FROM ".$_POST['tp']." WHERE $C1 = '".$_POST['l1']." AND $C2 = '".$_POST['l2']."'";
$cons=mysql_query($query,$Conec1);


while($row=mysql_fetch_assoc($cons)){
	
}

if($_POST['accion']=='vincular'){
	if(mysql_num_rows($cons)>0){
		$Log['res']='exito';
		$Log['tx'][]="El vínculo ya se encontraba erstablecido";
		terminar($Log);
	}else{
		
		$query="INSERT INTO ".$_POST['tp']." SET $C1 = '".$_POST['l1'].", $C2 = '".$_POST['l2']."'";
		$cons=mysql_query($query,$Conec1);
		if(mysql_error($Conec1)!=''){
			$Log['res']='err';
			$Log['tx'][]="error al crear el vínculo";
			$Log['tx'][]=mysql_error($Conec1);
			$Log['tx'][]=$query;
			terminar($Log);
		}
		$Log['res']='exito';
		$Log['tx'][]="El vínculo se ha creado";
		terminar($Log);		
	}
		
}


if($_POST['accion']=='desvincular'){
	if(mysql_num_rows($cons)==0){
		$Log['res']='exito';
		$Log['tx'][]="El vínculo ya se encontraba eliminado";
		terminar($Log);
	}else{
		
		$query="DELETE FROM ".$_POST['tp']." WHERE $C1 = '".$_POST['l1']." AND $C2 = '".$_POST['l2']."'";
		$cons=mysql_query($query,$Conec1);
		if(mysql_error($Conec1)!=''){
			$Log['res']='err';
			$Log['tx'][]="error al eliminar el vínculo";
			$Log['tx'][]=mysql_error($Conec1);
			$Log['tx'][]=$query;
			terminar($Log);
		}
		$Log['res']='exito';
		$Log['tx'][]="El vínculo se ha eliminado";
		terminar($Log);		
	}
		
}




