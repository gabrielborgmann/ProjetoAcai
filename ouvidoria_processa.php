<?php
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'acai_db2';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $tipo = $_POST['tipo'];
        $mensagem = $_POST['mensagem'];

        // Insere os dados no banco
        $query = "INSERT INTO ouvidoria (nome, email, tipo, mensagem, data_envio) 
                  VALUES (:nome, :email, :tipo, :mensagem, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':mensagem', $mensagem);

        if ($stmt->execute()) {
            echo "Sua mensagem foi enviada com sucesso!";
        } else {
            echo "Erro ao enviar sua mensagem. Tente novamente mais tarde.";
        }
    }
} catch (PDOException $e) {
    echo 'Erro na conexão: ' . $e->getMessage();
}
?>
