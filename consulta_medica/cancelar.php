<?php
session_start();
include "conexao.php"; 

if (!isset($_SESSION['paciente_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: paciente/paciente_login.php");
    exit();
}

$is_paciente = isset($_SESSION['paciente_id']);
$redirecionar_para = $is_paciente ? "paciente/minhas_consultas.php" : "admin/admin_consultas.php";
$usuario_id = $is_paciente ? $_SESSION['paciente_id'] : null;

$consulta_id = $_GET['id'] ?? null;
$mensagem_feedback = "";
$sucesso = false;

if ($consulta_id) {

    $sql_update = "UPDATE consultas SET status = 'CANCELADA' 
                   WHERE id = ?";

    if ($is_paciente) {
        $sql_update .= " AND paciente_id = ?";
    }

    if ($stmt = $mysqli->prepare($sql_update)) {
        
        if ($is_paciente) {
            $stmt->bind_param("ii", $consulta_id, $usuario_id);
        } else {
            $stmt->bind_param("i", $consulta_id);
        }
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensagem_feedback = "A consulta (ID {$consulta_id}) foi cancelada com sucesso.";
                $sucesso = true;
            } else {
                $mensagem_feedback = "Erro: A consulta não pôde ser cancelada.";
            }
        } else {
            $mensagem_feedback = "Erro de execução no banco de dados: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $mensagem_feedback = "Erro na preparação da query de cancelamento.";
    }
} else {
    $mensagem_feedback = "Ação de cancelamento inválida: ID da consulta não fornecido.";
}

if ($sucesso) {
    $_SESSION['feedback_sucesso'] = $mensagem_feedback;
} else {
    $_SESSION['feedback_erro'] = $mensagem_feedback;
}

header("Location: " . $redirecionar_para);
exit();
?>
