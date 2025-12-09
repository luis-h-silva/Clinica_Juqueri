<?php

session_start();
include "../conexao.php"; 

if (!isset($mysqli) || $mysqli->connect_error) {
    die("Erro Fatal: Serviço indisponível. Por favor, tente novamente mais tarde.");
}

$erro_login = "";

if (isset($_POST['usuario'], $_POST['senha'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $sql = "SELECT id FROM admin WHERE usuario = ? AND senha = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ss", $usuario, $senha);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows === 1) {
            $dados = $res->fetch_assoc();
            $_SESSION['admin_id'] = $dados['id'];
            
            header("Location: admin_home.php");
            exit();
        } else {
            $erro_login = '<p class="mensagem-erro">Usuário ou senha incorretos. Tente novamente.</p>';
        }
        $stmt->close();
    } else {
        $erro_login = '<p class="mensagem-erro">Erro interno. Contate o suporte.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo | Clínica Juqueri</title>
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
            <h2>Login Administrativo</h2>
            
            <?php echo $erro_login; // Exibe a mensagem de erro de login ?>

            <form method="POST" action="admin_login.php"> 
                
                <div class="input-group">
                    <label for="usuario">Usuário:</label>
                    <input type="text" id="usuario" name="usuario" required autofocus>
                </div>

                <div class="input-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <button type="submit" class="btn-submit">Entrar no Sistema</button>
            </form>
            
            <div class="form-links" style="margin-top: 20px;">
                <a href="../index.php">Voltar à Pagina Inicial</a>
            </div>

        </div> 
    </main>

    <footer class="footer-padrao">
        <span>&copy; Clínica Juqueri. Todos os direitos reservados.</span>
    </footer>

</body>
</html>