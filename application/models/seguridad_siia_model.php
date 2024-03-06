<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Seguridad_SIIA_Model extends CI_Model {	

	public function Usuario_Existe($Username,$Password){				
		$qry = "select * from si_users where Username=? and Password=?";					
		$resqry = $this->db->query($qry, array($Username,$Password));										
		if ($resqry->num_rows()>0){
			return true; 
		}else{			
			return false;
		}				    				
	}

	public function Obtener_Datos_Usuario($Username,$Password){			
		$qry = "select * from cliente where nombre=? and contrasena=?";		
		$resqry = $this->db->query($qry, array( $Username,$Password));								
		
		if ($resqry->num_rows()>0){
			$row=$resqry->unbuffered_row();
			return $row; 
		}else{			
			return 0;
		}		
	}

	public function Obtener_Usuario(){			
		$qry = "select * from Si_Users  where  pfc_User = ?";
		$resqry = $this->db->query($qry);										
		if ($resqry->num_rows()>0){			
			return $resqry; 
		}else{			
			return 0;
		}		
	}

	public function Obtener_Rol_Usuario($username) {
        $qry = "select description FROM Si_Users WHERE Full_Name = ?";
        $resqry = $this->db->query($qry, array($username));

		if ($resqry->num_rows()>0){
			$row=$resqry->unbuffered_row();
			return $row; 
		}else{			
			return NULL;
		}
    }
	
	public function Usuario_TieneDerecho_Aplicacion($Username, $Aplicacion){
		
		$qry = "select Username from Si_Users_Apps where application=? and Username = ?";		
		$resqry = $this->db->query($qry, array($Aplicacion,$Username));								
		
		return ($resqry->num_rows()>0);
	}

	public function Aplicacion_Activa($Aplicacion){		
		$qry = "select locked_access from security_apps where application=?";		
		$resqry = $this->db->query($qry, array( $Aplicacion));								
		
		if ($resqry->num_rows()>0){
			$row=$resqry->unbuffered_row();
			return ($row->locked_access == 'N'); 
		}else{
			return 0;
		}			
	}
	
	public function Obtener_Datos_Aplicacion($Aplicacion){		
		$qry = "select * from security_apps where application=?";		
		$resqry = $this->db->query($qry, array( $Aplicacion));								
		
		if ($resqry->num_rows()>0){
			$row=$resqry->unbuffered_row();
			return $row; 
		}else{			
			return 0;
		}		
	}
	
	public function Obtener_Array_Perfiles_x_Usuario_Aplicacion($Username,$Aplicacion){		
		$qry = "select UA.Pfc_User, UA.Dbname, D.Password, SU.description from Si_Users_Apps UA join Si_Db_Users D on UA.Dbname=D.Dbname join security_users SU on SU.name=UA.Pfc_User where UA.Username=? and UA.application=?";
		$resqry = $this->db->query($qry, array($Username,$Aplicacion));												
		if ($resqry->num_rows()>0){			
			$i=0;
			while ($row=$resqry->unbuffered_row('array')){
				$array[$i]=$row;
				$i++;
			}
			return $array; 
		}else{			
			return 0;
		}		
	}
	
	public function Obtener_PerfilxDefault($Username, $Aplicacion){		
		$qryCont = "select count(*) Contador from Si_Users_Apps where application=? and Username=?";		
		$resqryCont = $this->db->query($qryCont, array($Aplicacion,$Username));									
		if ($resqryCont->num_rows()>0){
			$rowCont=$resqryCont->unbuffered_row();
			if ($rowCont->Contador > 1){
				$qry = "select UA.Pfc_User, UA.Dbname, D.Password, SU.description from Si_Users_Apps UA join Si_Db_Users D on UA.Dbname=D.Dbname join security_users SU on SU.name=UA.Pfc_User  where UA.application=? and UA.Username=? and UA.Default_Pfc=0";		
				$resqry = $this->db->query($qry, array($Aplicacion,$Username));												
				
			}else {
				$qry = "select UA.Pfc_User, UA.Dbname, D.Password, SU.description from Si_Users_Apps UA join Si_Db_Users D on UA.Dbname=D.Dbname join security_users SU on SU.name=UA.Pfc_User  where UA.application=? and UA.Username=?";		
				$resqry = $this->db->query($qry, array($Aplicacion,$Username));																
			}
			$row=$resqry->unbuffered_row();				
			return $row;									
		}else{			
			return false;
		}					
	}
	
	public function Obtener_DatosPerfil($Username, $Aplicacion, $Perfil){	
		$qry = "select UA.Pfc_User, UA.Dbname, D.Password, SU.description from Si_Users_Apps UA join Si_Db_Users D on UA.Dbname=D.Dbname join security_users SU on SU.name=UA.Pfc_User where UA.application=? and UA.Username=? and UA.Pfc_User=?";				
		$resqry = $this->db->query($qry, array($Aplicacion, $Username, $Perfil));								
		
		if ($resqry->num_rows()>0){
			$row=$resqry->unbuffered_row();
			return $row; 
		}else{			
			return 0;
		}		
	}
	
	public function Usuario_Tiene_Asignado_Perfil($Username, $Aplicacion, $Perfil){		
		$qry = "select * from Si_Users_Apps where application=? and Username=? and Pfc_User=?";		
		$resqry = $this->db->query($qry, array($Aplicacion,$Username, $Perfil));								
		
		return ($resqry->num_rows()>0);		
	}
	
	public function Cambiar_Password($Username, $Password){		
		$qry = "update Si_Users set Password='".$Password."' where Username=?";			
		$resqry = $this->db->query($qry, array($Username));										
		return ($this->db->affected_rows()==1);		
	}
	
	public function Desencriptar($theStr){
		$key="???????";
		$tStr="";
  		$sourcePtr=0;
  		$tempVal=0;
  		$tempKey=0;
  		$is_encrypted= $theStr;
  		$keyPtr= 1;
  		$keyLen= strlen($key);
  		$sourceLen= strlen($is_encrypted);  		
 		$is_raw= "";
  		for ($sourcePtr=1; $sourcePtr <= $sourceLen ;$sourcePtr++)
  		{  			  			  			
			$tempVal= ord(substr($is_encrypted,$sourcePtr-1,1)); 			
  			$tempKey= ord(substr($key,$keyPtr-1,1));     		  			
    		$tempVal= $tempVal - $tempKey;
    		while ($tempVal < 0){	
      			if ($tempVal < 0){
        			$tempVal= $tempVal + 255;
      			}
    		}
    	    $tStr= chr($tempVal);    	    
    		$is_raw= $is_raw . $tStr;
    		$keyPtr++;
    		if ($keyPtr > strlen($key)){
      			$keyPtr= 1;
    		}
  		}    	
  		return $is_raw;
	}

	public function Encriptar($theStr){				
		$key="???????";
		$tStr="";
  		$sourcePtr=0;
  		$tempVal=0;
  		$tempKey=0;  		
  		$is_raw= $theStr;
  		$keyPtr= 1;
  		$keyLen= strlen($key);
  		$sourceLen= strlen($is_raw);  		
		$is_encrypted= "";  		
  		for ($sourcePtr=1; $sourcePtr <= $sourceLen ;$sourcePtr++)
  		{  			  			  			
			$tempVal= ord(substr($is_raw,$sourcePtr-1,1)); 			
  			$tempKey= ord(substr($key,$keyPtr-1,1));     		  			
    		$tempVal= $tempVal + $tempKey;
    		while ($tempVal > 255){	
      			if ($tempVal > 255){
        			$tempVal= $tempVal - 255;
      			}
    		}
    	    $tStr= chr($tempVal);    	    
    		$is_encrypted= $is_encrypted . $tStr;
    		$keyPtr++;
    		if ($keyPtr > strlen($key)){
      			$keyPtr= 1;
    		}
  		} 	
  		return $is_encrypted;
	}	
	
	public function Obtener_DBConfig_Values($Username,$Password){
		$config['hostname'] = $this->config->item('mycfg_database_server');
		$config['database'] = $this->config->item('mycfg_database');
		$config['dbdriver'] = 'mysql';
		$config['username'] = $Username;
		$config['password'] = $Password;
		$config['dsn'] = '';
		$config['dbprefix'] = '';
		$config['pconnect'] = FALSE;
		$config['db_debug'] = FALSE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'iso-8859-1';
		$config['dbcollat'] = 'iso-8859-1';
		return $config;
	}
	
	public function Usuario_Tiene_Derecho_Opcion($Id_Aplicacion,$Perfil,$Control){				
		$qry="select * from security_info where application=? and user_name=? and window='Menu' and control=? and status='I'";		
		$resqry = $this->db->query($qry, array($Id_Aplicacion,$Perfil,$Control));
		return ($resqry->num_rows()==0);
	}
	
	public function Usuario_Tiene_Derecho_Operacion($Id_Aplicacion,$Perfil,$Window,$Control){				
		$qry="select * from security_info where application=? and user_name=? and window=? and control=? and status='I'";
		$resqry = $this->db->query($qry, array($Id_Aplicacion,$Perfil,$Window,$Control));
		return ($resqry->num_rows()==0);
	}
	
	public function Crear_Menu_Usuario($Id_Aplicacion,$Perfil,$Active,$SubActive){				
		/*
		$qry="select * from security_info where application=? and user_name=? and window='Menu' and status='I'";		
		$resqry = $this->db->query($qry, array($Id_Aplicacion,$Perfil));
		if ($resqry->num_rows()>0){						
			while ($row=$resqry->unbuffered_row()){								
				$arrayOpcionesMenuInvisibles[]=$row->control;
			}			
		}
		*/
		return $this->Menu_General($Active,$SubActive,$arrayOpcionesMenuInvisibles);
	}	
	
	public function Obtener_Operaciones_No_Permitidas($Id_Aplicacion,$Perfil,$Window){				
		$qry="select s.control, t.object_type from security_info s join security_template t on s.application=t.application and s.window=t.window and s.control=t.control where s.application=? and s.user_name=? and s.window=? and s.status='I'";		
		$resqry = $this->db->query($qry, array($Id_Aplicacion,$Perfil,$Window));
		if ($resqry->num_rows()>0){						
			while ($row=$resqry->unbuffered_row()){								
				$arrayOperacionesNoPermitidas[$row->control]=$row->object_type;
			}			
		}
		return $arrayOperacionesNoPermitidas;
	}	
	
	public function Menu_Activo($Nombre,$Active){
		if ($Nombre==$Active){
			return "active";
		}else{
			return "";
		}
	}
	
	public function Crear_Opcion($Nombre,$Active,$SubActive,$Accion,$Descripcion,$arrOpcionesMenuInvisibles){
		if (!in_array($Nombre,$arrOpcionesMenuInvisibles)){
			return "<li class='".$this->Menu_Activo($Nombre,$Active)."' id='".$Nombre."'><a  href='".base_url()."index.php/".$Accion."'>".$Descripcion."<br><br> <span class='sr-only'>(current)</span></a></li>";
		}else{
			return "";
		}
	}
	
	public function Crear_Menu_Dropdown($Nombre,$Active,$SubActive,$Descripcion,$arrOpcionesMenuInvisibles,$arrayOpciones){
		if (!in_array($Nombre,$arrOpcionesMenuInvisibles)){
			$Header="<li class='".$this->Menu_Activo($Nombre,$Active)." dropdown' id='".$Nombre."'>
					 <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>".$Descripcion." <span class='caret'></span><br><br></a>
					 <ul class='dropdown-menu'>";						
			$Submenus="";
			foreach ($arrayOpciones as $Opcion){
				if (!in_array($Opcion['nombre'],$arrOpcionesMenuInvisibles)){
					$Submenus.=$this->Crear_Opcion($Opcion['nombre'],$SubActive,$SubActive,$Opcion['accion'],$Opcion['descripcion'],$arrOpcionesMenuInvisibles);
				}
			}					
			$Footer="</ul>
					 </li>";
			return $Header.$Submenus.$Footer;
		}else{
			return "";
		}
	}
	
	public function Menu_General($Active,$SubActive,$arrOpcionesMenuInvisibles){
		/*
		//Se define la plantilla del menu y el info solo para las opciones de menu que el perfil no debe ver, para este ejemplo:		
			insert into security_template values ('portal_gestion_convenios','Menu','Principal','-','option')
			go
			insert into security_template values ('portal_gestion_convenios','Menu','Catalogos','-','option')
			go
			insert into security_template values ('portal_gestion_convenios','Menu','TiposConvenios','-','option')
			go
			insert into security_template values ('portal_gestion_convenios','TiposConvenios','tbTiposConvenios','-','datatable_buttons')
			go
			insert into security_template values ('portal_gestion_convenios','Periodos','tbPeriodos','-','datatable_buttons')
			go
			insert into security_info values ('portal_gestion_convenios','TiposConvenios','tbTiposConvenios','gc_lectura','I')
			go
			insert into security_info values ('portal_gestion_convenios','Periodos','tbPeriodos','gc_lectura','I')
			go
			select * from security_template where application='portal_gestion_convenios'
			go
			select * from security_info where application='portal_gestion_convenios'
			go
		*/
		$Menu_HTML="";		
		$Menu_HTML.=$this->Crear_Opcion("Principal",$Active,$SubActive,"principal","Principal",$arrOpcionesMenuInvisibles);				
		$Menu_HTML.=$this->Crear_Menu_Dropdown("Catalogos",$Active,$SubActive,"Catálogos",$arrOpcionesMenuInvisibles,
												array(
													array("nombre"=>"Productos","accion"=>"producto","descripcion"=>"Productos"),
													array("nombre"=>"Usuarios","accion"=>"Usuarios","descripcion"=>"Usuarios")
												));
		$Menu_HTML.=$this->Crear_Menu_Dropdown("Procesos",$Active,$SubActive,"Procesos",$arrOpcionesMenuInvisibles,
												array(
													array("nombre"=>"Solicitudes","accion"=>"prestamo","descripcion"=>"Solicitudes"),
													array("nombre"=>"Prestamos","accion"=>"Devolucion","descripcion"=>"Prestamos")
												));	
		return $Menu_HTML;
	}
}
?>