<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acai_db2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
if($conn)
{
    echo "<p> conectado com sucesso22";
}

?>
<html>

<!-- BANCO DE DADOS

CREATE DATABASE acai;

USE acai;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    cargo ENUM('funcionario', 'gestor') NOT NULL,
    aprovado TINYINT DEFAULT 0
);

SELECT * FROM usuarios;

DROP DATABASE acai;-->

</html>
