<?php
/**
* consultas.php
*
* reliza consultas a la base de datos  
* 
* @package    	TReCC(tm) redsustentable.
* @subpackage 	
* @author     	TReCC SA
* @author     	<mario@trecc.com.ar> <trecc@trecc.com.ar>
* @author    	www.trecc.com.ar  
* @copyright	2015 TReCC SA
* @license    	http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 (GPL-3.0)
* trabajo derivado de agrega_f.php copyright: 2010 TReCC SA (GPL-3.0)
* Este archivo es parte de TReCC(tm) paneldecontrol y de sus proyectos hermanos: baseobra(tm) y TReCC(tm) intraTReCC.
* Este archivo es software libre: tu puedes redistriburlo 
* y/o modificarlo bajo los términos de la "GNU General Public License" 
* publicada por la Free Software Foundation, version 3
* 
* Este archivo es distribuido por si mismo y dentro de sus proyectos 
* con el objetivo de ser útil, eficiente, predecible y transparente
* pero SIN NIGUNA GARANTÍA; sin siquiera la garantía implícita de
* CAPACIDAD DE MERCANTILIZACIÓN o utilidad para un propósito particular.
* Consulte la "GNU General Public License" para más detalles.
* 
* Si usted no cuenta con una copia de dicha licencia puede encontrarla aquí: <http://www.gnu.org/licenses/>.
*/

include('./includes/encabezado.php');

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



if(isset($_POST['id'])){
	$where= "WHERE id_h_BPbuenasprac_id='".$_POST['id']."'";
}else{
	$where= "";
}


//cargua el tagueo de esclas
$query="
	SELECT *
	FROM `sustentabilidad`.`BPclasifEscala`
	$where
";
$consulta=mysql_query($query,$Conec1);
if(mysql_error($Conec1)!=''){
	$Log['res']='err';
	$Log['tx'][]="error al consultar la base de datos (escalas)";
	$Log['tx'][]=mysql_error($Conec1);
	$Log['tx'][]=$query;
	terminar($Log);
}
while($row=mysql_fetch_assoc($consulta)){
	$escalas[$row['id_h_BPbuenasprac_id']][$row['id_h_CLASescalas_id']]=$row;
}

$query="
	SELECT *
	FROM `sustentabilidad`.`BPclasifFases`
	$where
";
$consulta=mysql_query($query,$Conec1);
if(mysql_error($Conec1)!=''){
	$Log['res']='err';
	$Log['tx'][]="error al consultar la base de datos (fases)";
	$Log['tx'][]=mysql_error($Conec1);
	$Log['tx'][]=$query;
	terminar($Log);
}
while($row=mysql_fetch_assoc($consulta)){
	$fases[$row[id_h_BPbuenasprac_id]][$row['id_h_CLASfases_id']]=$row;
}

$query="
	SELECT *
	FROM `sustentabilidad`.`BPclasifTipo`
	$where
";
$consulta=mysql_query($query,$Conec1);
if(mysql_error($Conec1)!=''){
	$Log['res']='err';
	$Log['tx'][]="error al consultar la base de datos (tipo)";
	$Log['tx'][]=mysql_error($Conec1);
	$Log['tx'][]=$query;
	terminar($Log);
}
while($row=mysql_fetch_assoc($consulta)){
	$tipos[$row[id_h_BPbuenasprac_id]][$row['id_h_CLAStipos_id']]=$row;
}

$query="
	SELECT *
	FROM `sustentabilidad`.`BPclasifMedio`
	$where
";
$consulta=mysql_query($query,$Conec1);
if(mysql_error($Conec1)!=''){
	$Log['res']='err';
	$Log['tx'][]="error al consultar la base de datos (medio)";
	$Log['tx'][]=mysql_error($Conec1);
	$Log['tx'][]=$query;
	terminar($Log);
}
while($row=mysql_fetch_assoc($consulta)){
	$medios[$row[id_h_BPbuenasprac_id]][$row['id_h_CLASmedio_id']]=$row;
}


$query="
SELECT
`BPbuenasprac`.`id`,
`BPbuenasprac`.`nombre`,
`BPbuenasprac`.`descripcion`,
`BPbuenasprac`.`fuente`,
`BPbuenasprac`.`entidad`,
`BPbuenasprac`.`fecha`,
`BPbuenasprac`.`id_p_FUfuentes`,
`BPbuenasprac`.`copia`,
`BPbuenasprac`.`observaciones`,
`BPbuenasprac`.`fuentepags`,
`BPbuenasprac`.`valoracion`,
`BPbuenasprac`.`procedimiento`,
`BPbuenasprac`.`recursos`,
`BPbuenasprac`.`id_p_PROestrategias`
FROM `sustentabilidad`.`BPbuenasprac`
	$where
";

$consulta=mysql_query($query,$Conec1);
	if(mysql_error($Conec1)!=''){
		$Log['res']='err';
		$Log['tx'][]="error al consultar la base de datos";
		$Log['tx'][]=mysql_error($Conec1);
		$Log['tx'][]=$query;
		terminar($Log);
	}
	
while($row=mysql_fetch_assoc($consulta)){
	foreach($row as $k => $v){
		$r[$k]=utf8_encode($v);	
	}
	$r['escala']=$escalas[$row['id']];
	$r['fases']=$fases[$row['id']];
	$r['tipo']=$tipos[$row['id']];
	$r['medio']=$medios[$row['id']];
	$Fuentes['bpa'.$row['id']]=$r;
	unset($r);
}


if(isset($_POST['id'])){
	$where= "WHERE id='".$_POST['id']."'";
}else{
	$where= "";
}



$Log['res']='exito';
$Log['data']['BPAs']=$Fuentes;
terminar($Log);
	