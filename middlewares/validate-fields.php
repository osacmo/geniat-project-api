<?php

function validateFields($body)
{
    foreach ($body as $key => $value) {
        if (strlen($value) === 0) {
            http_response_code(400);
            echo json_encode(["msg" => "El campo " . $key . " es requerido."]);
            die();
        }
    }
}

function validateParam($id)
{
    if(strlen($id) === 0  || is_null($id)){
        http_response_code(400);
        echo json_encode(["msg" => "El parametro id"." es requerido."]);
        die();
    }
}
