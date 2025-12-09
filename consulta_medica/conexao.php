<?php
$host = "localhost";
$user = "root"; 
$password = "";
$database = "clinica"; 

$port = 3307; // tem que trocar essa porta para 3306 ou só excluir

$mysqli = new mysqli($host, $user, $password, $database, $port);

if ($mysqli->connect_errno) {
    die("Falha ao conectar ao banco de dados: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

?>
