<?php
require('../controllers/authController.php');
require('../database/config.php');
require('../database/validators.php');
require('../middlewares/validateJWT.php');

header("Content-type: application/json");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $body = json_decode(file_get_contents('php://input'));
        existsEmail($body->correo, $conn);
        login($body, $conn);
        break;

    default:
        http_response_code(404);
        echo json_encode(["msg" => "No existe peticion: " . $_SERVER['REQUEST_METHOD'] . " para este End-point."]);
        break;
}
