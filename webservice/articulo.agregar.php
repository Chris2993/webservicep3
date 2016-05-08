<?php

require_once '../negocio/Articulo.clase.php';
require_once '../util/funciones/Funciones.clase.php';

try {
    
    $objArticulo = new Articulo();
    
    $objArticulo->setNombre($_POST["p_nombre"]);
    $objArticulo->setPrecioVenta($_POST["p_precio_venta"]);
    $objArticulo->setCodigoCategoria($_POST["p_categoria"]);
    $objArticulo->setCodigoMarca($_POST["p_codigo_marca"]);
    
    $resultado=$objArticulo->agregar();
    
    if ($resultado==true) {
        Funciones::imprimeJSON(200, "Se ha registrado Correctamente", "");
    }
    
} catch (Exception $exc) {
    
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}


