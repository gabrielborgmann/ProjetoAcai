<?php
require_once("db.php");

$id = $_GET['id'];

$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Usuário rejeitado com sucesso.";
} else {
    echo "Erro ao rejeitar o usuário: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
