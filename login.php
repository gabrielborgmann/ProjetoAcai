<?php
require_once("db.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($senha, $row['senha'])) {
        if ($row['cargo'] == 'funcionario') {
            header("Location: menu_funcionario.php");
        } elseif ($row['cargo'] == 'gestor' && $row['aprovado'] == 1) {
            header("Location: gestor.php");
			} elseif ($row['cargo'] == 'menu_financeiro' && $row['aprovado'] == 1) {
            header("Location: menu_gestor.php");
        } else {
            echo "Aguardando aprovação do gestor.";
        }
    } else {
        echo "Credenciais inválidas.";
    }
} else {
    echo "Credenciais inválidas.";
}

$stmt->close();
$conn->close();
?>
