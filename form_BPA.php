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
	html, body {
	    height: 100%;
	    vertical-align:middle;
	    overflow:hidden;
	}
	label{
		font-size:12px;
		display:inline-block;
		width:50px;
		text-align:right;
	}
	
	label.ta{
		width:170px;
		text-align:left;
	}	
	input,select{
		width:150px;
	}
	textarea{
		width:98%;
		height:25px;
	}
	
	a{
		font-size:12px;
		background: #fff none repeat scroll 0 0;
	    border: 1px solid #08afd9;
	    border-radius: 5px;
	    margin: 0 0 0 5px;
	    padding: 2px;
	    text-align: center;
    }
    
	a[estado='formulado']{
		background-color:  #08afd9;
    }
    
  	input[estado='cambiante'], textarea[estado='cambiante'], select[estado='cambiante']{
		background-color:  #fbb;
		color:red;
    }  
   	textarea[estado='cambiante']{
		background-color:  #fbb;
		color:red;
    }  
       
		#formularioBPA{
			margin:0;
			display:inline-block;
			border: 1px solid blue;
			width:700px;
			height:65%;
			vertical-align:middle;
			border:2px solid #08afd9;
			border-radius:4px;
			box-shadow: 3px 3px 2px #888888;
			background-color:rgba(255,255,255,0.8);
			margin-bottom:5px;
			overflow-y:scroll;
		}
		#indicefuentes{
			display:inline-block;
			border: 1px solid blue;
			width: 220px;
			height:65%;
			vertical-align:middle;
			overflow-y:scroll;
			border:2px solid #08afd9;
			border-radius:4px;
			box-shadow: 3px 3px 2px #888888;
			background-color:rgba(255,255,255,0.8);
			margin-bottom:5px;
		}
		#indiceclases{
			display:inline-block;
			border: 1px solid blue;
			width: 122px;
			height:65%;
			vertical-align:middle;
			overflow-y:scroll;
			border:2px solid #08afd9;
			border-radius:4px;
			box-shadow: 3px 3px 2px #888888;
			background-color:rgba(255,255,255,0.8);
			margin-bottom:5px;
		}		
		#indiceBPAs{
			display:inline-block;
			width:20%;
			height:65%;
			vertical-align:middle;
			overflow-y:scroll;
			display:inline-block;
			border:2px solid #08afd9;
			border-radius:4px;
			box-shadow: 3px 3px 2px #888888;
			background-color:rgba(255,255,255,0.8);
			margin-bottom:5px;
		}
		
		#navegafuentes{
			vertical-align:middle;
			display:inline-block;
			border: 1px solid blue;
			width:95%;
			height:30%;
			overflow-x:scroll;
			border:2px solid #08afd9;
			border-radius:4px;
			box-shadow: 3px 3px 2px #888888;
			background-color:rgba(255,255,255,0.8);
		}
		
		#contenedorhojas{
			white-space: nowrap;
			display:inline-block;
			width:auto;
			height:98%;
		}
				
		.fuente{
			border-bottom:1px solid  #08afd9;
		}
		
		.nombre{
			font-size:14px;
			display:inline-block;
			width:85%;
		}		
		
		.scan{
			border: 1px solid silver;
			display:inline-block;
			width:50px;
			font-size:11px;
			width:45px;
			overflow:hidden;
		}
		
		.scan>a {
		    background-color: #08afd9;
		    color: #000;
		    font-size: 10px;
		    margin: 1px;
		    padding: 0;
		}
		div.nombre:hover{
			background-color:lightblue;
		}
		div.hoja{
			
			position:relative;
			height:100%;
			display:inline-block;
			margin-right:5px;
		}
		div.hoja>img{
			height:100%;
		}
		.refNumP{
			position:absolute;
			top:2px;
			left:5px;
			font-size:11px;
		}
		#navegaHojas{
			display:none;
			position:absolute;
			top:2px;
			right:5px;
			height:65%;
			width:35%;
			overflow:hidden;
			border:1px solid silver;
			background:#fff;
			border:4px solid #08afd9;
			border-radius:10px;
			box-shadow: 10px 10px 5px #888888;
		}
		#botonera{
			position:absolute;
			top:5px;
			left:5px;
		}
		
		#botonnueva{
			position: absolute;
		    right: 2px;
		    top: 22px;
		}
		#verHoja{
		 text-align: right;
		 height:100%;
			width:100%;
			overflow:scroll;
		}
		#verHoja img{
			width:100%;
		}
		
		.fuente .nombre{
			width:150px;
			height:60px;
			display:inline-block;
			overflow:hidden;
		}
		.fuente .scan img{
			height:60px;
		}
		#Icopia{
			height:40%;
		}
		
		#indiceclases {
			line-height: 15px;
			margin-right:5px;
		}
		
		#indiceclases a{
			background: #fff none repeat scroll 0 0;
			border: 1px solid #08afd9;
			border-radius: 5px;
			height: 12px;
			padding: 0;
			margin:0px;
			margin-left:5px;
			width: 98px;
			text-align:center;
			font-size:10px;
		}
		
		#indiceclases a.anade{
			background: transparent;
			color:#08afd9;
			border: none;
			border-radius: 0px;
			height: 12px;
			padding: 0;
			width: auto;
		}	

		#indiceclases > div > a{
		background-color:  #ccc;
		color:#444;
		border-color:#444;
		}
		
		#indiceclases > div > a[marcado='no']{
		background-color:  #fff;
		color:#000;
		border-color: #08afd9;
		}
				
		#indiceclases > div > a[marcado='si']{
		background-color:  #08afd9;
		border-color: #08afd9;
		color:#000;
		}
		
		#indicefuentes > div.fuente[marcado='si']{
		background-color:  #08afd9;
		color:#000;
		border-color:#000;
		}
		
		#indicefuentes > div.fuente[marcado='si'] div.scan{
		background-color:  #08afd9;
		color:#000;
		border-color:#000;
		}
		
		
		#indiceclases > div > a[marcado='err']{
		background-color:  red;
		color:#000;
		}
				
		#indiceclases .titulo{
			font-size:12px;
			border-top:3px solid #08afd9;
			margin-top:3px;
		}				
	</style>
	
