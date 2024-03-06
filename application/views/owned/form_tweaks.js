<script>
	var formulario_enviado=true;

	function mostrar_ocultar(capa) {
		div = document.getElementById(capa);
		if (div.style.display == ''){
			div.style.display = 'none';
		}else{
			if (formulario_enviado){
				div.style.display='';   
			}
		}
	}
	
	var theId;
	
	function grabID(e){ 	
		//se tienen que hacer estos cast de e.target porque si no, no funciona en IE				
		if (!e) e = window.event;	
		if ((((e.target || e.srcElement).type=='submit') || ((e.target || e.srcElement).getAttribute('href'))) && ((e.target || e.srcElement).name!=null ))
			if (((e.target || e.srcElement).getAttribute('href')!='#') && ((e.target || e.srcElement).getAttribute('href')!=null))
				//validaremos aquellos elementos que no deben mostrar la barra de progreso
				if (
					((e.target || e.srcElement).getAttribute('name')!='btnX') &&
					((e.target || e.srcElement).getAttribute('name')!='a_url_convenio')															
				)
					mostrar_ocultar('dvLoading');
	}
	
	document.onclick=grabID;

	function Obtener_Password_Aleatorio(min,max){
		var resAleatorio = Math.floor((Math.random() * (max - min + 1)) + min);
		return resAleatorio;	
	}
	
	//variables para la funcion handleKeyUp
	var functionlist = Array();
	var functionvalues = Array();
	var functionListLength=0;
			
	function handleKeyUp(search, cb){		
		var selectObj, textObj;
		var i, searchPattern, numShown;	
		
		//referencias
		selectObj = document.getElementById(cb);
		textObj   = document.getElementById(search);   
		
		if (functionlist.length==0){		
			for(i = 0; i < selectObj.options.length; i++){
				functionlist[i]=selectObj.options[i].text;		
				functionvalues[i]=selectObj.options[i].value;		
			}	
			//recordar tamaño array
			functionListLength = i+1;			
		}	
		
		searchPattern = textObj.value;

		//Crear expresión regular
		re = new RegExp(searchPattern,"gi");
		// Clear the options list
		selectObj.length = 0;

		//Búsqueda
		numShown = 0;
		for(i = 0; i < functionListLength; i++)
		{
			//buscamos por descripcion de cada opción (Text), asi podemos brindar mas opciones de localización, ej. ur, id emp, nombre, etc
			if(functionlist[i].search(re) != -1)
			{
				selectObj[numShown] = new Option(functionlist[i],functionvalues[i]);
				numShown++;
			}        
		}
		
		if(selectObj.length == 1)
		{
			selectObj.options[0].selected = true;
		}

	}	


	//begin desabilitar teclas
	document.onkeydown = function(event){		
		var evento, key;
		//IE uses this
		if (window.event){
			evento=window.event;
			key=window.event.keyCode;
			return keyz(evento,key);
		}
		//FF uses this
		else{
			evento=event;
			key=event.which;
			return keyz(evento,key);
		}     
	}

	function keyz(evento,key)
	{ 		
		//Evita F5 y F11
		//116->f5
		//122->f11
		if ((key == 122 || key == 116)){
			return false;
		}
		 
		//Si es Backspace
		if ((key == 8))
		{								
			//Si el campo es solo lectura
			if (document.activeElement.readOnly){			
				return false;
			}else{								
				if (
					(document.activeElement.getAttribute('name')=='objeto') ||
					(document.activeElement.getAttribute('name')=='e_objeto')
					){
					document.activeElement.value.keyCode = 8;
				}else{
					if (!document.activeElement.getAttribute('type')){
						return false;
					}else{
						//Si el valor es indefinido, o sea que no esta activo un elemento
						if (document.activeElement.value==define([
							'require',
							'dependency'
						], function(require, factory) {
							'use strict';
							
						});) {				
							return false;  //Evita Back en página.
						}else{				
							if (document.activeElement.getAttribute('type')=='select-one'){
								return false; 
							} //Evita Back en select.
							if (document.activeElement.getAttribute('type')=='select'){
								return false; 
							} //Evita Back en select.				
							if (document.activeElement.getAttribute('type')=='button'){
								return false; 
							} //Evita Back en button.
							if (document.activeElement.getAttribute('type')=='radio'){
								return false; 
							} //Evita Back en radio.
							if (document.activeElement.getAttribute('type')=='checkbox'){
								return false; 
							} //Evita Back en checkbox.
							if (document.activeElement.getAttribute('type')=='file'){
								return false; 
							} //Evita Back en file.
							if (document.activeElement.getAttribute('type')=='reset'){
								return false; 
							} //Evita Back en reset.
							if (document.activeElement.getAttribute('type')=='submit'){
								return false; 
							} //Evita Back en submit.
							else //Text, textarea o password
							{
								if (document.activeElement.value.length==0){
									return false; 
								} //No realiza el backspace(largo igual a 0).
								else{ 														
									document.activeElement.value.keyCode = 8; 
								} //Realiza el backspace.
							}				
						}
					}
				}
			}
		}
	}
	
	var Cuenta_Form_Enviado=0;		
	
	function Confirmar_Envio()
	{
		var agree=confirm("Se enviar� el formulario de registro �desea continuar?");
		if (agree)
			return true;
		else
			return false;
	}		
	
	function Confirmar_Envio_Mensaje(Mensaje)
	{
		var agree=confirm(Mensaje);
		if (agree)
			return true;
		else
			return false;
	}
		
	function Formulario_Enviado(){		
		if (Cuenta_Form_Enviado == 0)
		{
			Cuenta_Form_Enviado++;
			return true;
		}else {
			alert("El formulario ya est� siendo enviado, por favor aguarde un instante.");
			return false;
		}
	}
	
	function Cancelar(pagina){		
		document.location.href=pagina;
		return false;
	}
	
	function highlight(objeto,clase){
		objeto.className=clase;
	}
	
	function unhighlight(objeto,clase){
		objeto.className=clase;
	}
	
</script>