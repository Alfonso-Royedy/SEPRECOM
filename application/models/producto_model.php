<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Producto_model extends CI_Model {			
	
	public function Obtener_Producto(){			
		$qry = "SELECT * FROM producto WHERE id_producto IS NOT NULL";
		$resqry = $this->db->query($qry);										
		if ($resqry->num_rows()>0){			
			return $resqry; 
		}else{			
			return 0;
		}		
	}
	
    public function Obtener_Array_Nombre_Producto(){
        $resqry = $this->Obtener_Producto();												
        if ($resqry->num_rows() > 0){			
            $array = array();
            while ($row = $resqry->unbuffered_row('array')){
                $array[$row["id_producto"]] = $row["nombre_producto"];
            }
            return $array; 
        } else {			
            return 0;
        }		
    }

	
	public function Eliminar_Producto($id_producto){												
		$qry = "delete from producto where id_producto=?";
		$resqry = $this->db->query($qry,array((int)$id_producto));			
		return ($this->db->affected_rows()>0);		
	}
	

	public function Obtener_Sig_id_producto() {
        // Verificar que la conexión a la base de datos esté establecida
        if ($this->db !== null) {
            $qry = "SELECT COALESCE(MAX(id_producto), 0) + 1 AS Sig_id_producto FROM producto";
            
            // Realizar la consulta
            $resqry = $this->db->query($qry);
    
            // Verificar si la consulta se ejecutó correctamente
            if ($resqry !== false && $resqry->num_rows() > 0) {
                $regqry = $resqry->unbuffered_row();
                return $regqry->Sig_id_producto;
            } else {
                // Manejar el caso de una consulta sin resultados
                return 1;
            }
        } else {
            // Manejar el caso de una conexión nula
            return -1; // Puedes ajustar el valor de retorno según tus necesidades
        }
    }
	
	public function Crear_Producto($Nombre_Producto,$estado,$Num_Serie){						
		$Sig_Id_producto=$this->Obtener_Sig_id_producto();		
		$qry = "insert into producto (id_producto,nombre_producto,estado,Num_Serie) values (?,?,?,?)";
		$resqry = $this->db->query($qry,array((int)$Sig_Id_producto,$Nombre_Producto,$estado,(int)$Num_Serie));			
		return ($this->db->affected_rows()>0);
	}
	
	public function Editar_Producto($Id_Producto,$Descrip_Producto,$estado,$Num_serie){								
		$qry = "update producto set id_producto=?, nombre_producto=?, estado=?, Num_Serie=? where id_producto=?";
		$resqry = $this->db->query($qry,array((int)$Id_Producto,$Descrip_Producto,$estado,(int)$Num_serie,(int)$Id_Producto));			
		return ($this->db->affected_rows()==1);
	}
		
}
?>
