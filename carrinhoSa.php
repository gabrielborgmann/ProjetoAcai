<?php
session_start();

// Função para conectar ao banco de dados
function conectarBanco() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "acai_db2";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    return $conn;
}

// Adicionar item ao carrinho
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['produto']) && isset($_POST['preco'])) {
    $produto = $_POST['produto'];
    $preco = $_POST['preco'];

    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    // Verificar se o produto já está no carrinho
    $produto_existe = false;
    foreach ($_SESSION['carrinho'] as $item) {
        if ($item['produto'] === $produto && $item['preco'] == $preco) {
            $produto_existe = true;
            break;
        }
    }

    if (!$produto_existe) {
        $_SESSION['carrinho'][] = ['produto' => $produto, 'preco' => $preco];
    }
}

// Remover item do carrinho
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['index'])) {
    $index = $_GET['index'];
    if (isset($_SESSION['carrinho'][$index])) {
        unset($_SESSION['carrinho'][$index]);
        // Reindexar o array após a remoção
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
    }
}

// Função para obter o ID do produto com base no nome do produto
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

// Finalizar compra e registrar no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'finalizar') {
    $nome_cliente = $_POST['nome_cliente'];
    $endereco = $_POST['endereco'];
    $entrega = $_POST['entrega'];

    $conn = conectarBanco();
    $conn->autocommit(FALSE); // Iniciar transação

    $valor_total = 0;
    $descricao = [];

    // Inserir compra na tabela compra
    $sql_compra = "INSERT INTO compra (nome_cliente, valor_total, entrega, endereco) VALUES (?, ?, ?, ?)";
    $stmt_compra = $conn->prepare($sql_compra);
    $stmt_compra->bind_param("sdis", $nome_cliente, $valor_total, $entrega, $endereco);
    $stmt_compra->execute();

    $id_compra = $stmt_compra->insert_id; // Obter o ID da compra inserida

    foreach ($_SESSION['carrinho'] as $item) {
        $nome_produto = $item['produto'];
        $quantidade = 1; // Definir a quantidade conforme necessário
        $valor_item = $item['preco'];

        // Obter ID do produto
        $id_produto = getProdutoId($nome_produto, $conn);

        // Verificar se o ID do produto foi encontrado
        if ($id_produto !== null) {
            // Inserir item na tabela item
            $sql_item = "INSERT INTO item (id_compra, id_produto, quantidade, valor_item) VALUES (?, ?, ?, ?)";
            $stmt_item = $conn->prepare($sql_item);
            $stmt_item->bind_param("iiid", $id_compra, $id_produto, $quantidade, $valor_item);
            $stmt_item->execute();

            $valor_total += $valor_item;
            $descricao[] = $nome_produto;

            $stmt_item->close(); // Fechar a declaração após o uso
        } else {
            // Tratar caso o ID do produto não seja encontrado
            // Aqui você pode implementar um tratamento de erro adequado
            // Por exemplo, ignorar o item ou registrar um log de erro
            // Neste exemplo, o item será ignorado sem inserção na tabela item
            continue;
        }
    }

    // Atualizar valor_total na compra
    $sql_update_compra = "UPDATE compra SET valor_total = ? WHERE id_compra = ?";
    $stmt_update_compra = $conn->prepare($sql_update_compra);
    $stmt_update_compra->bind_param("di", $valor_total, $id_compra);
    $stmt_update_compra->execute();

    // Commit da transação
    $conn->commit();
    $conn->autocommit(TRUE);

    // Limpar o carrinho após finalizar a compra
    unset($_SESSION['carrinho']);
    $compraFinalizada = true;

    $stmt_compra->close(); // Fechar a declaração após o uso
    $stmt_update_compra->close(); // Fechar a declaração após o uso
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/menu.css">
    <title>Carrinho de Compras</title>
    <style>
        

        /* Reset de estilos */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Estilos gerais para o corpo do documento */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
}

/* Estilos para o menu superior */

/* Estilos para o cabeçalho (header) */
header {
    background-color: #3A004F;
    color: #fff;
    text-align: center;
    padding: 20px 0;
    margin-bottom: 20px;
    margin-left: auto; 
    margin-right: auto; 
    width: 100%; 
}

/* Estilos para o container principal do carrinho */
.container {
    max-width: 800px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Estilos para a tabela de produtos */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

/* Estilos para o total da compra */
.total {
    margin-top: 10px;
    text-align: right;
    font-weight: bold;
}

/* Estilos para o formulário de finalização da compra */
form {
    margin-top: 20px;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form div {
    margin-bottom: 10px;
}

form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333; /* Cor do texto do label */
}

form input[type="text"],
form input[type="submit"] {
    width: calc(100% - 22px);
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box; /* Garante que o padding não aumente a largura do input */
}

form input[type="submit"] {
    width: 100%;
    background-color: #6B9C00;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form input[type="submit"]:hover {
    background-color: #3A004F;
}

/* Estilos para a mensagem de confirmação */
.confirmacao {
    background-color: #6B9C00;
    color: #fff;
    text-align: center;
    padding: 10px;
    margin-top: 20px;
    border-radius: 5px;
}
input[type="submit"],

button {
    background-color: #3A004F; /* Cor roxa do menu */
    color: white;
    padding: 0.5em 1em;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1.1em;
    transition: background-color 0.3s;
    margin-top: 1em;
    width: 100%;
}

input[type="submit"]:hover,
button:hover {
    background-color: #1F0038; /* Cor mais escura ao passar o mouse */
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
            <a href="carrinhoSa.php"><button><img src="assets/carrinho.svg" alt="Carrinho"></button></a>
            <button><a href="login.html">LOGIN</a></button>
        </div>
    </menu>
    </br>
</br>
</br>
</br>
</br>
</br>
    <header>
        <h1>Seu Carrinho</h1>
    </header>
    <div class="container">
        <?php if (!empty($_SESSION['carrinho'])): ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['carrinho'] as $index => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['produto']); ?></td>
                            <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                            <td><a href="?action=remove&index=<?php echo $index; ?>" class="remove-link">Remover</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total">
                Total: R$ <?php echo number_format(array_sum(array_column($_SESSION['carrinho'], 'preco')), 2, ',', '.'); ?>
            </div>
            <form action="carrinhoSa.php" method="post">
                <input type="hidden" name="action" value="finalizar">
                <div>
                    <label for="nome_cliente">Nome:</label>
                    <input type="text" id="nome_cliente" name="nome_cliente" required>
                </div>
                <div>
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>
                <div>
                    <label for="entrega">Entrega:</label>
                    <input type="text" id="entrega" name="entrega" required>
                </div>
                <button type="submit">Finalizar Compra</button>
            </form>
        <?php else: ?>
            <p>Seu carrinho está vazio.</p><br/>
				        <p><center>
				        <p><center>
           <a href="index.html">Voltar</a>
        </p></center>
        <?php endif; ?>
    </div>

    <?php if (isset($compraFinalizada) && $compraFinalizada): ?>
        <div class="confirmacao">
            <p>Compra finalizada com sucesso!</p><br/>
            <img src="qrcode.png" />
				        <p><center>
           <a href="index.html">Voltar</a>
        </p></center>
        </div>
    <?php endif; ?>

</body>
</html>