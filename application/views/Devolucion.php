<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Prestamos</title>

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
					<li class='color_amarillo'>Procesos</li>											
					<li class='active' style='color: white;'>Prestamos</li>											
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

		<div id="adminOptions">
					<!-- Standard button -->

					<button style="margin-bottom: 15px;" type="button" id="EditarElemento" class="btn btn-primary">Realizar devolución</button>
						<script>
							$(document).ready(function () {
								$("#EditarElemento").click(function () {
									// Se obtienen los datos del registro seleccionado de la tabla
									var count = $('#tbSolicitudes').DataTable().rows({ selected: true }).count();

									if (count == 1) {
										var rows = $('#tbSolicitudes').DataTable().rows({ selected: true }).indexes();
										var data = $('#tbSolicitudes').DataTable().rows(rows).data();
										frmDevoluciones.reset();
										// Se inicializan los valores del formulario
										$('#id_prestamo').val(data[0].id_prestamo);
										$('#Equipo_Solicitado').html(data[0].id_producto);
										$('#id_producto').val(data[0].id_producto);
										$('#Encargado2').val(data[0].encargado_devo);
										$('#Fecha_devolucion').html(data[0].fecha_devo);
										$('#observacion1').val(data[0].observaciones);
										$('#id_solicitud1').val(data[0].id_solicitud);
										$('#pre_estado').val(data[0].estado);
										$('#Estado1').html(data[0].estado);

										// Verificar si el estado es "devuelto" y deshabilita la edición
										if (data[0].estado.toLowerCase() === 'devuelto') {
											// Deshabilita campos de edición
											$('#frmDevoluciones input, #frmDevoluciones textarea').prop('readonly', true);
										} else {
											// Habilita campos de edición si el estado no es "devuelto"
											$('#frmDevoluciones input, #frmDevoluciones textarea').prop('readonly', false);
										}

										// Se muestra la ventana modal del formulario
										$('#modalDevolucion').modal();

										// Se blanquea el div de errores del formulario
										$("#div_col_e_val_errors").html("");
									} else {
										alert('Debe elegir un registro');
									}
								});
							});

						</script>

					<!-- Indicates a successful or positive action -->
					<button style="margin-bottom: 15px;" type="button" id="EliminarElemento" class="btn btn-primary">Eliminar</button>
							<script>
								$(document).ready(function () {
									$("#EliminarElemento").click(function(){
										// Se obtienen los datos del registro seleccionado de la tabla
										var count = $('#tbSolicitudes').DataTable().rows({ selected: true }).count();
										if (count == 1) {
											var rows = $('#tbSolicitudes').DataTable().rows({ selected: true }).indexes();
											var data = $('#tbSolicitudes').DataTable().rows(rows).data();
											
											// Verificar el estado antes de mostrar el cuadro de confirmación
											if (data[0].estado === "Prestado") {
												var respuesta = confirm('¿Está seguro que desea eliminar el préstamo: ' + data[0].id_prestamo + '?');
												if (respuesta) {
													$.ajax({
														type: "POST",
														url: "<?php echo base_url();?>index.php/Devolucion/Eliminar_Prestamo",
														data: {"id_prestamo" : data[0].id_prestamo},
														success: function(msg){                                                            
															var msg_substr = msg.split("@", 3);
															var msg_html = msg_substr[0];
															var msg_cont_notif = msg_substr[1];
															var msg_result = msg_substr[2];
															$('#div_notifications_content').html(msg_html);    
															$("#span_notif_count").html(msg_cont_notif);         
															$('#modal_notificaciones').modal();
															if (msg_result == "T") {                                                                        
																$('#tbSolicitudes').DataTable().ajax.reload(null, false);
															}                                                                                                
														},
														error: function(){
															alert("Ocurrió un error al procesar la petición al servidor.");
														}
													});
												}
											} else {
												alert('No se permite eliminar registros con estado "devuelto".');
											}
										} else {
											alert('Debe elegir un registro');
										}
									});
								});
							</script>

					<!-- Contextual button for informational alert messages -->
					<button style="margin-bottom: 15px;" type="button" id="VerElemento" class="btn btn-primary">Ver Información</button>
						<script>
							$(document).ready(function () {
								$("#VerElemento").click(function(){
									//se obtienen los datos del registro seleccionado de la tabla
									var count = $('#tbSolicitudes').DataTable().rows({ selected: true }).count();
									if (count == 1){
										var rows =  $('#tbSolicitudes').DataTable().rows({ selected: true }).indexes();
										var data =  $('#tbSolicitudes').DataTable().rows(rows).data();                                                
										
										//se inicializan los valores del formulario
										$('#p_v_id_prestamo').html(data[0].id_prestamo);
										$('#p_v_producto').html(data[0].nombre_producto);
										
										// Verificar si la observación tiene datos
										if (data[0].encargado_devo.trim() !== "") {
											$('#p_v_devolucion').html(data[0].encargado_devo);
										} else {
											$('#p_v_devolucion').html('En espera');
										}
										// Verificar si la observación tiene datos
										if (data[0].fecha_devo.trim() !== "") {
											$('#p_v_fecha_devolucion').html(data[0].fecha_devo);
										} else {
											$('#p_v_fecha_devolucion').html('En espera');
										}
										// Verificar si la observación tiene datos
										if (data[0].observaciones.trim() !== "") {
											$('#p_v_observacion').html(data[0].observaciones);
										} else {
											$('#p_v_observacion').html('Sin información');
										}

										$('#p_v_idSolicitud').html(data[0].id_solicitud);
										$('#p_v_estado').html(data[0].estado);

										//se muestra la ventana modal del formulario
										$('#modalVisualizarPrestamo').modal();
									} else {
										alert('Debe elegir un registro');
									}
								});
							});
						</script>

				</div>


		<!-- Espacio disponible para mostrar informaci?n del portal -->	
		<div class='row'>
			<div class='col-md-12'>		
		
				<!-- Ventana modal del formulario para crear un nuevo registro -->	
				<div class='modal fade' id='modalNuevaInstituc'>
					<!--SI SE DESEA MODIFICAR EL ANCHO DE LA VENTANA style='width: 700px;'-->
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
									<h4 class='modal-title'>Nueva Institución</h4>
							</div>
							<div class='modal-body'>
