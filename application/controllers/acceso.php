<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acceso extends CI_Controller {
	
	public function index()	{	    		
		$this->load->view('acceso');		
	}		
	
	public function login(){
		$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));
	
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean', array('required' => 'Debe proporcionar un %s.'));
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean', array('required' => 'Debe proporcionar un %s.'));
	
		if ($this->form_validation->run() == FALSE)	{
			$this->load->view('acceso');
		} else {
			$rowUsuario = $this->Seguridad_SIIA_Model->Obtener_Datos_Usuario($this->input->post('username'), $this->input->post('password'));
	
			// Verificar si se obtuvieron datos del usuario
			if (!$rowUsuario) {
				MostrarNotificacion("El Username o Password no son válidos.", "Error", true);
				$this->load->view('acceso');
				return;
			}
	
			$session_array = array(
				'username' => $rowUsuario->nombre,
				'full_name' => $rowUsuario->nombre,
				'default_pfc' => $rowUsuario->matricula,
				'default_pfc_name' => $rowUsuario->Rol_admin,
				'oauth_logged_in' => 'N'
			);
			$this->session->set_userdata($this->config->item('mycfg_session_object_name'), $session_array);			
			redirect('principal');
		}
	}
	
	public function logout(){	
		if($this->session->userdata($this->config->item('mycfg_session_object_name'))){	
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));
			$this->session->unset_userdata($this->config->item('mycfg_session_object_name'));
			session_destroy();
		}
		if ($session_data['Username']==""){
			redirect('acceso');
		}
	}
//prueba
	public function obtenerDatosUsuario() {
		if ($this->session->userdata($this->config->item('mycfg_session_object_name'))) {    
			$session_data = $this->session->userdata($this->config->item('mycfg_session_object_name'));                            
			$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'), $this->config->item('mycfg_pwd_usuario_conexion')));                
			
			// Obtener datos del usuario desde tu modelo (reemplaza "TuModelo" con el nombre real de tu modelo)
			$this->load->model('TuModelo');
			$datosUsuario = $this->TuModelo->obtenerDatosUsuario($session_data['idUsuario']);
	
			if ($datosUsuario) {
				// Devolver los datos en formato JSON
				echo json_encode($datosUsuario);
			} else {
				// Manejar el caso en que no se encuentren datos del usuario
				http_response_code(404);
				echo json_encode(array('error' => 'Datos del usuario no encontrados'));
			}
		} else {
			// Manejar el caso en que no haya sesión iniciada
			http_response_code(401);
			echo json_encode(array('error' => 'No se ha iniciado sesión'));
		}
	}
	
	
	public function Usuario_Existe($Username){				
		if ($this->Seguridad_SIIA_Model->Usuario_Existe($Username)){				
			return true; 
		}else{						
			$this->form_validation->set_message('Usuario_Existe', 'El Usuario no existe');
			return false;
		}				
	}
	
	public function Password_Valido($Password,$Username){
		if ($this->Seguridad_SIIA_Model->Usuario_Existe($Username)){		
			$rowUsuario=$this->Seguridad_SIIA_Model->Obtener_Datos_Usuario($Username);						
			if ($this->Seguridad_SIIA_Model->Desencriptar($rowUsuario->Password)==$Password){			
				if ($this->Seguridad_SIIA_Model->Usuario_TieneDerecho_Aplicacion($Username,$this->config->item('mycfg_id_aplicacion'))){
					return true;
				}else{
					$this->form_validation->set_message('Password_Valido', 'El Usuario no tiene derecho de acceso a la aplicaci?n');
					return false;		
				}
			}else{
				$this->form_validation->set_message('Password_Valido', 'El Password no coincide');
				return false;	
			}
		}else{			
			$this->form_validation->set_message('Password_Valido', 'El Usuario no existe');
			return false;
		}								
	}
	
}
?>