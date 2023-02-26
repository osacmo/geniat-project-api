<?php
$servername = "containers-us-west-138.railway.app";
$username = "root";
$password = "JolSB17bmoQcsBa7IhMF";
$database = "railway";
$port = "7041";

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
