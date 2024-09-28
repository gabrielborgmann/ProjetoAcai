<?php
// Configurações do banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'acai_db2';

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o token e a nova senha foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token']) && isset($_POST['senha'])) {
    $token = $conn->real_escape_string($_POST['token']);
    $nova_senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Atualiza a senha do usuário
    $stmt = $conn->prepare("UPDATE usuarios SET senha = ?, token_reset_senha = NULL, data_token_reset_senha = NULL WHERE token_reset_senha = ?");
    $stmt->bind_param('ss', $nova_senha, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Senha redefinida com sucesso.";
    } else {
        echo "Falha ao redefinir a senha.";
    }
} else {
    echo "Dados não fornecidos.";
}

$conn->close();
?>