</head>
<body>
		<script language="javascript" type="text/javascript" src="./js/jquery/jquery-1.8.2.js"></script>
		
		<script language="javascript" type="text/javascript" src="./js/fuentesaBPA.js"></script>
		<script language="javascript" type="text/javascript" src="./js/BPA.js"></script>		
<?php
$Result=consultaPropEstructura();
//echo "<pre>";print_r($Result);echo "</pre>";
$Indice=$Result['Indice'];
$Estrategias=$Result['Estrategias'];
$Areas=$Result['Areas'];
$Ambitos=$Result['Ambitos'];
$Acciones=$Result['Acciones'];

	echo "<div id='indiceBPAs'></div>";	
	
	echo "
	<form id='formularioBPA' method='post' enctype='multipart/form-data'>
		<input type='hidden' name='id' id='Iid' autocomplete='off'>
		<label for='nombre'>nombre</label><input name='nombre' id='Inombre' autocomplete='off'>
		<label for='fuente'>fuente</label><input name='fuente' id='Ifuente' autocomplete='off' readonly='readonly'>
		<label for='entidad'>entidad</label><input name='entidad' id='Ientidad' autocomplete='off' readonly='readonly'>
		<input type='hidden' name='id_p_FUfuentes' id='Iid_p_FUfuentes' autocomplete='off' >
		
		<br><label for='fuentepags'>páginas</label><input id='Ifuentepags' name='fuentepags' autocomplete='off'>
		<label for='valoracion'>valoración</label>
			<select id='Ivaloracion' name='valoracion' autocomplete='off'>
			<option>- elegir-</option>
			<option value='viable'>viable</option>
			<option value='rechazado'>rechazado</option>
			</select>				
		<label for='fecha'>fecha</label><input name='fecha' id='Ifecha' autocomplete='off' readonly='readonly'>
		<input type='hidden' name='accion' id='Iacc' value='crear'>
			
		<input style='display:block' type='button' value='crear' id='botoncrear' onclick='enviarFormulario(this);'>
		<input style='display:none' type='button' value='guardar' id='botoncambiar' onclick='enviarFormulario(this);'>
		<input style='display:none' type='button' value='eliminar' id='botoneliminar' onclick='activaConfirmar();'>
		<input style='display:none' type='button' value='cancelar' id='botoncancelar' onclick='desactivaConfirmar();'>
		<input style='display:none' type='button' value='confirmar' id='botonconfirmar' onclick='enviarFormulario(this);'>
		<input style='display:block' type='button' value='nueva BPA' id='botonnueva' onclick='limpiaBPA();'>
		
		<br><label class='ta' for='descripcion'>descripción:</label><textarea id='Idescripcion' name='descripcion' id='Idescripcion' autocomplete='off'></textarea>
		
		<br><label class='ta'  for='observaciones'>observaciones:</label><textarea id='Iobservaciones' name='observaciones' autocomplete='off'></textarea>
		
		<br><label class='ta'  for='procedimiento'>procedimiento:</label><textarea id='Iprocedimiento' name='procedimiento' autocomplete='off'></textarea>
		
		<br><label class='ta'  for='recursos'>recursos:</label><textarea id='Irecursos' name='recursos' autocomplete='off'></textarea>
		
		<br><label class='ta'  for='copia'>transcripción del texto</label><textarea id='Icopia' name='copia' autocomplete='off'></textarea>
		
		
	</form>
	";
	echo "<div id='indiceclases'></div>";	
	echo "<div id='indicefuentes'></div>";
	echo "<div id='navegafuentes'><div id='contenedorhojas'></div></div>";
	echo "<div id='navegaHojas'><div id='botonera'><a id='Bcerrar' lass='cerrar' onclick='cerrar(this);'>cerrar</a><a class='ant' onclick='anterior(this);'>ant. '<' </a><a class='sig'  onclick='siguiente(this);'>sig. '>' </a></div><div id='verHoja'></div></div>";		
