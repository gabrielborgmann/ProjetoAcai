
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclua o autoload do PHPMailer
require 'C:\xampp\htdocs\ProjetoAcai\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\ProjetoAcai\PHPMailer\src\SMTP.php';
require 'C:\xampp\htdocs\ProjetoAcai\PHPMailer\src\Exception.php';

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

// Verifica se o email foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);

    // Verifica se o email existe no banco de dados
    $result = $conn->query("SELECT * FROM usuarios WHERE email = '$email'");
    if ($result->num_rows > 0) {
        // Gere um token único
        $token = bin2hex(random_bytes(50));

        // Salve o token e a data no banco de dados
        $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $conn->prepare("UPDATE usuarios SET token_reset_senha = ?, data_token_reset_senha = ? WHERE email = ?");
        $stmt->bind_param('sss', $token, $expira_em, $email);
        $stmt->execute();

        // Link de redefinição de senha
        $link = "http://localhost/ProjetoAcai/reset_password.php?token=$token";

        // Configuração do PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'acaitribusdf@gmail.com'; // Substitua pelo seu e-mail do Gmail
            $mail->Password = 'keixbyubzbvebfwa'; // Substitua pela sua senha do Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configurações do e-mail
            $mail->setFrom('weslleybra0@gmail.com', 'Sr.Usuário');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Redefinir Senha';
            $mail->Body = "Clique no link para redefinir sua senha: <a href='$link'>Redefinir Senha</a>";

            // Envia o e-mail
            $mail->send();
            echo 'Um link para redefinição de senha foi enviado para o seu e-mail.';
        } catch (Exception $e) {
            echo "Falha ao enviar o e-mail. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email não encontrado.";
    }
} else {
    echo "Email não informado.";
}

$conn->close();
?>
<html>

        <li><a href="index.html">Principal</a>
		
		</li></html>