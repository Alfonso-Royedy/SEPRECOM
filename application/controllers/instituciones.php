<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Instituciones extends CI_Controller {	

	public function index(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));			
			
			$data['menu']=$this->Seguridad_SIIA_Model->Crear_Menu_Usuario($this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc'],"Catalogos","Instituciones");						
			
			$this->load->view('instituciones',$data);			
			
		}else{
			redirect($this->router->default_controller);
		}	
	}


	public function Obtener_Dataset_Instituciones(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));				
			
			//Se armar? un json array con los registros de la consulta, este json alimentar? el datatable		
			$resInstituciones=$this->Gestion_Instituciones_Model->Obtener_Instituciones();										
			
			if ($resInstituciones){
				while ($rowInstitucion=$resInstituciones->unbuffered_row('array')){				
					foreach ($rowInstitucion as $key=>$value){
						//las cadenas se deben encodear a utf8 para que el datatable muestre bien los caracteres como acentos y ?
						if (gettype($rowInstitucion[$key])=="string"){													
							$rowInstitucion[$key]=utf8_encode($value);
						}else{
							$rowInstitucion[$key]=$value;
						}					
					}
					$output[]=$rowInstitucion;
				}								
				print(json_encode(array("data"=>$output)));
			}else{			
				print(json_encode(array("data"=>"")));
			}
		}else{
			redirect($this->router->default_controller);
		}
	}
	
	public function Eliminar_Institucion(){		
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){		
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));				
			
			$Operacion_Borrado_Exitosa=false;
			$this->db->trans_begin();								
			
			$Institucion_Eliminada=$this->Gestion_Instituciones_Model->Eliminar_Institucion($this->input->post('id_institucion'));															
			
			if ($Institucion_Eliminada){
				$this->db->trans_commit();
				MostrarNotificacion("Se elimino la institución exitosamente","OK",true);
				$Operacion_Borrado_Exitosa=true;
			}else{
				$this->db->trans_rollback();
				MostrarNotificacion("Ocurrio un error al intentar eliminar la institución","Error",true);
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
	
	public function Editar_Institucion(){
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			//los valores de tipo cadena deben decodificarse de utf8 para que lo almacena correctamente
			$this->form_validation->set_rules('e_institucion', 'Nombre de la institución', "required|xss_clean|strtoupper|utf8_decode",																							
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
				
				$Institucion_Editada=$this->Gestion_Instituciones_Model->Editar_Institucion($this->input->post('e_id_institucion'),$this->input->post('e_institucion'));
				
				if ($Institucion_Editada){
					$this->db->trans_commit();
					MostrarNotificacion("Se editó la institución exitosamente","OK",true);
					$Operacion_Edicion_Exitosa=true;
				}else{
					$this->db->trans_rollback();
					MostrarNotificacion("Ocurrio un error al intentar editar la institución","Error",true);
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