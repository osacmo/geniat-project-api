<?php

error_reporting(E_ERROR | E_PARSE);

function createPost($body, $conn, $id)
{
    $query = "INSERT INTO publicacion(titulo, descripcion)
    VALUES('$body->titulo', '$body->descripcion')";

    if ($conn->query($query)) {
        $queryDos = "INSERT INTO publicacion_has_usuario(publicacion_idPublicacion, usuario_idUsuario) VALUES($conn->insert_id, $id )";

        if ($conn->query($queryDos)) {
            http_response_code(201);
            echo json_encode(["msg" => "Publicacion creada."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "No se pudo crear la publicacion."]);
            die();
        }
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo crear la publicacion."]);
        die();
    }
    $conn->close();
}

function getPosts($conn)
{
    $query = "SELECT publicacion.idPublicacion as id, publicacion.titulo, publicacion.descripcion, publicacion.created_at, 
    concat(usuario.nombre, ' ' ,usuario.apellido) as nombre, 
    (SELECT tipo FROM rol WHERE idRol = usuario.rol_idRol) as rol FROM publicacion_has_usuario
    JOIN usuario ON publicacion_has_usuario.usuario_idUsuario = usuario.idUsuario
    JOIN publicacion ON publicacion_has_usuario.publicacion_idPublicacion = publicacion.idPublicacion
    WHERE publicacion.status = 1";

    $result = $conn->query($query);

    if ($result > 0) {
        for ($set = []; $row = $result->fetch_assoc(); $set[] = $row);
        http_response_code(200);
        echo json_encode($set);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo obtener las publicaciones."]);
    }

    $conn->close();
}

function updatePost($id, $body, $conn)
{
    $query = "UPDATE publicacion SET titulo='$body->titulo', descripcion='$body->descripcion' WHERE idPublicacion = '$id'";
    if ($conn->query($query)) {
        http_response_code(200);
        echo json_encode(["msg" => "Publicacion actualizada."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar la publicacion."]);
    }
}

function deletePost($id, $conn)
{
    if ($conn->query("UPDATE publicacion SET status = 0 WHERE idPublicacion = '$id'")) {
        http_response_code(200);
        echo json_encode(["msg" => "Publicacion eliminada."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar la publicacion."]);
    }
}
