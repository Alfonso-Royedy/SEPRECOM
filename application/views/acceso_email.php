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
	require "owned/estilos_portal.php";
?>
	
</head>
<body>

	<div class='container login_div_container'>	
		<div class='row'>			
			<div class='col-md-4'></div>
			<div class='col-md-4' style='text-align: center;'>
				<h4 class="form-signin-heading"><strong><?php echo $this->config->item('mycfg_nombre_aplicacion'); ?></strong><br>
				<small>Haga clic en el botón para proporcionar la información de acceso de su Cuenta de EMail Institucional</small></h4><br>
			</div>
			<div class='col-md-4'></div>
		</div>
		<div class='row'>			
			<div class='col-md-4'></div>
			<div class='col-md-1' style='padding-bottom: 10px; text-align: center;'>
				<img src='<?php echo base_url(); ?>application/views/imagenes/uac.png' class='img-thumbnail' alt='UAC'>
			</div>
			<div class='col-md-3'>
								
<?php				
				echo form_open("acceso_email/login","role='form' method='get'");
					echo form_input(array("type"=>"hidden","name"=>"code","id"=>"code","value"=>""));
					echo "<font class='font_notif_error'>".$error."</font><br>"; 										
?>					
					<button class="btn btn-primary btn-block" type="submit" name='btn_ingresar' value='Ingresar'>Ingresar con Cuenta de EMail Institucional</button>
				</form>
			</div>			
			<div class='col-md-4'></div>
		</div>	
		<br>
	</div>						
<?php	
	require "owned/footer.php";			
?>		
</body>
</html>