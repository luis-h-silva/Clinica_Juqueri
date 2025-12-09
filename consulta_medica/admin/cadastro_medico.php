<?php
session_start();
include "../conexao.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_nome = $_SESSION['admin_nome'] ?? "Administrador"; 
$mensagem = "";
$medicos = [];
$especialidades = [];

if (isset($_POST['nome'], $_POST['especialidade_id'])) { // CRM removido
    
    $nome = $_POST['nome'];
    $especialidade_id = $_POST['especialidade_id'];
    
    $sql_insert = "INSERT INTO medicos (nome, especialidade_id) VALUES (?, ?)";
    if ($stmt_insert = $mysqli->prepare($sql_insert)) {
        $stmt_insert->bind_param("si", $nome, $especialidade_id);
        
        if ($stmt_insert->execute()) {
            $mensagem = '<p class="mensagem-sucesso">Médico(a) ' . htmlspecialchars($nome) . ' cadastrado(a) com sucesso!</p>';
        } else {
            $mensagem = '<p class="mensagem-erro">Erro ao cadastrar: ' . $mysqli->error . '</p>';
        }
        $stmt_insert->close();
    } else {
        $mensagem = '<p class="mensagem-erro">Erro na preparação da inserção.</p>';
    }
}

$sql_esp = "SELECT id, nome FROM especialidades ORDER BY nome";
if ($res_esp = $mysqli->query($sql_esp)) {
    while ($row = $res_esp->fetch_assoc()) {
        $especialidades[] = $row;
    }
}

$sql_medicos = "
    SELECT m.id, m.nome, e.nome AS especialidade_nome
    FROM medicos m
    JOIN especialidades e ON m.especialidade_id = e.id
    ORDER BY m.nome ASC
";
if ($res_medicos = $mysqli->query($sql_medicos)) {
    while ($row = $res_medicos->fetch_assoc()) {
        $medicos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro e Consulta de Médicos | Admin</title>
    <link rel="stylesheet" href="../estilo.css">
    <style>
        /* Estilos da Tabela de Médicos */
        .tabela-medicos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .tabela-medicos th, .tabela-medicos td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        .tabela-medicos th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="tela">

    <header class="header-padrao">
        <div class="header-conteudo">
            <a href="admin_home.php" class="logo-link">
                <img src="../logo.png" alt="Logo Clínica Juqueri" class="logo-header">
                Clínica Juqueri - Admin
            </a>
            <nav class="links-navegacao">
                <a href="admin_home.php">Home</a>
                <a href="admin_consultas.php">Consultas</a>
                <a href="../logout.php" style="background-color: #dc3545; padding: 5px 15px; border-radius: 4px;">Sair</a>
            </nav>
        </div>
    </header>

    <main class="conteudo-principal-centralizado">
        
        <div class="form-container" style="max-width: 900px;">
            <h2>Cadastro de Novo Médico</h2>
            
            <?php echo $mensagem; ?>

            <form method="POST" action="cadastro_medico.php"> 
                
                <div class="input-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="input-group">
                    <label for="especialidade_id">Especialidade:</label>
                    <select id="especialidade_id" name="especialidade_id" required>
                        <option value="">-- Selecione a Especialidade --</option>
                        <?php foreach ($especialidades as $esp): ?>
                            <option value="<?php echo $esp['id']; ?>">
                                <?php echo htmlspecialchars($esp['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($especialidades)): ?>
                        <p class="mensagem-erro" style="margin-top: 10px;">Nenhuma especialidade cadastrada. <a href="cadastro_especialidade.php">Cadastre uma agora.</a></p>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn-submit">Cadastrar Médico</button>
            </form>
        </div>
        
        <div class="form-container" style="max-width: 900px; margin-top: 40px;">
            <h3>Médicos Cadastrados </h3>
            
            <?php if (empty($medicos) && empty($mensagem)): ?>
                <p>Nenhum médico cadastrado no momento.</p>
            <?php elseif (!empty($medicos)): ?>

                <table class="tabela-medicos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Especialidade</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php foreach($medicos as $m): ?>
                            <tr>
                                <td><?= $m['id'] ?></td>
                                <td><?= htmlspecialchars($m['nome']) ?></td>
                                <td><?= htmlspecialchars($m['especialidade_nome']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            
            <?php endif; ?>
        </div>

    </main>

    <footer class="footer-padrao">
        <span>&copy; Clínica Juqueri. Todos os direitos reservados.</span>
    </footer>

</body>
</html>