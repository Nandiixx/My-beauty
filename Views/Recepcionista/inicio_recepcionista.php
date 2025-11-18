<?php
/**
 * View: Dashboard da Recepcionista
 * Recebe dados processados do UsuarioController::mostrarDashboardRecepcionista()
 * 
 * Variáveis esperadas:
 * - $dados['nomeUsuario']
 * - $dados['cargo']
 * - $dados['total_clientes']
 * - $dados['total_profissionais']
 * - $dados['agendamentos_hoje']
 * - $dados['agendamentos_pendentes']
 */

// Extrai os dados fornecidos pelo Controller
$nomeUsuario = $dados['nomeUsuario'] ?? 'Recepcionista';
$cargo = $dados['cargo'] ?? 'Recepcionista';
$total_clientes = $dados['total_clientes'] ?? 0;
$total_profissionais = $dados['total_profissionais'] ?? 0;
$agendamentos_hoje = $dados['agendamentos_hoje'] ?? 0;
$agendamentos_pendentes = $dados['agendamentos_pendentes'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Recepcionista - MyBeauty</title>
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
                    <span class="burger-menu__title">Menu Recepcionista</span>
                    <button class="burger-menu__pin" onclick="togglePinMenu()" aria-label="Fixar Menu">
                        <i data-lucide="pin"></i>
                    </button>
                </div>
                <div class="burger-menu__content">
                    <a href="Index.php?acao=inicio" class="burger-menu__item">
                        <i data-lucide="home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="Index.php?acao=agendamento_recepcionista_mostrar" class="burger-menu__item">
                        <i data-lucide="calendar-plus"></i>
                        <span>Agendar Serviço</span>
                    </a>
                    <a href="Index.php?acao=recepcionista_agenda_mostrar" class="burger-menu__item">
                        <i data-lucide="calendar-search"></i>
                        <span>Consultar Agenda</span>
                    </a>
                    <a href="Index.php?acao=funcionario_cadastro_mostrar" class="burger-menu__item">
                        <i data-lucide="user-plus"></i>
                        <span>Cadastrar Profissional</span>
                    </a>
                    <a href="Index.php?acao=cliente_listar" class="burger-menu__item">
                        <i data-lucide="users"></i>
                        <span>Visualizar Clientes</span>
                    </a>
                    <div class="burger-menu__divider"></div>
                    <a href="Index.php?acao=logout" class="burger-menu__item">
                        <i data-lucide="log-out"></i>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
            
            <div class="navbar-actions">
                <a href="Index.php?acao=agendamento_recepcionista_mostrar" class="btn-header btn-header--primary">
                    <i data-lucide="calendar-plus"></i>
                    <span>Agendar Serviço</span>
                </a>
                <div class="navbar-user" onclick="toggleUserMenu()">
                    <div class="navbar-user__avatar">
                        <?php echo strtoupper(substr($nomeUsuario, 0, 1)); ?>
                    </div>
                    <div class="navbar-user__info">
                        <div class="navbar-user__name"><?php echo htmlspecialchars(explode(' ', $nomeUsuario)[0]); ?></div>
                        <div class="navbar-user__role">Recepcionista</div>
                    </div>
                    <div class="navbar-user__dropdown">
                        <a href="Index.php?acao=perfil_mostrar" class="navbar-user__dropdown-item">
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
                <h1>Olá, <?php echo htmlspecialchars($nomeUsuario); ?>! <i data-lucide="clipboard-check" class="inline-icon"></i></h1>
                <p>Painel da Recepção MyBeauty</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="calendar-clock"></i></div>
                <div class="stat-card__value"><?php echo $agendamentos_hoje; ?></div>
                <div class="stat-card__label">Agendamentos Hoje</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="clock"></i></div>
                <div class="stat-card__value"><?php echo $agendamentos_pendentes; ?></div>
                <div class="stat-card__label">Agendamentos Pendentes</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="users"></i></div>
                <div class="stat-card__value"><?php echo $total_clientes; ?></div>
                <div class="stat-card__label">Total de Clientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="user-check"></i></div>
                <div class="stat-card__value"><?php echo $total_profissionais; ?></div>
                <div class="stat-card__label">Profissionais</div>
            </div>
        </div>

        <div class="admin-grid">
            <a href="Index.php?acao=agendamento_recepcionista_mostrar" class="admin-card">
                <div class="admin-card__icon"><i data-lucide="calendar-plus"></i></div>
                <h3 class="admin-card__title">Agendar Serviço</h3>
                <p class="admin-card__description">Criar agendamento em nome de um cliente</p>
            </a>

            <a href="Index.php?acao=recepcionista_agenda_mostrar" class="admin-card">
                <div class="admin-card__icon"><i data-lucide="calendar-search"></i></div>
                <h3 class="admin-card__title">Consultar Agenda</h3>
                <p class="admin-card__description">Visualize a agenda de todos os profissionais</p>
            </a>

            <a href="Index.php?acao=funcionario_cadastro_mostrar" class="admin-card">
                <div class="admin-card__icon"><i data-lucide="user-plus"></i></div>
                <h3 class="admin-card__title">Cadastrar Profissional</h3>
                <p class="admin-card__description">Adicionar novo profissional ao sistema</p>
            </a>

            <a href="Index.php?acao=cliente_listar" class="admin-card">
                <div class="admin-card__icon"><i data-lucide="users"></i></div>
                <h3 class="admin-card__title">Visualizar Clientes</h3>
                <p class="admin-card__description">Consultar cadastro de clientes</p>
            </a>

            <a href="Index.php?acao=recepcionista_cliente_cadastro_mostrar" class="admin-card">
                <div class="admin-card__icon"><i data-lucide="user-plus-2"></i></div>
                <h3 class="admin-card__title">Cadastrar Cliente</h3>
                <p class="admin-card__description">Adicionar novo cliente ao sistema</p>
            </a>
        </div>
    </div>
</body>
</html>
