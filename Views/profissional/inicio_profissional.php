<?php
/**
 * View: Dashboard do Profissional
 * Recebe dados processados do AgendamentoController::mostrarDashboardProfissional()
 * 
 * Variáveis esperadas:
 * - $dados['usuario_nome']
 * - $dados['total_agendamentos']
 * - $dados['agendamentos_hoje']
 * - $dados['agendamentos_proximos']
 * - $dados['total_concluidos']
 * - $dados['proximos_count']
 */

// Inclui as funções auxiliares
require_once __DIR__ . '/../../helpers.php';

// Extrai os dados fornecidos pelo Controller
$usuario_nome = $dados['usuario_nome'] ?? 'Profissional';
$total_agendamentos = $dados['total_agendamentos'] ?? 0;
$agendamentos_hoje = $dados['agendamentos_hoje'] ?? [];
$agendamentos_proximos = $dados['agendamentos_proximos'] ?? [];
$total_concluidos = $dados['total_concluidos'] ?? 0;
$proximos_count = $dados['proximos_count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profissional - MyBeauty</title>
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
                    <span class="burger-menu__title">Menu</span>
                    <button class="burger-menu__pin" onclick="togglePinMenu()" aria-label="Fixar Menu">
                        <i data-lucide="pin"></i>
                    </button>
                </div>
                <div class="burger-menu__content">
                    <a href="Index.php?acao=inicio" class="burger-menu__item">
                        <i data-lucide="home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="Index.php?acao=agenda_profissional_mostrar" class="burger-menu__item">
                        <i data-lucide="calendar"></i>
                        <span>Minha Agenda</span>
                    </a>
                    <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="burger-menu__item">
                        <i data-lucide="clipboard-list"></i>
                        <span>Gerenciar Agendamentos</span>
                    </a>
                    <div class="burger-menu__divider"></div>
                    <a href="Index.php?acao=logout" class="burger-menu__item">
                        <i data-lucide="log-out"></i>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
            
            <div class="navbar-actions">
                <a href="Index.php?acao=agenda_profissional_mostrar" class="btn-header btn-header--primary">
                    <i data-lucide="calendar"></i>
                    <span>Minha Agenda</span>
                </a>
                <div class="navbar-user" onclick="toggleUserMenu()">
                    <div class="navbar-user__avatar">
                        <?php echo strtoupper(substr($usuario_nome, 0, 1)); ?>
                    </div>
                    <div class="navbar-user__info">
                        <div class="navbar-user__name"><?php echo htmlspecialchars(explode(' ', $usuario_nome)[0]); ?></div>
                        <div class="navbar-user__role">Profissional</div>
                    </div>
                    <div class="navbar-user__dropdown">
                        <a href="#" class="navbar-user__dropdown-item">
                            <i data-lucide="user"></i>
                            <span>Meu Perfil</span>
                        </a>
                        <a href="Index.php?acao=agenda_profissional_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="calendar"></i>
                            <span>Minha Agenda</span>
                        </a>
                        <a href="Index.php?acao=gerenciar_agendamento_mostrar" class="navbar-user__dropdown-item">
                            <i data-lucide="clipboard-list"></i>
                            <span>Gerenciar Agendamentos</span>
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
                <h1>Olá, <?php echo htmlspecialchars($usuario_nome); ?>! <i data-lucide="sparkles" class="inline-icon"></i></h1>
                <p>Painel Profissional MyBeauty</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="clipboard-list"></i></div>
                <div class="stat-card__value"><?php echo $total_agendamentos; ?></div>
                <div class="stat-card__label">Total de Agendamentos</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="clock"></i></div>
                <div class="stat-card__value"><?php echo count($agendamentos_hoje); ?></div>
                <div class="stat-card__label">Agendamentos Hoje</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="check-circle"></i></div>
                <div class="stat-card__value"><?php echo $total_concluidos; ?></div>
                <div class="stat-card__label">Concluídos</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon"><i data-lucide="calendar-clock"></i></div>
                <div class="stat-card__value"><?php echo $proximos_count; ?></div>
                <div class="stat-card__label">Próximos</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">Próximos Agendamentos</h2>
                </div>
                <div class="agendamentos-list">
                    <?php if (empty($agendamentos_proximos)): ?>
                        <div class="empty-state">
                            <div class="empty-state__icon"><i data-lucide="calendar-x"></i></div>
                            <div class="empty-state__text">Nenhum agendamento próximo</div>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($agendamentos_proximos, 0, 5) as $agendamento): 
                            $data = formatarData($agendamento->data_hora);
                            $status = statusBadge($agendamento->status);
                        ?>
                            <div class="agendamento-item">
                                <div class="agendamento-item__header">
                                    <div class="agendamento-item__date">
                                        <div class="date-badge">
                                            <div class="date-badge__day"><?php echo $data['dia']; ?></div>
                                            <div class="date-badge__month"><?php echo $data['mes']; ?></div>
                                        </div>
                                        <div class="agendamento-item__info">
                                            <h3 class="agendamento-item__title"><?php echo htmlspecialchars($agendamento->servicos ?? 'Serviço'); ?></h3>
                                            <p class="agendamento-item__details">
                                                <strong>Cliente:</strong> <?php echo htmlspecialchars($agendamento->cliente_nome ?? 'N/A'); ?><br>
                                                <strong>Horário:</strong> <?php echo $data['hora']; ?> • <?php echo $data['data_completa']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <span class="status-badge <?php echo $status['class']; ?>">
                                        <?php echo $status['texto']; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-card">
                <div class="section-card__header">
                    <h2 class="section-card__title">Ações Rápidas</h2>
                </div>
                <div class="quick-actions">
                    <a href="Index.php?acao=agenda_profissional_mostrar" class="quick-action-card">
                        <div class="quick-action-card__icon"><i data-lucide="calendar"></i></div>
                        <div class="quick-action-card__content">
                            <h3>Minha Agenda</h3>
                            <p>Visualizar agenda completa</p>
                        </div>
                    </a>
                    <a href="Index.php?acao=inicio" class="quick-action-card">
                        <div class="quick-action-card__icon"><i data-lucide="home"></i></div>
                        <div class="quick-action-card__content">
                            <h3>Dashboard</h3>
                            <p>Voltar ao início</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
