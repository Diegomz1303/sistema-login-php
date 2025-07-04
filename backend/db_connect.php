<?php



$servername = "127.0.0.1";
$username = "root";
$password = ""; 
$dbname = "login_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
   
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>