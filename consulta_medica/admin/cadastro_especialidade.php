<?php
session_start();
include "../conexao.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_nome = $_SESSION['admin_nome'] ?? "Administrador"; 
$mensagem = "";
$especialidades = [];

if (isset($_POST['nome_especialidade'])) {
    
    $nome = trim($_POST['nome_especialidade']);
    
    if (empty($nome)) {
         $mensagem = '<p class="mensagem-erro">O nome da especialidade não pode ser vazio.</p>';
    } else {

        $sql_check = "SELECT id FROM especialidades WHERE UPPER(nome) = UPPER(?)";
        if ($stmt_check = $mysqli->prepare($sql_check)) {
            $stmt_check->bind_param("s", $nome);
            $stmt_check->execute();
            $res_check = $stmt_check->get_result();
            
            if ($res_check->num_rows > 0) {
                $mensagem = '<p class="mensagem-erro">Erro: Especialidade "' . htmlspecialchars($nome) . '" já cadastrada.</p>';
            } else {
 
                $sql_insert = "INSERT INTO especialidades (nome) VALUES (?)";
                if ($stmt_insert = $mysqli->prepare($sql_insert)) {
                    $stmt_insert->bind_param("s", $nome);
                    
                    if ($stmt_insert->execute()) {
                        $mensagem = '<p class="mensagem-sucesso">Especialidade "' . htmlspecialchars($nome) . '" cadastrada com sucesso!</p>';
                    } else {
                        $mensagem = '<p class="mensagem-erro">Erro ao cadastrar: ' . $mysqli->error . '</p>';
                    }
                    $stmt_insert->close();
                } else {
                    $mensagem = '<p class="mensagem-erro">Erro na preparação da inserção.</p>';
                }
            }
            $stmt_check->close();
        }
    }
}

$sql_esp = "SELECT id, nome FROM especialidades ORDER BY nome ASC";
if ($res_esp = $mysqli->query($sql_esp)) {
    while ($row = $res_esp->fetch_assoc()) {
        $especialidades[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Especialidades | Clínica Juqueri - Admin</title>
    <link rel="stylesheet" href="../estilo.css">
    <style>
        /* Estilos da Tabela de Especialidades */
        .tabela-especialidades {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .tabela-especialidades th, .tabela-especialidades td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        .tabela-especialidades th {
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
        
        <div class="form-container" style="max-width: 600px;">
            <h2>Cadastro de Especialidade</h2>
            
            <?php echo $mensagem; // Exibe feedback de inserção/erro ?>

            <form method="POST" action="cadastro_especialidade.php"> 
                
                <div class="input-group">
                    <label for="nome_especialidade">Nome da Especialidade:</label>
                    <input 
                        type="text" 
                        id="nome_especialidade" 
                        name="nome_especialidade" 
                        placeholder="Ex: Cardiologia" 
                        required
                    >
                </div>
                
                <button type="submit" class="btn-submit">Cadastrar Especialidade</button>
            </form>
        </div>
        
        <div class="form-container" style="max-width: 600px; margin-top: 40px;">
            <h3>Especialidades Cadastradas</h3>
            
            <?php if (empty($especialidades)): ?>
                <p>Nenhuma especialidade cadastrada no momento.</p>
            <?php else: ?>

                <table class="tabela-especialidades">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome da Especialidade</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php foreach($especialidades as $esp): ?>
                            <tr>
                                <td><?= $esp['id'] ?></td>
                                <td><?= htmlspecialchars($esp['nome']) ?></td>
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