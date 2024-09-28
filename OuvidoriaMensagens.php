<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens da Ouvidoria - Tribus Açaí</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
            margin: 0;
        }
        header {
            background-color: #6200ea;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #6200ea;
            color: white;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            padding: 10px 0;
            background-color: #f4f4f9;
            color: #666;
        }
    </style>
</head>
<body>

<header>
    <h1>Mensagens da Ouvidoria - Tribus Açaí</h1>
</header>

<div class="container">
    <h2>Visualizar Mensagens</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Tipo</th>
                <th>Mensagem</th>
                <th>Data de Envio</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Conexão com o banco de dados
            $host = 'localhost';
            $dbname = 'acai_db2';
            $username = 'root';
            $password = '';

            try {
                $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Consulta para pegar as mensagens
                $query = "SELECT * FROM ouvidoria ORDER BY data_envio DESC"; // Ordernar pela data de envio
                $stmt = $conn->prepare($query);
                $stmt->execute();
                
                // Exibir os dados
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nome']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['tipo']}</td>
                            <td>{$row['mensagem']}</td>
                            <td>{$row['data_envio']}</td>
                          </tr>";
                }
            } catch (PDOException $e) {
                echo 'Erro na conexão: ' . $e->getMessage();
            }
            ?>
        </tbody>
    </table>
</div>

<footer>
    <p>Tribus Açaí &copy; 2024 - Todos os direitos reservados</p>
</footer>

</body>
</html>
