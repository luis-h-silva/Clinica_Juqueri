<?php
session_start();
include "../conexao.php"; 

if (!isset($mysqli) || $mysqli->connect_error) {
    die("Erro Fatal: Serviço indisponível. Por favor, tente novamente mais tarde.");
}

$mensagem = "";

if (isset($_POST['nome'], $_POST['email'], $_POST['senha'])) {
    
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha']; 
    
    $sql_check = "SELECT id FROM pacientes WHERE email = ?";
    
    if ($stmt_check = $mysqli->prepare($sql_check)) {
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $res_check = $stmt_check->get_result();
        
        if ($res_check->num_rows > 0) {
            $mensagem = '<p class="mensagem-erro">Este email já está cadastrado. Tente fazer login ou use outro email.</p>';
        } else {
            $sql_insert = "INSERT INTO pacientes (nome, email, senha) VALUES (?, ?, ?)";
            
            if ($stmt_insert = $mysqli->prepare($sql_insert)) {
                $stmt_insert->bind_param("sss", $nome, $email, $senha);
                
                if ($stmt_insert->execute()) {
                    $_SESSION['paciente_id'] = $mysqli->insert_id;
                    $_SESSION['paciente_nome'] = $nome; 
                    
                    $mensagem = '<p class="mensagem-sucesso">Cadastro realizado com sucesso! Você será redirecionado para a área de login.</p>';
                    header("Refresh:3; url=paciente_login.php"); 
                    exit();
                } else {
                    $mensagem = '<p class="mensagem-erro">Erro ao cadastrar: ' . $stmt_insert->error . '</p>';
                }
                $stmt_insert->close();
            } else {
                $mensagem = '<p class="mensagem-erro">Erro na preparação da inserção: ' . $mysqli->error . '</p>';
            }
        }
        $stmt_check->close();
    } else {
         $mensagem = '<p class="mensagem-erro">Erro na preparação da verificação de email: ' . $mysqli->error . '</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente | Clínica Juqueri</title>
    <link rel="stylesheet" href="../estilo.css">
    </head>
<body class="tela">

    <header class="header-padrao">
        <div class="header-conteudo">
            <a href="../index.php" class="logo-link">
                <img src="../logo.png" alt="Logo Clínica Juqueri" class="logo-header">
                Clínica Juqueri
            </a>
            <nav class="links-navegacao">
                <a href="paciente_login.php">Fazer Login</a>
            </nav>
        </div>
    </header>

    <main class="conteudo-principal-centralizado">
        
        <div class="form-container">
            <h2>Novo Cadastro</h2>
            
            <?php echo $mensagem; // Exibe a mensagem de feedback ?>

            <form method="POST" action="paciente_cadastro.php"> 
                
                <div class="input-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required autofocus>
                </div>

                <div class="input-group">
                    <label for="email">Email (Será seu Login):</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="senha">Criar Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <button type="submit" class="btn-submit">Cadastrar e Entrar</button>
            </form>
            
            <div class="form-links">
                Já possui uma conta? <a href="paciente_login.php">Acesse aqui</a>.
            </div>

        </div> 
    </main>

    <footer class="footer-padrao">
        <span>&copy; Clínica Juqueri. Todos os direitos reservados.</span>
    </footer>

</body>
</html>