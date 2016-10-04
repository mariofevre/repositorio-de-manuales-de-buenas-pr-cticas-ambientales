<?php
/**
* ed_agrega_adjunto.php
*
* aplicación para guardar y registrar en la base correspondiente un archivo subido por el usuario
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
$Base=$_SESSION['AppSettings']->DATABASE_NAME;

$Hoy=date("Y-m-d");

$nombre=str_pad($_POST['id'], 5, '0', STR_PAD_LEFT);	
$path='./documentos/fuentes/originales/';
if(!file_exists($path)){
	$Log['tx'][]="creando carpeta $path";mkdir($path, 0777, true);chmod($path, 0777);	
}
if(!file_exists($path)){
	$Log['tx'][]="Error al crear la carpeta";
}
	
	
if($_POST['accion']=='cargar'){
	
	$b = explode(".", $_FILES['upload']['name']);
	$ext = strtolower($b[(count($b)-1)]);
	$nuevonombre= $path.$nombre.".".$ext;
	
	
	$extVal['pdf']='1';		
	if(!isset($extVal[strtolower($ext)])){
		$Log['tx'][]="solo se aceptan los formatos:";
		foreach($extVal as $k => $v){$Log['tx'][]=" $k,";}			
	}	
	
	if (!copy( $_FILES['upload']['tmp_name'], $nuevonombre)) {
	   $Log['tx'][]= "Error al copiar $nuevonombre";
	}else{
		$Log['tx'][]= "archivo guardado. ";		
	
		$query="UPDATE $Base.FUfuentes SET FI_copialocal='".$nuevonombre."' WHERE id='".$_POST['id']."'";	
		$cons=mysql_query($query,$Conec1);	
		if(mysql_error($Conec1)!=''){
			$Log['res']='err';
			$Log['tx'][]='Error al editar registro en base de datos';
			$Log['tx'][]=mysql_error($Conec1);
			$Log['tx'][]=$query;
			terminar($Log);	
		}	
	}
}else{	
		$query="UPDATE $Base.FUfuentes SET FI_copialocal='' WHERE id='".$_POST['id']."'";	
		$cons=mysql_query($query,$Conec1);	
		if(mysql_error($Conec1)!=''){
			$Log['res']='err';
			$Log['tx'][]='Error al editar registro en base de datos';
			$Log['tx'][]=mysql_error($Conec1);
			$Log['tx'][]=$query;
			terminar($Log);	
		}	
}
	
?>
