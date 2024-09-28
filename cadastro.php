<?php
require_once("db.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$cargo = $_POST['cargo'];

$sql = "INSERT INTO usuarios (nome, email, senha, cargo) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nome, $email, $senha, $cargo);

if ($stmt->execute()) {
    echo "Usuário cadastrado com sucesso.";
} else {
    echo "Erro ao cadastrar o usuário: " . $stmt->error;
	  
}

$stmt->close();
$conn->close();
?>

<html>

        <li><a href="index.html">Principal</a>
		
		</li></html>
