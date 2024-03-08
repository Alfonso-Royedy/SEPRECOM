<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Usuarios</title>

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
		
	<!-- contenedor principal de la aplicaci?n -->		
	<div class='container main_div_container'>
					
		<div class='row'>
		
			<!-- Ubicación actual dentro del portal -->	
			<div class='col-md-7'>
				<ol class='breadcrumb main_breadcrumb'>
					<li><a class='color_amarillo' href='principal'><?php echo $this->config->item('mycfg_nombre_aplicacion'); ?></a></li>
					<li class='color_amarillo'>Catálogos</li>											
					<li class='active' style='color: white;'>Usuarios</li>											
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

		<button style="margin-bottom: 15px;" type="button" id="CrearNuevo" class="btn btn-primary">Nuevo</button>
			<script>
					$(document).ready(function(){
						$("#CrearNuevo").click(function(){
							frmNuevoUsuario.reset(); 
							//se muestra la ventana modal del formulario
							$('#modalNuevoUsuario').modal();
							//se blanquea el div de errores del formulario
							$("#div_col_val_errors").html("");
						});
					});
			</script>

<button style="margin-bottom: 15px;" type="button" id="EliminarElemento" class="btn btn-primary">Eliminar</button>
							<script>
								$(document).ready(function () {
									$("#EliminarElemento").click(function(){
										//se obtienen los datos del registro seleccionado de la tabla
										var count = $('#tbCliente').DataTable().rows({ selected: true }).count();
										if (count==1){
											var rows =  $('#tbCliente').DataTable().rows({ selected: true }).indexes();
											var data =  $('#tbCliente').DataTable().rows( rows ).data();												
											var respuesta = confirm('¿Está seguro que desea eliminar el usuario:\n\n'+data[0].nombre+'?');
											if (respuesta){										
												$.ajax({
													type: "POST",
													url: "<?php echo base_url();?>index.php/Usuarios/Eliminar_Usuario",
													data: {"matricula" : data[0].matricula},
													success: function(msg){															
														var msg_substr = msg.split("@", 3);
														var msg_html = msg_substr[0];
														var msg_cont_notif = msg_substr[1];
														var msg_result = msg_substr[2];
														$('#div_notifications_content').html(msg_html);	
														$("#span_notif_count").html(msg_cont_notif);         
														$('#modal_notificaciones').modal();
														if (msg_result=="T"){																																	
															$('#tbCliente').DataTable().ajax.reload(null, false);
														}																								
													},
													error: function(){
														alert("Ocurrió error al procesar la petición al servidor.");
													}
												});
											}	
										}else{
											alert('Debe elegir un registro');
										}
									});
								});
							</script>


		<button style="margin-bottom: 15px;" type="button" id="VerElemento" class="btn btn-primary">Ver Información</button>
			<script>
				$(document).ready(function () {
					$("#VerElemento").click(function(){
						//se obtienen los datos del registro seleccionado de la tabla
						var count = $('#tbCliente').DataTable().rows({ selected: true }).count();
						if (count==1){
							var rows =  $('#tbCliente').DataTable().rows({ selected: true }).indexes();
							var data =  $('#tbCliente').DataTable().rows( rows ).data();												
							//se inicializan los valores del formulario												
							$('#p_v_usuario').html(data[0].nombre);
							$('#p_v_correo').html(data[0].correo);
							$('#p_v_cargo').html(data[0].cargo);
							$('#p_v_rol').html(data[0].Rol_admin);
							//se muestra la ventana modal del formulario
							$('#modalVerUsuario').modal();
						}else{
							alert('Debe elegir un registro');
						}
					});
				});

			</script>

		<!-- Espacio disponible para mostrar informaci?n del portal -->	
		<div class='row'>
			<div class='col-md-12'>		
		
				<!-- Ventana modal del formulario para crear un nuevo registro -->	
				<div class='modal fade' id='modalNuevoUsuario'>
					<!--SI SE DESEA MODIFICAR EL ANCHO DE LA VENTANA style='width: 700px;'-->
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Nuevo usuario</h4>
							</div>
							<div class='modal-body'>
<?php 
							echo form_open("Usuarios/Crear_Usuario","id='frmNuevoUsuario' name='frmNuevoUsuario' role='form'"); 
