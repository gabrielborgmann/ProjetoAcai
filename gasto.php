<?php
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'acai_db2';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para buscar os gastos do mês atual
    $query = "
        SELECT SUM(valor) as total, DAY(data) as dia
        FROM gastos
        WHERE MONTH(data) = MONTH(CURRENT_DATE())
        AND YEAR(data) = YEAR(CURRENT_DATE())
        GROUP BY DAY(data)
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $resultados_gastos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erro na conexão: ' . $e->getMessage();
}
?>
