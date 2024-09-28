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

// Verifica se o token foi passado na URL
if (isset($_GET['token'])) {
    $token = $conn->real_escape_string($_GET['token']);

    // Verifica se o token é válido
    $result = $conn->query("SELECT * FROM usuarios WHERE token_reset_senha = '$token' AND data_token_reset_senha > NOW()");
    if ($result->num_rows > 0) {
        // Exibir formulário para redefinir a senha
        echo '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Redefinir Senha</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f5f5f5;
                    margin: 0;
                    padding: 0;
                }

                h2 {
                    text-align: center;
                    color: #3A004F;
                    margin-top: 50px;
                }

                form {
                    width: 90%;
                    max-width: 300px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                label {
                    display: block;
                    margin-bottom: 5px;
                    color: #333;
                }

                input[type="password"] {
                    width: 100%;
                    margin-bottom: 15px;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    box-sizing: border-box;
                }

                button {
                    width: 100%;
                    background-color: #3A004F;
                    color: #fff;
                    border: none;
                    padding: 10px;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: background-color 0.3s;
                }

                button:hover {
                    background-color: #6B9C00;
                }
            </style>
        </head>
        <body>
            <h2>Redefinir Senha</h2>
            <form action="processaNovaSenha.php" method="post">
                <input type="hidden" name="token" value="' . $token . '">
                <label for="senha">Nova Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua nova senha" required>
                <button type="submit">Redefinir Senha</button>
            </form>
        </body>
        </html>
        ';
    } else {
        echo "Token inválido ou expirado.";
    }
} else {
    echo "Token não fornecido.";
}

$conn->close();
?>
