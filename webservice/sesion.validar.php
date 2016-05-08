<?php

require_once '../negocio/Sesion.clase.php';
require_once '../util/funciones/Funciones.clase.php';

if (!isset($_POST["email"]) || !isset($_POST["clave"])){
    Funciones::imprimeJSON(500, "Falta completar los datos requeridos","");
    exit();
}

$email= $_POST["email"];
$clave= $_POST["clave"];

try {
    $objSesion = new Sesion();
    $objSesion->setEmail($email);
    $objSesion->setClave($clave);
    $resultado = $objSesion->validarSesion();
    
    switch ($resultado){
        case "UI":
            Funciones::imprimeJSON(500, "El usuario esta inactivo", "");
            break;
        case "CI":
            Funciones::imprimeJSON(500, "La clave es incorrecta", "");
            break;
        case "NE":
            Funciones::imprimeJSON(500, "El usuario no existe", "");
            break;
        default:
            $dniUsuario=$resultado;
            $datosUsuario = $objSesion->obtenerDatosUsuario($dniUsuario);
            Funciones::imprimeJSON(200, "Inicio de Sesión Satisfactorio", $datosUsuario);
    }
    
    //Funciones::imprimeJSON(200, "", $resultado);
    
} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
