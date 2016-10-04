<?php

//include("../includes/mensajesdesarrollo.php");// carga sistema de mensajes de desarrollo
include ('./includes/encabezado.php');	

ini_set('display_errors', '1');

//if($_SERVER[SERVER_ADDR]=='192.168.0.252')ini_set('display_errors', '1');ini_set('display_startup_errors', '1');ini_set('suhosin.disable.display_errors','0'); error_reporting(-1);

	$Base=$_SESSION['AppSettings']->DATABASE_NAME;
	$Tabla = $_GET["tabla"];
	$Accion = $_GET["accion"];
	if($Accion!='agrega'){
		$Id = $_GET["id"];
	}else{
		$Id='';
	}
	//$Modo = $_GET["modo"];///???

	//$query = "SHOW TABLES FROM $Base";
	
	$query="select column_name, column_comment , table_name 
		from `information_schema`.`columns`  
		where `table_schema` = 'sustentabilidad' ";
	$algo=mysql_query($query,$Conec1);
	echo mysql_error($Conec1);
	while($row=mysql_fetch_assoc($algo)){
		//print_r($row);
		$Tablas[$row['table_name']]['columnas'][$row['column_name']]=$row['column_comment'];
		//print_r($row);
		$nc=$row['column_name'];
		$e=explode("_",$nc);
		if(count($e)>2){
			if($e[0]=='id'&&$e[1]=='h'&&$e[2]==$Tabla){
				$Links[$row['table_name']]['link']=$nc;
				$Links[$row['table_name']]['alink']=array();
				$tablasL[$row['table_name']]=$row;
			}
		}
	}
	
	//echo "<pre>";print_r($tablasL);echo "</pre>";
	
	foreach($tablasL as $tn => $tdat){		
		$nc=$tdat['column_name'];
		$nt=$tdat['table_name'];	
		
		//echo "<pre>";print_r($Tablas[$tn]['columnas']);echo "</pre>";
		
				
		foreach($Tablas[$tn]['columnas'] as $kcol => $vcol){
			
			$e=explode("_",$kcol);			
					
			if(count($e)>2){
				$lin=array();
					
				if($e[0]=='id'&&$e[1]=='h'&&$e[2]!=$Tabla){
					$lin[$e[2]]=array();
					
				$query="
					SELECT ".$nc." FROM ".$tn." WHERE 
					".$Links[$tn]['link']."='".$Id."'
				";
				$algo=mysql_query($query,$Conec1);			 
				echo mysql_error($Conec1);
				while($res=mysql_fetch_assoc($algo)){					
					$lin[$e[2]]['val'][$res['id']]=$res;
				}	
				$tablasAL[$e[2]]['campo']=$kcol;
				$tablasAL[$e[2]]['tablaPuente']=$nt;
				
				$tablasAL[$e[2]]['desc']=$vcol;				
				$Links[$tn]['alink']=$lin;		
				}
			}
		}
		
	}
	
	
	//echo "<pre>";print_r($Links);echo "</pre>";

	
	foreach($tablasAL as $key => $val){
		$query="
			SELECT * FROM $key
		";
		$algo=mysql_query($query,$Conec1);
		echo mysql_error($Conec1);		
		$tablasAL[$key]['valores']=array();
		while($row=mysql_fetch_assoc($algo)){
			$tablasAL[$key]['valores'][$row['id']]=$row;
		}				
	}
	

	//echo "<pre>";print_r($tablasAL);echo "</pre>";	
		
	$CampoS=array();
	if(isset($_GET["campos"])){
		$CampoS = $_GET["campos"];
	}

	if(!isset($_GET["campofijo"])){$_GET["campofijo"]='vacio';}
	$campofijo = $_GET["campofijo"];
	if(!isset($_GET["campofijo_c"])){$_GET["campofijo_c"]='';}
	$campofijo_c = $_GET["campofijo_c"];
	
	if(!isset($_GET["campofijob"])){$_GET["campofijob"]='vacio';}
	$campofijob = $_GET["campofijob"];
	if(!isset($_GET["campofijob_c"])){$_GET["campofijob_c"]='';}
	$campofijob_c = $_GET["campofijob_c"];

	
	
	mysql_select_db ('sustentabilidad',$Conec1);
													
	$PASAR=array();
	foreach($_GET as $k => $v){// estas variables son pasadas por als aplicaciones comunes manteniendose.
		if(substr($k,0,5)=='PASAR'){
			$PASAR[$k]=$v;
		}
	}

												
	
	
