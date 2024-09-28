<?php
require_once("db.php");

$sql = "SELECT * FROM usuarios WHERE aprovado = 0"; echo "<p>".$sql;
$result = $conn->query($sql);
/*
print_r ($result);
$ar_result = mysqli_fetch_assoc($result);
echo "<p> <pre>"; 
print_r ($ar_result);
echo "</pre>";
*/
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprovar Usuários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #3A004F;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border-bottom: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #6B9C00;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Usuários Pendentes de Aprovação</h2>
    <?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Aprovar</th>
            <th>Rejeitar</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['nome'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td><a href='aprovar.php?id=" . $row['ID'] . "'>Aprovar</a></td>";
            echo "<td><a href='rejeitar.php?id=" . $row['ID'] . "'>Rejeitar</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <?php else: ?>
    <p>Nenhum usuário pendente de aprovação.</p>
    <?php endif; ?>
</body>
</html>
<?php
$conn->close();
?>
