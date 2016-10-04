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




function consultaPropEstructura(){
	global $Conec1;

		$query="
	SELECT `PROambitos`.`id`,
	    `PROambitos`.`nombre`,
	    `PROambitos`.`descripcion`
	FROM `sustentabilidad`.`PROambitos`;";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	while($row=mysql_fetch_assoc($consulta)){
		$Ambitos[$row['id']]=$row;
	}
	
	
		$query="
	SELECT `PROareas`.`id`,
	    `PROareas`.`nombre`,
	    `PROareas`.`definicion`,
	    `PROareas`.`problematica`,
	    `PROareas`.`conflictos`
	FROM `sustentabilidad`.`PROareas`;";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	while($row=mysql_fetch_assoc($consulta)){
		$Areas[$row['id']]=$row;
	}
	
		
	$query="
	SELECT `PROestrategias`.`id`,
	    `PROestrategias`.`ident`,
	    `PROestrategias`.`resumen`,
	    `PROestrategias`.`desarrollo`
	FROM `sustentabilidad`.`PROestrategias`
	ORDER BY ident
	;";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	while($row=mysql_fetch_assoc($consulta)){
		$Estrategias[$row['id']]=$row;
	}
		
	foreach($Estrategias as $Ek => $Ev){
		if(!isset($Indice[$Ek])){
			$Indice[$Ek]=array();	
		}
		
		
		foreach($Ambitos as $Amk => $Amv){	
			if(!isset($Indice[$Ek][$Amk])){
				$Indice[$Ek][$Amk]=array();	
			}			
			
			foreach($Areas as $Ark => $Arv){
					
				if(!isset($Indice[$Ek][$Amk][$Ark])){
					$Indice[$Ek][$Amk][$Ark]=array();	
				}

					
			}	
				
		}	
		
	}
	
	$Acciones=array();
	$query="
		SELECT `PROacciones`.`id`,
	    `PROacciones`.`resumen`,
	    `PROacciones`.`desarrollo`,
	    `PROacciones`.`id_p_PROestrategias`,
	    `PROacciones`.`id_p_PROareas`,
	    `PROacciones`.`id_p_PROambitos`
	FROM `sustentabilidad`.`PROacciones`;";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	while($row=mysql_fetch_assoc($consulta)){
		$Acciones[$row['id']]=$row;
		$Indice[$row['id_p_PROestrategias']][$row['id_p_PROambitos']][$row['id_p_PROareas']]['PROaccionesId']=$row['id'];
	}
	
	$query="
	SELECT `PROaccNull`.`id`,
	    `PROaccNull`.`id_p_PROestrategias`,
	    `PROaccNull`.`id_p_PROareas`,
	    `PROaccNull`.`id_p_PROambitos`
	FROM `sustentabilidad`.`PROaccNull`;";
	$consulta=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	while($row=mysql_fetch_assoc($consulta)){
		$Indice[$row['id_p_PROestrategias']][$row['id_p_PROambitos']][$row['id_p_PROareas']]['PROaccNull']=$row['id'];
	}
		
	
	$result['Indice']=$Indice;
	$result['Estrategias']=$Estrategias;
	$result['Ambitos']=$Ambitos;
	$result['Areas']=$Areas;
	$result['Acciones']=$Acciones;
	return $result;
		
}

function consultaClasesAjax(){
	global $Conec1;
	
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


	$query="
		SELECT * 
			FROM `sustentabilidad`.`CLASescalas`
	";
	$consulta=mysql_query($query,$Conec1);
	if(mysql_error($Conec1)!=''){
		$Log['res']='err';
		$Log['tx'][]="error al consultar la base de datos (escala)";
		$Log['tx'][]=mysql_error($Conec1);
		$Log['tx'][]=$query;
		terminar($Log);
	}
		
	$Clases['escalas']=array();
	while($row=mysql_fetch_assoc($consulta)){
		foreach($row as $k => $v){
			$r[$k]= utf8_encode($v);
		}
		$Clases['escalas'][$row['id']]=$r;
		unset($r);
	}
	
	
	$query="
		SELECT * 
			FROM `sustentabilidad`.`CLASfases`
	";
	$consulta=mysql_query($query,$Conec1);
	$consulta=mysql_query($query,$Conec1);
	if(mysql_error($Conec1)!=''){
		$Log['res']='err';
		$Log['tx'][]="error al consultar la base de datos (fases)";
		$Log['tx'][]=mysql_error($Conec1);
		$Log['tx'][]=$query;
		terminar($Log);
	}
	
	$Clases['fases']=array();
	while($row=mysql_fetch_assoc($consulta)){
		foreach($row as $k => $v){
			$r[$k]= utf8_encode($v);
		}
		$Clases['fases'][$row['id']]=$r;
		unset($r);
		
	}

	$query="
		SELECT * 
			FROM `sustentabilidad`.`CLASmedio`
	";
	$consulta=mysql_query($query,$Conec1);
	$consulta=mysql_query($query,$Conec1);
	if(mysql_error($Conec1)!=''){
		$Log['res']='err';
		$Log['tx'][]="error al consultar la base de datos (medio)";
		$Log['tx'][]=mysql_error($Conec1);
		$Log['tx'][]=$query;
		terminar($Log);
	}
	
	$Clases['medio']=array();
	while($row=mysql_fetch_assoc($consulta)){
		foreach($row as $k => $v){
			$r[$k]= utf8_encode($v);
		}
		$Clases['medio'][$row['id']]=$r;
		unset($r);
	}	

	$query="
		SELECT * 
			FROM `sustentabilidad`.`CLAStipos`
	";
	$consulta=mysql_query($query,$Conec1);
	$consulta=mysql_query($query,$Conec1);
	if(mysql_error($Conec1)!=''){
		$Log['res']='err';
		$Log['tx'][]="error al consultar la base de datos (tipos)";
		$Log['tx'][]=mysql_error($Conec1);
		$Log['tx'][]=$query;
		terminar($Log);
	}
	
	$Clases['tipos']=array();
	while($row=mysql_fetch_assoc($consulta)){
		foreach($row as $k => $v){
			$r[$k]= utf8_encode($v);
		}
		$Clases['tipos'][$row['id']]=$r;
		unset($r);
	}	
	
	
	$Log['res']='exito';
	$Log['data']['clases']=$Clases;
	terminar($Log);
}


	