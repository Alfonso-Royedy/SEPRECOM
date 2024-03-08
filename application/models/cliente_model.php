<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente_model extends CI_Model {			
	
	public function Obtener_Cliente(){			
		$qry = "SELECT * FROM cliente WHERE matricula IS NOT NULL";
		$resqry = $this->db->query($qry);										
		if ($resqry->num_rows()>0){			
			return $resqry; 
		}else{			
			return 0;
		}		
	}
	

	public function Obtener_Array_Nombre_Cliente(){
        $resqry = $this->Obtener_Cliente();												
        if ($resqry->num_rows() > 0){			
            $array = array();
            while ($row = $resqry->unbuffered_row('array')){
                $array[$row["matricula"]] = $row["nombre"];
            }
            return $array; 
        } else {			
            return 0;
        }		
    }
	
	public function Eliminar_Matricula($matricula){												
		$qry = "delete from cliente where matricula=?";
		$resqry = $this->db->query($qry,array((int)$matricula));			
		return ($this->db->affected_rows()>0);		
	}

	public function Obtener_Sig_Id_Usuario() {
        // Verificar que la conexión a la base de datos esté establecida
        if ($this->db !== null) {
            $qry = "SELECT COALESCE(max(matricula),0)+1 Sig_matricula from cliente";
            
            // Realizar la consulta
            $resqry = $this->db->query($qry);
    
            // Verificar si la consulta se ejecutó correctamente
            if ($resqry !== false && $resqry->num_rows() > 0) {
                $regqry = $resqry->unbuffered_row();
                return $regqry->Sig_matricula;
            } else {
                // Manejar el caso de una consulta sin resultados
                return 1;
            }
        } else {
            // Manejar el caso de una conexión nula
            return -1; // Puedes ajustar el valor de retorno según tus necesidades
        }
    }
	
	public function Crear_Usuario($nombreUsuario,$correo,$cargo,$roladmin,$contra){						
		$Sig_matricula=$this->Obtener_Sig_Id_Usuario();		
		$qry = "insert into cliente (matricula,nombre,correo,cargo,Rol_admin,contrasena) values (?,?,?,?,?,?)";
		$resqry = $this->db->query($qry,array((int)$Sig_matricula,$nombreUsuario,$correo,$cargo,$roladmin,$contra));			
		return ($this->db->affected_rows()>0);
	}
	
	public function Editar_Institucion($Id_Institucion,$Descrip_Institucion){								
		$qry = "update GC_Instituciones set Institucion=? where Id_Institucion=?";
		$resqry = $this->db->query($qry,array($Descrip_Institucion,(int)$Id_Institucion));			
		return ($this->db->affected_rows()==1);
	}
		
}
?>
