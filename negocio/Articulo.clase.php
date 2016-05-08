<?php

require_once '../datos/Conexion.clase.php';

class Articulo extends Conexion{
    private $codigoArticulo;
    private $nombre;
    private $precioVenta;
    private $codigoCategoria;
    private $codigoMarca;
    
    function getCodigoArticulo() {
        return $this->codigoArticulo;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getPrecioVenta() {
        return $this->precioVenta;
    }

    function getCodigoCategoria() {
        return $this->codigoCategoria;
    }

    function getCodigoMarca() {
        return $this->codigoMarca;
    }

    function setCodigoArticulo($codigoArticulo) {
        $this->codigoArticulo = $codigoArticulo;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setPrecioVenta($precioVenta) {
        $this->precioVenta = $precioVenta;
    }

    function setCodigoCategoria($codigoCategoria) {
        $this->codigoCategoria = $codigoCategoria;
    }

    function setCodigoMarca($codigoMarca) {
        $this->codigoMarca = $codigoMarca;
    }

        
    public function listar(){
        try {
            $sql = "select * from f_listar_articulo(0,0,0)";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
        }
    
    public function agregar(){
        $this->dblink->beginTransaction();
        
        try {
            $sql="select * from f_generar_correlativo('articulo') as nc";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            if ($sentencia->rowcount()) {
                $resultado=$sentencia->fetch(PDO::FETCH_ASSOC);
                $nuevoCodigoArticulo=$resultado["nc"];
                
                $this->setCodigoArticulo($nuevoCodigoArticulo);
                
                echo $nuevoCodigoArticulo;
                
                $sql = "INSERT INTO articulo
                        ( 
                                codigo_articulo, 
                                nombre, 
                                precio_venta, 
                                codigo_categoria, 
                                codigo_marca
                        )
                        VALUES 
                        (
                                :p_codigo_articulo, 
                                :p_nombre, 
                                :p_precio_venta, 
                                :p_codigo_categoria, 
                                :p_codigo_marca
                        )";
                
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_codigo_articulo", $this->getCodigoArticulo());
                $sentencia->bindParam(":p_nombre", $this->getNombre());
                $sentencia->bindParam(":p_precio_venta", $this->getPrecioVenta());
                $sentencia->bindParam(":p_codigo_categoria", $this->getCodigoCategoria());
                $sentencia->bindParam(":p_codigo_marca", $this->getCodigoMarca());
                $sentencia->execute();
                
                $sql = "update correlativo set numero = numero + 1 where tabla = 'articulo'";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->execute();
                
                $this->dblink->commit();
                
                return true;
            }
        } catch (Exception $exc) {
            throw $exc;
            $this->dblink->rollback();
        }
        }
    
}
