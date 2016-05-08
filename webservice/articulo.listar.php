<?php

require_once '../negocio/Articulo.clase.php';
require_once '../util/funciones/Funciones.clase.php';

try {
    $objArticulo = new Articulo();
    $resultado = $objArticulo->listar();
    
    $direccionWS= Funciones::$DIRECCION_WEB_SERVICE;
    
//    echo $direccionWS;
    
    $datosArticulo = array();
    
    for ($i = 0; $i < count($resultado); $i++) {
        //$URLImagen = $direccionWS . "imagenes/" . $resultado[$i]["codigo"];
        $URLImagen ="../imagenes/" . $resultado[$i]["codigo"];
        
        if (file_exists($URLImagen.".jpg")) {
            $URLImagen=$URLImagen.".jpg";
        }else{
            if (file_exists($URLImagen.".png")) {
                $URLImagen=$URLImagen.".png";
            }else{
            $URLImagen="";
            }
        }
        
        $ruta= $URLImagen;
        
        if($URLImagen == ""){
            $base64="none";
        }else{
            $type = pathinfo($ruta, PATHINFO_EXTENSION);
            $data = file_get_contents($ruta);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        
        $datos = array
                (
                    "codigo" => $resultado[$i]["codigo"],
                    "nombre" => $resultado[$i]["nombre"],
                    "precio" => $resultado[$i]["precio"],
                    "linea" => $resultado[$i]["linea"],
                    "categoria" => $resultado[$i]["categoria"],
                    "marca" => $resultado[$i]["marca"],
                    "imagen" => $base64
                );
        $datosArticulo[$i] = $datos;
    }
    
    Funciones::imprimeJSON(200, "", $datosArticulo);
    
} catch (Exception $exc) {
    Funciones::imprimeJSON(200, $exc->getMessage(), "");
}


