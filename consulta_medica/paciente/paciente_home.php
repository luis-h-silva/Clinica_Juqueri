<?php
session_start();
include "../conexao.php"; 

if (!isset($_SESSION['paciente_id'])) {
    header("Location: paciente_login.php");
    exit();
}

$paciente_id = $_SESSION['paciente_id'];
$mensagem = "";
$paciente_nome = $_SESSION['paciente_nome'] ?? "Paciente";

$especialidades = [];
$sql_esp = "SELECT id, nome FROM especialidades ORDER BY nome";
$res_esp = $mysqli->query($sql_esp);

if ($res_esp) {
    while ($row = $res_esp->fetch_assoc()) {
        $especialidades[] = $row;
    }
} else {
    $mensagem .= '<p class="mensagem-erro">Erro ao carregar especialidades: ' . $mysqli->error . '</p>';
}

$medicos = [];
$sql_med = "SELECT id, nome, especialidade_id FROM medicos ORDER BY nome";
$res_med = $mysqli->query($sql_med);

if ($res_med) {
    while ($row = $res_med->fetch_assoc()) {
        $medicos[] = $row;
    }
} else {
    $mensagem .= '<p class="mensagem-erro">Erro ao carregar médicos: ' . $mysqli->error . '</p>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['medico_id'], $_POST['data_consulta'], $_POST['hora_consulta'])) {
    
    $medico_id = $mysqli->real_escape_string($_POST['medico_id']);
    $data_consulta = $mysqli->real_escape_string($_POST['data_consulta']);
    $hora_consulta = $mysqli->real_escape_string($_POST['hora_consulta']);
    $datahora_agendada = "{$data_consulta} {$hora_consulta}";

    $sql_conflito = "SELECT paciente_id FROM consultas 
                     WHERE medico_id = ? AND data_hora = ?";
    
    if ($stmt_conflito = $mysqli->prepare($sql_conflito)) {
        $stmt_conflito->bind_param("is", $medico_id, $datahora_agendada);
        $stmt_conflito->execute();
        $res_conflito = $stmt_conflito->get_result();
        
        if ($res_conflito->num_rows > 0) {
            
            // Conflito Encontrado!
            $dados_conflito = $res_conflito->fetch_assoc();
            
            // VALIDAÇÃO 2: USUÁRIO JÁ TEM CONSULTA NESTE HORÁRIO (Requisito 2)
            if ($dados_conflito['paciente_id'] == $paciente_id) {
                $mensagem = '<p class="mensagem-erro">Você já tem uma consulta agendada para este médico, data e hora.</p>';
            } else {
                // VALIDAÇÃO 3: OUTRO USUÁRIO JÁ TEM CONSULTA NESTE HORÁRIO (Requisito 3)
                $mensagem = '<p class="mensagem-erro">O horário selecionado ('.date('d/m/Y H:i', strtotime($datahora_agendada)).') não está mais disponível para este médico.</p>';
            }
            $stmt_conflito->close();
            
        } else {
            // Se NÃO HÁ CONFLITO, realiza a inserção
            $stmt_conflito->close();
            
            // INSERÇÃO DA NOVA CONSULTA (CORRIGIDO: Usando 'consultas')
            $sql_insert = "INSERT INTO consultas (paciente_id, medico_id, data_hora, status) 
                           VALUES (?, ?, ?, 'Agendado')";
            
            if ($stmt_insert = $mysqli->prepare($sql_insert)) {
                $stmt_insert->bind_param("iis", $paciente_id, $medico_id, $datahora_agendada);
                
                if ($stmt_insert->execute()) {
                    $mensagem = '<p class="mensagem-sucesso">Sua consulta foi agendada com sucesso para '.date('d/m/Y H:i', strtotime($datahora_agendada)).'!</p>';
                    header("Refresh:3; url=minhas_consultas.php"); 
                } else {
                    $mensagem = '<p class="mensagem-erro">Erro ao agendar: ' . $mysqli->error . '</p>';
                }
                $stmt_insert->close();
            } else {
                $mensagem = '<p class="mensagem-erro">Erro na preparação da inserção da consulta.</p>';
            }
        }
    } else {
        $mensagem = '<p class="mensagem-erro">Erro na preparação da validação de conflito.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Consulta | Clínica Juqueri</title>
    <link rel="stylesheet" href="../estilo.css">
    </head>
<body class="tela">

    <header class="header-padrao">
        <div class="header-conteudo">
            <a href="paciente_home.php" class="logo-link">
                <img src="../logo.png" alt="Logo Clínica Juqueri" class="logo-header">
                Clínica Juqueri
            </a>
            <nav class="links-navegacao">
                <a href="paciente_home.php">Home (<?php echo htmlspecialchars($paciente_nome); ?>)</a>
                <a href="minhas_consultas.php">Minhas Consultas</a>
                <a href="../logout.php" style="background-color: #dc3545; padding: 5px 15px; border-radius: 4px;">Sair</a>
            </nav>
        </div>
    </header>

    <main class="conteudo-principal-centralizado">
        
        <div class="form-container" style="max-width: 500px;">
            <h2>Agendar Nova Consulta</h2>
            <p>Selecione a especialidade e o horário desejado.</p>
            
            <?php echo $mensagem; // Exibe a mensagem de feedback ?>

            <form method="POST" action="marcar_consulta.php"> 
                
                <div class="input-group">
                    <label for="especialidade_id">Especialidade Médica:</label>
                    <select id="especialidade_id" name="especialidade_id" required> 
                        <option value="">-- Selecione a Especialidade --</option>
                        <?php foreach ($especialidades as $esp): ?>
                            <option value="<?php echo $esp['id']; ?>">
                                <?php echo htmlspecialchars($esp['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="input-group">
                    <label for="medico_id">Médico:</label>
                    <select id="medico_id" name="medico_id" required>
                        <option value="">-- Selecione o Médico --</option>
                        <?php 
                        // Exibe todos os médicos. O filtro por especialidade deve ser feito via JS/AJAX.
                        foreach ($medicos as $med): ?>
                            <option value="<?php echo $med['id']; ?>" data-especialidade="<?php echo $med['especialidade_id']; ?>">
                                <?php echo htmlspecialchars($med['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-group">
                    <label for="data_consulta">Data da Consulta:</label>
                    <input type="date" id="data_consulta" name="data_consulta" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="input-group">
                    <label for="hora_consulta">Hora da Consulta:</label>
                    <input type="time" id="hora_consulta" name="hora_consulta" required list="horarios-disponiveis">
                    <datalist id="horarios-disponiveis">
                        <option value="08:00:00">08:00</option>
                        <option value="09:00:00">09:00</option>
                        <option value="10:00:00">10:00</option>
                        <option value="11:00:00">11:00</option>
                        <option value="14:00:00">14:00</option>
                        <option value="15:00:00">15:00</option>
                        <option value="16:00:00">16:00</option>
                    </datalist>
                </div>
                
                <button type="submit" class="btn-submit">Confirmar Agendamento</button>
            </form>

        </div>