<?php 
							echo form_open("instituciones/crear_institucion","id='frmNuevaInstitucion' name='frmNuevaInstitucion' role='form'"); 
?>												
								<div class='row'>												
									<div class='col-md-12' id='div_col_val_errors' name='div_col_val_errors'>										
									</div>
								</div>
								<div class='row'>
									<div class='col-md-4'>
										Nombre de la Institución:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											EditBox("institucion","institucion","form-control","",1, 255,255,false,set_value('institucion'),"",false,"Nombre de la Institución","");												
?>
										</div>
									</div>
								</div>
							</div>
							<div class='modal-footer'>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Cancelar</button>
								<button type='button' class='btn btn-primary' id='btnGuardarNuevaInstitucion' name='btnGuardarNuevaInstitucion' value='Guardar'><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
							</div>
							</form>
						</div>
					</div>
				</div>				

<script>															
				$(document).ready(function () {
					$("#btnGuardarNuevaInstitucion").click(function(){
						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>index.php/instituciones/crear_institucion",
							data: $('#frmNuevaInstitucion').serialize(),
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
									$("#modalNuevaInstitucion").modal('hide');																				
									$('#tbInstituciones').DataTable().ajax.reload(null, false);
									$('#tbInstituciones').DataTable().page('last');
									$("#div_col_val_errors").html("");
								}else{
									$("#div_col_val_errors").html(msg_val_errors);
								}									
							},
							error: function(){
								alert("Ocurri? un error al procesar la petici?n servidor.");
							}
						});
					});
										
				});
</script>	
				
				<!-- Ventana modal del formulario para editar un registro -->	
				<div class='modal fade' id='modalDevolucion'>
					<div class='modal-dialog'>
						<div style='width: 480px;' class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button style="background-color: #FFCD00;" type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Datos del préstamo</h4>
							</div>
							<div class='modal-body'>
<?php 
							echo form_open("Devolucion/Realizar_Devolucion","id='frmDevoluciones' name='frmDevoluciones' role='form'"); 
								//Agregamos los campos de la llave primaria como campos de tipo hidden
								echo form_input(array("type"=>"hidden","name"=>"id_prestamo","id"=>"id_prestamo","value"=>""));
								echo form_input(array("type"=>"hidden","name"=>"id_producto","id"=>"id_producto","value"=>""));
								echo form_input(array("type"=>"hidden","name"=>"id_solicitud1","id"=>"id_solicitud1","value"=>""));
								echo form_input(array("type"=>"hidden","name"=>"pre_estado","id"=>"pre_estado","value"=>""));

