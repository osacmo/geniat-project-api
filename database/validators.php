<?php

function existsEmail($correo, $conn)
{
    $query = "SELECT correo from usuario WHERE correo = '$correo'";
    $res = $conn->query($query)->fetch_assoc();

    if (is_null($res)) {
        http_response_code(400);
        echo json_encode(["msg" => "No existe el correo: " . $correo]);
        die();
    }
}


function emailExists($correo, $conn)
{
    $query = "SELECT correo from usuario WHERE correo = '$correo'";
    $res = $conn->query($query)->fetch_assoc() ?? FALSE;

    if ($res) {
        http_response_code(400);
        echo json_encode(["msg" => "Ya existe el correo: " . $correo]);
        die();
    }
}

function existsId($id, $conn)
{
    $query = "SELECT idUsuario FROM usuario WHERE idUsuario = '$id'";
    $res = $conn->query($query)->fetch_assoc() ?? FALSE;

    if (!$res) {
        http_response_code(400);
        echo json_encode(["msg" => "No existe el id: " . $id]);
        die();
    }
}

function existsPost($id, $conn)
{
    $query = "SELECT idPublicacion FROM publicacion WHERE idPublicacion = '$id'";
    $res = $conn->query($query)->fetch_assoc() ?? FALSE;

    if (!$res) {
        http_response_code(400);
        echo json_encode(["msg" => "No existe la publicacion con el id: " . $id]);
        die();
    }   
}

function validateRole($tkn, $roles)
{
    if (!in_array($tkn->data->rol, $roles)) {
        http_response_code(401);
        echo json_encode(["msg" => "No cuentas con privilegios de acceso."]);
        die();
    }
}
