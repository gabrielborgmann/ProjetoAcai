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

// Consulta para obter os dados dos pedidos
$sql = "SELECT * FROM compras_sa ORDER BY data_compra DESC";
$result = $conn->query($sql);
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
            background-color:  #3A004F;
            color: white;
            padding: 1em 0;
            text-align: center;
            width: 100%; /* Largura completa */
          margin-left: auto; /* Remove margem à esquerda */
               margin-right: auto; /* Remove margem à direita */
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
            table-layout: auto;
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
            <button href="carrinhoSa.php"><a href="carrinhoSa.php"><img src="assets/carrinho.svg" alt="Carrinho"></a></button>
            <button href="login.php"><a href="login.html">LOGIN</a></button>
        </div>
    </menu>
</br>
</br>
</br>
</br>
</br>
    <header>
        <h1>Bancada de Compras</h1>
    </header>

    <!-- Conteúdo da página -->
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome do Cliente</th>
                <th>Descrição</th>
                <th>Valor Total</th>
                <th>Entrega</th>
                <th>Endereço</th>
                <th>Data da Compra</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nome_cliente']); ?></td>
                    <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                    <td>R$<?php echo number_format($row['valor_total'], 2, ',', '.'); ?></td>
                    <td><?php echo $row['entrega'] ? 'Sim' : 'Não'; ?></td>
                    <td><?php echo htmlspecialchars($row['endereco']); ?></td>
                    <td><?php echo $row['data_compra']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-result">Nenhuma compra encontrada.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
