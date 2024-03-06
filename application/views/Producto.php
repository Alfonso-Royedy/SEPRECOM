<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Productos</title>

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
					<li class='active' style='color: white;'>Productos</li>											
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
					<button style="margin-bottom: 15px;" type="button" id="CrearNuevo" class="btn btn-primary">Nuevo</button>
						<script>
							$(document).ready(function(){
								$("#CrearNuevo").click(function(){
									frmNuevoProducto.reset(); 
									//se muestra la ventana modal del formulario
									$('#ModalNuevoProducto').modal();
									//se blanquea el div de errores del formulario
									$("#div_col_val_errors").html("");
								});
							});
						</script>

					<!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
					<button style="margin-bottom: 15px;" type="button" id="EditarElemento" class="btn btn-primary">Editar</button>
							<script>
								$(document).ready(function () {
									$("#EditarElemento").click(function(){
							
										//se obtienen los datos del registro seleccionado de la tabla
										var count = $('#tbproducto').DataTable().rows({ selected: true }).count();
										if (count==1){
											var rows =  $('#tbproducto').DataTable().rows({ selected: true }).indexes();
											var data =  $('#tbproducto').DataTable().rows( rows ).data();												
											//se resetean los valores del formulario
											frmEditarProducto.reset();
											//se inicializan los valores del formulario
											$('#e_id_producto').val(data[0].id_producto);
											$('#v_id_producto').html(data[0].id_producto);
											$('#E_Nombre_Producto').val(data[0].nombre_producto);												
											$('#E_Estado_p').val(data[0].estado);
											$('#E_Num_serie').val(data[0].Num_Serie);				
											//se muestra la ventana modal del formulario
											$('#ModalEditarProducto').modal();
											//se blanquea el div de errores del formulario
											$("#div_col_e_val_errors").html("");
										}else{
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
										//se obtienen los datos del registro seleccionado de la tabla
										var count = $('#tbproducto').DataTable().rows({ selected: true }).count();
										if (count==1){
											var rows =  $('#tbproducto').DataTable().rows({ selected: true }).indexes();
											var data =  $('#tbproducto').DataTable().rows( rows ).data();												
											var respuesta = confirm('¿Está seguro que desea eliminar el producto:\n\n'+data[0].nombre_producto+'?');
											if (respuesta){										
												$.ajax({
													type: "POST",
													url: "<?php echo base_url();?>index.php/producto/Eliminar_Producto",
													data: {"id_producto" : data[0].id_producto},
													success: function(msg){															
														var msg_substr = msg.split("@", 3);
														var msg_html = msg_substr[0];
														var msg_cont_notif = msg_substr[1];
														var msg_result = msg_substr[2];
														$('#div_notifications_content').html(msg_html);	
														$("#span_notif_count").html(msg_cont_notif);         
														$('#modal_notificaciones').modal();
														if (msg_result=="T"){																																	
															$('#tbproducto').DataTable().ajax.reload(null, false);
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

					<!-- Contextual button for informational alert messages -->
					<button style="margin-bottom: 15px;" type="button" id="VerElemento" class="btn btn-primary">Ver Información</button>
							<script>
								$(document).ready(function () {
									$("#VerElemento").click(function(){
										//se obtienen los datos del registro seleccionado de la tabla
										var count = $('#tbproducto').DataTable().rows({ selected: true }).count();
										if (count==1){
											var rows =  $('#tbproducto').DataTable().rows({ selected: true }).indexes();
											var data =  $('#tbproducto').DataTable().rows( rows ).data();												
											//se inicializan los valores del formulario												
											$('#v_Id_producto').html(data[0].id_producto);
											$('#v_nombre_producto').html(data[0].nombre_producto);
											$('#v_estado_producto').html(data[0].estado);
											$('#v_serie').html(data[0].Num_Serie);
											//se muestra la ventana modal del formulario
											$('#modalVerProducto').modal();
										}else{
											alert('Debe elegir un registro');
										}
									});
								});

							</script>

				</div>

				<!-- En tu vista principal.php -->
				
	
		<!-- Ventana modal del formulario para realizar el registro de producto -->	
		<div class='modal fade' id='ModalNuevoProducto'>
					<div class='modal-dialog'>
						<div style='width: 480px;' class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button style="background-color: #FFCD00;" type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Datos del Producto</h4>
							</div>
							<div class='modal-body'>
<?php 
							echo form_open("producto/Crear_Producto","id='frmNuevoProducto' name='frmNuevoProducto' role='form'"); 
								//Agregamos los campos de la llave primaria como campos de tipo hidden
						
?>												
								<div class='row'>												
									<div class='col-md-12' id='div_col_val_errors' name='div_col_val_errors'>										
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Nombre de Producto:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>									
<?php
										EditBox("Nombre_Producto","Nombre_Producto","form-control","",1, 255,255,false,set_value('Nombre_Producto'),"",false,"Nombre del producto","");											
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Estado del producto:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php
										ComboBox("Estado_p","Estado_p","form-control","",1,false,1,array("ACTIVO"=>"ACTIVO"),"","","","Elige el estado");																																																				
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Número de serie:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
									EditBox("Num_serie","Num_serie","form-control","",1, 255,255,false,set_value('Num_serie'),"",false,"Número de serie","");																							
?>
										</div>
									</div>
								</div>



							</div>
							<div style="background-color: #000053;" class='modal-footer'>
								<button type='button' class='btn btn-close' data-dismiss='modal'><span class='glyphicon glyphicon-remove'></span>Cancelar</button>
								<button type='button'  class='btn btn-color' id='btnGuardarProducto' name='btnGuardarProducto' value='Guardar'><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
							</div>
							</form>
						</div>
					</div>
				</div>
<script>															
				$(document).ready(function () {
					$("#btnGuardarProducto").click(function(){
						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>index.php/producto/Crear_Producto",
							data: $('#frmNuevoProducto').serialize(),
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
									$("#ModalNuevoProducto").modal('hide');																				
									$('#tbproducto').DataTable().ajax.reload(null, false);
									$('#tbproducto').DataTable().page('last');
									$("#div_col_val_errors").html("");
								}else{
									$("#div_col_val_errors").html(msg_val_errors);
								}									
							},
							error: function(){
								alert("Ocurrió un error al procesar la petición servidor.");
							}
						});
					});
										
				});
</script>	
				
				<!-- Ventana modal del formulario para editar un producto -->	
				<div class='modal fade' id='ModalEditarProducto'>
					<div class='modal-dialog'>
						<div style='width: 480px;' class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button style="background-color: #FFCD00;" type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Editar Datos del Producto</h4>
							</div>
							<div class='modal-body'>
<?php 
							echo form_open("producto/Editar_Producto","id='frmEditarProducto' name='frmEditarProducto' role='form'"); 
								//Agregamos los campos de la llave primaria como campos de tipo hidden
							echo form_input(array("type"=>"hidden","name"=>"e_id_producto","id"=>"e_id_producto","value"=>""));
?>												
								<div class='row'>												
									<div class='col-md-12' id='div_col_e_val_errors' name='div_col_e_val_errors'>										
									</div>
								</div>
										
								<div class='row'>
									<div class='col-md-5'>
										Id del Producto:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>

										<p id='v_id_producto' name='v_id_producto'></p>

										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Nombre de Producto:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>									
<?php
										EditBox("E_Nombre_Producto","E_Nombre_Producto","form-control","",1, 255,255,false,set_value('E_Nombre_Producto'),"",false,"Nombre del producto","");											
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Estado del producto:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
										ComboBox("E_Estado_p","E_Estado_p","form-control","",1,false,1,array("ACTIVO"=>"ACTIVO","INACTIVO"=>"INACTIVO"),"","","","Elige el estado");																															
?>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Número de serie:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
<?php 
									EditBox("E_Num_serie","E_Num_serie","form-control","",1, 255,255,false,set_value('E_Num_serie'),"",false,"Número de serie","");																							
?>
										</div>
									</div>
								</div>



							</div>
							<div style="background-color: #000053;" class='modal-footer'>
								<button type='button' class='btn btn-close' data-dismiss='modal'><span class='glyphicon glyphicon-remove'></span>Cancelar</button>
								<button type='button'  class='btn btn-color' id='btnGuardarEdicionProducto' name='btnGuardarEdicionProducto' value='Guardar'><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
							</div>
							</form>
						</div>
					</div>
				</div>
<script>															
				$(document).ready(function () {
					$("#btnGuardarEdicionProducto").click(function(){
						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>index.php/producto/Editar_Producto",
							data: $('#frmEditarProducto').serialize(),
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
									$("#ModalEditarProducto").modal('hide');																				
									$('#tbproducto').DataTable().ajax.reload(null, false);
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
				<div class='modal fade' id='modalVerProducto'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div style="background-color: #000053;" class='modal-header'>
								<!--<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>-->
									<h4 style="color: white;" class='modal-title'>Vizualizar información del producto</h4>
							</div>
							<div class='modal-body'>

								<div class='row'>
									<div class='col-md-5'>
										Id del Producto:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>

										<p style="color:orange; font-size: 17px; font-weight: bold;" id='v_Id_producto' name='v_Id_producto'></p>

										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Nombre de Producto:
									</div>
									<div class='col-md-8'>
										<div class='form-group'>									
										<p style="color:orange; font-size: 17px; font-weight: bold;" id='v_nombre_producto' name='v_nombre_producto'></p>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-5'>
										Estado del producto:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
										<p style="color:orange; font-size: 17px; font-weight: bold;" id='v_estado_producto' name='v_estado_producto'></p>
										</div>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-4'>
										Número de serie:
									</div>
									<br>
									<div class='col-md-8'>
										<div class='form-group'>											
										<p style="color:orange; font-size: 17px; font-weight: bold;" id='v_serie' name='v_serie'></p>
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
				<table id='tbproducto' name='tbproducto' class='display cell-border order-column dt-responsive'>
					<thead>
						<tr>							
							<th style='width: 30px;'>Id. Producto		
							<th>Nombre de producto
							<th>Estado
							<th>Numero de serie		
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
				var tbproducto;
				$(document).ready( function () {
					$.fn.dataTable.ext.errMode = 'throw';
					tbproducto = $('#tbproducto').DataTable(
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
							ajax: '<?php echo base_url();?>index.php/producto/Obtener_Dataset_Producto',
							autoWidth: false,							
							columns: [								
								{ data: "id_producto" },
								{ data: "nombre_producto" },
								{ data: "estado" },
								{ data: "Num_Serie" }

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
					$('#tbproducto').DataTable().columns().every( function () {
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