<?php
/**
 * View: Dashboard do Administrador
 * Recebe dados processados do UsuarioController::mostrarDashboardAdmin()
 * 
 * Variáveis esperadas:
 * - $dados['nomeUsuario']
 * - $dados['cargo']
 * - $dados['total_clientes']
 * - $dados['total_funcionarios']
 * - $dados['total_servicos']
 * - $dados['total_agendamentos']
 */

// Extrai os dados fornecidos pelo Controller
$nomeUsuario = $dados['nomeUsuario'] ?? 'Admin';
$cargo = $dados['cargo'] ?? 'Admin';
$total_clientes = $dados['total_clientes'] ?? 0;
$total_funcionarios = $dados['total_funcionarios'] ?? 0;
$total_servicos = $dados['total_servicos'] ?? 0;
$total_agendamentos = $dados['total_agendamentos'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - MyBeauty</title>
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="../../assets/js/navbar.js"></script>
</head>
<body>
    <!-- Burger Menu Overlay -->
    <div class="burger-menu-overlay" onclick="if(!document.body.classList.contains('menu-pinned')) { toggleBurgerMenu(); }"></div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <button class="navbar-burger" onclick="toggleBurgerMenu()" aria-label="Menu">
                    <i data-lucide="menu"></i>
                </button>
                <a href="Index.php?acao=inicio" class="navbar-brand">
                    <div class="navbar-brand__icon"><i data-lucide="sparkles"></i></div>
                    <span>MyBeauty</span>
                </a>
            </div>
            <div class="burger-menu">
                <div class="burger-menu__header">
                    <span class="burger-menu__title">Menu Admin</span>
                    <button class="burger-menu__pin" onclick="togglePinMenu()" aria-label="Fixar Menu">
                        <i data-lucide="pin"></i>
                    </button>
                </div>
                <div class="burger-menu__content">
                    <a href="Index.php?acao=inicio" class="burger-menu__item">
                        <i data-lucide="home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="burger-menu__item">
                        <i data-lucide="clipboard-list"></i>
                        <span>Gerenciar Agendamentos</span>
                    </a>
                    <a href="Index.php?acao=servico_listar" class="burger-menu__item">
                        <i data-lucide="sparkles"></i>
                        <span>Serviços</span>
                    </a>
                    <a href="Index.php?acao=funcionario_listar" class="burger-menu__item">
                        <i data-lucide="user-check"></i>
                        <span>Profissionais</span>
                    </a>
                    <a href="Index.php?acao=cliente_listar" class="burger-menu__item">
                        <i data-lucide="users"></i>
                        <span>Clientes</span>
                    </a>
                    <div class="burger-menu__divider"></div>
                    <a href="Index.php?acao=logout" class="burger-menu__item">
                        <i data-lucide="log-out"></i>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
            
            <div class="navbar-actions">
                <a href="Index.php?acao=servico_listar" class="btn-header btn-header--primary">
                    <i data-lucide="sparkles"></i>
                    <span>Serviços</span>
                </a>
                <div class="navbar-user" onclick="toggleUserMenu()">
                    <div class="navbar-user__avatar">
                        <?php echo strtoupper(substr($nomeUsuario, 0, 1)); ?>
                    </div>
                    <div class="navbar-user__info">
                        <div class="navbar-user__name"><?php echo htmlspecialchars(explode(' ', $nomeUsuario)[0]); ?></div>
                        <div class="navbar-user__role">Admin</div>
                    </div>
                    <div class="navbar-user__dropdown">
                        <a href="#" class="navbar-user__dropdown-item">
                            <i data-lucide="user"></i>
                            <span>Meu Perfil</span>
                        </a>
                        <div class="navbar-user__dropdown-divider"></div>
                        <a href="Index.php?acao=logout" class="navbar-user__dropdown-item">
                            <i data-lucide="log-out"></i>
                            <span>Sair</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="dashboard-header__greeting">
                <h1>Olá, <?php echo htmlspecialchars($nomeUsuario); ?>! <i data-lucide="crown" class="inline-icon"></i></h1>
                <p>Painel Administrativo MyBeauty • <?php echo htmlspecialchars($cargo); ?></p>
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
</body>
</html>
