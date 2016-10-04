<?php
include("../includes/mensajesdesarrollo.php");// carga sistema de mensajes de desarrollo
include ('./includes/encabezado.php');	
rendimiento_checkpoint('encabezado cargado: ',__DIR__,__FILE__,__LINE__);//medicion rendimiento lamp
//include ('./fichacontrato/consultas.php');
rendimiento_checkpoint('ingesado: ',__DIR__,__FILE__,__LINE__);//medicion rendimiento lamp
//include ('./consultanovedades.php');
include ('../includes/usuarioaccesos.php');
rendimiento_checkpoint('conectado: ',__DIR__,__FILE__,__LINE__);//medicion rendimiento lamp
$Seccion = $_SESSION['AppSettings']->SECCION;
$Usuario = usuarioaccesos();// en ./includes/usuarioaccesos.php

if(count($_POST)>0){
	$Entrada=$_POST;
}else{
	$Entrada=$_GET;
}

	$Id_contrato = $Entrada['contrato'];
	$Tabla = $Entrada['tabla'];
	$Id = $Entrada['id'];
	$Accion = $Entrada['accion'];
	
	$Campo = $Entrada['campo'];
	$Salida = $Entrada['salida'];
	$Salidaid = $Entrada['salidaid'];	
	$Salidatabla = $Entrada['salidatabla'];	
	

	$Base = $_SESSION['panelcontrol']->DATABASE_NAME;
	$Index = $_SESSION['panelcontrol']->INDEX;
	
	$HOY = date(Y."-".m."-".d);
	
	$HOYa = date(Y);
	$HOYm = date(m);
	$HOYd = date(d);
	
	
	foreach($Entrada as $k => $v){// estas variables son pasadas por als aplicaciones comunes manteniendose.
		if(substr($k,0,5)=='PASAR'){
			$PASAR[$k]=$v;
		}
	}
	
	?>
	<head>
	<style>
	img{
		width:150px;
	}
	div{
		display:inline;
	}
	</style>
	</head>
	<?php

	//ver $CODELIM en encabezado // $CODIGOELIMINACION = '-[-BORRX-]-'; //esta es la codificación con la que debe recibirse un campo que debe ser eliminado, a diferencia de un campo sobre el que no halla cambios requeridos.

	$_SESSION['DEBUG']['mensajes'][] = "tabla: $Tabla ".$Entrada['tabla'];
echo "tabla: $Tabla ".$Entrada['tabla'];
	$query="SELECT * FROM $Base.$Tabla WHERE id='$Id'";
	$Consulta = mysql_query($query,$Conec1);	
	$_SESSION['DEBUG']['mensajes'][] = mysql_error($Conec1);	
