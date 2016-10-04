<!DOCTYPE html>
<head>
	 
	   <title>RED SUSTENTABLE DE LA CONSTRUCCIÓN</title>
	   <link href="./img/pdfajpg.ico" type="image/x-icon" rel="shortcut icon">
	   <link rel="stylesheet" type="text/css" href="./css/panelbase.css" />   
	<style>
		body{
			
		}
		table{
			border: 1px solid black;
    		border-collapse: collapse;
			background-color:#e8f6fb;
		}
		td, th{
			max-width:20em;
			border: 1px solid black;
    		border-collapse: collapse;
    		font-size:12px;
		}
		td{
			vertical-align:top;
		}
		
		td a{
			padding:0;
			border-radius:2px;
			font-size 11px;
			min-width:11px;
			text-align:center;
			margin:0px 1px 0px 0px;
			vertical-align:top;
			
		}
		
		a, img{
			display: inline-block;
		}
		a{
			color:#000; 
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
			margin-bottom:0px;
			vertical-align:middle;
		}
		h1 > img{
			margin:3px;
		}
		
		div.proyecto{
			background-color:#fff;
			border:2px solid #ccc;
			display:block;
			min-height:90px;
			margin:2px;
		}
		div.variables{
			display:inline-block;
			position:relative;
		    border:1px solid #000;
		    font-size:10px;
		    width:204px;
		} 
		
		div.periodo{
			display:block;
			position:relative;
			width:202px;
			margin:1px;
		}
		
		div.periodo.N{
			background-color:#44a;
		}
		div.periodo.D{
			background-color:#bbf;
			
		}
		
		div.registro{
			display:block;
			position:relative;
			width:100%;
			margin:1px;
		}
		
		div.registro > div{
			display:inline-block;
			position:relative;
			width:50px;
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
		div.doc{
			display:inline-block;
			border:1px solid #000;
			margin:1px;
			font-size:11px;			
		}	
		h3, h4{
			margin-bottom:0px;
			margin-top:10px;
		}	
		h3>span.nombre{
			display:inline-block;
			margin:1px;
			border:1px solid #444;
			width:500px;
			font-size: 14px;
		}
		h3>a.procesar{
			display:inline-block;
			margin:1px;
			border:1px solid #444;
			width:60px;
			font-size:12px;
			padding:0px;
		}
		.LMP{
			display:inline-block;
			position:relative;
		}
		.leyenda{
			position:absolute;
			top:-20px;
			font-size:12px;
		}
		.Rev{
			position:relative;
			display:inline-block;
		}
		.adjuntador{
		    display: inline-block;
		    height: 18px;
		    vertical-align: top;
		    width: 90px;
		}
		.upload{
			font-size:12px;
		}
		iframe{
			width:20em;
			height:0em;
			position:fixed;
			right:0px;
			top:0px;
		}
		td.anulada{
			background-color:#ccc;
			background-image: url('./img/anulada.png');
			background-size:100% 100%;
			
			
		}
		div.accion{
			display:inline-block;
			width:10em;
			min-height:40px;
		}
		tr.fm1{
			border-top:2px solid #000;
		}
		.botonera{
			width:0px;
			height:0px;
			display:inline-block;
			position:relative;
			overflow:visible;
						vertical-align:top;
		}		
		.botones{
			width:60px;
			height:10px;
			display:block;
			position:absolute;
			top:0px;
			left:0px;
			vertical-align:top;
		}
	</style>
	
</head>
<body>
<?php

include('./includes/encabezado.php');
include('./cons_general.php');


echo "
<iframe id='ventanaaccion' name='ventanaaccion'></iframe>
";

$Result=consultaPropEstructura();
//echo "<pre>";print_r($Result);echo "</pre>";
$Indice=$Result['Indice'];
$Estrategias=$Result['Estrategias'];
$Areas=$Result['Areas'];
$Ambitos=$Result['Ambitos'];
$Acciones=$Result['Acciones'];


	echo "<table>";
	
		echo "<tr class='TOT'>";
		echo "<th>";
				echo "estrategias";
			echo "</th>";
			echo "<th>";
				echo "ambito";
			echo "</th>";
		
		$e=	reset(reset($Indice));
		foreach($e as $areaid => $area){
			echo "<td class='nombreP'>";
				echo $Areas[$areaid]['nombre'];			
			echo "</td>";			
		}
		echo "</tr>";

			
	foreach($Indice as $Esk => $Esd){
			$a=1;
			echo "<tr class='fm$a'>";
			echo "<th rowspan='".count($Esd)."'>";
			echo $Estrategias[$Esk]['ident']."<br>".$Estrategias[$Esk]['resumen'];
			echo "</th>";	
				
				foreach($Esd as $Amk => $Amd){
					if($a>1){echo "<tr class='fm$a'>";}
					
					echo "<th>";
					echo $Ambitos[$Amk]['nombre'];
					echo "</th>";					
						foreach($Amd as $Ark => $Ard){
							if($Ard['PROaccNull']>0){
								$ClassNull='anulada';
								$botonnull="<a target='ventanaaccion' href='ed_prop.php?tabla=PROaccNull&accion=borra&id=".$Ard['PROaccNull']."'>habilitar</a>";
								$botonAccE='';
								$botonAccAd='';
							}else{
								$ClassNull='';
								$botonnull="<a target='ventanaaccion' href='ed_prop.php?tabla=PROaccNull&accion=anulacelda&id_p_PROestrategias=$Esk&id_p_PROambitos=$Amk&id_p_PROareas=$Ark'>/</a>";
								$botonAccE="<a target='ventanaaccion' href='form_tabla.php?tabla=PROacciones&accion=cambia&id=".$Acciones[$Ark]['resumen']."'>@</a>";
								$botonAccAd= "<a href='form_tabla.php?salida=form_tabla&salidatabla=BPbuenasprac&salidaid=nid&tabla=BPbuenasprac&accion=agrega&id_p_PROestrategias=$Esk&id_p_PROamb=$Amk&id_p_PROarea=$Ark'>+</a>";
							}
							echo "<td title='$tit' class='$ClassNull'>";
								
								echo "<div class='botonera'>";
									echo "<div class='botones'>";
										echo $Acciones[$Ark]['resumen'];
										echo $botonAccE;
										echo $botonAccAd;
										echo $botonnull;
									echo "</div>";
								echo "</div>";
								echo "<div class='accion'></div>";
							echo "</td>";			
						}
	    
					echo "</tr>";	
					$a++;		
				}
					
			echo "</tr>";		
				
		

	}
		echo "<tr class='TOT'>";
		echo "<th>Total</th>";	
		echo "</tr>";
		echo "<tr class='TOT'>";
		echo "<th>";
				echo "cantidad de alertas activas";
			echo "</th>";
			
		foreach($Columnas as $panel => $sd){
			echo "<td>";
			echo $AlertaPanel[$panel];
			echo "</td>";			
		}
		echo "</tr>";
echo "</table>";

?>

        <script type="text/javascript">        
                    window.scrollTo(0,'<?php echo $_GET['y'];?>');     
        </script>
        
       <?php
        
/*
$resSim=consultaRepres();
//echo "<pre>";print_r($resSim);echo "</pre>";
echo "<div id='menu'><a href='indice_simbologias.php'>configurar simbologías</a></div>";



$ID='';


$Arr=consultaProyTs($ID);
//echo "<pre>";print_r($Arr);echo "</pre>";
echo "<iframe style='position:absolute;top:5px;right:10px;height:60px;' id='cargaimagen' name='cargaimagen'></iframe>";
		
echo "<h2>Listado de proyectos</h2>";
echo "<a href='./form_tabla.php?accion=agrega&tabla=PROproyectos&salida=indice_proyectos'>crear nuevo proyecto</a>";

foreach($Arr['Proyectos'] as $pro){
	echo "<div class='proyecto'>";
	echo "<h3><span class='nombre'>".$pro['id']."_ ".$pro['nombre']."</span>";
		echo "<a class='procesar' href='./proces_proyecto.php?id=".$pro['id']."'>procesar</a>";
		echo "<a class='procesar' href='./resultados_proyecto.php?id=".$pro['id']."'>ver resultados guardados</a>";
		echo "<form class='adjuntador' enctype='multipart/form-data' method='post' action='./ed_agrega_adjunto.php' target='cargaimagen'>";
		
			echo "<label style='height: 18px;position: relative;width: 170px;display:inline-block;' class='upload'>";
			echo "<span class='upload' style='color:gray;padding:2px;display:block;position:absolute;top:0px;left:0px;width:170px;height:12px;vertical-align:middle;border:1px solid #000;margin:0px;'> - arrastre aquí un archivo - </span>";
			echo "<input id='uploadinput' style='display:block;position:absolute;top:0px;left:0px;opacity:0;width:170px;height:18px;vertical-align:middle;border:1px solid #000;margin:1px;' type='file' name='upload' value='' onchange='this.parentNode.parentNode.submit();'></label>";
			echo "<input type='hidden' id='actividad' name='proyecto' value='".$pro['id']."'>";
			echo "<input type='hidden' id='actividad' name='salida' value='indice_proyectos'>";

		echo "</form>";
	echo "</h3>";

	foreach($pro['variables'] as $vN => $vnd){
		
		
	
		foreach($vnd as $vPr => $vpd){
				echo "<div class='variables'>";
				echo "<h4>$vN - $vPr</h4>";
			foreach($vpd as $vPe => $vhd){
				echo "<div class='periodo $vPe'>";
				foreach($vhd as $vH => $var){
					echo "<div class='registro'>";
						
						echo "<div>";
						echo "$vH";
						echo "</div>";
						
						echo "<div>";
						echo $var['estado'];
						echo "</div>";
						
						echo "<div>";
						echo $var['resultado'];
						echo "</div>";
					
						echo "<div>";
						echo $var['desde'];
						echo "</div>";
					echo "</div>"; 
					
				}	
				echo "</div>";	
			}
			echo "</div>";			
		}
		
	}

	echo "</div>";
}

?>

</body>