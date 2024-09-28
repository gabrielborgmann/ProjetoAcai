<?php
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'acai_db2';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica o filtro selecionado
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

    switch ($filtro) {
        case 'mais':
            $query_clientes = "
                SELECT nome_cliente, SUM(valor_total) as total_compras
                FROM compra
                GROUP BY nome_cliente
                ORDER BY total_compras DESC
                LIMIT 10
            ";
            $titulo = 'Clientes que Mais Compram';
            break;
        case 'menos':
            $query_clientes = "
                SELECT nome_cliente, SUM(valor_total) as total_compras
                FROM compra
                GROUP BY nome_cliente
                ORDER BY total_compras ASC
                LIMIT 10
            ";
            $titulo = 'Clientes que Menos Compram';
            break;
        case 'todos':
        default:
            $query_clientes = "
                SELECT nome_cliente, SUM(valor_total) as total_compras
                FROM compra
                GROUP BY nome_cliente
                ORDER BY total_compras DESC
            ";
            $titulo = 'Todos os Clientes';
            break;
    }

    // Prepara e executa a consulta
    $stmt = $conn->prepare($query_clientes);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erro na conexão: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Carregar a biblioteca do Google Charts
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Dados do gráfico
            var data = google.visualization.arrayToDataTable([
                ['Cliente', 'Total Gasto'],
                <?php
                foreach ($clientes as $cliente) {
                    echo "['" . $cliente['nome_cliente'] . "', " . $cliente['total_compras'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Clientes que Mais Gastaram',
                chartArea: {width: '50%'},
                hAxis: {
                    title: 'Total Gasto (R$)',
                    minValue: 0
                },
                vAxis: {
                    title: 'Clientes'
                }
            };

            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
  <style>
    /* Reset básico para garantir consistência entre navegadores */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Estilo geral */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        line-height: 1.6;
    }

    /* Menu fixo */
    menu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #6200ea;
        color: white;
    }

    menu a {
        color: white;
        text-decoration: none;
        padding: 10px 15px;
    }

    #logo {
        height: 50px;
    }

    #menucontainer {
        display: flex;
        gap: 10px;
    }

    /* Estilo do título */
    header h1 {
        text-align: center;
        margin-top: 30px;
        font-size: 2em;
        color: #6200ea;
    }

    /* Estilo do formulário de filtro */
    #filtro-form {
        text-align: center;
        margin: 20px 0;
    }

    #filtro-form select {
        padding: 10px;
        font-size: 1em;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    /* Estilos para tabelas */
    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-transform: uppercase;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Estilo do gráfico */
    #chart_div {
        width: 90%;
        height: 500px;
        margin: 40px auto;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Responsividade */
    @media (max-width: 768px) {
        table {
            width: 100%;
        }

        #chart_div {
            width: 100%;
            height: 300px;
        }

        header h1 {
            font-size: 1.5em;
        }

        #menucontainer {
            flex-direction: column;
            align-items: center;
        }

        menu a {
            padding: 5px;
        }
    }
</style>

</head>
<body>

<!-- Menu fixo -->
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

<!-- Título e Filtro -->
<header>
    <h1><?= $titulo ?></h1>
</header>

<!-- Formulário de Filtro -->
<form method="GET" id="filtro-form" style="text-align: center; margin-bottom: 20px;">
    <label for="filtro">Filtrar por:</label>
    <select name="filtro" id="filtro" onchange="document.getElementById('filtro-form').submit();">
        <option value="todos" <?= ($filtro == 'todos') ? 'selected' : '' ?>>Todos</option>
        <option value="mais" <?= ($filtro == 'mais') ? 'selected' : '' ?>>Mais Compram</option>
        <option value="menos" <?= ($filtro == 'menos') ? 'selected' : '' ?>>Menos Compram</option>
    </select>
</form>

<!-- Tabela de Clientes -->
<table>
    <tr>
        <th>Nome do Cliente</th>
        <th>Total Gasto</th>
    </tr>
    <?php foreach ($clientes as $cliente): ?>
        <tr>
            <td><?= htmlspecialchars($cliente['nome_cliente']) ?></td>
            <td>R$ <?= number_format($cliente['total_compras'], 2, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Gráfico de Clientes -->
<div id="chart_div"></div>

</body>
</html>
