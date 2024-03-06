			<!-- Bot�n para mostrar la ventana de notificaciones -->				
			<div class='col-md-2'>
				<button type='button' class='btn btn-warning' data-toggle='modal' data-target='#modal_notificaciones' style='width: 100%;'>Notificaciones <span class='badge' id='span_notif_count' name='span_notif_count'></span></button>
			</div>
			
			<div style="margin-top: 56px;"></div>
			<!-- Barra de progreso -->	
			<div class='col-md-3'>								
				<div id='dvLoading' name='dvLoading' class='progress progress-striped active' style='display: none; width: 425%;'>
					<div class='progress-bar progress-bar-warning'  role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width: 100%;'>
						Cargando, espere un momento por favor...
						<span class='sr-only'>Cargando, espere un momento por favor...</span>
					</div>
				</div>	
			</div>
	
	
			<!-- Ventana modal de notificaciones no visible por defecto -->	
			<div class='modal fade' id='modal_notificaciones' tabindex='-1' role='dialog' aria-labelledby='modallbl_notificaciones' aria-hidden='true'  style='z-index: 10000;'>
				<div class='modal-dialog modal-lg'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
							<h4 class='modal-title' id='modallbl_notificaciones'>Notificaciones</h4>
						</div>
						<div class='modal-body'>
							<div id='div_notifications_content'  name='div_notifications_content'>
							</div>
						</div>
					</div>
				</div>
			</div>