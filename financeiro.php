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
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'dia';

    switch ($filtro) {
        case 'mes':
            $group_by = "MONTH(data)";
            $label = 'Mês';
            break;
        case 'bimestre':
            $group_by = "FLOOR((MONTH(data)-1)/2) + 1";
            $label = 'Bimestre';
            break;
        case 'ano':
            $group_by = "YEAR(data)";
            $label = 'Ano';
            break;
        case 'dia':
        default:
            $group_by = "DAY(data)";
            $label = 'Dia';
            break;
    }

    // Consulta para buscar as vendas com base no filtro
    $query_vendas = "
        SELECT SUM(valor) as total, $group_by as periodo
        FROM vendas
        WHERE YEAR(data) = YEAR(CURRENT_DATE())
        GROUP BY $group_by
    ";
    $stmt_vendas = $conn->prepare($query_vendas);
    $stmt_vendas->execute();
    $resultados_vendas = $stmt_vendas->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para buscar os gastos com base no filtro
    $query_gastos = "
        SELECT SUM(valor) as total, $group_by as periodo
        FROM gastos
        WHERE YEAR(data) = YEAR(CURRENT_DATE())
        GROUP BY $group_by
    ";
    $stmt_gastos = $conn->prepare($query_gastos);
    $stmt_gastos->execute();
    $resultados_gastos = $stmt_gastos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erro na conexão: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/menu.css">
    <title>Relatório Financeiro - Vendas e Gastos</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Carrega o Google Charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Gráfico de Vendas
            var vendasData = google.visualization.arrayToDataTable([
                ['<?= $label ?>', 'Total de Vendas'],
                <?php
                foreach($resultados_vendas as $row) {
                    echo "['" . $row['periodo'] . "', " . $row['total'] . "],";
                }
                ?>
            ]);

            var vendasOptions = {
                title: 'Vendas por <?= $label ?> no Ano Atual',
                hAxis: {title: '<?= $label ?>'},
                vAxis: {title: 'Total de Vendas'},
                legend: { position: 'bottom' }
            };

            var vendasChart = new google.visualization.LineChart(document.getElementById('vendas_chart'));
            vendasChart.draw(vendasData, vendasOptions);

            // Gráfico de Gastos
            var gastosData = google.visualization.arrayToDataTable([
                ['<?= $label ?>', 'Total de Gastos'],
                <?php
                foreach($resultados_gastos as $row) {
                    echo "['" . $row['periodo'] . "', " . $row['total'] . "],";
                }
                ?>
            ]);

            var gastosOptions = {
                title: 'Gastos por <?= $label ?> no Ano Atual',
                hAxis: {title: '<?= $label ?>'},
                vAxis: {title: 'Total de Gastos'},
                legend: { position: 'bottom' }
            };

            var gastosChart = new google.visualization.LineChart(document.getElementById('gastos_chart'));
            gastosChart.draw(gastosData, gastosOptions);
        }
    </script>
    <style>
        /* Estilos gerais */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilos para o menu */
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

        #menu a:hover {
            text-decoration: underline;
        }

        #menu button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            margin-left: 10px;
        }

        #menu button img {
            width: 24px;
            height: 24px;
        }

        #menu button a {
            color: white;
            text-decoration: none;
        }

        #menu button a:hover {
            color: #ddd;
        }

        /* Estilos para o cabeçalho */
        header {
            background-color: #3A004F;
            color: white;
            padding: 1em 0;
            text-align: center;
            width: 100%;
            margin-top: 70px; /* Ajusta a margem para não sobrepor o menu fixo */
        }

        h1 {
            margin: 0;
            font-size: 2.5em;
        }

        /* Estilos para os gráficos */
        #vendas_chart, #gastos_chart {
            margin: 0 auto;
            width: 90%;
            max-width: 900px;
            height: 500px;
            margin-top: 20px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
                margin-top: 80px;
            }

            #vendas_chart, #gastos_chart {
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
        <h1>Relatório Financeiro - Vendas e Gastos</h1>
    </header>

<!-- Formulário de Filtro -->
<form method="GET" id="filtro-form">
    <label for="filtro">Filtrar por:</label>
    <select name="filtro" id="filtro" onchange="toggleFilterOptions()">
        <option value="dia" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'dia') ? 'selected' : '' ?>>Dia</option>
        <option value="mes" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'mes') ? 'selected' : '' ?>>Mês</option>
        <option value="bimestre" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'bimestre') ? 'selected' : '' ?>>Bimestre</option>
        <option value="ano" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'ano') ? 'selected' : '' ?>>Ano</option>
    </select>

    <div id="month-selection" style="display: <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'mes') ? 'block' : 'none' ?>;">
        <label for="mes">Escolha o mês:</label>
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

    <div id="year-selection" style="display: <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'ano') ? 'block' : 'none' ?>;">
        <label for="ano">Escolha o ano:</label>
        <select name="ano" id="ano">
            <?php
            // Gera uma lista de anos, por exemplo, de 2000 até o ano atual
            $ano_atual = date("Y");
            for ($ano = 2000; $ano <= $ano_atual; $ano++) {
                echo "<option value='$ano' " . (isset($_GET['ano']) && $_GET['ano'] == $ano ? 'selected' : '') . ">$ano</option>";
            }
            ?>
        </select>
    </div>

    <div id="day-selection" style="display: <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'dia') ? 'block' : 'none' ?>;">
        <label for="dia">Escolha o dia:</label>
        <select name="dia" id="dia">
            <?php
            // Gera uma lista de dias
            for ($dia = 1; $dia <= 31; $dia++) {
                echo "<option value='$dia' " . (isset($_GET['dia']) && $_GET['dia'] == $dia ? 'selected' : '') . ">$dia</option>";
            }
            ?>
        </select>
    </div>
</form>

<script>
    function toggleFilterOptions() {
        const filtro = document.getElementById('filtro').value;
        document.getElementById('month-selection').style.display = filtro === 'mes' ? 'block' : 'none';
        document.getElementById('year-selection').style.display = filtro === 'ano' ? 'block' : 'none';
        document.getElementById('day-selection').style.display = filtro === 'dia' ? 'block' : 'none';
        document.getElementById('filtro-form').submit(); // Submete o formulário ao mudar o filtro
    }
</script>


    <!-- Gráficos -->
    <div id="vendas_chart"></div>
    <div id="gastos_chart"></div>
</body>
</html>
