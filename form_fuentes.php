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
			margin-bottom:10px;
			vertical-align:middle;
		}
		h1 > img{
			margin:3px;
		}
		form{
			display: inline-block;
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
		#cargando{
			position:fixed;
			top:60px;
			left:300px;
			background-color:#fff;
			padding:10px;
			border:1px solid #08AFD9;
			display:none;
		}	
		
		.contenedor{
			display:inline-block;
			position:relative;			
		}	
		.borrador{
			position:absolute;
			top:4px;
			left:4px;
		}	
	</style>
	
</head>
<body>
	<script language="javascript" type="text/javascript" src="../jscripts/jquery/jquery-1.8.2.js"></script>	
<?php

echo "
<div class='seccion pdfs'>
	<form target='ventanaccion' action='./pdfaimg.php' method='post' enctype='multipart/form-data'>
		<h1><img src='./img/pdfs.png'>cargar nuevo pdf</h1>
		<input type='file' name='archivo'>
		<br>fijar página: <input type='text' name='pagn'> (opcional, es más rápido)<br>
		<a onclick='document.getElementById(\"cargando\").style.display=\"block\";this.parentNode.submit();'>cargar</a>
	</form>
</div>
";



$scan=scandir('./documentos');

echo "
<div class='seccion jpgs'>
	<h1><img src='./img/jpgs.png'>imágenes ya generadas</h1>
";

if(count($scan)>2){
echo "
	<p>La imágenes generadas (".(count($scan)-2).") serán eliminadas al escanear un nuevo pdf</p>
";

echo "
	<div class='opciones'>
		<form target='ventanaccion' action='./pdfaimg.php' method='post'>
			<input type='hidden' name='accion' value='borrar'>
			<a onclick='this.parentNode.submit();'>¡Borrar Imágenes Ahora!</a>
		</form>
		<a href='./imgahtml.php'>ver salida en formato html para pdf</a>
	</div>
";	
}else{
	echo "
	<p>Cargue un pdf para generar nuevas imágenes del mismo</p>
	";
}






foreach($scan as $f){
	if($f!='.'&&$f!='..'){
		$c++;
		echo "
			<div class='contenedor' id='C$c'>
			<a href='./documentos/$f' download><div class='contenidopolar'><img style='width:100px' src='./documentos/$f'></div><p class='epigrafe'>$f</p></a>
			<a class='borrador' onclick='borrarImg(\"$f\", \"C".$c."\");'>X</a>
			</div>
		";	
		$a=$f;
	}
}

echo "
</div>";

echo "

<div id='cargando'>
	<h1>Cargado y proceando</h1>
	<p><img src='./img/esperaTReCC.gif'> Esto puede llevar unos minutos.</p>
</div>
<iframe style='display:none;' name='ventanaccion' id='ventanaccion'></iframe>
";
/*
 * en deasrrollo, crear pdf desde imagen única
 * Falta instalar librería pdf en PHP
 
	$pdf = PDF_new();
	PDF_open_file($pdf,'.docpdf.pdf');
	PDF_begin_page($pdf,595,842);
	$image = PDF_load_image($pdf,"jpg","$a","");
	PDF_place_image($pdf,$image,64,26,.24);
*/

?>

<script type='text/javascript'>


function borrarImg(_file, _idCont){
		var parametros = {
			"archivo" : _file,
			"accion" : 'borrar',
			"idcont" : _idCont
		};

		//Llamamos a los puntos de la actividad
		$.ajax({
				data:  parametros,
				url:   'pdfaimg.php',
				type:  'post',
				success:  function (response){
					var _res = $.parseJSON(response);
					console.log(_res);
					if(_res.res='exito'){
						_elem=document.getElementById(_res.data.eliminado);
						_elem.parentNode.removeChild(_elem);
					}else{
						console.log(_res);
					}
				}
		});

}

	
	
	
</script>

</body>