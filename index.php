<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conexao.php';

try {
    // 1. Cria a tabela de login se ela não existir (Sintaxe adaptada para Postgres)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id SERIAL PRIMARY KEY,
            usuario VARCHAR(50) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // 2. Verifica se a tabela está vazia para inserir um usuário padrão de teste
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    $total = $stmt->fetchColumn();

    if ($total == 0) {
        // Senha '123456' criptografada de forma segura
        $senhaHash = password_hash('123456', PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO usuarios (usuario, senha) VALUES (:usuario, :senha)";
        $insert = $pdo->prepare($sql);
        $insert->execute([
            ':usuario' => 'edison_professor',
            ':senha' => $senhaHash
        ]);
        $mensagemCarga = "🔹 Tabela populada com o usuário 'edison_professor' (senha: 123456).";
    } else {
        $mensagemCarga = "🔹 A tabela já possui registros.";
    }

    // 3. Consulta os dados para exibir na tela
    $stmt = $pdo->query("SELECT id, usuario, criado_em FROM usuarios");
    $listaUsuarios = $stmt->fetchAll();

    echo json_encode([
        "sucesso" => true,
        "linguagem" => "PHP",
        "status" => "Tabela 'usuarios' verificada no PostgreSQL com sucesso!",
        "carga" => $mensagemCarga,
        "dados" => $listaUsuarios
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "erro" => "Falha na operação estrutural do banco.",
        "detalhe" => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>
