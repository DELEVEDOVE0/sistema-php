<?php
try {
    $pdo = new PDO("sqlite:" . __DIR__ . "/banco.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        cpf TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        telefone TEXT,
        cep TEXT,
        endereco TEXT,
        cidade TEXT,
        estado TEXT,
        data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>