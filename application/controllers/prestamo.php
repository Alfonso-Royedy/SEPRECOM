<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Prestamo extends CI_Controller {	

	public function index(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));			
			
			$this->load->model('Cliente_model');
			$data['array_cliente']=$this->Cliente_model->Obtener_Array_Nombre_Cliente();

			$this->load->model('Prestamo_model');
			$data['array_prestador']=$this->Prestamo_model->Obtener_Array_Nombre_prestador();

			$this->load->model('Producto_model');
			$data['array_producto']=$this->Producto_model->Obtener_Array_Nombre_Producto();

			$data['menu']=$this->Seguridad_SIIA_Model->Crear_Menu_Usuario($this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc'],"Procesos","Solicitudes");						

            $this->load->view('prestamos',$data);			
			
		}else{
			redirect($this->router->default_controller);
		}	
	}
    
    public function Obtener_Dataset_Prestamo(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));				
			
			//Se armar? un json array con los registros de la consulta, este json alimentar? el datatable
            $this->load->model('Prestamo_model');	
			$resPrestamo=$this->Prestamo_model->Obtener_Prestamo();										
			
			if ($resPrestamo){
				while ($rowPrestamo=$resPrestamo->unbuffered_row('array')){				
					foreach ($rowPrestamo as $key=>$value){
						//las cadenas se deben encodear a utf8 para que el datatable muestre bien los caracteres como acentos y ?
						if (gettype($rowPrestamo[$key])=="string"){													
							$rowPrestamo[$key]=utf8_encode($value);
						}else{
							$rowPrestamo[$key]=$value;
						}					
					}
					$output[]=$rowPrestamo;
				}								
				print(json_encode(array("data"=>$output)));
			}else{			
				print(json_encode(array("data"=>"")));
			}
		}else{
			redirect($this->router->default_controller);
		}
	}

    public function Crear_Solicitud() {
        if($this->session->userdata($this->config->item('mycfg_session_object_name'))){

            $this->form_validation->set_rules('Nombre_Solicitante','Nombre de solicitante',"required|xss_clean|strtoupper|utf8_decode",
                                                array(
                                                        'required' => 'Debe proporcionar un %s.'
                                                    )
                                            );
    
            $this->form_validation->set_rules('Edificio','edificio',"required|xss_clean|strtoupper|utf8_decode",
                                                array(
                                                    'required' => 'Debe proporcionar un %s.'
                                                    )
                                            );
    
            $this->form_validation->set_rules('Tipo_Area','area',"required|xss_clean|strtoupper|utf8_decode",
                                                array(
                                                    'required' => 'Debe proporcionar un %s.'
                                                    )
                                             );
    
            $this->form_validation->set_rules('Id_Area','id del area',"required|xss_clean|strtoupper|utf8_decode",
                                                array(
                                                    'required' => 'Debe proporcionar un %s.'
                                                    )
                                            );
            $this->form_validation->set_rules('Equipo_Solicitado1','equipo o accesorio',"required|xss_clean|strtoupper|utf8_decode",
                                                array(
                                                    'required' => 'Debe proporcionar un %s.'
                                                    )
                                            );
            $this->form_validation->set_rules('Equipo_Solicitado2','equipo o accesorio',"xss_clean|strtoupper|utf8_decode",
                                                array(
                                                    'required' => 'Debe proporcionar un %s.'
                                                    )
                                            );
            $this->form_validation->set_rules('Equipo_Solicitado3','equipo o accesorio',"xss_clean|strtoupper|utf8_decode",
                                                array(
                                                    'required' => 'Debe proporcionar un %s.'
                                                    )
                                            );
            $this->form_validation->set_rules('Encargado','Nombre del encargado',"required|xss_clean|strtoupper|utf8_decode",
                                                array(
                                                    'required' => 'Debe proporcionar un %s.'
                                                    )
                                            );
            $this->form_validation->set_rules('Fecha_solicitud', '"Fecha de solicitud',"required|xss_clean|strtoupper|utf8_decode",	
																									
                                                array(
                                                    'required' => 'Debe proporcionar un %s.'
                                                )
                                            );
    
            // Puedes agregar más reglas de validación según tus necesidades
    
            if ($this->form_validation->run() == FALSE){
                MostrarNotificacion("Hay errores en los datos capturados, corrija e intente de nuevo por favor","Error",true);
				echo "@".Obtener_Contador_Notificaciones();
				echo "@F";
				echo "@<div class='bg-danger' style='padding: 5px;'><b>Errores de validación:</b><br><font class='font_notif_error'>".validation_errors()."</font></div><br>";
            } else {
                $session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
				$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));

				$Operacion_Creacion_Exitosa=false;
				$this->db->trans_begin();
                // Obtener los datos del formulario para la solicitud
                $solicitud_data = array(
                    'profesor' => $this->input->post('Nombre_Solicitante'),
                    'Edificio' => $this->input->post('Edificio'),
                    'Tipo_Area' => $this->input->post('Tipo_Area'),
                    'Num_Area' => $this->input->post('Id_Area'),
                    'encargado_prest' => $this->input->post('Encargado'),
                    'fecha_prest' => $this->input->post('Fecha_solicitud'),
                    // Otros campos de solicitud si es necesario
                );
    
                // Insertar la solicitud en la base de datos
                $this->load->model('Prestamo_model');
                $id_solicitud = $this->Prestamo_model->Crear_Data_Solicitud($solicitud_data);

                    // Obtener los datos del formulario para el préstamo
                    $equipo_solicitado1 = $this->input->post('Equipo_Solicitado1');
                    $equipo_solicitado2 = $this->input->post('Equipo_Solicitado2');
                    $equipo_solicitado3 = $this->input->post('Equipo_Solicitado3');

                    $this->load->model('Devolucion_model');

                        // Crear el préstamo con los productos
                    $id_prestamo = $this->Devolucion_model->crearPrestamoConProductos($equipo_solicitado1, $equipo_solicitado2, $equipo_solicitado3);


                    if ($id_solicitud) {
                    $this->db->trans_commit();
                    MostrarNotificacion("Se creó la Solicitud y el prestamo exitosamente.", "OK", true);
                    $Operacion_Creacion_Exitosa = true;
                    } else {
                        $this->db->trans_rollback();
                        MostrarNotificacion("Ocurrió un error al intentar crear la solicitud y el prestamo.", "Error", true);
                    }

                        echo "@" . Obtener_Contador_Notificaciones();
                        if ($Operacion_Creacion_Exitosa) {
                            echo "@T";
                        } else {
                            echo "@F";
                        }
                }
            }else{
                    redirect($this->router->default_controller);
                }
}

	
	public function Eliminar_Solicitud(){		
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){		
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));				
			
			$Operacion_Borrado_Exitosa=false;
			$this->db->trans_begin();
			
			$this->load->model('Prestamo_model');
			$Solicitud_Eliminar=$this->Prestamo_model->Eliminar_Solicitud($this->input->post('id_solicitud'));															
			
			if ($Solicitud_Eliminar){
				$this->db->trans_commit();
				MostrarNotificacion("Se elimino la solicitud exitosamente.","OK",true);
				$Operacion_Borrado_Exitosa=true;
			}else{
				$this->db->trans_rollback();
				MostrarNotificacion("Ocurrio un error al intentar eliminar la solicitud, primero elimina el prestamo asociado a la solicitud (Id_Solicitud).","Error",true);
			}
			
			echo "@".Obtener_Contador_Notificaciones();
			if ($Operacion_Borrado_Exitosa){
				echo "@T";
			}else{
				echo "@F";
			}
		}else{
			redirect($this->router->default_controller);
		}
			
	}
	
	public function Crear_Institucion(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			//los valores de tipo cadena deben decodificarse de utf8 para que lo almacena correctamente
			$this->form_validation->set_rules('institucion', 'Nombre de la institución', "required|xss_clean|strtoupper|utf8_decode",																							
												array(
													'required' => 'Debe proporcionar un %s.'
												)
											);												
				
			if ($this->form_validation->run() == FALSE){
				MostrarNotificacion("Hay errores en los datos capturados, corrija e intente de nuevo por favor","Error",true);
				echo "@".Obtener_Contador_Notificaciones();
				echo "@F";
				echo "@<div class='bg-danger' style='padding: 5px;'><b>Errores de validación:</b><br><font class='font_notif_error'>".validation_errors()."</font></div><br>";
			}else{
				$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
				$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));					
				
				$Operacion_Creacion_Exitosa=false;
				$this->db->trans_begin();								
				
				$Institucion_Creada=$this->Gestion_Instituciones_Model->Crear_Institucion($this->input->post('institucion'));
				
				if ($Institucion_Creada){
					$this->db->trans_commit();
					MostrarNotificacion("Se creó la institución exitosamente","OK",true);
					$Operacion_Creacion_Exitosa=true;
				}else{
					$this->db->trans_rollback();
					MostrarNotificacion("Ocurrio un error al intentar crear la institución","Error",true);
				}
				
				echo "@".Obtener_Contador_Notificaciones();
				if ($Operacion_Creacion_Exitosa){
					echo "@T";
				}else{
					echo "@F";
				}
			}
		}else{
			redirect($this->router->default_controller);
		}
	}
	
	public function Editar_Solicitud(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
										
		//los valores de tipo cadena deben decodificarse de utf8 para que lo almacena correctamente

		$this->form_validation->set_rules('Nombre_Solicitante1', 'Nombre',"required|xss_clean|strtoupper|utf8_decode",	
																		
											array(
												'required' => 'Debe proporcionar un %s.'
											)
										);
		$this->form_validation->set_rules('Edificio1', 'edificio',"required|xss_clean|strtoupper|utf8_decode",	
																		
											array(
												'required' => 'Debe proporcionar un %s.'
											)
										);
		$this->form_validation->set_rules('Tipo_Area1', 'tipo area',"required|xss_clean|strtoupper|utf8_decode",	
																		
											array(
												'required' => 'Debe proporcionar un %s.'
											)
											);
		$this->form_validation->set_rules('Id_Area1', 'id area',"required|xss_clean|strtoupper|utf8_decode",	
																		
											array(
											'required' => 'Debe proporcionar un %s.'
											)
											);

		$this->form_validation->set_rules('Encargado1', 'encargado',"required|xss_clean|strtoupper|utf8_decode",	
																								
											array(
												'required' => 'Debe proporcionar un %s.'
											)
											);

		$this->form_validation->set_rules('Fecha_solicitud1', 'fecha solicitud',"required|xss_clean|strtoupper|utf8_decode",	
																		
											array(
											'required' => 'Debe proporcionar un %s.'
											)
											);										
				
			if ($this->form_validation->run() == FALSE){
				MostrarNotificacion("Hay errores en los datos capturados, corrija e intente de nuevo por favor","Error",true);
				echo "@".Obtener_Contador_Notificaciones();
				echo "@F";
				echo "@<div class='bg-danger' style='padding: 5px;'><b>Errores de validación:</b><br><font class='font_notif_error'>".validation_errors()."</font></div><br>";
			}else{
				$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
				$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));					
				
				$Operacion_Edicion_Exitosa=false;
				$this->db->trans_begin();								
				
				$this->load->model('Prestamo_model');
				$Solicitud_Editada=$this->Prestamo_model->Editar_Solicitud($this->input->post('e_id_Solicitud'),$this->input->post('Nombre_Solicitante1'),$this->input->post('Edificio1'),$this->input->post('Tipo_Area1'),$this->input->post('Id_Area1'),$this->input->post('Encargado1'),$this->input->post('Fecha_solicitud1'));
				
				if ($Solicitud_Editada){
					$this->db->trans_commit();
					MostrarNotificacion("Se editó la solicitud exitosamente","OK",true);
					$Operacion_Edicion_Exitosa=true;
				}else{
					$this->db->trans_rollback();
					MostrarNotificacion("Ocurrio un error al intentar editar la solicitud","Error",true);
				}
				
				echo "@".Obtener_Contador_Notificaciones();
				if ($Operacion_Edicion_Exitosa){
					echo "@T";
				}else{
					echo "@F";
				}
			}
		}else{
			redirect($this->router->default_controller);
		}
	}	


}
?>