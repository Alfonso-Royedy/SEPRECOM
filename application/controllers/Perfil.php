<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class usuario extends CI_Controller {

	
	public function index()	{					
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));			
			$rowPerfil=$this->Seguridad_SIIA_Model->Obtener_DatosPerfil($session_data['username'],$this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc']);						
			$this->db->close();							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($rowPerfil->Dbname,$this->Seguridad_SIIA_Model->Desencriptar($rowPerfil->Password)));
			
			$data['menu']=$this->Seguridad_SIIA_Model->Crear_Menu_Usuario($this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc'],"Usuario","");
			$data['arrPerfiles']=$this->Seguridad_SIIA_Model->Obtener_Array_Perfiles_x_Usuario_Aplicacion($session_data['username'],$this->config->item('mycfg_id_aplicacion'));
			$this->load->view('usuario',$data);
		}else{			
			redirect($this->router->default_controller);
		}														
	}			
	
	
	
	public function cambiar_perfil(){	
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));				
			$rowPerfil=$this->Seguridad_SIIA_Model->Obtener_DatosPerfil($session_data['username'],$this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc']);						
			$this->db->close();							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($rowPerfil->Dbname,$this->Seguridad_SIIA_Model->Desencriptar($rowPerfil->Password)));			
		
			$this->form_validation->set_rules('perfil', 'perfil', 'required|xss_clean',
											array(
												'required' => 'Debe seleccionar un %s.'											
											)
											);
			
			if ($this->form_validation->run() == FALSE){
				$data['menu']=$this->Seguridad_SIIA_Model->Crear_Menu_Usuario($this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc'],"Usuario","");
				$data['arrPerfiles']=$this->Seguridad_SIIA_Model->Obtener_Array_Perfiles_x_Usuario_Aplicacion($session_data['username'],$this->config->item('mycfg_id_aplicacion'));
				$this->load->view('usuario',$data);
			}else{						
				$rowUsuario=$this->Seguridad_SIIA_Model->Obtener_Datos_Usuario($session_data['username']);						
					
				$rowPerfil=$this->Seguridad_SIIA_Model->Obtener_DatosPerfil($session_data['username'],$this->config->item('mycfg_id_aplicacion'),$this->input->post('perfil'));			
								
				$session_array = array(
					 'username' => $rowUsuario->Username,
					 'full_name' => $rowUsuario->Full_Name,
					 'default_pfc' => $rowUsuario->Pfc_User,
					 'default_pfc_name' => $rowPerfil->description,
					 'oauth_logged_in' => $session_data['oauth_logged_in']
					);
				$this->session->set_userdata($this->config->item('mycfg_session_object_name'), $session_array);
				$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
				
				redirect('principal');			
			}			
		}else{
			redirect($this->router->default_controller);
		}
	}
	
	public function cambiar_password(){				
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){		
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));			
			$rowPerfil=$this->Seguridad_SIIA_Model->Obtener_DatosPerfil($session_data['username'],$this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc']);						
			$this->db->close();							
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($rowPerfil->Dbname,$this->Seguridad_SIIA_Model->Desencriptar($rowPerfil->Password)));
			
			$this->form_validation->set_rules('password_actual', 'Password Actual', "required|xss_clean|callback_Password_Valido[".$session_data['username']."]",																							
												array(
													'required' => 'Debe proporcionar un %s.'
												)
											);
											
			$this->form_validation->set_rules('password_nuevo', 'Password Nuevo', "required|xss_clean|callback_Nuevo_Password_Valido[".$this->input->post('password_actual')."]",																							
											array(
												'required' => 'Debe proporcionar un %s.'
											)
										);
										
			$this->form_validation->set_rules('password_nuevo_confirmar', 'Confirmar Password Nuevo', "required|xss_clean|matches[password_nuevo]",																							
											array(
												'required' => 'Debe %s.',
												'matches' => 'No coinciden los passwords'
											)
										);
				
			if ($this->form_validation->run() == FALSE)	{				
				$data['menu']=$this->Seguridad_SIIA_Model->Crear_Menu_Usuario($this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc'],"Usuario","");
				$data['arrPerfiles']=$this->Seguridad_SIIA_Model->Obtener_Array_Perfiles_x_Usuario_Aplicacion($session_data['username'],$this->config->item('mycfg_id_aplicacion'));
				$this->load->view('usuario',$data);				
			}else{	
				$this->db->trans_begin();
				$Password_Cambiado=$this->Seguridad_SIIA_Model->Cambiar_Password($session_data['username'],$this->Seguridad_SIIA_Model->Encriptar($this->input->post('password_nuevo')));			
				if ($Password_Cambiado)	{
					$this->db->trans_commit();
					$data['notificacion_exito']="Contraseña cambiada con exito";		
				}else{
					$this->db->trans_rollback();
					$data['notificacion_error']="Ocurrió error al intentar cambiar la contraseña";		
				}
				$data['menu']=$this->Seguridad_SIIA_Model->Crear_Menu_Usuario($this->config->item('mycfg_id_aplicacion'),$session_data['default_pfc'],"Usuario","");
				$data['arrPerfiles']=$this->Seguridad_SIIA_Model->Obtener_Array_Perfiles_x_Usuario_Aplicacion($session_data['username'],$this->config->item('mycfg_id_aplicacion'));
				$this->load->view('usuario',$data);
			}	
		}else{
			redirect($this->router->default_controller);
		}			
	}
	
	public function Password_Valido($Password,$Username){							
		if ($this->Seguridad_SIIA_Model->Usuario_Existe($Username)){
			$rowUsuario=$this->Seguridad_SIIA_Model->Obtener_Datos_Usuario($Username);						
			if ($this->Seguridad_SIIA_Model->Desencriptar($rowUsuario->Password)==$Password){			
				return true;
			}else{
				$this->form_validation->set_message('Password_Valido', 'El Password especificado no coincide con tu password actual');
				return false;	
			}
		}else{			
			$this->form_validation->set_message('Password_Valido', 'El Usuario no existe');
			return false;
		}								
	}
	
	public function Nuevo_Password_Valido($NewPassword,$OldPassword){					
		if ($NewPassword==$OldPassword){		
			$this->form_validation->set_message('Nuevo_Password_Valido', 'El Nuevo Password especificado debe ser distinto al password actual');
			return false;				
		}else{						
			return true;
		}								
	}
		
}
?>