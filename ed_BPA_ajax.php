<?php
include ('./includes/encabezado.php');	
$Base=$_SESSION['AppSettings']->DATABASE_NAME;

//print_r($_FILES);
$Log=array();
function terminar($Log){
	$res = json_encode($Log);
	if($res==''){
		echo "error al codificar el array resultante";
		print_r($Log);
	}else{
		echo $res;
	}	
	exit;
}


if(!isset($_POST['accion'])){
	$Log['res']='err';
	$Log['tx'][]=utf8_encode('error, no se defin�o la accion');	
	terminar($Log);
}


$campos=array('nombre','descripcion','fuente','entidad','fecha','id_p_FUfuentes','copia','observaciones','fuentepags','valoracion','id_p_PROestrategias','procedimiento','recursos');

foreach($campos as $c){
	if($_POST[$c]!=''){
		if($_POST[$c]==$CODELIM){$_POST[$c]='';}
		$e=explode("_",$c);
		if($e[0]=='FI'){continue;}		
		$sets.="$c ='".utf8_decode($_POST[$c])."', ";
	}
}
$sets=substr($sets,0,-2);




if($_POST['accion']=='crear'){	
	$query="INSERT INTO $Base.BPbuenasprac SET $sets";
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
		$Log['tx'][]=utf8_encode('No se gener� un id postivo en la base');
		terminar($Log);	
	}

	
	$Log['res']='exito';
	$NID=mysql_insert_id($Conec1);
	$Log['data']['nid']=mysql_insert_id($Conec1);
	$Log['data']['id']='NA';
	
	if(count($_FILES)>0){
		$Log['tx'][]=utf8_encode('guardando archivo adjunto');
		procesarFile($NID,$_FILES[0]);
	}		
	
	terminar($Log);	
}

if($_POST['accion']=='guardar'){$_POST['accion']='cambiar';}
if($_POST['accion']=='cambiar'){

	$query="UPDATE $Base.BPbuenasprac SET $sets WHERE id='".$_POST['id']."'";
	$cons=mysql_query($query,$Conec1);
	
	if(mysql_error($Conec1)!=''){
		$Log['res']='err';
		$Log['tx'][]='Error al insertar en base de datos';
		$Log['tx'][]=mysql_error($Conec1);
		$Log['tx'][]=$query;
		terminar($Log);	
	}	

	if(count($_FILES)>0){
		$Log['tx'][]=utf8_encode('guardando archivo adjunto');
		procesarFile($NID,$_FILES[0]);
	}		
		
	$Log['res']='exito';
	$Log['data']['nid']='NA';
	$Log['data']['id']=$_POST['id'];
	terminar($Log);	
	
}




if($_POST['accion']=='eliminar'||$_POST['accion']=='confirmar'){

	$query="UPDATE $Base.BPbuenasprac SET zz_borrada='1' WHERE id='".$_POST['id']."'";
	$cons=mysql_query($query,$Conec1);
	
	if(mysql_error($Conec1)!=''){
		$Log['res']='err';
		$Log['tx'][]='Error al insertar en base de datos';
		$Log['tx'][]=mysql_error($Conec1);
		$Log['tx'][]=$query;
		terminar($Log);	
	}	
	
	$Log['res']='exito';
	$Log['data']['nid']='NA';
	$Log['data']['id']=$_POST['id'];
	terminar($Log);	
	
}



