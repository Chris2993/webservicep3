<?php

require_once '../datos/Conexion.clase.php';

class Sesion extends Conexion{
    private $email;
    private $clave;
    
    function getEmail() {
        return $this->email;
    }

    function getClave() {
        return $this->clave;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    public function validarSesion(){
        try {
            $sql="select 
                            u.dni_usuario, 
                            u.clave, 
                            u.estado 
                    from 
                            usuario u
                            inner join personal p on (u.dni_usuario=p.dni)
                    where
                            p.email = :p_email
                            ";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_email", $this->getEmail());
            $sentencia->execute();
            
            if ($sentencia->rowCount()) {
                $resultado = $sentencia->fetch();
                if ($resultado["clave"] == $this->getClave()){
                    
                    if ($resultado["estado"] == "A") {
                        return $resultado["dni_usuario"];
                    }
                    return "UI";                    
                }else{
                      //la clave es incorrecta
                      return "CI";
                }
            }else{
                return "NE";
            }
            
        } catch (Exception $exc) {
            throw $exc;
        }
            return null;
        }
        
        public function obtenerDatosUsuario($p_dni){
            try {
                $sql = "select 
                            apellido_paterno, 
                            apellido_materno, 
                            nombres 
                        from 
                            personal 
                        where 
                            dni = :p_dni";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_dni", $p_dni);
                $sentencia->execute();
                $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
                return $resultado;
            } catch (Exception $exc) {
                throw $exc;
            }
        }
}
