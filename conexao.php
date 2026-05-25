<?php
// Lê as variáveis de ambiente seguras do Render
$host = getenv('DB_HOST');
$port = getenv('DB_PORT') ?: '5432'; // Porta padrão do Postgres
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');

try {
    // String de conexão específica para PostgreSQL (pgsql)
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo json_encode([
        "sucesso" => false, 
        "erro" => "Erro de conexão com o banco PostgreSQL.",
        "detalhe" => $e->getMessage()
    ]);
    exit;
}
?>