?>

    <script type="text/javascript">     
    
    	var _statusROJO=0;
    	   
 		$('#formularioBPA select').change(function() { 			
 			if(this.getAttribute('estado')!='cambiante'){ 			
				this.setAttribute('estado','cambiante');
				_statusROJO++;
			}
		});
		   
		$('#formularioBPA input, #formularioBPA textarea').keydown(function() {
			if(this.getAttribute('readonly')!='readonly'){		
				if(this.getAttribute('estado')!='cambiante'){
					this.setAttribute('estado','cambiante');
					_statusROJO++;
				}
			}
		});
					
    
    
		window.scrollTo(0,'<?php echo $_GET['y'];?>');     
    </script>
    
   <script type="text/javascript">        
       cargarFuentes();//en js/fuentes.js
       
	   var _ClasesCargadas='no' // la función cargar clases modifica esta variable al terminar.
	   var _DataBPAs=Array(); // esta variable se completa con la función cargarBPAs.
	   cargarClases();// en /js/BPA.js
   
       
		function checkVariable() {
		   if(_ClasesCargadas=='si'){
		      cargarBPAs(); 
		   }
		}
		setTimeout(checkVariable,200);
          
       var _estadodecarga='inactivo';
       _contO=document.getElementById('navegafuentes');
       _contenido=document.getElementById('contenedorhojas');
       _contO.onscroll = function(ev) {       				
			if(_estadodecarga=='activo'){
				//console.log(_contO.clientWidth +"+"+ _contO.scrollLeft +">="+ (_contenido.clientWidth-60));
				if ((_contO.clientWidth + _contO.scrollLeft) >= (_contenido.clientWidth-60)) {
					_estadodecarga='inactivo';
					console.log(_contO.getAttribute('pag'));
					navegar(_contO,_contO.getAttribute('pag'));
			    }
		   }
		};	
		
		function cerrar(_this){
			_this.parentNode.parentNode.style.display='none';
			document.getElementById('verHoja').innerHTML='';
		}
	
    	function enviarFormulario(_this){             		
         		var _Acc=_this.getAttribute('value');
         		document.getElementById('Iacc').value=_Acc;
				__FF=$('#formularioBPA').serialize();
				console.log(__FF);
         		$.post('ed_BPA_ajax.php', $('#formularioBPA').serialize(),function(response){						  	
				        var _res = $.parseJSON(response);
						console.log(_res);
						if(_res.res=='exito'){
							if(_Acc=='crear'){
								if(_res.data.nid!='NA'){
									cargarFuentes(_res.data.nid);
								}
							}else if(_Acc=='guardar'){
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
          		document.getElementById('formularioBPA').reset();
          		
          		desmarcaCambiante();
          		
              	_desel=document.getElementById('indiceclases').querySelectorAll('a');
          		for(_nn in _desel){
          			if(typeof _desel[_nn] == 'object'){
          				_desel[_nn].removeAttribute('marcado');
          			}else{
          				console.log(typeof _desel[_nn]);
          			}
          		}                  		
          }     
          
          function desmarcaCambiante(){
          	_desel=document.getElementById('formularioBPA').querySelectorAll('input, textarea');
          		for(_nn in _desel){
          			if(typeof _desel[_nn] == 'object'){
          				_desel[_nn].removeAttribute('estado');
          			}else{
          				console.log(typeof _desel[_nn]);
          			}
          		}
          		
          		_desel=document.getElementById('indiceBPAs').querySelectorAll('a');
          		for(_nn in _desel){
          			if(typeof _desel[_nn] == 'object'){
          				_desel[_nn].removeAttribute('estado');
          			}else{
          				console.log(typeof _desel[_nn]);
          			}
          		}
          		
          		_statusROJO = 0;
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
          
          
          function tagToggle(_this){
          		var _this = _this;
              	_est=_this.getAttribute('marcado');
              	if(_est=='si'){
              		var _accion='borra';
              	}else if(_est=='no'){
              		var _accion='agrega';
              	}else{
              		alert('guarde esta BPA antes de definir sus clasificaciones. error al consultar estado (att: "marcado")');
              		return;
              	}
              	
              	_clase=_this.parentNode.getAttribute('clasif');
              	_idclase=_this.getAttribute('idreg');
              	_idBPA = document.getElementById('Iid').value;
              	
          		var parametros = {
					clase: _clase,
					idclase : _idclase,
					idBPA : _idBPA,
					accion: _accion
				};
						
				$.ajax({
					data:  parametros,
					url:   'ed_marcaclase_ajax.php',
					type:  'post',
					success:  function (response){
						var _res = $.parseJSON(response);
						console.log(_res);							
						if(_res.res=='exito'){
							if(_accion=='agrega'){
								_this.setAttribute('marcado','si');								
							}else{
								_this.setAttribute('marcado','no');
							}
						}else{
							_this.setAttribute('marcado','err');
							_this.removeAttribute('onclick');
						}
						cargarBPAs();
					},
					failure:  function (response){
						_this.setAttribute('marcado','err');
						_this.removeAttribute('onclick');
					}
				})
          }
        </script>

</body>