<?php
session_start();
include "../conexao.php"; 

// 1. VERIFICAÇÃO DE SESSÃO
if (!isset($_SESSION['paciente_id'])) {
    header("Location: paciente_login.php");
    exit();
}

$paciente_id = $_SESSION['paciente_id'];
$paciente_nome = $_SESSION['paciente_nome'] ?? "Paciente";
$consultas = [];
$mensagem = "";

// 2. BUSCA DE CONSULTAS DO PACIENTE
try {
    $sql = "SELECT
                c.id AS consulta_id,
                c.data,
                c.hora,
                c.status,
                m.nome AS medico_nome,
                e.nome AS especialidade_nome
            FROM consultas c
            JOIN medicos m ON c.medico_id = m.id
            JOIN especialidades e ON m.especialidade_id = e.id
            WHERE c.paciente_id = ?
            ORDER BY c.data DESC, c.hora DESC";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $paciente_id);
        $stmt->execute();
        $res = $stmt->get_result();
        
        while ($row = $res->fetch_assoc()) {
            $consultas[] = $row;
        }
        $stmt->close();
    } else {
        throw new Exception("Erro na preparação da consulta: " . $mysqli->error);
    }
} catch (Exception $e) {
    $mensagem = '<p class="mensagem-erro">Erro ao carregar suas consultas: ' . $e->getMessage() . '</p>';
}

// 3. FEEDBACK
$feedback_sucesso = $_SESSION['feedback_sucesso'] ?? '';
$feedback_erro = $_SESSION['feedback_erro'] ?? '';
unset($_SESSION['feedback_sucesso'], $_SESSION['feedback_erro']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Consultas | Clínica Juqueri</title>
    <link rel="stylesheet" href="../estilo.css">
    <style>
        .tabela-consultas {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .tabela-consultas th, .tabela-consultas td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .tabela-consultas th {
            background-color: #f2f2f2;
            color: var(--cor-texto-principal);
        }
        .status-ATIVA { color: green; font-weight: bold; }
        .status-CANCELADA { color: red; font-weight: bold; }
        .status-CONCLUIDA { color: gray; }
    </style>
</head>
<body class="tela">

<header class="header-padrao">
    <div class="header-conteudo">
        <a href="paciente_home.php" class="logo-link">
            <img src="../logo.png" alt="Logo Clínica Juqueri" class="logo-header">
            Clínica Juqueri
        </a>
        <nav class="links-navegacao">
            <a href="paciente_home.php">Home (<?= htmlspecialchars($paciente_nome) ?>)</a>
            <a href="marcar_consulta.php">Agendar Nova Consulta</a>
            <a href="../logout.php" style="background-color: #dc3545; padding: 5px 15px; border-radius: 4px;">Sair</a>
        </nav>
    </div>
</header>

<main class="conteudo-principal-centralizado">
    <div class="form-container" style="max-width: 900px;">
        <h2>Minhas Consultas</h2>

        <?php 
            if ($feedback_sucesso) echo '<p class="mensagem-sucesso">'.$feedback_sucesso.'</p>';
            if ($feedback_erro) echo '<p class="mensagem-erro">'.$feedback_erro.'</p>';
            echo $mensagem;
        ?>

        <?php if (empty($consultas)): ?>
            <p>Você ainda não possui consultas agendadas.</p>
            <p><a href="marcar_consulta.php">Clique aqui para marcar uma consulta.</a></p>
        <?php else: ?>

        <table class="tabela-consultas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Especialidade</th>
                    <th>Médico</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($consultas as $c): 
                    $status_upper = strtoupper($c['status']);
                    $status_class = "status-" . $status_upper;
                ?>
                <tr>
                    <td><?= $c['consulta_id'] ?></td>
                    <td><?= date('d/m/Y', strtotime($c['data'])) ?></td>
                    <td><?= date('H:i', strtotime($c['hora'])) ?></td>
                    <td><?= htmlspecialchars($c['especialidade_nome']) ?></td>
                    <td><?= htmlspecialchars($c['medico_nome']) ?></td>

                    <td class="<?= $status_class ?>"><?= $status_upper ?></td>

                    <td>
                        <?php if ($status_upper == 'ATIVA'): ?>
                            <a href="../cancelar.php?id=<?= $c['consulta_id'] ?>" class="btn-cancelar">Cancelar</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
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
