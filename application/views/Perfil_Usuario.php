<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Perfil Usuario</title>

<?php
	require "third_party/jquery.js";
	require "third_party/bootstrap.js";
	require "third_party/datatables.js";
	require "third_party/bsdatatimepicker.js";	
	require "third_party/googlecharts.js";
	require "owned/form_tweaks.js";	
	require "owned/estilos_portal.php";
?>
	
</head>
<body>
	
<?php	
	$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));					
	require "owned/navigation_bar.php";
	require "owned/footer.php";				
?>	
	
	<!-- contenedor principal de la aplicación -->		
	<div class='container main_div_container'>
					
		<div class='row'>
		
			<!-- Ubicación actual dentro del portal -->	
			<div class='col-md-7'>
				<ol class='breadcrumb main_breadcrumb'>
					<li><a class='color_verde_uac' href='principal'><?php echo $this->config->item('mycfg_nombre_aplicacion'); ?></a></li>
					<li class='active' style='color: white;'>Usuario</li>											
				</ol>
			</div>		
			
						
<?php
			require "owned/notification_center.php";
			
			if (isset($notificacion_exito)){
				MostrarNotificacion($notificacion_exito,"OK",true);																					
			}
				
			if (isset($notificacion_error)){
				MostrarNotificacion($notificacion_error,"Error",true);																					
			}
?>	
		</div>				
	
		<!-- Espacio disponible para mostrar información del portal -->	
		<div class='row'>
			<div class='col-md-12'>				
				
<?php								
				
				if ($session_data['oauth_logged_in']=='N'){
					echo "<div class='row'>";			  
						echo "<div class='col-md-12'>";
							echo "<div class='panel panel-default'>";
								echo "<div class='panel-heading'>Cambiar Contraseña de Acceso</div>";										
								echo "<div class='panel-body'>";																				
				
									echo "<div class='row'>";	
										echo "<div class='col-md-1'>";																										
											echo "Contraseña actual:";
										echo "</div>";
										echo form_open("usuario/cambiar_password","role='form'");									
											echo "<div class='col-md-2'>";																										
												EditBox('password_actual','password_actual','form-control','',1, 20,20,true,set_value('password_actual'),'',false,'Capture su Contraseña actual','');				
												echo "<font class='font_notif_error'>".form_error('password_actual')."</font><br>";
											echo "</div>";												
											echo "<div class='col-md-1'>";																										
												echo "Contraseña nueva:";
											echo "</div>";													
											echo "<div class='col-md-2'>";																										
												EditBox('password_nuevo','password_nuevo','form-control','',1, 20,20,true,set_value('password_nuevo'),'',false,'Capture su Nueva Contraseña','');				
												echo "<font class='font_notif_error'>".form_error('password_nuevo')."</font><br>";
											echo "</div>";	
											echo "<div class='col-md-1'>";																										
												echo "Confirmar Contraseña nueva:";
											echo "</div>";	
											echo "<div class='col-md-2'>";																										
												EditBox('password_nuevo_confirmar','password_nuevo_confirmar','form-control','',1, 20,20,true,set_value('password_nuevo_confirmar'),'',false,'Confirme su Nueva Contraseña','');				
												echo "<font class='font_notif_error'>".form_error('password_nuevo_confirmar')."</font><br>";
											echo "</div>";	
											echo "<div class='col-md-3'>";			
												echo "<button type='submit' class='btn btn-primary' id='btnCambiarPassword' name='btnCambiarPassword' value='Cambiar Password'><span class='glyphicon glyphicon-floppy-disk'></span> Cambiar Password</button>";																					
											echo "</div>";	
										echo "</form>";
									echo "</div>";											
																				
								echo "</div>";										
							echo "</div>";
						echo "</div>";
					echo "</div>";
				}
				echo "<div class='row'>";			  
					echo "<div class='col-md-12'>";
						echo "<div class='panel panel-default'>";
							echo "<div class='panel-heading'>Cambiar Perfil de Acceso</div>";										
							echo "<div class='panel-body'>";																				
			
								echo "<div class='row'>";	
									echo "<div class='col-md-1'>";																										
										echo "Perfil Actual:";
									echo "</div>";													
									echo "<div class='col-md-11'>";																										
										echo "<font color='orange'>".$session_data['default_pfc_name']."</font>";
									echo "</div>";												
								echo "</div>";
								echo "<br>";
								echo "<div class='row'>";	
									echo "<div class='col-md-1'>";																										
										echo "Cambiar a:";
									echo "</div>";			
									echo form_open("usuario/cambiar_perfil","role='form'");																																			
										echo "<div class='col-md-4'>";																															
											dbComboBox('perfil','perfil','form-control','',3,false,0,$arrPerfiles,array("Pfc_User"),array("description"),set_value('perfil'),"","","Seleccione un Perfil de la lista");			
											echo "<font class='font_notif_error'>".form_error('perfil')."</font><br>";
										echo "</div>";												
										echo "<div class='col-md-7'>";	
											echo "<button type='submit' class='btn btn-primary' id='btnCambiarPerfil' name='btnCambiarPerfil' value='Cambiar Perfil'><span class='glyphicon glyphicon-floppy-disk'></span> Cambiar Perfil</button>";											
										echo "</div>";																									
									echo "</form>";
								echo "</div>";											
																			
							echo "</div>";										
						echo "</div>";
					echo "</div>";
				echo "</div>";
												
					
?>					
				
				
			</div>	
		</div>	
	
	</div>
	
<?php
	require "owned/set_security_controller.php";
	require "owned/notification_messages_controller.php";
?>
</body>
</html>