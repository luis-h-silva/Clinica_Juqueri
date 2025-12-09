<?php
session_start();
include "../conexao.php"; 

if (!isset($mysqli) || $mysqli->connect_error) {
    die("Erro Fatal: Serviço indisponível. Por favor, tente novamente mais tarde.");
}

$erro_login = "";

if (isset($_POST['email'], $_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $sql = "SELECT id, nome FROM pacientes WHERE email = ? AND senha = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ss", $email, $senha);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows === 1) {
            $dados = $res->fetch_assoc();
            $_SESSION['paciente_id'] = $dados['id'];
            $_SESSION['paciente_nome'] = $dados['nome'];
            
            header("Location: paciente_home.php");
            exit();
        } else {
            $erro_login = '<p class="mensagem-erro">Email ou senha incorretos. Verifique suas credenciais.</p>';
        }
        $stmt->close();
    } else {
        $erro_login = '<p class="mensagem-erro">Erro interno na consulta.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Paciente | Clínica Juqueri</title>
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
                <a href="../index.php">Voltar ao Início</a>
            </nav>
        </div>
    </header>

    <main class="conteudo-principal-centralizado">
        
        <div class="form-container">
            <h2>Área do Paciente</h2>
            
            <?php echo $erro_login; // Exibe a mensagem de erro de login ?>

            <form method="POST" action="paciente_login.php"> 
                
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>

                <div class="input-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <button type="submit" class="btn-submit">Fazer Login</button>
            </form>
            
            <div class="form-links">
                Ainda não tem cadastro? <a href="paciente_cadastro.php">Cadastre-se aqui</a>.
            </div>

        </div> </main>

    <footer class="footer-padrao">
        <span>&copy; Clínica Juqueri. Todos os direitos reservados.</span>
    </footer>

</body>
</html>