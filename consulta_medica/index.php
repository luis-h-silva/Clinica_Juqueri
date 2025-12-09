<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Juqueri | Agendamento Online</title>
    <link rel="stylesheet" href="estilo.css"> 
</head>
<body class="tela">

    <header class="header-padrao">
        <div class="header-conteudo">
            <a href="index.php" class="logo-link">
                <img src="logo.png" alt="Logo Clínica Juqueri" class="logo-header">
                Clínica Juqueri
            </a>
            <nav class="links-navegacao">
                <a href="index.php">Início</a>
                <a href="./paciente/paciente_login.php">Área do Paciente</a>
            </nav>
        </div>
    </header>
    
    <div class="banner">
        <center><img src="cj.png" alt="Banner Clínica Juqueri" class="capa"></center>
    </div>

    <main class="conteudo-principal-centralizado">
        
        <h2>Bem-vindo(a) ao Sistema de Agendamento da Clínica Juqueri</h2>
        <p>Acesse sua área para agendar ou consultar seus atendimentos de forma rápida e segura.</p>

        <div class="botoes-iniciais">
            
            <a href="./paciente/paciente_login.php" class="botao">
                Área do Paciente (Login/Cadastro)
            </a>
            
            <a href="./admin/admin_login.php" class="botao botao-administrativo">
                Área Administrativa
            </a>

        </div>

    </main>
    
    <footer class="footer-padrao">
        <span>&copy; Clínica Juqueri. Todos os direitos reservados.</span>
    </footer>

</body>
</html>