?>												
								<div class='row'>												
									<div class='col-md-12' id='div_col_val_errors' name='div_col_val_errors'>										
									</div>
								</div>
								<div class='row'>
									<div class='col-md-4'>
										Nombre de usuario:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											EditBox("Usuario1","Usuario1","form-control","",1, 255,255,false,set_value('Usuario1'),"",false,"Ingrese usuario","");												
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Correo:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											EditBox("Correo1","Correo1","form-control","",1, 255,255,false,set_value('Correo1'),"",false,"Ingrese correo","");												
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Cargo:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											EditBox("Cargo1","Cargo1","form-control","",1, 255,255,false,set_value('Cargo1'),"",false,"Ingrese cargo","");												
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Rol de administrador:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											EditBox("Rol_adm","Rol_adm","form-control","",1, 255,255,false,set_value('Rol_adm'),"",false,"Ingrese rol de administrador","");												
?>
										</div>
									</div>
								</div>
							</div>
							<div style="background-color: #000053;" class='modal-footer'>
								<button type='button' class='btn btn-close' data-dismiss='modal'>Cancelar</button>
								<button type='button' class='btn btn-color' id='btnGuardarNuevoUsuario' name='btnGuardarNuevoUsuario' value='Guardar'><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
							</div>
							</form>
						</div>
					</div>
				</div>				

<script>															
				$(document).ready(function () {
					$("#btnGuardarNuevoUsuario").click(function(){
						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>index.php/Usuarios/Crear_Usuario",
							data: $('#frmNuevoUsuario').serialize(),
							success: function(msg){																					
								var msg_substr = msg.split("@", 4);
								var msg_html = msg_substr[0];
								var msg_cont_notif = msg_substr[1];
								var msg_result = msg_substr[2];
								var msg_val_errors = msg_substr[3];
								$('#div_notifications_content').html(msg_html);	
								$("#span_notif_count").html(msg_cont_notif);         																																																																					
								$('#modal_notificaciones').modal();								
								if (msg_result=="T"){																				
									$("#modalNuevoUsuario").modal('hide');																				
									$('#tbCliente').DataTable().ajax.reload(null, false);
									$('#tbCliente').DataTable().page('last');
									$("#div_col_val_errors").html("");
								}else{
									$("#div_col_val_errors").html(msg_val_errors);
								}									
							},
							error: function(){
								alert("Ocurrió un error al procesar la petición al servidor.");
							}
						});
					});
										
				});
</script>	
				
				<!-- Ventana modal del formulario para editar un registro -->	
				<div class='modal fade' id='modalEditarInstitucion'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
									<h4 class='modal-title'>Institución</h4>
							</div>
							<div class='modal-body'>
<?php 
							echo form_open("instituciones/editar_institucion","id='frmEditarInstitucion' name='frmEditarInstitucion' role='form'"); 
								//Agregamos los campos de la llave primaria como campos de tipo hidden
								echo form_input(array("type"=>"hidden","name"=>"e_id_institucion","id"=>"e_id_institucion","value"=>""));
?>												
								<div class='row'>												
									<div class='col-md-12' id='div_col_e_val_errors' name='div_col_e_val_errors'>										
									</div>
								</div>
								<div class='row'>
									<div class='col-md-4'>
										Id. de la Institución:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>											
											<!-- Mostramos los valores de la llave primaria como textos no editables <p></p> -->
											<p id='p_e_id_institucion' name='p_e_id_institucion'></p>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-4'>
										Nombre de la Institución:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											EditBox("e_institucion","e_institucion","form-control","",1, 255,255,false,set_value('e_institucion'),"",false,"Nombre de la institución","");												
?>
										</div>
									</div>
								</div>
							</div>
							<div class='modal-footer'>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Cancelar</button>
								<button type='button' class='btn btn-primary' id='btnGuardarEdicionInstitucion' name='btnGuardarEdicionInstitucion' value='Guardar'><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
							</div>
							</form>
						</div>
					</div>
				</div>				

<script>															
				$(document).ready(function () {
					$("#btnGuardarEdicionInstitucion").click(function(){
						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>index.php/instituciones/editar_institucion",
							data: $('#frmEditarInstitucion').serialize(),
							success: function(msg){																					
								var msg_substr = msg.split("@", 4);
								var msg_html = msg_substr[0];
								var msg_cont_notif = msg_substr[1];
								var msg_result = msg_substr[2];
								var msg_val_errors = msg_substr[3];
								$('#div_notifications_content').html(msg_html);	
								$("#span_notif_count").html(msg_cont_notif);         																																																																					
								$('#modal_notificaciones').modal();								
								if (msg_result=="T"){																				
									$("#modalEditarInstitucion").modal('hide');																				
									$('#tbInstituciones').DataTable().ajax.reload(null, false);
									$("#div_col_e_val_errors").html("");
								}else{
									$("#div_col_e_val_errors").html(msg_val_errors);
								}									
							},
							error: function(){
								alert("Ocurrió un error al procesar la petición servidor.");
							}
						});
					});
					
					
				});
