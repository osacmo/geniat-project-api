<?php

require('../database/config.php');

error_reporting(E_ERROR | E_PARSE);

function createUser($body, $conn)
{
    $new_pass = password_hash($body->contraseña, PASSWORD_DEFAULT);

    $query = "INSERT INTO usuario(nombre, apellido, correo, contraseña, rol_idRol) 
    VALUES('$body->nombre', '$body->apellido','$body->correo','$new_pass','$body->rol')";

    if ($conn->query($query)) {
        http_response_code(201);
        echo json_encode(["msg" => "Usuario creado."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo crear el usuario."]);
    }
}

function getUsers($conn)
{
    $result = $conn->query("SELECT idUsuario, nombre, apellido, correo, (SELECT tipo FROM rol WHERE rol_idRol = idRol) AS rol FROM usuario WHERE status = 1");
    if ($result > 0) {
        for ($set = []; $row = $result->fetch_assoc(); $set[] = $row);
        http_response_code(200);
        echo json_encode($set);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo obtener los usuarios."]);
    }
}

function updateUser($id, $body, $conn)
{
    $query = "UPDATE usuario SET nombre = '$body->nombre', apellido = '$body->apellido',correo = '$body->correo', rol_idRol = '$body->rol' WHERE idUsuario = '$id'";

    if ($conn->query($query)) {
        http_response_code(200);
        echo json_encode(["msg" => "Usuario actualizado."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar el usuario."]);
    }
}

function deleteUser($id, $conn)
{
    if ($conn->query("UPDATE usuario SET status = 0 WHERE idUsuario = '$id'")) {
        http_response_code(200);
        echo json_encode(["msg" => "Usuario eliminado."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar el usuario."]);
    }
}
