<?php
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'acai_db2';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Filtro padrão
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'dia';

    // Obter o mês se fornecido
    $mes_selecionado = isset($_GET['mes']) ? $_GET['mes'] : '';

    // Obter as datas personalizadas se fornecidas
    $data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
    $data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

    // Valida o formato das datas fornecidas (opcional)
    if (!empty($data_inicio) && !empty($data_fim)) {
        $valid_data_inicio = date('Y-m-d', strtotime($data_inicio));
        $valid_data_fim = date('Y-m-d', strtotime($data_fim));
        $where_data = "data_compra BETWEEN '$valid_data_inicio' AND '$valid_data_fim'";
    } elseif (!empty($mes_selecionado)) {
        // Se o mês estiver selecionado, filtrar por mês e ano atual
        $where_data = "MONTH(data_compra) = $mes_selecionado AND YEAR(data_compra) = YEAR(CURRENT_DATE())";
    } else {
        $where_data = "YEAR(data_compra) = YEAR(CURRENT_DATE())";
    }

    // Escolhe a forma de agrupamento com base no filtro
    switch ($filtro) {
        case 'mes':
            $group_by = "MONTH(data_compra)";
            $label = 'Mês';
            break;
        case 'ano':
            $group_by = "YEAR(data_compra)";
            $label = 'Ano';
            break;
        case 'dia':
        default:
            $group_by = "DATE(data_compra)";
            $label = 'Dia';
            break;
    }

    // Consulta SQL com o filtro de data e agrupamento selecionado
    $query_pedidos = "
        SELECT COUNT(*) as total_pedidos, SUM(valor_total) as total_vendas, $group_by as periodo
        FROM compra
        WHERE $where_data
        GROUP BY $group_by
    ";

    $stmt_pedidos = $conn->prepare($query_pedidos);
    $stmt_pedidos->execute();
    $resultados_pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_ASSOC);

    $total_pedidos = array_sum(array_column($resultados_pedidos, 'total_pedidos'));
    $total_vendas = array_sum(array_column($resultados_pedidos, 'total_vendas'));
    $media_pedidos = $total_pedidos > 0 ? $total_pedidos / count($resultados_pedidos) : 0;

} catch (PDOException $e) {
    echo 'Erro na conexão: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/menu.css">
    <title>Relatório de Pedidos</title>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            var pedidosData = google.visualization.arrayToDataTable([
                ['<?= $label ?>', 'Total de Pedidos'],
                <?php
                foreach($resultados_pedidos as $row) {
                    echo "['" . $row['periodo'] . "', " . $row['total_pedidos'] . "],";
                }
                ?>
            ]);

            var pedidosOptions = {
                title: 'Total de Pedidos por <?= $label ?>',
                hAxis: {title: '<?= $label ?>'},
                vAxis: {title: 'Total de Pedidos'},
                legend: { position: 'bottom' }
            };

            var pedidosChart = new google.visualization.LineChart(document.getElementById('pedidos_chart'));
            pedidosChart.draw(pedidosData, pedidosOptions);
        }

        // Função para mostrar ou ocultar o campo de mês baseado no filtro selecionado
        function toggleMesField() {
            var filtro = document.getElementById('filtro').value;
            var mesField = document.getElementById('mes-field');
            if (filtro === 'mes') {
                mesField.style.display = 'inline-block';
            } else {
                mesField.style.display = 'none';
            }
        }
    </script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        menu {
            background-color: #3A004F;
            color: white;
            padding: 1em 0;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        #menucontainer {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        #menu a {
            color: white;
            text-decoration: none;
            padding: 0 15px;
            font-weight: bold;
        }

        header {
            background-color: #3A004F;
            color: white;
            padding: 1em 0;
            text-align: center;
            width: 100%;
            margin-top: 70px;
        }

        h1 {
            margin: 0;
            font-size: 2.5em;
        }

        #pedidos_chart {
            margin: 20px auto;
            width: 90%;
            max-width: 900px;
            height: 500px;
        }

        #filtro-form {
            text-align: center;
            margin-top: 20px;
        }

        .mes-container {
            display: none;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
                margin-top: 80px;
            }

            #pedidos_chart {
                width: 100%;
                height: auto;
            }
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

    <header>
        <h1>Relatório de Pedidos</h1>
    </header>

    <!-- Formulário de Filtro -->
    <form method="GET" id="filtro-form">
        <label for="filtro">Filtrar por:</label>
        <select name="filtro" id="filtro" onchange="toggleMesField()">
            <option value="dia" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'dia') ? 'selected' : '' ?>>Dia</option>
            <option value="mes" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'mes') ? 'selected' : '' ?>>Mês</option>
            <option value="ano" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'ano') ? 'selected' : '' ?>>Ano</option>
        </select>

        <!-- Campo de mês exibido apenas se o filtro for "Mês" -->
        <div id="mes-field" class="mes-container" style="display: <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'mes') ? 'inline-block' : 'none' ?>;">
            <label for="mes">Selecionar Mês:</label>
            <select name="mes" id="mes">
                <option value="1" <?= (isset($_GET['mes']) && $_GET['mes'] == '1') ? 'selected' : '' ?>>Janeiro</option>
                <option value="2" <?= (isset($_GET['mes']) && $_GET['mes'] == '2') ? 'selected' : '' ?>>Fevereiro</option>
                <option value="3" <?= (isset($_GET['mes']) && $_GET['mes'] == '3') ? 'selected' : '' ?>>Março</option>
                <option value="4" <?= (isset($_GET['mes']) && $_GET['mes'] == '4') ? 'selected' : '' ?>>Abril</option>
                <option value="5" <?= (isset($_GET['mes']) && $_GET['mes'] == '5') ? 'selected' : '' ?>>Maio</option>
                <option value="6" <?= (isset($_GET['mes']) && $_GET['mes'] == '6') ? 'selected' : '' ?>>Junho</option>
                <option value="7" <?= (isset($_GET['mes']) && $_GET['mes'] == '7') ? 'selected' : '' ?>>Julho</option>
                <option value="8" <?= (isset($_GET['mes']) && $_GET['mes'] == '8') ? 'selected' : '' ?>>Agosto</option>
                <option value="9" <?= (isset($_GET['mes']) && $_GET['mes'] == '9') ? 'selected' : '' ?>>Setembro</option>
                <option value="10" <?= (isset($_GET['mes']) && $_GET['mes'] == '10') ? 'selected' : '' ?>>Outubro</option>
                <option value="11" <?= (isset($_GET['mes']) && $_GET['mes'] == '11') ? 'selected' : '' ?>>Novembro</option>
                <option value="12" <?= (isset($_GET['mes']) && $_GET['mes'] == '12') ? 'selected' : '' ?>>Dezembro</option>
            </select>
        </div>

        <!-- Novos campos de intervalo de datas -->
        <label for="data_inicio">Data Início:</label>
        <input type="date" id="data_inicio" name="data_inicio" value="<?= isset($data_inicio) ? $data_inicio : '' ?>">

        <label for="data_fim">Data Fim:</label>
        <input type="date" id="data_fim" name="data_fim" value="<?= isset($data_fim) ? $data_fim : '' ?>">

        <button type="submit">Filtrar</button>
    </form>

    <div id="pedidos_chart"></div>

    <h2 style="text-align: center;">Média de Pedidos: <?= number_format($media_pedidos, 2) ?></h2>
    <h2 style="text-align: center;">Valor Total das Vendas: R$ <?= number_format($total_vendas, 2, ',', '.') ?></h2>

</body>
</html>
