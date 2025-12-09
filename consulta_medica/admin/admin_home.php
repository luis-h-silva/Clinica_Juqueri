<?php
session_start();
include "../conexao.php"; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_nome = $_SESSION['admin_nome'] ?? "Administrador"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo | Clínica Juqueri</title>
    <link rel="stylesheet" href="../estilo.css"> 
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            width: 100%;
            max-width: 1000px;
            margin-top: 40px;
        }

        .dashboard-card {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card h3 {
            color: var(--cor-principal);
            font-size: 1.4em;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--cor-saude);
            padding-bottom: 10px;
        }

        .dashboard-card a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            text-decoration: none;
            color: white;
            background-color: var(--cor-secundaria);
            border-radius: 4px;
            transition: background-color 0.2s;
            font-weight: 600;
        }

        .dashboard-card a:hover {
            background-color: #0069d9;
        }

        .saudacao-admin {
            color: var(--cor-texto-principal);
            font-size: 1.8em;
            margin-bottom: 10px;
            width: 100%;
            text-align: left;
            max-width: 1000px;
            padding: 0 20px;
            box-sizing: border-box;
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
                <a href="admin_consultas.php">Consultas</a>
                <a href="../logout.php" style="background-color: #dc3545; padding: 5px 15px; border-radius: 4px;">Sair</a>
            </nav>
        </div>
    </header>

    <main class="conteudo-principal-centralizado">
        
        <h1 class="saudacao-admin">Bem-vindo(a), <?php echo htmlspecialchars($admin_nome); ?>.</h1>
        <p>Utilize o painel abaixo para gerenciar o sistema de agendamento.</p>

        <div class="dashboard-grid">
            
            <div class="dashboard-card">
                <h3>Agendamentos e Pacientes</h3>
                <p>Visualize todos os agendamentos e localize pacientes.</p>
                <a href="admin_consultas.php">Ver Todas as Consultas</a>
                <a href="buscar_paciente.php">Buscar Paciente</a>
            </div>

            <div class="dashboard-card">
                <h3>Cadastro de Médicos</h3>
                <p>Cadastre novos profissionais e consulte a lista atual.</p>
                <a href="cadastro_medico.php">Gerenciar Médicos</a> 
            </div>

            <div class="dashboard-card">
                <h3>Especialidades</h3>
                <p>Adicione ou edite as especialidades oferecidas pela clínica.</p>
                <a href="cadastro_especialidade.php">Gerenciar Especialidades</a>
            </div>

        </div> </main>

    <footer class="footer-padrao">
        <span>&copy; Clínica Juqueri. Todos os direitos reservados.</span>
    </footer>

</body>
</html>