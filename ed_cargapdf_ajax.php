<?php
include("../includes/mensajesdesarrollo.php");// carga sistema de mensajes de desarrollo
include ('./includes/encabezado.php');	
include ('../includes/usuarioaccesos.php');

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


if($_POST['accion']!='borrar'){
	$FIL=reset($_FILES);
	if($FIL['type']!='application/pdf'){
		echo "tipo de archivo no reconocido";
		echo "<br>archivo: ".$FIL['name'];
		echo "<br>tipo: ".$FIL['type'];
		break;
	}
}


$files = glob('./documentos/*'); // get all file names


if($_POST['archivo']!=''){
	$doc='./documentos/'.$_POST['archivo'];
	if(is_file($doc)){
	  	unlink($doc); // delete file
	  	$Log['res']='exito';
		$Log['tx']='archivo eliminado '.$_POST['archivo'];
		$Log['data']['eliminado']=$_POST['idcont'];
		
	  	terminar($Log);
	}else{
		$Log['res']='error';
		$Log['tx']='el archivo no fue encontrado en $doc';
		$Log['data']['eliminado']=$_POST['idcont'];
	  	terminar($Log);
	}
}


foreach($files as $file){ // iterate files
  if(is_file($file))
  unlink($file); // delete file
}

?>
<!DOCTYPE html>
<head>
	 
	   <title>Pdf a Jpg</title>
	   <link href="./img/pdfajpg.ico" type="image/x-icon" rel="shortcut icon">
	   <link rel="stylesheet" type="text/css" href="../css/general.css" />   
	<style>
		a, img{
		display: inline-block;
		}
		a{
			padding:4px;
			margin:1px;
			font-size:10px;
			background-color: #08AFD9;
		}		
		a:hover{
			background-color: #e41937;
		}
		input{
			margin:2px;
		}
		form{
			
		}
		h1, h2{
			margin:0px;
			vertical-align:middle;
		}
		h1 > img{
			margin:3px;
		}
		.contenidopolar{
			display:block;
			width:90px;
			height:90px;
			background-color: #fff;
		}
		.contenidopolar img{
			max-height:90px;
			max-width:90px;
		}
		.epigrafe{
			width:90px;
			height:30px;
			line-height:10px;
			display:block;
			color:#fff;
		}
		.seccion{
			margin:10px;
			padding:10px;
		}
		.seccion.pdfs{
			border:1px solid #e41937;
		}		
		.seccion.jpgs{
			border:1px solid #08AFD9;
		}				
	</style>
	
</head>
<body>
<?

if($_POST['accion']!='borrar'){
	
	$pdf_file  =$FIL['tmp_name'];
	$nombre=asegurarfilename(substr($FIL['name'],0,-4));
	
	$img = new imagick();
	
	//this must be called before reading the image, otherwise has no effect - "-density {$x_resolution}x{$y_resolution}"
	//this is important to give good quality output, otherwise text might be unclear
	$img->setResolution(200,200);
	
	
	
	if($_POST['pagn']>0){
		
		echo "procesando pag". $_POST['pagn'];
		$i=$_POST['pagn']-1;
		
		$img->readImage("{$pdf_file}[$i]");
		//set new format
		$img->setImageFormat('jpg');
		$i++;
		$itx=str_pad($i, 4, "0",STR_PAD_LEFT);
		$save_to="./documentos/".$nombre."_$itx.jpg";
		$doc[]="./documentos/".$nombre."_$itx.jpg";
		$img->writeImage($save_to);	
		
	}else{
		
		echo "procesando pdf";
		//read the pdf
		$img->readImage("{$pdf_file}");
		
		$paginas = $img->getNumberImages(); 
		
		
		
		if($paginas==0){
			echo "el pdf no tiene páginas!";
			break;
		}else{
			echo "<br> $paginas paginas";
		}
		
		$i=0;
		while($i<$paginas ){
			
			$img->readImage("{$pdf_file}[$i]");
			//set new format
			$img->setImageFormat('jpg');
			$i++;
			
			$itx=str_pad($i, 4, "0",STR_PAD_LEFT);
			$save_to="./documentos/".$nombre."_$itx.jpg";
			$doc[]="./documentos/".$nombre."_$itx.jpg";
			$img->writeImage($save_to);
		}
	
	
		 	
	}
	
	foreach($doc as $d){
		echo "<a href='$d' download><img style='width:200px' src='$d'></a>";
	}
	
	
}

?>

<script type='text/javascript'>
	window.parent.location='formulario.php';
</script>
