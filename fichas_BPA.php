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
	   <link rel="stylesheet" type="text/css" href="./css/fichas_BPA.css" />          
	<style>
					
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
	<div class='ficha' id='fmodelo'>
		<div class='columna' id='columna1'>
			<div id='id-nombre'>
				<div id='id'>-sin datos-</div>
				<div id='nombre'>-sin datos-</div>
			</div>
			
			<div id='descripcion'>-sin datos-</div>
			<div id='tags'>
				<div id='escalas'>
					<div class='titulito'>
						para escala:
					</div>
				</div><div id='fases'>
					<div class='titulito'>
						para fases:
					</div>
				</div><div id='medios'>
					<div class='titulito'>
						para medios:
					</div>
				</div><div id='tipos'>
					<div class='titulito'>
						para tipologías:
					</div>
				</div>		
			</div>
			<div id='proced-rec'>
				<div class='aclara'>Procedimiento recomendado:</div>
				<div id='procedimiento'></div>
				<div id='recursos'><div class='aclara'>Recursos necesarios:</div></div>
			</div>
		</div>
		
		<div class='columna' id='columnaM'></div>
		
		<div class='columna' id='columna2'>
			<div class='aclara'>Para más información sobre esta buena práctica recomendamos:</div>
			<div id='fuente'>-sin datos-</div>
			<div id='tapa'></div>
			<div id='pagina'></div>
			<div id='transcripcion'>x</div>
		</div>				
	</div>
	";
?>

   <script type="text/javascript">
           
       var _FuentesData=Array();
       var _FuentesIndice=Array();
       cargarFuentesFichas();//en js/fuentesaBPA.js
       
	   var _ClasesCargadas='no' // la función cargar clases modifica esta variable al terminar.
	   var _DataBPAs=Array(); // esta variable se completa con la función cargarBPAs.
	   var _ClasesData=Array();
	   cargarClasesVar();// en /js/BPA.js
   
       
		function checkVariable() {
		   if(_ClasesCargadas=='si'){
		     cargarBPAsFicha(); 
		   }
		}
		setTimeout(checkVariable,200);
	
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