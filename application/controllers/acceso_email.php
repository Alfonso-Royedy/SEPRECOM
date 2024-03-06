<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acceso_EMail extends CI_Controller {
	
	public function index()	{			    		
		$this->load->view('acceso_email');											
	}	

	public function view_info()	{			    		
		phpinfo();										
	}	
	
	public function login(){
		$this->load->database($this->Seguridad_SIIA_Model->Obtener_DBConfig_Values($this->config->item('mycfg_usuario_conexion'),$this->config->item('mycfg_pwd_usuario_conexion')));
		
		$client = new Google_Client();					
		$client->setRedirectUri($this->config->item('mycfg_redirect_uri'));
		$client->setClientId($this->config->item('mycfg_client_id'));
		$client->setClientSecret($this->config->item('mycfg_client_secret'));		
		$client->addScope(email);	
		$client->addScope(profile);
		if ($this->input->get('code')=="") {		
			$auth_url = $client->createAuthUrl();			
			redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
		}else{								
			$client->authenticate($this->input->get('code'));
			$token = $client->getAccessToken();	
			$token_data = $client->verifyIdToken($token->id_token);				
			if ($token_data) {			
				$data = $token_data->getAttributes();						
				$domain=$data['payload']['hd'];
				$email=$data['payload']['email'];				
				$google_oauth =new Google_Service_Oauth2($client);
				$Nombre=$google_oauth->userinfo->get()->name;						
				if ($domain=='uacam.mx'){									
					/*
					$rowUsuario=$this->Seguridad_SIIA_Model->Obtener_Datos_Usuario(substr($email,0,8));									
					if ($rowUsuario->Username!=""){
						$rowPerfilxDefault=$this->Seguridad_SIIA_Model->Obtener_PerfilxDefault($rowUsuario->Username,$this->config->item('mycfg_id_aplicacion'));																	
						if ($this->Seguridad_SIIA_Model->Usuario_TieneDerecho_Aplicacion($rowUsuario->Username,$this->config->item('mycfg_id_aplicacion'))){
							$session_array = array(
								'username' => $rowUsuario->Username,
								'full_name' => $rowUsuario->Full_Name,
								'default_pfc' => $rowPerfilxDefault->Pfc_User,
								'default_pfc_name' => $rowPerfilxDefault->description,
								'oauth_logged_in' => 'S'
							);
							$this->session->set_userdata($this->config->item('mycfg_session_object_name'), $session_array);			
							redirect('principal');										
						}else{						
							$data['error']="El Usuario no tiene derecho de acceso a la aplicaci�n";
							$this->load->view('acceso_email',$data);											
						}														
					}else{
						$data['error']="No se encontr� al usuario, reporte a la Direcci�n de C�mputo Administrativo";
						$this->load->view('acceso_email',$data);					
					}
					*/
					$session_array = array(
						'username' => $email,
						'full_name' => $Nombre,
						'default_pfc' => 'usr_oper',
						'default_pfc_name' => 'Usuario operativo',
						'oauth_logged_in' => 'S'
					);
					$this->session->set_userdata($this->config->item('mycfg_session_object_name'), $session_array);			
					redirect('principal');										

				}else{					
					$data['error']="Solo se permite el acceso con la cuenta de email institucional (uacam.mx)";
					$this->load->view('acceso_email',$data);					
				}		
			}else{				
				$data['error']="No se pudo obtener un token de acceso";
				$this->load->view('acceso_email',$data);				
			}
		}
	}
		
}
?>