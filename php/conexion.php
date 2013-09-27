<?php

//Configuracion de la conexion a base de datos
error_reporting(0);
$bd_host = "127.0.0.1:3306";
$bd_usuario = "root";
$bd_password = "andres.5421";
$bd_base = "jusers";//juseres
global $connected;
$connected = true;
$link = mysql_connect($bd_host, $bd_usuario, $bd_password);
if (!$link) {
    $connected = false;
    $array = array(
        "error_conexion" => "Error en la conexión, los datos no son los correctos",
        "bd_host" => $bd_host,
        "bd_usuario" => $bd_usuario,
    );
    print json_encode($array);
    return;
}
$baseConnected = mysql_select_db($bd_base, $link);
if (!$baseConnected) {
    $connected = false;
    $array = array(
        "error_conexion" => "Error al momento de seleccionar la base de datos, posiblemente la que ingresaste no está habilitada",
        "bd_base" => $bd_base,
        "host" => $bd_host,
        "usuario" => $bd_usuario
    );
    print json_encode($array);
}
?>