</script>					
				
				<!-- Ventana modal del formulario para editar un registro -->	
				<div class='row'>
			<div class='col-md-12'>		
		
				<!-- Ventana modal del formulario para crear un nuevo registro -->	
				<div class='modal fade' id='modalVerUsuario'>
					<!--SI SE DESEA MODIFICAR EL ANCHO DE LA VENTANA style='width: 700px;'-->
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Visualizar información del usuario</h4>
							</div>
							<div class='modal-body'>

								<div class='row'>
									<div class='col-md-4'>
										Nombre de usuario:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
										<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_usuario' name='p_v_usuario'></p>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Correo:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
										<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_correo' name='p_v_correo'></p>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Cargo:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
										<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_cargo' name='p_v_cargo'></p>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Rol de administrador:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
										<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_rol' name='p_v_rol'></p>
										</div>
									</div>
								</div>
							</div>
							<div style="background-color: #000053;" class='modal-footer'>
								<button type='button' class='btn btn-close' data-dismiss='modal'>Cancelar</button>
							</div>
							</form>
						</div>
					</div>
				</div>			

				
				
				<!-- Tabla din?mica para mostrar los registros del cat?logo -->	
				<table id='tbCliente' name='tbCliente' class='display cell-border order-column dt-responsive'>
					<thead>
						<tr>							
							<th style='width: 50px;'>Matrícula				
							<th>Nombre
							<th>Correo
							<th>Cargo
							<th>Rol de administrador
							<th>Contraseña
										
					</thead>
					<tfoot>
						<tr>																										
							<th>						
							<th>					
					</tfoot>					
					<tbody>															
					</tbody>
				</table>


<script>
				var tbCliente;
				$(document).ready( function () {
					$.fn.dataTable.ext.errMode = 'throw';
					tbCliente = $('#tbCliente').DataTable(
						{																									
							dom : 'Blfiprtip',																																																	
							language: {
								processing:     "Procesando...",
								search:         "Buscar:",
								lengthMenu:     "Mostrar _MENU_ registro(s) a la vez",
								info:           "Mostrando _START_ a _END_ de _TOTAL_ registro(s)",
								infoEmpty:      "Mostrando 0 a 0 de 0 registros",
								infoFiltered:   "(Filtrados de _MAX_ registros en total)",
								infoPostFix:    "",
								loadingRecords: "Cargando...",
								zeroRecords:    "No hay registros para mostrar",
								emptyTable:     "No hay datos disponibles en la tabla",
								paginate: {
									first:      "Primero",
									previous:   "Anterior",
									next:       "Siguiente",
									last:       "Ultimo"
								},
								aria: {
									sortAscending:  ": Ordenar ascendentemente",
									sortDescending: ": Ordenar descendentemente"
								},
								select: {
									rows: {
										_: " - %d registros seleccionados",
										0: "",
										1: " - 1 registro seleccionado"
									}
								}
							},											
							"pageLength": 10,
							"lengthMenu": [ 5,10, 25, 50, 100, 250, 500, 1000, 5000, 10000],
							responsive: true,
							select: {
								style: 'os'
							},
							buttons: [
								{
									extend: 'copyHtml5',
									text: '<span class="glyphicon glyphicon-indent-left"></span> Copiar registros'
								},								
								{
									extend: 'excelHtml5',
									text: '<span class="glyphicon glyphicon-export"></span> Exportar a Excel'
								}	
							],																		
							columnDefs: [
								{ responsivePriority: 1, targets: 0 },
								{ responsivePriority: 1, targets: 1 }								
							],											
							ajax: '<?php echo base_url();?>index.php/Usuarios/Obtener_Dataset_Cliente',
							autoWidth: false,							
							columns: [
								{ data: "matricula" },
								{ data: "nombre" },
								{ data: "correo" },
								{ data: "cargo" },
								{ data: "Rol_admin" },
								{ data: "contrasena", visible: false}
							],
							"footerCallback": function ( row, data, start, end, display ) {
								var api = this.api(), data;
					 
								// Remove the formatting to get only the number data
								var numericVal = function ( i ) {
									return typeof i === 'string' ?
										i.replace(/[\$,]/g, '')*1 :
										typeof i === 'number' ?
											i : 0;
								};
								/*
								// Total over all pages
								total = api
									.column( 1 )
									.data()
									.reduce( function (a, b) {
										return numericVal(a) + numericVal(b);
									}, 0 );
					 
								// Total over this page
								pageTotal = api
									.column( 1, { page: 'current'} )
									.data()
									.reduce( function (a, b) {
										return numericVal(a) + numericVal(b);
									}, 0 );
					 
								// Update footer data
								$( api.column( 1 ).footer() ).html(
									pageTotal +' (de '+ total +')'
								);
								*/
							}

							
						} 
					);																							
					
					// Apply the search
					$('#tbCliente').DataTable().columns().every( function () {
						var that = this;
				 
						$( 'input', this.header() ).on( 'keyup change', function () {				
							if ( that.search() !== this.value ) {
								that
									.search( this.value )
									.draw();
							}
						} );
					} );
					
				} );	
								
</script>
			
			</div>	
		</div>	
	
	</div>
	
	
<?php
	require "owned/set_security_controller.php";
	require "owned/notification_messages_controller.php";
?>
</body>
</html>