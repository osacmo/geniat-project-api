<?php
require('../vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function validateToken($conn)
{
    $headers = getallheaders();
    $tkn = str_replace(['Bearer', ' '], '', $headers['Authorization']) ?? '';

    if (strlen($tkn) === 0) {
        http_response_code(401);
        echo json_encode(["msg" => "No hay token en la peticion."]);
        die();
    }

    try {
        http_response_code(200);
        $tkn_decoded = JWT::decode($tkn, new Key('privatekey', 'HS512'));
        $user = $tkn_decoded->data->id;
        $result = $conn->query("SELECT status FROM usuario WHERE idUsuario = '$user'")->fetch_assoc();

        if (is_null($result["status"])) {
            http_response_code(401);
            echo json_encode(["msg" => "No existe el usuario en la base de datos."]);
            die();
        } elseif (!$result["status"]) {
            http_response_code(401);
            echo json_encode(["msg" => "Token no valido, usuario con status false."]);
            die();
        }

        return $tkn_decoded;
    } catch (Exception $e) {
        if ($e->getMessage() == "Expired token") {
            http_response_code(401);
            echo json_encode(["msg" => "El token ha expirado."]);
            die();
        }
    }
}
