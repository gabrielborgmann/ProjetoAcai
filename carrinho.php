<?php
// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acai_db2";

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$nome_cliente = $_POST['nome_cliente'];
$tamanho = $_POST['tamanho'];
$ingredientes = isset($_POST['ingredientes']) ? $_POST['ingredientes'] : [];
$entrega = isset($_POST['entrega']) ? 1 : 2;
$endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';

// Calcular o valor total baseado no tamanho
$valores_tamanho = [
    'pequeno' => 10.00,
    'medio' => 15.00,
    'grande' => 20.00
];
$valor_total = $valores_tamanho[$tamanho];

// Inserir na tabela compra
$sql_compra = "INSERT INTO compra (nome_cliente, valor_total, entrega, endereco) VALUES (?, ?, ?, ?)";
$stmt_compra = $conn->prepare($sql_compra);
$stmt_compra->bind_param("sdss", $nome_cliente, $valor_total, $entrega, $endereco);
$stmt_compra->execute();
$id_compra = $stmt_compra->insert_id;

// Inserir itens na tabela item
foreach ($ingredientes as $ingrediente) {
    // Aqui você deve mapear os nomes dos ingredientes para seus respectivos IDs de produto e valores unitários.
    $id_produto = getProdutoId($ingrediente, $conn);
    $valor_item = getProdutoValor($ingrediente, $conn);
    $quantidade = 1; // Supondo quantidade 1 para cada ingrediente

    $sql_item = "INSERT INTO item (id_compra, id_produto, quantidade, valor_item) VALUES (?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);
    $stmt_item->bind_param("iiid", $id_compra, $id_produto, $quantidade, $valor_item);
    $stmt_item->execute();
}

$stmt_compra->close();
$conn->close();

header("Location: carrinhoSa.php");
exit();

function getProdutoId($nome_produto, $conn) {
    $sql = "SELECT id_produto FROM produto WHERE nome_produto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome_produto);
    $stmt->execute();
    $stmt->bind_result($id_produto);
    $stmt->fetch();
    $stmt->close();
    return $id_produto;
}

function getProdutoValor($nome_produto, $conn) {
    $sql = "SELECT valor_unitario FROM produto WHERE nome_produto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome_produto);
    $stmt->execute();
    $stmt->bind_result($valor_unitario);
    $stmt->fetch();
    $stmt->close();
    return $valor_unitario;
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
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
            background-color: #6B9C00;
            color: white;
            padding: 1em 0;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 2em;
        }

        /* Estilos para a seção de compras */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin-bottom: 20px;
        }

        li {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            margin-bottom: 10px;
        }

        li:last-child {
            margin-bottom: 0;
        }

        p {
            margin: 0;
            color: #666;
        }

        /* Estilos para os links */
        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
        }

        /* Estilos para o botão "Finalizar Compra" */
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            margin: 20px auto;
            font-size: 1em;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Estilos para os inputs de texto */
        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <h1>Carrinho de Compras</h1>
    </header>

    <!-- Conteúdo da página -->
    <div class="container">
        <?php if (!empty($_SESSION['carrinho'])): ?>
            <ul>
                <?php foreach ($_SESSION['carrinho'] as $monteacai => $pedido): ?>
                    <li>
                        <p>
                            <?php
                                echo htmlspecialchars($pedido['nome_cliente']) . ' - Tamanho: ' . htmlspecialchars($pedido['tamanho']);
                                if (!empty($pedido['ingredientes'])) {
                                    echo ' - Ingredientes: ' . implode(', ', $pedido['ingredientes']);
                                }
                            ?>
                        </p>
                        <a href="carrinho.php?action=remove&monteacai=<?php echo $monteacai; ?>">Excluir</a>
                        <form action="carrinho.php" method="POST" class="edit-form">
                            <input type="hidden" name="monteacai" value="<?php echo $monteacai; ?>">
                            <input type="text" name="novoIngrediente" placeholder="Novo Ingrediente">
                            <input type="submit" name="edit" value="Editar">
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p>Valor Total: R$<?php echo number_format(array_sum(array_column($_SESSION['carrinho'], 'valor_total')), 2, ',', '.'); ?></p>
            <?php if (!empty($_SESSION['carrinho'][0]['entrega'])): ?>
                <p>Entrega: Sim</p>
                <p>Endereço: <?php echo htmlspecialchars($_SESSION['carrinho'][0]['endereco']); ?></p>
            <?php else: ?>
                <p>Entrega: Não</p>
            <?php endif; ?>
            <form action="carrinho.php" method="POST">
                <input type="submit" name="finalizar" value="Finalizar Compra">
            </form>
        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
        <?php endif; ?>
        <?php if(isset($mensagem_confirmacao)): ?>
            <p><?php echo $mensagem_confirmacao; ?></p>
            <a href="monteacai.html">Realizar outra compra</a>
        <?php else: ?>
            <a href="monteacai.html">Voltar para a seleção de ingredientes</a>
        <?php endif; ?>
    </div>
</body>
</html>
