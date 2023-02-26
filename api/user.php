<?php
require('../controllers/userController.php');
require('../database/config.php');
require('../database/validators.php');
require('../middlewares/validate-fields.php');
require('../middlewares/validateJWT.php');

header("Content-type: application/json");
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $body = json_decode(file_get_contents('php://input'));
        validateFields($body);
        emailExists($body->correo, $conn);
        createUser($body, $conn);
        break;

    case 'GET':
        $tkn = validateToken($conn);
        validateRole($tkn, [2, 3, 4, 5]);
        getUsers($conn);
        break;

    case 'PUT':
        $body = json_decode(file_get_contents('php://input'));
        validateParam($_GET['id']);
        validateFields($body);
        $tkn = validateToken($conn);
        validateRole($tkn, [4, 5]);
        existsId($_GET['id'], $conn);
        updateUser($_GET['id'], $body, $conn);
        break;

    case 'DELETE':
        validateParam($_GET['id']);
        $tkn = validateToken($conn);
        validateRole($tkn, [5]);
        existsId($_GET['id'], $conn);
        deleteUser($_GET['id'], $conn);
        break;

    default:
        http_response_code(404);
        echo json_encode(["msg" => "No existe peticion: " . $_SERVER['REQUEST_METHOD'] . " para este End-point."]);
        break;
}
