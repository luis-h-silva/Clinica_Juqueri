<?php
session_start();
include "../conexao.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_nome = $_SESSION['admin_nome'] ?? "Administrador"; 
$mensagem = "";
$pacientes = [];
$termo_busca = "";

if (isset($_GET['busca'])) {
    $termo_busca = trim($_GET['busca']);

    $sql = "SELECT id, nome, email FROM pacientes WHERE nome LIKE ? ORDER BY nome ASC";
    
    if ($stmt = $mysqli->prepare($sql)) {

        $termo_busca_param = "%" . $termo_busca . "%";
        
        $stmt->bind_param("s", $termo_busca_param);
        $stmt->execute();
        $res = $stmt->get_result();
        
        while ($row = $res->fetch_assoc()) {
            $pacientes[] = $row;
        }
        $stmt->close();

        if (empty($pacientes) && !empty($termo_busca)) {
            $mensagem = '<p class="mensagem-erro">Nenhum paciente encontrado com o termo "' . htmlspecialchars($termo_busca) . '".</p>';
        }
    } else {
        $mensagem = '<p class="mensagem-erro">Erro na preparação da busca: ' . $mysqli->error . '</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Paciente | Clínica Juqueri - Admin</title>
    <link rel="stylesheet" href="../estilo.css"> 
    <style>
        /* Estilo da Tabela de Resultados */
        .tabela-pacientes {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .tabela-pacientes th, .tabela-pacientes td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        .tabela-pacientes th {
            background-color: var(--cor-fundo-claro);
            font-weight: 600;
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
        
        <div class="form-container" style="max-width: 800px;">
            <h2>Buscar Paciente por Nome</h2>
            <p>Digite parte do nome ou o nome completo para localizar o cadastro.</p>
            
            <?php echo $mensagem; // Exibe mensagem de erro na busca ?>

            <form method="GET" action="buscar_paciente.php" style="display: flex; gap: 10px; margin-bottom: 20px;">
                <input 
                    type="search" 
                    id="busca" 
                    name="busca" 
                    value="<?= htmlspecialchars($termo_busca) ?>"
                    placeholder="Digite o nome ou sobrenome do paciente..."
                    required
                    style="flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
                >
                <button type="submit" class="btn-submit" style="width: auto;">Buscar</button>
            </form>

            <?php if (!empty($pacientes)): ?>
                
                <h3>Resultados da Busca</h3>
                <p>Encontrados <?= count($pacientes) ?> paciente(s).</p>

                <table class="tabela-pacientes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pacientes as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                <td><?= htmlspecialchars($p['email']) ?></td>
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