?>
<!DOCTYPE html>
<head>
	<title>Panel de Control</title>
	<link rel="stylesheet" type="text/css" href="./css/general.css">
	<style type="text/css">
		.gris {
		background-color:lightgray;
		}
		
		input{
			 vertical-align: middle;
			 margin:0px;
		}
		a.selector{
			background: #fff none repeat scroll 0 0;
		    border: 1px solid #08afd9;
		    color: #08afd9;
		    display: inline-block;
		    
		    margin: 1px;
		    vertical-align: middle;
		    padding:2px;
		}
		a.selector:hover{
			background: #08afd9 none repeat scroll 0 0;
		    border: 1px solid #000;
		    color: #000;
		}		
		
		.carga {
		background-color:#A9F5BC;
		}
		
		select{
		width:300px;
		float:left;
		}
		
		form{
		margin:0;
		width:160px;
		width:auto;
		}

		table{
		width:800px;
		margin:5px;
		}

		textarea{
			font-family:'arial';
			font-size:11px;
			width:100%;
		}
		
		div.checkbox{
			display:inline-block;
			width:14px;
			height:14px;
			background-image:url('./img/checkok.png');
			vertical-align:middle;
		}
		
		a[estado='no']{
			background-color:lightblue;
		}
		
		a[estado='no']>div.checkbox{
			display:inline-block;
			width:14px;
			height:13px;
			background-image:url('./img/checkvacio.png');
		}
		
		.dato{
		min-height: 55px;
		border-top:2px solid #000;
		margin-top:2px;
		}
		
		.boton{
		width:80px;		
		}
		
				
		.boton2{
		width:25px;		
		}
		
		.boton3{
		width:150px;
		}
		
		p{
		margin:0 10px;
		}
		
		h1{
		margin:0 10px;
		}
		
		input.fecha{
		float:left;
		width:40px;		
		}		

		input.chico{
		width:300px;
		}		
		
		input.mini{
		float:left;
		width:25px;
		}		
		
		select.chico{
		width:100px;
		}			
			
		th{
		font-size:12px;
		text-align:right;
		background-color:lightblue;
		width:100px;
		}

		.paquete{
		width:300px;
		margin-bottom:5px;
		float:left;
		border: 1px gray solid; 
		padding: 5px;
		background-color:#C4DBE2;
		}
				
		.salva{
		width:90px;
		margin-bottom:5px;
		float:left;
		}

		.reporte{
		width:200px;
		}

		.marco{
		border-bottom: 1px solid gray;
		float: none;
		width: 100%;		
		}
		
		.referencia{
		font-size: 10px;
		}

		.alerta{
		background-color:red;
		}

		.similth{
		background-color:none;
		width:800px;
		text-align:left;
		}	
	
	
		.similth > div{
		background-color:lightblue;
		width:100px;
		margin-left:3px;
		border:white 2px solid;
		text-align:right;
		position:relative;
		top:-4px;
		}
		
	#reporte_bid{
	float:left;
	}
	
	#PTUBA{
	float:left;
	}
	
	#imagenesI{
	float:left;
	}
	
	#imagenesII{
	float:left;
	}
	
	iframe{
		border:1px solid gray;
		width:310px;
		height:80px;
	}

	input[readonly='readonly']{	
		background-color:lightblue;		
		height:4px;
	}

	input[type="button"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;
	}
	input[type="submit"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;	  
	    top:2px;
	    left:200px;
	    width:105px;	      
	}
	input[type="reset"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;		  
	    top:2px;
	    left:310px;      
	    width:105px;	    
	}		

	input[value="Eliminar"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;		  
	    top:2px;
	    left:540px;    
	    width:105px;
	    background-color:#FA5858;
	    font-weight:bold;
	    color:#000;
	}		
	input[value="Recuperar"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;		  
	    top:2px;
	    left:540px;    
	    width:105px;
	    background-color:#FA5858;
	    font-weight:bold;
	    color:#000;
	}	
	input[value="Cancelar"]{
	    height:19px;
	    min-width:60px;
	    position:absolute;		  
	    top:2px;
	    left:420px;    
	    width:105px;
	}		


	
	.dato > a{
	    display: inline-block;
	    height: 12px;
	    overflow: visible;
	    width: 145px;
	}
	
	.dato > a:hover{
	    z-index:30;
	}	
	
	.contenedor{
		font-size:9px;
		height:10px;
		display:inline-block;
		width:20px;
		margin-right:2px;
		position:relative;
		overflow:visible;
		vertical-align: top;
	}
	.contenido{
		background-color:lightblue;
		border:#08AFD9 1px solid;
		color:#000;
		display:inline-block;
		width:19px;
		height:10px;
		margin:0px;
		position:absolute;
		overflow:hidden;
		text-align:center;
	}
	
	.subcontenedor.oculto{
		display:none;
		width:90px;
		height:auto;
		position:absolute;
	}
	
	
	
	.contenedor:hover > .contenido.aclara{
		display:none;
	}
	
	.contenedor:hover > .subcontenedor.oculto{
		display:block;
	}
	
	.contenedor:hover > .subcontenedor.oculto > .contenido.oculto{
		display:block;
		position:absolute;		
		width:auto;
		min-width:20px;
		max-width:90px;
		z-index:20;
		height:auto;
		border:1px solid #000;
	}	
	
		.aprobada{
			background-color:#55BF55;
			border-color: #CDF7CD #008000 #008000 #CDF7CD;
		}
		.rechazada{
			background-color:#FF565B;
			border-color:  #FFC0CB #F00 #F00 #FFC0CB;
		}
		.enevaluacion{
			background-color:blue;
			background-color:#3797FF;
			border-color:   #5CF2FF   #00F #00F #5CF2FF;
		}
		.apresentar{
			background-color:grey;
			border-color:  silver #000 #000 silver;			
		}	
		.anuladamuestra{
			background-color:#000;
			color:#fff;
			border-color:  silver #000 #000 silver;			
		}	
	
	</style>
	
	
	<script LANGUAGE="javascript">
	_fechaobjetivo='';
	</script>	
	
