<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Acceso al portal</title>

<?php
	require "third_party/jquery.js";
	require "third_party/bootstrap.js";
	require "third_party/datatables.js";
	require "third_party/bsdatatimepicker.js";	
	require "third_party/googlecharts.js";
	require "owned/form_tweaks.js";
  require "owned/login_estilo.php";
?>
	
</head>
<body>

		<div class='wrapper fadeInDown'>
        <div id='formContent'>

          <div id='header'>
            <a class='underlineHover' >SEPRECOM</a>
          </div>

          
          <h2 class='active'> Introduzca su información de acceso </h2>
      
        
          <div class='fadeIn first'>
            <img src='<?php echo base_url(); ?>application/views/imagenes/LogoSeprecom.jpg'  id='icon' alt='User Icon' />
          </div>

          <br>
      
		  <?php				
				echo form_open("acceso/login","role='form'");
					echo "Nombre de usuario:";
					EditBox("username","username","form-control","",1, 50,8,false,set_value('username'),"",false,"Ingrese su usuario","autofocus");												
					echo "<font class='font_notif_error'>".form_error('username')."</font><br>"; 					
					echo "Contraseña:";
					EditBox("password","password","form-control","",1, 20,20,true,"","",false,"Ingrese su contraseña","");												
					echo "<font class='font_notif_error'>".form_error('password')."</font><br>";
			?>					
					<input type='submit' class='fadeIn fourth' name='btn_ingresar' value='Ingresar'>
				</form>
      
        <br>
         
          <div id='formFooter'>
            <a class='footer' style='text-align: center; font-size: 12px; text-decoration:none; padding-top: 5px; color: white;'>
				      Servicio de Prestamo de Computo @2024<br>				
				      Desarrollado por: <font color='orange'>Alumnos De ITS 8vo Semestre</font>
            </a>
          </div>
      
        </div>
      </div>					

</body>
</html>