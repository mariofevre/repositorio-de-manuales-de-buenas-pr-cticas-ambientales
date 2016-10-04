<?php
include('./includes/encabezado.php');
ini_set('display_errors', '1');
$Base=$_SESSION['AppSettings']->DATABASE_NAME;
	
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

$nombre=str_pad($_POST['id'], 5, '0', STR_PAD_LEFT);	
$doc='./documentos/fuentes/originales/'.$nombre.".pdf";


if($_POST['accion']=='consulta'||$_POST['estado']=='inicio'){
	$img = new Imagick();
	$img->pingImage($doc);
	$pagsTot=$img->getNumberImages();
	$Log['data']['totPags']=$pagsTot;
	$Log['data']['actPags']=0;
	$cont='0_'.$pagsTot;
	$Log['data']['cont']=$cont;
	$Log['res']='exito';
}elseif($_POST['accion']=='scan'){
	
	$e=explode("_",$_POST['estado']);	
	$i=$e[0];
	
	
	$mc1=microtime(true);
	$mc0=$mc1;
	$Log['tx'][]='inicia control mc:'.$mc0;	


	$mcN=microtime(true);
	$Log['tx'][]='mcd (no ping):'.number_format(($mcN-$mc1),6);
	$mc1=$mcN;	
	
	$mcN=microtime(true);
	$Log['tx'][]='mcd (pags):'.number_format(($mcN-$mc1),6);
	$mc1=$mcN;	

	$img = new Imagick();
	

	

	
	
	if($_POST['resolucion']=='HD'){		
		$img->setResolution(200, 200);
		$img->setCompressionQuality(95); 
	}
	$img->readImage("{$doc}[$i]");
		
	$mcN=microtime(true);
	$Log['tx'][]='mcd (leido):'.number_format(($mcN-$mc1),6);
	$mc1=$mcN;	
	
	$mc=microtime(true);
	$img->setImageFormat('jpg');
	
	$mcN=microtime(true);
	$Log['tx'][]='mcd (formateado):'.number_format(($mcN-$mc1),6);
	$mc1=$mcN;
	
	
	$i++;
	$itx=str_pad($i, 4, "0",STR_PAD_LEFT);
	
	$nombre=str_pad($_POST['id'], 5, '0', STR_PAD_LEFT);	
	$path='./documentos/fuentes/imagen'.$_POST['resolucion'].'/'.$nombre.'/';
	if(!file_exists($path)){
		$Log['tx'][]="creando carpeta $path";mkdir($path, 0777, true);chmod($path, 0777);	
	}
	$cont=$i."_".$e[1];
	$save_to=$path."_".$itx.".jpg";
	
	$img->writeImage($save_to);	
	
	$mcN=microtime(true);
	$Log['tx'][]='mcd (guardado):'.number_format(($mcN-$mc1),6);
	$mc1=$mcN;
	
	
	$Log['data']['totPags']=$e[1];
	$Log['data']['actPags']=$i;	
	$Log['data']['cont']=$cont;
	$Log['data']['scan']=$i;
	$Log['res']='exito';	
}

if($_POST['resolucion']=='HD'){
	$camposcan="zz_escaneadoHD";
}else{
	$camposcan="zz_escaneado";
}

$query="UPDATE $Base.FUfuentes SET $camposcan ='".$cont."' WHERE id='".$_POST['id']."'";	
mysql_query($query,$Conec1);
if(mysql_error($Conec1)!=''){
	$Log['res']='err';
	$Log['tx'][]='Error al insertar en base de datos';
	$Log['tx'][]=mysql_error($Conec1);
	$Log['tx'][]=$query;
	terminar($Log);	
}	

terminar($Log);	
?>