</head>
<body>
	<script language="javascript" type="text/javascript" src="./js/jquery/jquery-1.8.2.js"></script>
	<?php
	



	if($Accion == ""){
		$Accion = "agrega";
	}		

	
	$Consulta = mysql_query("SELECT * FROM $Tabla WHERE id='$Id'",$Conec1);
	$Consulta_filas = mysql_num_rows($Consulta);
	
	if($Consulta_filas>0&&$Accion!='borra'&&$Accion!='recupera'){
		$Accion = "cambia";
	}
	


		$Datos = "";
	
	if ($Accion == "agrega"){$Href = "agrega.php";$AccionNom = "Agregar";}
	elseif ($Accion == "cambia"){$Href = "cambia.php";$AccionNom = "Guardar";}
	elseif ($Accion == "borra"){$Href = "borra.php";$AccionNom = "Borrar";}
	elseif ($Accion == "recupera"){$Href = "borra.php";$AccionNom = "Recuperar";}
	else {$Href = "error.php";}
	
	

	if($Tabla=='DOCdocumento'){		
		if($config['doc-criterionum']=="único"){
			$numeracion=$PLANOSACARGADOS;
		}elseif($config['doc-criterionum']=="único por grupo"){
			$numeracion=$PLANOSgruposACARGADOS;								
		}elseif($config['doc-criterionum']=="irrestricto"){
			$numeracion=0;
		}
		
	}

	
	
	
	if ($Accion == "agrega" || $Accion == "cambia"){
	?>
			<div id="marco">
				<form action="./ed_<?php echo $Href;?>" method="POST" enctype='multipart/form-data'>
					<input type="hidden" name="tabla" value="<?php echo $Tabla;?>">		
					<input type="hidden" name="id" value="<?php echo $Id;?>">
					<?php
					if(isset($campofijo)){
						if(!isset($campofijo_c)){$campofijo_c='';}
						echo "<input type='hidden' name='".$campofijo."' value='".$campofijo_c."'>";
					}
					if(isset($campofijob)){
						if(!isset($campofijob_c)){$campofijob_c='';}
						echo "<input type='hidden' name='".$campofijob."' value='".$campofijob_c."'>";
					}
					if(isset($Campo)){						
						echo "<input type='hidden' name='campo' value='".$Campo."'>";
					}
					?>
					
					<input type="hidden" name="accion" value="<?php echo $Accion;?>">
					
					<?php
					if(isset($_GET["salida"])){
						echo '<input type="hidden" name="salida" value="'.$_GET["salida"].'">';
						
						if(isset($_GET["salidaid"])){
							echo '<input type="hidden" name="salidaid" value="'.$_GET["salidaid"].'">';
						}
						if(isset($_GET["salidatabla"])){
							echo '<input type="hidden" name="salidatabla" value="'.$_GET["salidatabla"].'">';
						}						
					}
					?>
	
					<h1><?php echo $Accion . " " . $Tabla;?></h1>
					<div id="hoja">	
			
						<?php	
						foreach($PASAR as $k => $v){
							echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
						}
						
					    $result = mysql_query('SHOW FULL COLUMNS FROM `'.$Tabla.'`',$Conec1);
						echo mysql_num_rows($result);
						echo mysql_error($Conec1);
					    if (mysql_num_rows($result) > 0) {
					    			 
							$empaquetado = -100;
							$borradato="no";
					        while ($row = mysql_fetch_assoc($result)){
					        	if($row['Field']=='zz_borrada'&&mysql_result($Consulta, 0, $row['Field'])=='1'){$REGISTROENPAPELERA="si";}
						
					        	$campohabilitado='no';
								
								foreach($CampoS as $c){
									if($c==$row['Field']){										
										$campohabilitado='si';	
									}
								}
								
					        	$contenido='';
					        	$comentario = $row['Comment'];
								
								
								if($row['Default']!=''&&$row['Default']!=null){$Def=$row['Default'];}else{$Def='';}
								
					        	$wheremas ="";
					        	if($row['Field']=='id_p_grupos_id_nombre_tipoa'&&$Tabla=='comunicaciones'){
					        		$comentario = $config['com-grupoa'];
					        	}
								$Consultalista="";
								
								if($empaquetado == -100){echo "<div class='paquete'>";$empaquetado =0;}
								
								if($borradato=="no"){
									echo "<div class='dato'>";
								}else{
									$borradato="no";
								}
								
								if($Accion != "agrega"){
									$contenido = mysql_result($Consulta, 0, $row['Field']);
								}elseif($Accion == "agrega"){
									$x = "C-".$row['Field'];
									if(isset($_GET[$x])){
									$contenido = $_GET[$x];
									if($contenido==''){
										$contenido = $Def;
									}
									}
								}
								
								$i=substr($row['Field'],0,3);
								
								$Type = substr($row['Type'],0,3);
								
								if($row['Field'] == 'id'){
									$empaquetado --;
									$borradato="si";
								
								}elseif($row['Field'] == 'id_p_B_usuarios_usuarios_id_nombre'){
									echo '<input type="hidden" name="id_p_B_usuarios_usuarios_id_nombre" value="'.$UsuarioI.'">';
									echo "<p>".$comentario.": ".$UsuarioN."</p>";
								}elseif($row['Field'] == 'numerodeplano'){
									echo '<input onkeyup="nombreunico(this.value);" id="'.$row['Field'].'" class="chico" type="text" size="2" name="'.$row['Field'].'" value="'.$contenido.'">';
									
									echo "<p>".$comentario.": ".$UsuarioN."</p>";
								}elseif($i == 'zz_'){
									$valor='';
									
									if($row['Field']=="zz_pass"){
										echo $comentario;
										echo "<input type='password' name='".$row['Field']."' value='".$valor."'>";
										
									}else{
										
										echo "<input type='hidden' name='".$row['Field']."' value='".$valor."'>";
										$empaquetado --;
										$borradato="si";
									}
									$valor='';
								
									
								}elseif($row['Field'] == $campofijo){
									echo "<p>".$comentario.": ".$campofijo_c."</p>";

								}elseif($row['Field'] == $campofijob){
									echo "<p>".$comentariob.": ".$campofijob_c."</p>";
								
								}elseif($i == 'FI_'){
									if($Campo == "" || $Campo == $row['Field'] || $campohabilitado=='si'){
										echo "<p>".$comentario.": ".$campofijo_c."</p>";
										if($contenido!=''){
											echo "<a href='$contenido'>documento actual</a>";
											echo "reemplazar por:";
										}	
										echo "<input type='hidden' name='".$row['Field']."' value='".$contenido."'>";
										
										$path='path'.$row['Field'];
										echo "<input type='hidden' name='archivo_".$row['Field']."_path' value='".$_GET[$path]."'>";	
										
										echo "<input type='file' name='archivo_".$row['Field']."'>";
									}else{
										echo "<input type='hidden' name='archivo_".$row['Field']."' value='".$contenido."'>";
									}
									
								}elseif($i != 'id_'){
									if(1==1||$campohabilitado=='si'){
										echo $comentario;
										echo "<br>";
										
										if ($Type == "tex"){
											$empaquetado =+ 3;
											?>
											<textarea cols="34" rows="8" name="<?php echo $row['Field'];?>"><?php echo $contenido;?></textarea>
											
											<?php	
										}elseif ($Type == "dat"){
											// if($Accion == "agrega" ){$contenido = date("Y") . "-" . date("m") . "-" . date("d");} 
											if($Accion == "agrega" && $row['Field'] == "fechacierre"){$contenido = "0000-00-00";}
											if($Accion == "agrega" && $Tabla == "comunicaciones" && $row['Field'] == "cerradodesde"){$contenido = "0000-00-00";}
											if($row['Comment'] == "fecha en que se realiza el pedido"){$estado = "READONLY";}else{$estado = "";}
											if(!fechavalida($contenido)){$contenido="";}
											
											echo "<input class='mini' type='text' size='2' id='".$row['Field']."_d' name='".$row['Field']."_d' value='".dia($contenido)."' $estado";
												if($Accion == "agrega" && $Tabla == "comunicaciones" && $row['Field'] == "fecharecepcion"){
													echo " onchange='
														getElementById(\"fechainicio_d\").value = this.value;
														if(_fechaobjetivo==\"fijada\"){
															getElementById(\"fechaobjetivo_d\").value = this.value;
															getElementById(\"cerradodesde_d\").value = this.value;
														}'
													";
												}
											echo ">";
											
											echo "<input class='mini' type='text' id='".$row['Field']."_m' name='".$row['Field']."_m' value='".mes($contenido)."' $estado";

													if($Accion == "agrega" && $Tabla == "comunicaciones" && $row['Field'] == "fecharecepcion"){
													echo " onchange='
														getElementById(\"fechainicio_m\").value = this.value;
														if(_fechaobjetivo==\"fijada\"){
															getElementById(\"fechaobjetivo_m\").value = this.value;
															getElementById(\"cerradodesde_m\").value = this.value;
														}'
													";
												}
											echo ">";
											
											echo "<input class='fecha' type='text' id='".$row['Field']."_a' name='".$row['Field']."_a' value='".ano($contenido)."' $estado";

													if($Accion == "agrega" && $Tabla == "comunicaciones" && $row['Field'] == "fecharecepcion"){
													echo " onchange='
														getElementById(\"fechainicio_a\").value = this.value;
														if(_fechaobjetivo==\"fijada\"){
															getElementById(\"fechaobjetivo_a\").value = this.value;
															getElementById(\"cerradodesde_a\").value = this.value;
														}'
													";
												}
											echo ">";
													
												
											if($contenido!=''){
												?>
												<input type='button' value='borrar fecha' 
													onclick="
														document.getElementById('<?php echo $row['Field'];?>_d').value = '00';
														document.getElementById('<?php echo $row['Field'];?>_d').setAttribute('readonly', 'readonly');
														document.getElementById('<?php echo $row['Field'];?>_m').value = '00';
														document.getElementById('<?php echo $row['Field'];?>_m').setAttribute('readonly', 'readonly');
														document.getElementById('<?php echo $row['Field'];?>_a').value = '0000';
														document.getElementById('<?php echo $row['Field'];?>_a').setAttribute('readonly', 'readonly');
														">
												<?php
											}
											
											?>
											
											
											
											<?php
										}elseif ($Type == "enu"){
											$campo=$row['Field'];
											$listado = $row['Type'];
											$listado = str_replace("enum('","",$listado);
											$listado = str_replace("')","",$listado);
											$lista = explode("','", $listado);
											echo "<select name='".$campo."' ";
												
											echo ">";
											foreach ($lista as $v) {	
												if($contenido == $v){$selecta = "selected='yes'";
												}else{$selecta = "";}
												if($v == 'entrante'){$va = $entra." (".$v.")";
												}elseif($v == 'saliente'){$va = $sale." (".$v.")";
												}else{$va = $v;}
											    echo "<option value='" . $v . "'" . $selecta . ">". $va . "</option>";
											}
											echo "</select>";									
											
					
										}elseif ($row['Type'] == "tinyint(1)"){
											$campo=$row['Field'];
											
											if($contenido==1){$contenidocheck=" checked";}else{$contenidocheck="";}							
											?>	
											
											<input type="hidden" name="<?php echo $row['Field'];?>" id="<?php echo $row['Field'];?>" value="">
											<input type="checkbox" name="" value="" <?php echo $contenidocheck;?> 
											 onclick="
											 	alterna('<?php echo $row['Field'];?>', this.checked);
											 	<?php if(($campo=='com-aprobacion'||$campo=='com-aprobacion-sale')&&$Tabla=='configuracion'){
											 		echo "
											 			window.open('./ediciontablagenerica.php?tabla=comunestadoslista&accion=cambia','mywindow');
											 		";
												}?>
											"
											>											
											<?php 
										}else{
											?>
											<input class="chico" type="text" size="2" name="<?php echo $row['Field'];?>" value="<?php echo $contenido;?>">
											
											<?php
											if($Consultalista != ''){
												while ($listaitem = mysql_fetch_assoc($Consultalista)) {
													$itemlista=$listaitem[$row['Field']];
													?>	
													<a onclick="document.getElementById('<?php echo $row['Field'];?>').value = '<?php echo $itemlista;?>'">
														<?php echo $itemlista;?>
													</a>
													<?php
												}
												?>
												<a onclick="document.getElementById('<?php echo $row['Field'];?>').value = ''">
														ninguno
												</a>
												<?php
											}
										}
										
									}elseif($campofijo == $row['Field']){
									?>	
									<input type="hidden" name="<?php echo $campofijo;?>" value="<?php echo $campofijo_c;?>">
									<?php
										
									}else{
										?>
										<input class="chico" type="hidden" size="2" name="<?php echo $row['Field'];?>" value="<?php echo str_replace('"',"'",$contenido);?>">
										<?php				
									}
									
									
								}elseif($i == 'id_' && $row['Field'] != $campofijo && $row['Field'] != "id_p_paneles_id_nombre"){
									
									
										
										$Typolink = substr($row['Field'],0,4);
										//para tablas padre 
										if($Typolink == "id_p"){	
											$Baselink = substr($row['Field'],0,6);
											// para tablas padre en otras bases
											if($Baselink == "id_p_B"){
												$o = explode("_", $row['Field']);
												$basepadre = $o[3];
												$tablapadre = $o[4];
												$campopadre = $o[5];
												echo $comentario;
												
												if($o[6] != ""){$referencia = $o[6];}else{
												$Column=mysql_query("SHOW FULL COLUMNS FROM $basepadre.$tablapadre",$Conec1);
													echo mysql_error($Conec1);
													while($col=mysql_fetch_assoc($Column)){
														if($col['Field']!='id'){
															$referencia =$col['Field'];
															break;
														}
													}
												}
												
											//	echo "<br> linkeado a: base: ".$basepadre." tabla: ".$tablapadre." campo: ".$campopadre." referencia: ".$referencia;
											
												$Consultadosactual = mysql_query("SELECT * FROM $basepadre.$tablapadre WHERE $campopadre = '$contenido' ORDER BY $referencia",$Conec1);
												echo mysql_error();
												if(mysql_num_rows($Consultadosactual)>0){
													$contenidopadre = mysql_result($Consultadosactual, 0, $campopadre);
												}
												
												$Consultados = mysql_query("SELECT * FROM $basepadre.$tablapadre order by id desc",$Conec1);
												$Consultados_filas = mysql_num_rows($Consultados);	
												echo "<select name='".$row['Field']."'>";
												echo "<option value='0'>-elegir-</option>";
												if($Consultados_filas > 0){
													$filas = 0;
													if($Accion == "agrega" && $row['Field'] == "id_p_B_usuarios_usuarios_id_nombre_autor"){$contenidopadre = $UsuarioI;}elseif($Accion == "agrega"){$contenidopadre = "";}
													if($Accion == "cambia"){$contenidopadre=$contenido;}
											        while ($filas < $Consultados_filas ) {
											        	
														$tx = mysql_result($Consultados, $filas, $referencia);
														$idl = mysql_result($Consultados, $filas, $campopadre);
														
														if($contenidopadre == $idl)
														{
															$selecta = "selected='yes'";
														}
														
														else
														{
															$selecta = "";
														}
														
														echo "<option value='" . $idl . "'" . $selecta . ">". $tx . "</option>";
														$filas ++;
													}
													
													echo "</select><br>";
												}
											}else{
												
												// para tablas padre en la misma base
												$o = explode("_", $row['Field']);
												$basepadre = $Base;
												$tablapadre = $o[2];
												$padre = $basepadre . "." . $tablapadre;
												echo $comentario;
																								
												//echo "<a target='_blank' href='./ediciontablagenerica.php?tabla=".$tablapadre."'> (editar listado)</a>";
												
												
													$extracondicion='';
										
												$estructurapadre = mysql_query('SHOW FULL COLUMNS FROM '.$basepadre.'.'.$tablapadre,$Conec1);
												echo mysql_error();
						  						if (mysql_num_rows($estructurapadre) > 0) {
						  							$campozzborra='no';
													$wheremas = "WHERE '1'='1'";
						  							while ($value = mysql_fetch_assoc($estructurapadre)){
						  								if($value['Field']=='zz_borrada'){
															$wheremas .= " AND zz_borrada='0'";
														}
													}		
																  					
						  							mysql_data_seek($estructurapadre, 0);
						  							
													while ($value = mysql_fetch_assoc($estructurapadre)){
														if(substr($value['Field'],0,15)=="id_p_paneles_id"){
															$wheremas .= " AND ".$tablapadre.".".$value['Field']." = ".$PanelI;
														}
													}	
												}		
												
										
												$resultdos = mysql_query('SHOW FULL COLUMNS FROM '.$padre,$Conec1);		
												if (mysql_num_rows($result) > 0) {
													if($tablapadre == 'grupos')
														{$wheremas .= " AND ".$tablapadre.".id_p_paneles_id_nombre = '$PanelI' ";
													}elseif($tablapadre == 'paneles'){
														$wheremas .= " AND id = '$PanelI' ";
													}
													
													$query= "SELECT * FROM $padre $wheremas";
													$Consultados = mysql_query($query,$Conec1);
													echo mysql_error($Conec1);
													$Consultados_filas = mysql_num_rows($Consultados);	
													$Consultados_filas_cuenta = 0;
													
													echo "<input type='hidden' id='".$row['Field']."' name='".$row['Field']."' value='".$contenido."'>";
													
													
													if($row['Field']=='id_p_comunicaciones_id_ident_entrante'||$row['Field']=='id_p_comunicaciones_id_ident_rechazada'||$row['Field']=='id_p_comunicaciones_id_ident_aprobada'||$row['Field']=='id_p_comunicaciones_id_ident_anulada'){
														$readonly = "READONLY";
													}else{$readonly = "";}// algunos campos no permiten la autogeneración de nuevos registros asociados
														
													if($row['Field'] == 'id_p_DOCdocumento_id' && $Tabla == 'DOCversion'){ //no despliega listado en caso de estar llamando la tabla de vesriones de docuemntos
														echo "<input type='button' style='position:relative;' class='chico' id='".$row['Field']."-n' name='".$row['Field']."_n' value=''";
														?>
														onclick="window.location='./agrega_f.php?tabla=DOCdocumento&accion=cambia&id=<?php echo $contenido;?>';"
														<?php
														echo ">";
													}else{
														
														echo "<input $readonly class='chico' id='".$row['Field']."-n' name='".$row['Field']."_n' value=''";
														?>
															 onKeyUp="
															 _valor = this.value;
															 _campo = '<?php echo $row['Field'];?>';
															 document.getElementById('<?php echo $row['Field'];?>').value = includes(_campo, _valor);"												 
														<?php
													
													
														echo"><br>";
														?>
														   <a 
														    onclick="
														    	document.getElementById('<?php echo $row['Field'];?>-n').value = '';
														    	document.getElementById('<?php echo $row['Field'];?>').value = '0'
														    ">
																-vacio-
															</a>
														<?php
													}
													
													$campovalor = isset($o[4])?$o[4]:'nombre';
													
													$ocultoporexeso='no';
													while($Consultados_filas_cuenta < $Consultados_filas){
														$v = mysql_result($Consultados, $Consultados_filas_cuenta, $campovalor);
														$va = $v;
														
														if($Consultados_filas_cuenta>'50'&&$ocultoporexeso=='no'){
															//oculta los resultados posteriores al numero 50 para evitar formularios confusos
															$ocultoporexeso='si';
															echo "<br><a ";?>onclick="this.nextSibling.style.display='block';"<?php echo ">más (más viejos)-></a>";
															echo "<div class='dato' style='display:none;'>";
														}
														
														if($row['Field']=='id_p_comunicaciones_id_ident_entrante'||$row['Field']=='id_p_comunicaciones_id_ident_rechazada'||$row['Field']=='id_p_comunicaciones_id_ident_aprobada'||$row['Field']=='id_p_comunicaciones_id_ident_anulada'){
															if(mysql_result($Consultados, $Consultados_filas_cuenta, 'preliminar')=='extraoficial'){$o='x';}else{$o='';}
															$PREN['entrante']=$config['com-entra-preN'.$o];
															$PREN['saliente']=$config['com-sale-preN'.$o];	
															
															$sentido=mysql_result($Consultados, $Consultados_filas_cuenta, 'sentido');
															
															$ca=$GRUPOS['a'][mysql_result($Consultados, $Consultados_filas_cuenta, 'id_p_grupos_id_nombre_tipoa')]['codigo'];
															$na=$GRUPOS['a'][mysql_result($Consultados, $Consultados_filas_cuenta, 'id_p_grupos_id_nombre_tipoa')]['nombre'];
															$cb=$GRUPOS['b'][mysql_result($Consultados, $Consultados_filas_cuenta, 'id_p_grupos_id_nombre_tipob')]['codigo'];
															$nb=$GRUPOS['b'][mysql_result($Consultados, $Consultados_filas_cuenta, 'id_p_grupos_id_nombre_tipob')]['nombre'];
															
															if($ca!=''){$A = $ca;}else{$A = $na;}
															if($cb!=''){$B = $cb;}else{$B = $nb;}	
															
																			
															$v = $PREN[$sentido].$v;
															$va = "<span class='contenedor aclara'><span class='subcontenedor oculto'><span class='contenido oculto'>".$na."</span></span><span class='contenido aclara'>".$A."</span></span><span class='contenedor aclara'><span class='subcontenedor oculto'><span class='contenido oculto'>".$nb."</span></span><span class='contenido aclara'>".$B."</span></span>".$v;
											
															if(mysql_result($Consultados, $Consultados_filas_cuenta, 'id')==$contenido){
																?>
																<script type='text/javascript'>
																	document.getElementById('<?php echo $row['Field'];?>-n').value = '<?php echo $v;?>';
																	
																</script>
																<?php																
															}
															if($row['Field']=='id_p_comunicaciones_id_ident_entrante'){
																if($sentido=='saliente'){
																	$Opcionespostergadas[mysql_result($Consultados, $Consultados_filas_cuenta, 'id')]=$v;
																	$Consultados_filas_cuenta ++;continue;// saltea el restro del while evitando que se cargue inicialmente la opción
																}
															}
															if(($row['Field']=='id_p_comunicaciones_id_ident_rechazada'||$row['Field']=='id_p_comunicaciones_id_ident_aprobada'||$row['Field']=='id_p_comunicaciones_id_ident_anulada')){
																if($sentido=='entrante'){
																	$Opcionespostergadas[mysql_result($Consultados, $Consultados_filas_cuenta, 'id')]=$v;
																	$Consultados_filas_cuenta ++;continue;// saltea el restro del while evitando que se cargue inicialmente la opción
																}
															}	
														}
														$vt = mysql_result($Consultados, $Consultados_filas_cuenta, 'nombre');
														$v = ($v != '') ? $v : $vt;
														$idv = mysql_result($Consultados, $Consultados_filas_cuenta, 'id');
														if($idv==$contenido){
															?>
															<script LANGUAGE="javascript">
																document.getElementById('<?php echo $row['Field'];?>-n').value = '<?php echo $v;?>';
															</script>
															<?php
														}
															?>
															
														<script LANGUAGE="javascript">	
															var <?php echo $row['Field'];?>=new Array();
															<?php echo $row['Field'];?>['id']=new Array();
															<?php echo $row['Field'];?>['n']=new Array();
															
															<?php echo $row['Field'];?>['id'].push("<?php echo $idv;?>");
															<?php echo $row['Field'];?>['n'].push("<?php echo $v;?>");  
		
														</script>	
																
														<?php													
														if($row['Field'] != 'id_p_DOCdocumento_id' || $Tabla !='DOCversion'){ // no despliega listado en caso de estar llamando la tabla de vesriones de docuemntos
															?>
															
														    <a 
														    title='<?php echo $vt;?>'
															
														   	ondblclick="window.location='./agrega_f.php?accion=cambia&tabla=<?php echo $tablapadre;?>&id=<?php echo $idv;?>';"												   
														    onclick="
														    	document.getElementById('<?php echo $row['Field'];?>-n').value = '<?php echo $v;?>';
														    	document.getElementById('<?php echo $row['Field'];?>').value = '<?php echo $idv;?>'
														    ">
																<?php echo $va;?>
															</a>
														<?php
														}
														$Consultados_filas_cuenta ++;
													}

													if($ocultoporexeso=='si'){
															//oculta los resultados posteriores al numero 50 para evitar formularios confusos
															echo "</div>";
													}

													if(isset($Opcionespostergadas)){
														echo "<br><a ";?>onclick="this.nextSibling.style.display='block';"<?php echo ">más (de otro tipo)-></a>";
														echo "<div class='dato' style='display:none;'>";
														foreach($Opcionespostergadas as $rowid => $rownombre){
															?>
															 <a 
														    title='<?php echo $rownombre;?>'															
														   	ondblclick="window.location='./agrega_f.php?accion=cambia&tabla=<?php echo $tablapadre;?>&id=<?php echo $rowid;?>';"												   
														    onclick="
														    	document.getElementById('<?php echo $row['Field'];?>-n').value = '<?php echo $rownombre;?>';
														    	document.getElementById('<?php echo $row['Field'];?>').value = '<?php echo $rowid;?>'
														    ">
																<?php echo $rownombre;?>
															</a>
															<?php
														}
														
														echo "</div>";
														unset($Opcionespostergadas);
													}
													
													
												}
											}	
										}else{
											$empaquetado --;
											$borradato="si";
										}
									
								}elseif($row['Field'] == "id_p_paneles_id_nombre"){
									echo "<input type='hidden' name='id_p_paneles_id_nombre' value='".$PanelI."'>";
									$empaquetado --;
									$borradato="si";
								}else{
									$empaquetado --;
									$borradato="si";
								}

								
								//print_r($row);
								if($borradato!="si"){
								echo "</div>";	
								}
								$empaquetado ++;
								if($empaquetado > 5){
									echo "</div>";
									$empaquetado = -100;
								}
					        }
					        if($Accion!='agrega'){
					        foreach($tablasAL as $kl => $vl){
					        	if($empaquetado == -100){echo "<div class='paquete'>";$empaquetado =0;}
								
								if($borradato=="no"){
									echo "<div class='dato'>";
								}else{
									$borradato="no";
								}	
					        	
					        	echo "<div class='vinculante'>";
								echo "<h3>".$vl['desc']."</h3>";
								echo "<div id='L_".$kl."' class='links'>";
									foreach($vl['valores'] as $valid => $valdat){
										echo "<a tablaP='".$vl['tablaPuente']."' value='' estado='no' name='L_".$kl."_$valid' id='L_".$kl."_$valid' class='selector' title='".$valdat['descripcion']."' valid='$valid' onclick='cambiaEstado(this)'><div class='checkbox'></div> ".$valdat['nombre']."</a>";									
									}
									echo "
								</div>
								<br>otra:
					        	<input class='otra' tabla='".$kl."' id='NL_".$kl."' value='' autocomplete='off'><input onclick='crear(this);' type='button' class='otra' value='crear'>
					        	";
								echo "				        	
					        	</div>";
							
								if($borradato!="si"){
									echo "</div>";	
									}
									$empaquetado ++;
								if($empaquetado > 5){
									echo "</div>";
									$empaquetado = -100;
								}
							
					        }
					        }
					    }
					    
						
	
						?>
						
					<script type='text/javascript'>
					
						function cambiaEstado(_this){
								console.log(_this);
								console.log(_this.parentNode);
								
								_est=_this.getAttribute('estado');
								_tabex=_this.getAttribute('id').split('_');
								
								_tp=_this.getAttribute('tablaP');
								
								_l1='<?php echo $Id;?>';
								_t1='<?php echo $Tabla;?>';
															
								_l2=_this.getAttribute('valid');
								_t2=_tabex[1];

								if(_est=='si'){
									desvincular(_t1,_l1,_t2,_l2,_tp);
								}else if(_est=='no'){
									vincular(_t1,_l1,_t2,_l2,_tp);	
								}
							
						}
						
						function desvincular(_t1,_l1,_t2,_l2,_tp){
							
							
							var _idtag='L_'+_t2+'_'+_l2;
							var parametros = {
								"tp": _tp,
								"t1": _t1,
								"l1": _l1,
								"t2": _t2,
								"l2": _l2, 
								"accion" : 'desvincular'
							};
									
							$.ajax({
								data:  parametros,
								url:   'ed_vinculos_ajax.php',
								type:  'post',
								success:  function (response){
									var _res = $.parseJSON(response);
									console.log(_res);
									
									if(_res.res=='exito'){
										document.getElementById(_idtag).setAttribute('estado','no');
									}
								}
							})		
							
						}
						
						
						function vincular(_t1,_l1,_t2,_l2,_tp){
							
							var _idtag='L_'+_t2+'_'+_l2;
							var parametros = {
								"tp": _tp,
								"t1": _t1,
								"l1": _l1,
								"t2": _t2,
								"l2": _l2, 
								"accion" : 'vincular'
							};
									
							$.ajax({
								data:  parametros,
								url:   'ed_vinculos_ajax.php',
								type:  'post',
								success:  function (response){
									var _res = $.parseJSON(response);
									console.log(_res);
									
									if(_res.res=='exito'){
										document.getElementById(_idtag).setAttribute('estado','si');
									}
								}
							})		
							
						}
						
						function crear(_this){
							
							var _tabla=_this.previousSibling.getAttribute('tabla');
							var _nombre=_this.previousSibling.value;
							
							var parametros = {
								"tabla": _tabla,
								"nombre": _nombre 
							};
							console.log(_tabla);		
							$.ajax({
								data:  parametros,
								url:   'ed_creaclase_ajax.php',
								type:  'post',
								success:  function (response){
									
									var _res = $.parseJSON(response);
									console.log(_res);
									
									_cont=document.getElementById('L_'+_tabla);
									
									if(_res.alerta!=undefined){
										alert(_res.alerta);
									} 
									
									
									if(_res.res=='exito'){
										if(_res.data.nid>0){
											_link=document.createElement("a");	
											_link.setAttribute('id','L_'+_tabla+'_'+_res.data.nid);
											_link.setAttribute('class','selector');
											_link.setAttribute('name','L_'+_tabla+'_'+_res.data.nid);
											_link.setAttribute('value','1');
											
											_check=document.createElement("input");
											_check.setAttribute('type','checkbox');
											_check.setAttribute('checked','cheked');
											_link.appendChild(_check);
											_link.innerHTML+=_nombre;
											
											_cont.appendChild(_link);
										}else if(_res.data.eid!=undefined){
											
											vincular(_res.data.eid)
											
										}
									}									
								}
							})
							
						}
						
						
					</script>
					
					</div>	
							
					<input type="submit" value="<?php echo $AccionNom;?>">
					<input type="reset" value="Reiniciar" onclick='window.location=window.location'>					
					<input type="button" value="Cancelar" onclick="window.location.href='./<?php echo $Salida;?>.php?tabla=<?php echo $Tabla;?>&salida=<?php echo $Salida;?>&salidatabla=<?php echo $Salidatabla;?>';">
					
					<?php
					
					if($Accion!='agrega'){
						if($REGISTROENPAPELERA=="si"){
						?>
						<input type="button" value="Recuperar" onclick="window.location.href = './form_tabla.php?tabla=<?php echo $Tabla;?>&id=<?php echo $Id;?>&accion=recupera&salida=<?php echo $Salida;?>&salidatabla=<?php echo $Salidatabla;?>';">
						<?php	
						}else{
						?>
						<input type="button" value="Eliminar" onclick="window.location.href = './form_tabla.php?tabla=<?php echo $Tabla;?>&id=<?php echo $Id;?>&accion=borra&salida=<?php echo $Salida;?>&salidatabla=<?php echo $Salidatabla;?>';">
						<?php
						}
					}
					
					
					?>
				</form>
			</div>
	
	<?php
		}
elseif($Accion == "borra"||$Accion == "recupera"){
	?>
			<div id="marco">
				<form action="./ed_<?php echo $Href;?>" method="POST">
					<input type="hidden" name="tabla" value="<?php echo $Tabla;?>">
					<input type="hidden" name="contrato" value="<?php echo $Id_contrato;?>">
					<input type="hidden" name="id" value="<?php echo $Id;?>">
					<input type="hidden" name="accion" value="<?php echo $Accion;?>">
					<input type="hidden" name="tablahermana" value="<?php echo $Tablahermana;?>">
					<input type="hidden" name="idhermana" value="<?php echo $Idhermana;?>">
					<input type="hidden" name="salida" value="<?php echo $Salida;?>">
					<input type="hidden" name="salidaid" value="<?php echo $Salidaid;?>">					
					<input type="hidden" name="salidatabla" value="<?php echo $Salidatabla;?>">	
					<h1><?php echo $Accion . " " . $Tabla;?></h1>
					<input type="submit" value="<?php echo $Accion;?>">	
					<div id="hoja">	
			
						<?php	
						foreach($PASAR as $k => $v){
							echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
						}
						
					    $result = mysql_query("SHOW FULL COLUMNS FROM $Tabla",$Conec1);
						echo mysql_error($Conec1);
					    if (mysql_num_rows($result) > 0) {		    	
							
					        while ($row = mysql_fetch_assoc($result)) {
					 	
					        	$contenido = mysql_result($Consulta, 0, $row['Field']);
						
								echo '<div class="campo">';	
									$i=substr($row['Field'],0,2);
									
									echo "<h4>".$row['Comment']. "</h4> ".$contenido;
								echo '</div>';	
					        }					        
					    }
					?>
						
					</div>	
									
				</form>
			</div>
	
	
	
	<?php	
	}


	?>	
	
	
</body>


<script type="text/javascript">

	function nombreunico(_nombre){
	var arrayLength = _arraydenombres.length;
	_nombre = _nombre.replace(/ /g, "");
	_nombre = _nombre.toUpperCase()
	_primero='';	
	for (var i = 0; i < arrayLength; i++) {

		if(_arraydenombres[i]==_nombre){
			_primero=_nombre;
	   		alert('Nombre repetido! '+_arraydenombres[i]);
	   		document.getElementById(_nombre).style.backgroundColor='red';
			document.getElementById("listadenombres").scrollTop = document.getElementById(_nombre).offsetTop;
		}else if(_arraydenombres[i].indexOf(_nombre)>-1){
			if(_primero==''){_primero=_arraydenombres[i];}
			document.getElementById(_arraydenombres[i]).style.backgroundColor='yellow';
			//document.getElementById("listadenombres").scrollTop = document.getElementById(_arraydenombres[i]).offsetTop;
		}else{
			document.getElementById(_arraydenombres[i]).style.backgroundColor='#fff';
		}
		if(_primero!=''){
			document.getElementById("listadenombres").scrollTop = document.getElementById(_primero).offsetTop;
		}
		

		
	}
}
</script>

<script type="text/javascript">

	function cambiame() 
{ 
    window.open("","ventanita","width=800,height=600,toolbar=0"); 
    var o = window.setTimeout("document.form1.submit();",500); 
}

	function cambiametb() 
{ 
    window.open("","ventanitatb","width=800,height=600,toolbar=0"); 
    var o = window.setTimeout("document.form1.submit();",500); 
}  

function include(arr, obj) {
    for(var i=0; i<arr['n'].length; i++) {
        if (arr['n'][i] == ob){ return arr['id'][i];}
        else {return 'n';}
    }
}

function includes(_arr, obj) {
    return 'n';
}

function alterna(_id, _estado){
	if(_estado==false){
		document.getElementById(_id).value='0';
	}else if(_estado==true){
		document.getElementById(_id).value='1';
	}
}


</script>
