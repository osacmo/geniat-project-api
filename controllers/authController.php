<?php
require('../vendor/autoload.php');

use Firebase\JWT\JWT;

function login($body, $conn)
{
    $result = $conn->query("SELECT * FROM usuario WHERE correo ='$body->correo'");
    for ($set = []; $row = $result->fetch_assoc(); $set[] = $row);
    if (password_verify($body->contraseña, $set[0]["contraseña"])) {

        $time = time();
        $tkn = array(
            "iat" => $time,
            "exp" => $time + 1200,
            "data" => [
                "id" => $set[0]["idUsuario"],
                "email" => $set[0]["correo"],
                "rol" => $set[0]["rol_idRol"]
            ]
        );

        $jwt = JWT::encode($tkn,  "privatekey", 'HS512');

        http_response_code(200);
        echo json_encode([
            "msg" => "Logged",
            "tkn" => $jwt
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["msg" => "La contraseña no es correcta."]);
    }
}
