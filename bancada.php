<?php
session_start();

// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acai_db2";

// Função para conectar ao banco de dados
function conectarBanco() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    return $conn;
}

// Conectar ao banco de dados
$conn = conectarBanco();

// Consulta para obter os dados da bancada
$sql = "SELECT * FROM compra ORDER BY data_compra DESC";
$result = $conn->query($sql);

// Verifica se a consulta teve sucesso
if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bancada de Compras</title>
    <link rel="stylesheet" href="style/menu.css">
    <style>
        /* Estilos gerais */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilos para o cabeçalho */
        header {
            background-color: #3A004F;
            color: white;
            padding: 1em 0;
            text-align: center;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        h1 {
            margin: 0;
            font-size: 2em;
        }

        /* Estilos para a tabela de compras */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #6B9C00;
            color: white;
        }

        /* Estilo para linhas alternadas */
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Estilos para a mensagem de nenhum resultado */
        .no-result {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2em;
            color: #666666;
        }

        /* Estilos para o link de voltar */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #0056b3;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #003366;
        }
    </style>
</head>
<body>
    <menu id="menu">
        <a href="index.html"><img id="logo" src="assets/logo.png" alt="Logo"></a>
        <div id="menucontainer">
            <a href="#">Contate-nos</a>
            <a href="sobrenos.html">Sobre nós</a>
            <a href="monteacai.html">Monte seu açaí</a>
            <a target="_blank" href="https://maps.app.goo.gl/WuygUboKgi6uBZ5J9">Localização</a>
            <button><a href="carrinhoSa.php"><img src="assets/carrinho.svg" alt="Carrinho"></a></button>
            <button><a href="login.html">LOGIN</a></button>
        </div>
    </menu>
    <br><br><br><br><br><br>
    <header>
        <h1>Bancada de Compras</h1>
    </header>

    <!-- Conteúdo da página -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome do Cliente</th>
                <th>Valor Total</th>
                <th>Entrega</th>
                <th>Endereço</th>
                <th>Data da Compra</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo isset($row['id_compra']) ? $row['id_compra'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['nome_cliente']) ? htmlspecialchars($row['nome_cliente']) : 'N/A'; ?></td>
                    <td><?php echo isset($row['valor_total']) ? 'R$' . number_format($row['valor_total'], 2, ',', '.') : 'N/A'; ?></td>
                    <td><?php echo isset($row['entrega']) ? ($row['entrega'] == 1 ? 'Entrega' : 'Retirada') : 'N/A'; ?></td>
                    <td><?php echo isset($row['endereco']) ? htmlspecialchars($row['endereco']) : 'N/A'; ?></td>
                    <td><?php echo isset($row['data_compra']) ? $row['data_compra'] : 'N/A'; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-result">Nenhuma compra encontrada.</p>
    <?php endif; ?>

    <!-- Link de voltar -->
    <a class="back-link" href="monteacai.html">Voltar para a seleção de ingredientes</a>

    <?php $conn->close(); ?>
</body>
</html>