<?php
include('./includes/encabezado.php');
include('./cons_general.php');
?>
<!DOCTYPE html>
<head>
	 
	   <title>RED SUSTENTABLE DE LA CONSTRUCCIÓN</title>
	   <link href="./img/pdfajpg.ico" type="image/x-icon" rel="shortcut icon">
	   <link rel="stylesheet" type="text/css" href="./css/panelbase.css" />
	   <link rel="stylesheet" type="text/css" href="./css/indice_fuentes.css" />      
	<style>
		#cargafile{
			position:absolute;
			display:none;
			width:200px;
			font-size:11px;
			border:1px solid #08afd9;
			background-color: #eefbff;
		}
		#cargafile input{
			width:194px;
			font-size:11px;
		}
		#descargafile{
			position:absolute;
			display:none;
			width:120px;
			font-size:11px;
			border:1px solid #08afd9;
			background-color: #eefbff;
		}
		#formToggle{
			display:block;
			position:absolute;
			height:8px;
			top:-1px;
			left:58px;
			border:1px solid #000;
			font-size:10px;
		}
		#formulario[estado='cerrado']{
			overflow:hidden;
		}		
		#formulario[estado='cerrado']{
			height:10px;
		}
		
	</style>
	
</head>
<body>
		<script language="javascript" type="text/javascript" src="./js/jquery/jquery-1.8.2.js"></script>
		
		<script language="javascript" type="text/javascript" src="./js/fuentes.js"></script>		
<?php
$Result=consultaPropEstructura();
//echo "<pre>";print_r($Result);echo "</pre>";
$Indice=$Result['Indice'];
$Estrategias=$Result['Estrategias'];
$Areas=$Result['Areas'];
$Ambitos=$Result['Ambitos'];
$Acciones=$Result['Acciones'];


	echo "<table id='tabla'>";
		echo "
		<tr class='encabezado'>
			<th>id</th>
			<th>nombre</th>
			<th>descripcion</th>
			<th>url</th>
			<th>FI_copialocal</th>
			<th>autor</th>
			<th>entidad</th>
			<th>país</th>
			<th>fecha pub</th>
			<th>ISBN</th>
			<th>escaneado</th>
			<th>escaneado Hd</th>
		</tr>
	</table>";
	
	echo "<form id='formulario' estado='abierto' method='post' enctype='multipart/form-data'>
		<a id='formToggle'>-</a>
		<label for='id'>id</label><input name='id' id='Iid' readonly='readonly' autocomplete='off'>
		<label for='nombre'>nombre</label><input name='nombre' id='Inombre' autocomplete='off'>
		<label for='descripcion'>descripcion</label><textarea id='Idescripcion' name='descripcion' descripcion='Idescripcion' autocomplete='off'></textarea>
		<label for='url'>url</label><input id='Iurl' name='url' url='Iurl' autocomplete='off'>
		<label for='autor'>autor</label><input name='autor' id='Iautor' autocomplete='off'>
		<label for='entidad'>entidad</label><input name='entidad' id='Ientidad' autocomplete='off'>
		<label for='pais'>pais</label><input name='pais' id='Ipais' autocomplete='off'>
		<label for='fecha'>fecha</label><input name='fecha' id='Ifecha' autocomplete='off'>
		<label for='isbn'>ISBN</label><input name='isbn' id='IISBN' autocomplete='off'>	
		<input type='hidden' name='accion' id='Iacc' value='crear'>	
		<input style='display:block' type='button' value='crear' id='botoncrear' onclick='enviarFormulario(this);'>
		<input style='display:none' type='button' value='cambiar' id='botoncambiar' onclick='enviarFormulario(this);'>
		<input style='display:none' type='button' value='eliminar' id='botoneliminar' onclick='activaConfirmar();'>
		<input style='display:none' type='button' value='cancelar' id='botoncancelar' onclick='desactivaConfirmar();'>
		<input style='display:none' type='button' value='confirmar' id='botonconfirmar' onclick='enviarFormulario(this);'>
	</form>
	<div id='cargafile'><label for='FI_copialocal'>FI_copialocal</label><a onclick='desactivarCarga();'>cancelar</a><input type='file' name='FI_copialocal' id='IFI_copialocal' autocomplete='off' idreg='' onchange='cargarFile();'></div>
	<div id='descargafile'><input type='button' name='FI_copialocal' id='Idescarga' idreg='' onclick='descargarFile();' value='confirmo eliminar'></div>
		
	";
	

	if(!isset($_GET['y'])){$_GET['y']=0;}	