?>												
								<div class='row'>												
									<div class='col-md-12' id='div_col_e_val_errors' name='div_col_e_val_errors'>										
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Nombre de quién recibe devolución:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>									

										<input style="border: none; outline: none; !important; color:orange; font-size: 17px; font-weight: bold; margin-bottom: -25px;" type="text" name='Encargado2' value="<?php echo set_value('Encargado2', $session_data['full_name']); ?>" readonly />

										</div>
									</div>
								</div>						

								<div class='row'>
									<div class='col-md-5'>
										Equipo o accesorio solicitado:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											

											<p style="color:orange; font-size: 17px; font-weight: bold;" id='Equipo_Solicitado' name='Equipo_Solicitado'></p>

										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										observación:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>									
<?php
										TextArea("observacion1","observacion1","form-control","",1,5,"",false,set_value('observacion1'),"",false,"");
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Fecha devolucion:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
											DateEditBox("Fecha_devolucion", "Fecha_devolucion", "form-control", "", 1, 255, 255, false, "Fecha de devolucion", "");												
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Estado del prestamo:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											

											<p style="color:orange; font-size: 17px; font-weight: bold;" id='Estado1' name='Estado1'></p>

										</div>
									</div>
								</div>



							</div>
							<div style="background-color: #000053;" class='modal-footer'>
								<button type='button' class='btn btn-close' data-dismiss='modal'>Cancelar</button>
								<button type='button'  class='btn btn-color' id='btnGuardarDevolucion' name='btnGuardarDevolucion' value='Guardar'><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
							</div>
							</form>
						</div>
					</div>
				</div>				

<script>															
				$(document).ready(function () {
					$("#btnGuardarDevolucion").click(function(){
						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>index.php/Devolucion/Realizar_Devolucion",
							data: $('#frmDevoluciones').serialize(),
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
									$("#modalDevolucion").modal('hide');																				
									$('#tbSolicitudes').DataTable().ajax.reload(null, false);
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
				
				<!-- Ventana modal  para visualizar la informacion de la información -->	

				<div class='modal fade' id='modalVisualizarPrestamo'>
					<div class='modal-dialog'>
						<div style='width: 480px;' class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button style="background-color: #FFCD00;" type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Visualizar información del préstamo</h4>
							</div>
							<div class='modal-body'>
											
								<div class='row'>												
									<div class='col-md-12' id='div_col_e_val_errors' name='div_col_e_val_errors'>										
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Id Prestamo:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>											
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_id_prestamo' name='p_v_id_prestamo'></p>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-5'>
										Nombre del producto(Id):
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_producto' name='p_v_producto'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										encargado de la devolución:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_devolucion' name='p_v_devolucion'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Fecha de la devolución:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_fecha_devolucion' name='p_v_fecha_devolucion'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										observaciones:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_observacion' name='p_v_observacion'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Id de la solicitud:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_idSolicitud' name='p_v_idSolicitud'></p>										
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Estado del prestamo:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>
											<p style="color:orange; font-size: 17px; font-weight: bold;" id='p_v_estado' name='p_v_estado'></p>										
										</div>
									</div>
								</div>



							</div>
							<div style="background-color: #000053;" class='modal-footer'>
								<button type='button' class='btn btn-close' data-dismiss='modal'><span class='glyphicon glyphicon-remove'></span>Cancelar</button>
							</div>
							</form>
						</div>
					</div>
				</div>		

				
				
				<!-- Tabla din?mica para mostrar los registros del cat?logo -->	
				<table id='tbSolicitudes' name='tbSolicitudes' class='display cell-border order-column dt-responsive'>
					<thead>
						<tr>							
							<th>id_prestamo					
							<th>id_producto
							<th>encargado_devo
							<th>fecha_devo
							<th>observaciones
							<th>id_solicitud
							<th>estado
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
				var tbSolicitudes;
				$(document).ready( function () {
					$.fn.dataTable.ext.errMode = 'throw';
					tbSolicitudes = $('#tbSolicitudes').DataTable(
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
							ajax: '<?php echo base_url();?>index.php/Devolucion/Obtener_Dataset_Prestamo',
							autoWidth: false,							
							columns: [								
								{ data: "id_prestamo" },
								{ data: "id_producto" },
								{ data: "encargado_devo" },
								{ data: "fecha_devo" },
								{ data: "observaciones" },
								{ data: "id_solicitud" },
								{ data: "estado" }
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
					$('#tbSolicitudes').DataTable().columns().every( function () {
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