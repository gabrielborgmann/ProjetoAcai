<?php
require_once("db.php");

$id = $_GET['id'];

$sql = "UPDATE usuarios SET aprovado = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Usuário aprovado com sucesso.";
} else {
    echo "Erro ao aprovar o usuário: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<html>

        <li><a href="index.html">Principal</a>
		
		</li></html>