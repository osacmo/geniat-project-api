<?php

require('../controllers/postController.php');
require('../database/config.php');
require('../database/validators.php');
require('../middlewares/validateJWT.php');
require('../middlewares/validate-fields.php');

header("Content-type: application/json");
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $body = json_decode(file_get_contents('php://input'));
        validateFields($body);
        $tkn = validateToken($conn);
        validateRole($tkn, [3, 4, 5]);
        createPost($body, $conn, $tkn->data->id);
        break;

    case 'GET':
        $tkn = validateToken($conn);
        validateRole($tkn, [2, 4, 5]);
        getPosts($conn);
        break;

    case 'PUT':
        $body = json_decode(file_get_contents('php://input'));
        validateParam($_GET['id']);
        validateFields($body);
        $tkn = validateToken($conn);
        validateRole($tkn, [4, 5]);
        existsPost($_GET['id'], $conn);
        updatePost($_GET['id'], $body, $conn);
        break;

    case 'DELETE':
        validateParam($_GET['id']);
        $tkn = validateToken($conn);
        validateRole($tkn, [5]);
        existsPost($_GET['id'], $conn);
        deletePost($_GET['id'], $conn);
        break;

    default:
        http_response_code(404);
        echo json_encode(["msg" => "No existe peticion: " . $_SERVER['REQUEST_METHOD'] . " para este End-point."]);
        break;
}
