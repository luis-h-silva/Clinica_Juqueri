<?php
session_start();
include "../conexao.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_nome = $_SESSION['admin_nome'] ?? "Administrador"; 
$mensagem_erro = "";
$consultas = [];

$sql = "
SELECT 
    c.id, c.data, c.hora, c.status,
    p.nome AS paciente,
    m.nome AS medico,
    e.nome AS especialidade
FROM consultas c
JOIN pacientes p ON c.paciente_id = p.id
JOIN medicos m ON c.medico_id = m.id
JOIN especialidades e ON m.especialidade_id = e.id
ORDER BY c.data DESC, c.hora DESC
";

$res = $mysqli->query($sql);

if ($res) {
    while($row = $res->fetch_assoc()) {
        $consultas[] = $row;
    }
} else {
    $mensagem_erro = '<p class="mensagem-erro">Erro ao buscar consultas: ' . $mysqli->error . '</p>';
}

$feedback_sucesso = $_SESSION['feedback_sucesso'] ?? '';
$feedback_erro = $_SESSION['feedback_erro'] ?? '';
unset($_SESSION['feedback_sucesso'], $_SESSION['feedback_erro']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas as Consultas | Clínica Juqueri - Admin</title>
    <link rel="stylesheet" href="../estilo.css"> 
    <style>
        .tabela-consultas-admin {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .tabela-consultas-admin th, .tabela-consultas-admin td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        .tabela-consultas-admin th {
            background-color: var(--cor-fundo-claro); 
            font-weight: 600;
        }
        .status-ATIVA { color: var(--cor-saude); font-weight: bold; }
        .status-CANCELADA { color: #dc3545; font-weight: bold; }
        .status-CONCLUIDA { color: #6c757d; }
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
            <a href="../logout.php" style="background-color: #dc3545; padding: 5px 15px; border-radius: 4px;">Sair</a>
        </nav>
    </div>
</header>

<main class="conteudo-principal-centralizado">

    <div class="form-container" style="max-width: 1100px;">
        <h2>Gerenciamento de Consultas</h2>
        <p>Visualização e controle de todos os agendamentos da clínica.</p>

        <?= $mensagem_erro ?>
        <?php if ($feedback_sucesso): ?>
            <p class="mensagem-sucesso"><?= $feedback_sucesso ?></p>
        <?php endif; ?>
        <?php if ($feedback_erro): ?>
            <p class="mensagem-erro"><?= $feedback_erro ?></p>
        <?php endif; ?>

        <?php if (empty($consultas)): ?>
            <p>Nenhuma consulta encontrada no sistema.</p>
        <?php else: ?>

            <table class="tabela-consultas-admin">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Especialidade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($consultas as $c): 
                        $data_formatada = date('d/m/Y', strtotime($c['data']));
                        $hora_formatada = date('H:i', strtotime($c['hora']));
                        $status_upper = strtoupper($c['status']);
                        $status_class = "status-$status_upper";
                        $data_hora_consulta = new DateTime($c['data'] . ' ' . $c['hora']);
                        $data_atual = new DateTime();
                    ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= $data_formatada ?></td>
                            <td><?= $hora_formatada ?></td>
                            <td><?= htmlspecialchars($c['paciente']) ?></td>
                            <td><?= htmlspecialchars($c['medico']) ?></td>
                            <td><?= htmlspecialchars($c['especialidade']) ?></td>
                            <td class="<?= $status_class ?>"><?= $status_upper ?></td>
                            <td>
                                <?php if ($status_upper === 'ATIVA' && $data_hora_consulta > $data_atual): ?>
                                    <a href="../cancelar.php?id=<?= $c['id'] ?>" class="btn-cancelar">Cancelar</a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

</main>

<footer class="footer-padrao">
    <span>&copy; Clínica Juqueri. Todos os direitos reservados.</span>
</footer>

</body>
</html>