echo "<br>".$query;	
echo "<br>".mysql_error($_SESSION['panelcontrol']->Conec1);	
 	$result = mysql_query('SHOW FULL COLUMNS FROM `'.$Tabla.'`',$Conec1);
	$_SESSION['DEBUG']['mensajes'][] = mysql_error($Conec1);
			


		//print_r($Entrada);
		
	    if (mysql_num_rows($result) > 0) {
	    			    	
	        while ($row = mysql_fetch_assoc($result)) {
	        	
	        	$campo = $row['Field'];
	        	$datomas = $Entrada[$campo];
				$Typo = substr($row['Field'],0,3);			
				$Typolink = substr($row['Field'],0,4);
					
				
				if($campo=='id'){
					
				}elseif($Typolink == "id_p"){
					$_SESSION['DEBUG']['mensajes'][] = "<br>padre en: ".$campo. "->".$datomas;
					if($datomas == "n"){
						$_SESSION['DEBUG']['mensajes'][] = "<br>nuevo item";
						$extraset='';
						$Baselink = substr($row['Field'],0,6);
						if($Baselink != "id_p_B")
						{
							$_SESSION['DEBUG']['mensajes'][] = "<br>padre interno";
							$o = explode("_", $row['Field']);
							$basepadre = $Base;
							$tablapadre = $o[2];
							$campopadre = $o[4];
							$extra = "";
							if($o[5]=='tipoa'){$extra = ", tipo = 'a'";}
							elseif($o[5]=='tipob'){$extra = ", tipo = 'b'";}
							elseif($o[5]=='tipo'){$extra.=", ".$o[5]."='".$o[6]."'";}
							$padre = $basepadre . "." . $tablapadre;
							$campocont = $campo."_n";
							$nuevocontenido=$Entrada[$campocont];
							
							
							
						}
					}
					$_SESSION['DEBUG']['mensajes'][] = "<br>otro;". " - " . $row['Field']. " - " . $datomas."<br>";
						if($datomas != ""){
						$Datos .= " `" . $campo . "`='" .  $datomas . "',";
						}
				}elseif($Typo == 'FI_'){
					
					$_SESSION['DEBUG']['mensajes'][] = "Dectectado campo de fichero (FI_), se guardaran los archivos enviados:<br>";
					echo "<pre>";print_r($_FILES);echo "</pre>";

					$NombrePHParchivo='archivo_'.$campo;
					
					if(isset($_FILES[$NombrePHParchivo])){
						$imagenid = $_FILES[$NombrePHParchivo]['name'];	
						$_SESSION['DEBUG']['mensajes'][] = "<br>cargando: ".$imagenid."<br>";
						$b = explode(".",$imagenid);
						$ext = $b[(count($b)-1)];
	
						$path = $Entrada[('archivo_'.$campo.'_path')];	
						
						/* verificar y crear directorio */
						$path;
							$Publicacion.="analizando ruta de guardado<br>";
							$carpetas= explode("/",$path);
							$rutaacumulada="";
							foreach($carpetas as $valor){
								
							$rutaacumulada.=$valor."/";
							$_SESSION['DEBUG']['mensajes'][] = "probando ruta: ".$rutaacumulada."<br>";
								if (!file_exists($rutaacumulada)&&$valor!=''){
									$Publicacion.="creando: $rutaacumulada<br>";
								    mkdir($rutaacumulada, 0777, true);
								    chmod($rutaacumulada, 0777);
								}
							}
						/* FIN verificar y crear directorio */	
												
						$nombretipo = $Tabla.$Id;
						$nombrerequerido = isset($Entrada[$Campo]) ? $Entrada[$Campo]:'';
						
												
																		
						if($nombrerequerido!=''&&!file_exists($nombrerequerido)){
							$nombre=$nombrerequerido;
						}else{
							$nombre=$nombretipo;						
						}				
						
						$c=explode('.',$nombre);
						
						$cod = cadenaArchivo(10); // define un código que evita la predictivilidad de los documentos ante búsquedas maliciosas
						$nombre=$path.$c[0].$cod.".".$ext;
	
						
						if($ext=="JPG"||$ext=="jpg"||$ext=="png"||$ext=="PNG"||$ext=="tif"||$ext=="TIF"||$ext=="bmp"||$ext=="BMP"||$ext=="gif"||$ext=="GIF"||$ext=="pdf"||$ext=="PDF"){
							$_SESSION['DEBUG']['mensajes'][] = "guardado en: ".$nombre."<br>";
							
							if (!copy($_FILES[$NombrePHParchivo]['tmp_name'], $nombre)) {
							    $_SESSION['DEBUG']['mensajes'][] = "Error al copiar $pathI...\n";
							}else{
								$_SESSION['DEBUG']['mensajes'][] = "imagen guardada";
								$datomas = $nombre;	
								$Datos .= " `" . $campo . "`='" .  $datomas . "',";
								$_SESSION['DEBUG']['mensajes'][] = 'sentencia: '.$campo . "`='" .  $datomas . "',";
							}
						}else{
							$_SESSION['DEBUG']['mensajes'][] = "solo se aceptan los formatos: jpg, png, tif, gif, bmp, pdf";
							$imagenid='';
						}
						
						
					}elseif(isset($Entrada[$campo])){
						
						$Datos .= " `" . $campo . "`='" .  $datomas . "',";
						
					}
				
				}elseif($Typo == 'zz_'&& $campo == 'zz_AUTOFECHAMODIF'){
					
					$Datos .= " `" . $campo . "`='" .  $HOY . "',";
				
				}elseif($Typo == 'zz_'&& $campo == 'zz_AUTOFECHACREACION'){
					/* este campo nunca se debe modificar, debe ser una impresión del momento de creación del registro */
				}else{
					$Type = substr($row['Type'],0,3);
			
					if ($Type == "tex"||$Type == "lon"){
						$datomas = str_replace("'",'"',$Entrada[$campo]);
						$datomas = str_replace("<br />","",$datomas);
						
						$_SESSION['DEBUG']['mensajes'][] = "<br>text;". " - " . $row['Field']. " - " . $datomas;
							if($datomas != ""){
								if($datomas == $CODELIM){$datomas ='';}
								$Datos .= " `" . $campo . "`='" .  $datomas . "',";
							}
					}elseif($Type == "dat"){
						$_SESSION['DEBUG']['mensajes'][] = "<br>fecha;". " - " . $row['Field']. " - " . $datomas;
						$campo_a = $campo . "_a";
						$campo_m = $campo . "_m";
						$campo_d = $campo . "_d";
						
						$datomas = $Entrada[$campo_a] . "-" . $Entrada[$campo_m] . "-" . $Entrada[$campo_d];
							
							
							if($datomas != "--"){
								if($datomas=='0000-00-00'){$datomas=" null ";}else{$datomas="'$datomas'";}
								$Datos .= " `" . $campo . "`=" .  $datomas . ",";
							}
					}else{
						$datomas = $Entrada[$campo];
						$_SESSION['DEBUG']['mensajes'][] = "<br>campo:" . $row['Field']. ", ".$row['Comment']." - :" . $datomas."<br>";
							if($datomas != ""){
								if($datomas == $CODELIM){$datomas ='';}
								$Datos .= " `" . $campo . "`='" .  $datomas . "',";
							}
					}
	        	}
				
		    } 
			
		}	
		
		$Datos = substr($Datos,0,(strlen($Datos)-1));
		

		
		
	
		
		echo "tabla: ".$Tabla."<br>";
		
		$query="UPDATE $Base.$Tabla SET $Datos WHERE id='$Id'";
		
		mysql_query($query,$Conec1);
		
		$_SESSION['DEBUG']['mensajes'][]="query=$query" ;
		
		$_SESSION['DEBUG']['mensajes'][] = mysql_error($Conec1);
		echo mysql_error($Conec1);
		echo $query;
		

		$_SESSION['DEBUG']['mensajes'][] = $Salida;
		$_SESSION['DEBUG']['mensajes'][] =".php?tabla=";
		$_SESSION['DEBUG']['mensajes'][] = $Salidatabla;
		$_SESSION['DEBUG']['mensajes'][] ="&id=";
		$_SESSION['DEBUG']['mensajes'][] = $Salidaid;
	
		if($Salida==''){$Salida="procesarbase";}
	
	
	
		if($Salida!=''){
			$cadenapasar='';
			foreach($PASAR as $k => $v){
				$cadenapasar.='&'.substr($k,5).'='.$v;
			}	
		?>
		    <SCRIPT LANGUAGE="javascript">
			   location.href = "./<?php echo $Salida;?>.php?tabla=<?php echo $Salidatabla;?>&id=<?php echo $Salidaid.$cadenapasar;?>";
		    </SCRIPT>
		<?php
		}else{
			
		?>
		
		    <SCRIPT LANGUAGE="javascript">
			   window.close();
		    </SCRIPT>
		<?php   
		
		}
