<?php

	//Sirve para agregar los mensajes al panel modal de mensajes
	$Contador_Mensajes=0;

	//Sirve para agregar las notificaciones correctamente al centro de notificaciones
	$Contador_Notificaciones=0;

	function CrearNotificacion(){			
		global $Contador_Notificaciones;
		$Contador_Notificaciones++;		
		return "notification_".$Contador_Notificaciones;
	}
	
	function MostrarNotificacion($Mensaje,$Tipo,$AgregarCentroNotificaciones){	
		if ($AgregarCentroNotificaciones){						
			$IdNotif=CrearNotificacion();
			switch ($Tipo){
				case 'Nota':
					echo "<div class='alert alert-warning' id='".$IdNotif."'>";											
					break;
				case 'Error':
					echo "<div class='alert alert-danger' id='".$IdNotif."'>";											
					break;
				case 'OK':
					echo "<div class='alert alert-success' id='".$IdNotif."'>";										
					break;
			}			
		}else{
			switch ($Tipo){
				case 'Nota':
					echo "<div class='alert alert-warning'>";											
					break;
				case 'Error':
					echo "<div class='alert alert-danger'>";											
					break;
				case 'OK':
					echo "<div class='alert alert-success'>";										
					break;
			}
		}					
			echo $Mensaje;								
		echo "</div>";									
		return true;
	}

	function CrearMensaje(){			
		global $Contador_Mensajes;		
		$Contador_Mensajes++;
		return "message_".$Contador_Mensajes;
	}
	
	function MostrarMensaje($Mensaje,$Tipo,$AgregarCentroMensajes){	
		if ($AgregarCentroMensajes){						
			$IdMsg=CrearMensaje();
			switch ($Tipo){
				case 'Nota':
					echo "<div class='alert alert-warning' id='".$IdMsg."'>";											
					break;
				case 'Error':
					echo "<div class='alert alert-danger' id='".$IdMsg."'>";											
					break;
				case 'OK':
					echo "<div class='alert alert-success' id='".$IdMsg."'>";										
					break;
			}			
		}else{
			switch ($Tipo){
				case 'Nota':
					echo "<div class='alert alert-warning'>";											
					break;
				case 'Error':
					echo "<div class='alert alert-danger'>";											
					break;
				case 'OK':
					echo "<div class='alert alert-success'>";										
					break;
			}
		}					
			echo $Mensaje;								
		echo "</div>";									
		return true;
	}
	
	function Obtener_Contador_Notificaciones(){
		global $Contador_Notificaciones;
		return $Contador_Notificaciones;
	}
	
	function Obtener_Contador_Mensajes(){
		global $Contador_Mensajes;
		return $Contador_Mensajes;
	}

?>