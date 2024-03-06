<!-- Barra de Navegación colocada en la parte superior y fija -->	
	<nav style="background-color:#000053;"  class="navbar navbar-default navbar-fixed-top">
		<div style='width: 98%;' class="container-fluid">		
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Menu</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <!-- Logo y título del portal -->	
		  <a class="navbar-brand" href="<?php echo base_url();?>index.php/principal" style='border-right: 2px solid lightgray; height: 70px;'><img style='margin-right: 5px; height: 100%; border-radius:100%;' src='<?php echo base_url();?>application/views/imagenes/LogoSeprecom.jpg' alt='uac'><?php echo $this->config->item('mycfg_nombre_aplicacion'); ?></a>
		</div>		
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<!-- Menú de opciones -->	
<?php
				echo $menu;
?>				
			</ul>		  
			<ul class='nav navbar-nav navbar-right'>		  
				<!-- Opción para visualizar perfil del usuario conectado -->	
				<li class='active'><a href='<?php echo base_url();?>index.php/principal'><span class='glyphicon glyphicon-user'></span> <?php echo $session_data['full_name']; ?><br> <font color='orange'><?php echo $session_data['default_pfc_name']; ?></font></a></li>				
<?php
				echo form_open("acceso/logout","class='navbar-form navbar-right' role='form'");
?>				
				
					<!-- Botón para salir del portal -->	
					<button type='submit' class='navbar-btn btn btn-success'>Salir</button>
				</form>				
			</ul>		  
		</div>
		</div>
	</nav>	
	
	<!-- ventana modal de mensajes no visible por defecto -->	
	<div class='modal fade' id='modal_mensajes' tabindex='-1' role='dialog' aria-labelledby='modallbl_mensajes' aria-hidden='true'>
		<div class='modal-dialog modal-lg'>
			<div class='modal-content'>
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					<h4 class='modal-title' id='modallbl_mensajes'>Mensajes</h4>
				</div>
				<div class='modal-body'>
					<div id='div_messages_content'  name='div_messages_content'>
					</div>
				</div>
			</div>
		</div>
	</div>