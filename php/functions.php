<?php

error_reporting(0);
include "conexion.php";
if ($connected) {
    if (isset($_GET['function'])) {
        $function = $_GET['function'];
        if ($function == "login") {
            $username = $_GET['username'];
            $password = $_GET['password'];
            login($username, $password);
        }
        if ($function == "createUser") {
            $uername = $_GET['username'];
            $password = $_GET['password'];
            createUser($uername, $password);
        }
        if ($function == "getImagesUser") {
            $username = $_GET['username'];
            getImagesUser($username);
        }
        if ($function == "addDescription") {
            $idPicture = $_GET['id'];
            $description = $_GET['description'];
            addDescription($idPicture, $description);
        }
        if ($function == "deleteImage") {
            $idPicture = $_GET['id'];
            deleteImage($idPicture);
        }
    }
} else {
    $array = array(
        "error" => "Error en la conexión a la base de datos",
    );
    print json_encode($array);
    return;
}

function login($username, $password) {
    $result = mysql_query("SELECT * FROM users WHERE username = '$username' AND password = '$password' ");
    if ($result) {
        $rows = array();
        while ($r = mysql_fetch_assoc($result)) {
            $rows[] = $r;
        }
        $total = count($rows);
        if ($total > 0) {
            print json_encode($rows);
        } else {
            $array = array(
                "error" => "El usuario o la contraseña son incorrectos",
            );
            print json_encode($array);
            return;
        }
        return;
    }
}

function createUser($username, $password) {
    $check = "select id from usuarios where username = '$username';";
    $qry = mysql_query($check);
    $num_rows = mysql_num_rows($qry);
    if ($num_rows != 0) {
        $array = array(
            "error" => "Este usuario ya existe en la base de datos",
        );
        print json_encode($array);
        return;
        exit;
    } else {
        $insert = mysql_query("INSERT INTO users (id,username,password) VALUES ('NULL', '{$username}', '{$password}')");
        if ($insert) {
            $array = array(
                "complete" => "El usuario se ha creado correctamente",
            );
            print json_encode($array);
            return;
        }
    }
}

function getImagesUser($username) {
    $result = mysql_query("SELECT * FROM images WHERE user = '$username'");
    if ($result) {
        $rows = array();
        while ($r = mysql_fetch_assoc($result)) {
            $rows[] = $r;
        }
        $total = count($rows);
        if ($total > 0) {
            print json_encode($rows);
        } else {
            $array = array(
                "error" => "A&uacute;n no tienes fotos",
            );
            print json_encode($array);
            return;
        }
        return;
    }
}

function addDescription($idPicture, $description) {
    $result = mysql_query("UPDATE images SET description = '$description' WHERE idimages = '$idPicture'");
    if ($result) {
        $array = array(
            "success" => "La descripci&oacute;n ha sido actualizada",
        );
        print json_encode($array);
        return;
    }
}

function deleteImage($idPicture) {
    $result = mysql_query("SELECT * FROM images WHERE idimages = '$idPicture'");
    while ($row = mysql_fetch_array($result)) {
        $link = $row['link'];
        $deltePicture = unlink('../' . $link);
        $result = mysql_query("DELETE FROM images WHERE idimages = '$idPicture' ");
        if ($deltePicture && $result) {
            $array = array(
                "success" => "La im&aacute;gen se elimin&oacute; correctamente",
            );
            print json_encode($array);
            return;
        } else {
            $array = array(
                "error" => "La im&aacute;gen no se pudo eliminar",
            );
            print json_encode($array);
            return;
        }
    };
}

?>