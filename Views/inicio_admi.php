<?php
// Garante que o usuário está logado e é um admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_cargo'] ?? '', ['PROPRIETARIO', 'GERENTE_FINANCEIRO'])) {
    header("Location: Index.php?acao=login_mostrar");
    exit;
}

$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Admin';
$cargo = $_SESSION['usuario_cargo'] ?? 'Admin';

// Buscar estatísticas básicas
require_once __DIR__ . '/../Models/ConexaoDB.php';

$pdo = ConexaoDB::getConnection();

// Total de clientes
$stmt = $pdo->query("SELECT COUNT(*) as total FROM Cliente");
$total_clientes = $stmt->fetch()['total'] ?? 0;

// Total de funcionários
$stmt = $pdo->query("SELECT COUNT(*) as total FROM Funcionario");
$total_funcionarios = $stmt->fetch()['total'] ?? 0;

// Total de serviços
$stmt = $pdo->query("SELECT COUNT(*) as total FROM Servico");
$total_servicos = $stmt->fetch()['total'] ?? 0;

// Total de agendamentos
$stmt = $pdo->query("SELECT COUNT(*) as total FROM Agendamento");
$total_agendamentos = $stmt->fetch()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - MyBeauty</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-header__greeting">
                <h1>Olá, <?php echo htmlspecialchars($nomeUsuario); ?>! <i data-lucide="crown" style="width: 2rem; height: 2rem; display: inline-block; vertical-align: middle;"></i></h1>
                <p>Painel Administrativo MyBeauty • <?php echo htmlspecialchars($cargo); ?></p>
            </div>
            <div class="dashboard-header__actions">
                <a href="Index.php?acao=logout" class="btn-header btn-header--primary">
                    <i data-lucide="log-out"></i>
                    <span>Sair</span>
                </a>
            </div>
        </div>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-header__greeting">
                <h1>Olá, <?php echo htmlspecialchars($nomeUsuario); ?>! <i data-lucide="crown" style="width: 2rem; height: 2rem; display: inline-block; vertical-align: middle;"></i></h1>
                <p>Painel Administrativo MyBeauty • <?php echo htmlspecialchars($cargo); ?></p>
            </div>
            <div class="dashboard-header__actions">
                <a href="Index.php?acao=logout" class="btn-header btn-header--secondary">
                    <i data-lucide="log-out"></i>
                    <span>Sair</span>
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="users"></i></div>
                <div class="stat-card__value"><?php echo $total_clientes; ?></div>
                <div class="stat-card__label">Total de Clientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="user-check"></i></div>
                <div class="stat-card__value"><?php echo $total_funcionarios; ?></div>
                <div class="stat-card__label">Funcionários</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="sparkles"></i></div>
                <div class="stat-card__value"><?php echo $total_servicos; ?></div>
                <div class="stat-card__label">Serviços</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="calendar"></i></div>
                <div class="stat-card__value"><?php echo $total_agendamentos; ?></div>
                <div class="stat-card__label">Agendamentos</div>
            </div>
        </div>

        <div class="admin-grid">
            <a href="Index.php?acao=servico_listar" class="admin-card">
                <div class="admin-card__icon"><i data-lucide="sparkles"></i></div>
                <h3 class="admin-card__title">Gerenciar Serviços</h3>
                <p class="admin-card__description">Adicione, edite ou remova serviços oferecidos pelo salão</p>
            </a>

            <a href="Index.php?acao=funcionario_listar" class="admin-card">
                <div class="admin-card__icon"><i data-lucide="user-check"></i></div>
                <h3 class="admin-card__title">Gerenciar Profissionais</h3>
                <p class="admin-card__description">Gerencie funcionários e profissionais do salão</p>
            </a>

            <a href="Index.php?acao=cliente_listar" class="admin-card">
                <div class="admin-card__icon"><i data-lucide="users"></i></div>
                <h3 class="admin-card__title">Gerenciar Clientes</h3>
                <p class="admin-card__description">Visualize e gerencie o cadastro de clientes</p>
            </a>
        </div>
    </div>
    <script>
        // Inicializar ícones Lucide após o DOM carregar
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
