<?php
require_once 'conexao.php';

$mensagem = "";
$tipoMensagem = "";

// LÓGICA DE ELIMINAR REGISTO (DELETE)
if (isset($_GET['delete_id'])) {
    $idDeletar = intval($_GET['delete_id']);
    $stmtDelete = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    try {
        $stmtDelete->execute([':id' => $idDeletar]);
        $mensagem = "Registo eliminado com sucesso!";
        $tipoMensagem = "sucesso";
    } catch (PDOException $e) {
        $mensagem = "Erro ao eliminar registo.";
        $tipoMensagem = "erro";
    }
}

// LÓGICA DE CADASTRO (INSERT)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    $nome     = trim($_POST['nome']);
    $cpf      = trim($_POST['cpf']);
    $email    = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cep      = trim($_POST['cep']);
    $endereco = trim($_POST['endereco']);
    $cidade   = trim($_POST['cidade']);
    $estado   = trim($_POST['estado']);

    if (!empty($nome) && !empty($cpf) && !empty($email)) {
        $sql = "INSERT INTO usuarios (nome, cpf, email, telefone, cep, endereco, cidade, estado) 
                VALUES (:nome, :cpf, :email, :telefone, :cep, :endereco, :cidade, :estado)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([
                ':nome'     => $nome,
                ':cpf'      => $cpf,
                ':email'    => $email,
                ':telefone' => $telefone,
                ':cep'      => $cep,
                ':endereco' => $endereco,
                ':cidade'   => $cidade,
                ':estado'   => $estado
            ]);
            $mensagem = "Usuário cadastrado com sucesso!";
            $tipoMensagem = "sucesso";
        } catch (PDOException $e) {
            $mensagem = "Erro ao cadastrar: CPF ou E-mail já cadastrado.";
            $tipoMensagem = "erro";
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos obrigatórios (*).";
        $tipoMensagem = "erro";
    }
}

// LÓGICA DE CONSULTA (SELECT)
$sqlConsulta = "SELECT * FROM usuarios ORDER BY id DESC";
$stmtConsulta = $pdo->query($sqlConsulta);
$usuarios = $stmtConsulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta e Registro de Usuários</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { background-color: #f4f6f9; color: #333; padding: 30px 15px; display: flex; justify-content: center; }
        .container { width: 100%; max-width: 1100px; background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #1a73e8; font-size: 1.3rem; margin-bottom: 20px; border-bottom: 2px solid #eef2f6; padding-bottom: 8px; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; }
        .alert.sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        form { background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 30px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        label { font-weight: bold; font-size: 0.85rem; color: #495057; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px; font-size: 0.95rem; }
        button { background-color: #1a73e8; color: white; border: none; padding: 12px 24px; border-radius: 5px; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #1557b0; }

        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 0.85rem; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e9ecef; }
        th { background-color: #f1f3f4; }
        .btn-delete { color: #dc3545; text-decoration: none; font-weight: bold; }
        .btn-delete:hover { text-decoration: underline; }
        .vazio { color: #6c757d; font-style: italic; }
    </style>
</head>
<body>

<div class="container">

    <h2>Cadastrar Novo Usuário</h2>

    <?php if (!empty($mensagem)): ?>
        <div class="alert <?= $tipoMensagem ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>
            </div>
            <div class="form-group">
                <label for="cpf">CPF *</label>
                <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" placeholder="exemplo@dominio.com" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000">
            </div>
            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" id="cep" name="cep" placeholder="00000-000">
            </div>
            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" placeholder="Rua, Número, Bairro">
            </div>
            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" id="cidade" name="cidade" placeholder="Sua Cidade">
            </div>
            <div class="form-group">
                <label for="estado">Estado (UF)</label>
                <input type="text" id="estado" name="estado" placeholder="Ex: RJ">
            </div>
        </div>
        <button type="submit" name="cadastrar">Salvar Registro</button>
    </form>

    <h2>Registros Existentes (Consulta)</h2>

    <div class="table-responsive">
        <?php if (count($usuarios) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>CEP</th>
                        <th>Endereço</th>
                        <th>Cidade/UF</th>
                        <th>Data</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $user): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['nome']) ?></td>
                            <td><?= htmlspecialchars($user['cpf']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['telefone'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($user['cep'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($user['endereco'] ?: '-') ?></td>
                            <td><?= htmlspecialchars(($user['cidade'] ?: '-') . '/' . ($user['estado'] ?: '-')) ?></td>
                            <td>
                                <?php 
                                    $data = isset($user['data_cadastro']) ? $user['data_cadastro'] : 'N/A';
                                    echo ($data !== 'N/A') ? date('d/m/Y H:i', strtotime($data)) : 'N/A';
                                ?>
                            </td>
                            <td>
                                <a href="index.php?delete_id=<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="vazio">Nenhum registro encontrado no banco de dados.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>