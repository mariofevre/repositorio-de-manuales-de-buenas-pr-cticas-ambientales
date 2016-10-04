<?php
/**
* ed_agrega.php
*
* aplicación para generar registros genéricos
 * 
 *  
* @package    	Plataforma Colectiva de Información Territorial: UBATIC2014
* @subpackage 	actividad
* @author     	Universidad de Buenos Aires
* @author     	<mario@trecc.com.ar>
* @author    	http://www.uba.ar/
* @author    	http://www.trecc.com.ar/recursos/proyectoubatic2014.htm
* @author		based on TReCC SA Procesos Participativos Urbanos, development. www.trecc.com.ar/recursos
* @copyright	2015 Universidad de Buenos Aires
* @copyright	esta aplicación se desarrollo sobre una publicación GNU 2014 TReCC SA
* @license    	http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 (GPL-3.0)
* Este archivo es parte de TReCC(tm) paneldecontrol y de sus proyectos hermanos: baseobra(tm), TReCC(tm) intraTReCC  y TReCC(tm) Procesos Participativos Urbanos.
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU General Public License" 
* publicada por la Free Software Foundation, version 3
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NINGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
* 
*
*/
include('./includes/encabezado.php');
include('./cons_general.php');

if(count($_POST)>0){
	$Entrada=$_POST;
}else{
	$Entrada=$_GET;
}


	$Tabla = $Entrada['tabla'];
	$Id = $Entrada['id'];
	$Accion = $Entrada['accion'];
	
	$Campo = $Entrada['campo'];
	$Salida = $Entrada['salida'];
	$Salidaid = $Entrada['salidaid'];	
	$Salidatabla = $Entrada['salidatabla'];	

	$Base = $_SESSION["AppSettings"]->DATABASE_NAME;
	$Log['tx'][]="base: $Base";
	
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

	//ver $CODELIM en encabezado //$CODIGOELIMINACION = '-[-BORRX-]-'; //esta es la codificación con la que debe recibirse un campo que debe ser eliminado, a diferencia de un campo sobre el que no halla cambios requeridos.

	$Log['tx'][]="tabla: $Tabla ".$Entrada['tabla'];
	$query="SELECT * FROM $Base.$Tabla WHERE id='$Id'";
	$Consulta = mysql_query($query,$Conec1);	
	$_SESSION['DEBUG']['mensajes'][] = mysql_error($Conec1);	
$Log['tx'][]="<br>".$query;	
$Log['tx'][]="<br>".mysql_error($_SESSION['panelcontrol']->Conec1);	
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
					if($datomas == "n"){
						$Log['tx'][]="<br>nuevo item";
						$extraset='';
						$Baselink = substr($row['Field'],0,6);
						if($Baselink != "id_p_B")
						{
							$Log['tx'][]="<br>padre interno";
							$o = explode("_", $row['Field']);
							$basepadre = $Base;
							$tablapadre = $o[2];
							$campopadre = $o[4];
							$extra = "";
							$padre = $basepadre . "." . $tablapadre;
							$campocont = $campo."_n";
							$nuevocontenido=$Entrada[$campocont];	
						}
					}
					$Log['tx'][]="<br>otro;". " - " . $row['Field']. " - " . $datomas."<br>";
						if($datomas != ""){
						$Datos .= " `" . $campo . "`='" .  $datomas . "',";
						}
				}elseif($Typo == 'FI_'){
					
					$Log['tx'][]="Dectectado campo de fichero (FI_), se guardaran los archivos enviados:<br>";
					echo "<pre>";print_r($_FILES);echo "</pre>";
					$NombrePHParchivo='archivo_'.$campo;					
					if(isset($_FILES[$NombrePHParchivo])){
						$imagenid = $_FILES[$NombrePHParchivo]['name'];	
						$Log['tx'][]="<br>cargando: ".$imagenid."<br>";
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
							$Log['tx'][]="probando ruta: ".$rutaacumulada."<br>";
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
							$Log['tx'][]="guardado en: ".$nombre."<br>";
							
							if (!copy($_FILES[$NombrePHParchivo]['tmp_name'], $nombre)) {
							    $Log['tx'][]="Error al copiar $pathI...\n";
							}else{
								$Log['tx'][]="imagen guardada";
								$datomas = $nombre;	
								$Datos .= " `" . $campo . "`='" .  $datomas . "',";
								$_SESSION['DEBUG']['mensajes'][] = 'sentencia: '.$campo . "`='" .  $datomas . "',";
							}
						}else{
							$Log['tx'][]="solo se aceptan los formatos: jpg, png, tif, gif, bmp, pdf";
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
						
						$Log['tx'][]="<br>text;". " - " . $row['Field']. " - " . $datomas;
							if($datomas != ""){
								if($datomas == $CODELIM){$datomas ='';}
								$Datos .= " `" . $campo . "`='" .  $datomas . "',";
							}
					}elseif($Type == "dat"){
						$Log['tx'][]="<br>fecha;". " - " . $row['Field']. " - " . $datomas;
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
						$Log['tx'][]="<br>campo:" . $row['Field']. ", ".$row['Comment']." - :" . $datomas."<br>";
							if($datomas != ""){
								if($datomas == $CODELIM){$datomas ='';}
								$Datos .= " `" . $campo . "`='" .  $datomas . "',";
							}
					}
	        	}
				
		    } 
			
		}	
		
		$Datos = substr($Datos,0,(strlen($Datos)-1));

		
	
		
		$Log['tx'][]="tabla: ".$Tabla."<br>";
		
		$query="INSERT INTO $Base.$Tabla SET $Datos";
		$NID=mysql_insert_id($Conec1);
		if($Salidaid=='nid'){$Salidaid=$NID;}
		mysql_query($query,$Conec1);
		$Log['tx'][]="query: $query" ;
		
		$Log['tx'][]=mysql_error($Conec1);
			

		$_SESSION['DEBUG']['mensajes'][] = $Salida;
		$_SESSION['DEBUG']['mensajes'][] =".php?tabla=";
		$_SESSION['DEBUG']['mensajes'][] = $Salidatabla;
		$_SESSION['DEBUG']['mensajes'][] ="&id=";
		$_SESSION['DEBUG']['mensajes'][] = $Salidaid;
	
		print_r($Log);
		if($Salida==''){$Salida="indice_propuestas";}	
	
		if($Salida!=''){
			$cadenapasar='';
			foreach($PASAR as $k => $v){
				$cadenapasar.='&'.substr($k,5).'='.$v;
			}	
			
			break;
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