?>

        <script type="text/javascript">        
                    window.scrollTo(0,'<?php echo $_GET['y'];?>');     
        </script>
        
        <script type="text/javascript">        
             cargarFuentes();//en js/fuentes.js
              

             function enviarFormulario(_this){             		
             		var _Acc=_this.getAttribute('value');
             		document.getElementById('Iacc').value=_Acc;

             		$.post('ed_fuente_ajax.php', $('#formulario').serialize(),function(response){						  	
					        var _res = $.parseJSON(response);
							console.log(_res);
							if(_res.res=='exito'){
								if(_Acc=='crear'){
									if(_res.data.nid!='NA'){
										cargarFuentes(_res.data.nid);
									}
								}else if(_Acc=='cambiar'){
									if(_res.data.id!='NA'){
										actualizarFuentes(_res.data.id);
									}
								}else if(_Acc=='confirmar'){
									if(_res.data.id!='NA'){
										descargarFuentes(_res.data.id);
									}
								}
							vaciarFormulario();	
							}
					});				
              }
              
              function activaConfirmar(){
              		document.getElementById('botoneliminar').style.display='none';
              		document.getElementById('botoncancelar').style.display='block';
              		document.getElementById('botonconfirmar').style.display='block';
              	
              }
              
              function desactivaConfirmar(){
                	document.getElementById('botoneliminar').style.display='block';
              		document.getElementById('botoncancelar').style.display='none';
              		document.getElementById('botonconfirmar').style.display='none';
              }
                 
              function vaciarFormulario(){
              		document.getElementById('botoncrear').style.display='block';
              		document.getElementById('botoncambiar').style.display='none';
              		document.getElementById('botoneliminar').style.display='none';
              		document.getElementById('botoncancelar').style.display='none';
              		document.getElementById('botonconfirmar').style.display='none';
              		document.getElementById('formulario').reset();
              }     
              
              function cargador(_this,_event){              
              		_div=document.getElementById('cargafile');
              		_div.style.display='block';
              		_div.style.left=_event.pageX+'px';
              		_div.style.top=_event.pageY+'px';
              		document.getElementById('IFI_copialocal').setAttribute('idreg',_this.parentNode.parentNode.getAttribute('idreg'));              		
              }  

              function descargador(_this,_event){              
              		_div=document.getElementById('descargafile');
              		_div.style.display='block';
              		_div.style.left=_event.pageX+'px';
              		_div.style.top=_event.pageY+'px';
              		document.getElementById('Idescarga').setAttribute('idreg',_this.parentNode.parentNode.getAttribute('idreg'));
              		
              }
                            
              function desactivarCarga(){
              		document.getElementById('cargafile').style.display='none';
              		document.getElementById('IFI_copialocal').setAttribute('idreg','');
              		document.getElementById('descargafile').style.display='none';
              		document.getElementById('Idescarga').setAttribute('idreg','');
              }
              
              $('#formToggle').click(function(){
              	console.log(this.parentNode);
              	if(this.parentNode.getAttribute('estado')=='abierto'){              		
              		this.parentNode.setAttribute('estado','cerrado');
              		this.innerHTML='+';
              	}else{
              		this.parentNode.setAttribute('estado','abierto');
              		this.innerHTML='-';
              	}
              });
        </script>

</body>